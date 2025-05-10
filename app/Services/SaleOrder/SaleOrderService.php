<?php

namespace App\Services\SaleOrder;

use App\Common\Entity\DatatableParams;
use App\Exports\RevenueReportExport;
use App\Models\RegisterPayment;
use App\Enums\SaleOrderReceiptStatusEnum;
use App\Enums\SaleOrderStatusEnum;
use App\Helpers\SaleOrderHelper;
use App\Jobs\SendInvoiceJob;
use App\Models\ProductLocation;
use App\Models\SaleOrder;
use App\Models\SaleOrderDetail;
use App\Services\BaseService;
use App\Repositories\BaseRepository;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class SaleOrderService extends BaseService implements SaleOrderServiceInterface
{
    /**
     * SaleOrderRepositoryInterface
     *
     * ?return void
     */
    public function __construct(
        BaseRepository $repository
    ) {
        parent::__construct($repository);
    }

    /**
     * Register Payment
     *
     * @param  array $params
     * @param  string $id
     * @return RegisterPayment
     */
    public function registerPayment(array $params, string $id): RegisterPayment
    {
        $invoice = $this->repository->findById($id);
        return $this->repository->registerPayment($params, $invoice->id);
    }

    /**
     * create
     *
     * @param  array $params
     * @return Model
     */
    public function create(array $params): Model
    {
        $params['tax_info'] = json_encode($params['tax_data']);
        $params['tax_rate'] = $params['tax_data'] ? collect($params['tax_data'])->sum('rate') : 0;
        $params['receipt_status'] = SaleOrderReceiptStatusEnum::READY;
        $saleOrder = parent::create($params);
        $saleOrderId = $saleOrder->id;
        $saleOrder->code = $this->generateCode($saleOrderId);
        $saleOrder->invoice_code = $this->generateInvoiceCode($saleOrderId);
        $saleOrder->receipt_code = $this->generateReceiptCode($saleOrder);
        $saleOrder->save();
        $productData = [];
        foreach ($params['products'] as $product) {
            $productData[$product['id']] = [
                'quantity' => $product['order_quantity'],
                'unit_price' => $product['unit_price'],
                'discount_rate' => $product['discount_amount']
            ];
        }
        if ($productData) {
            $saleOrder->products()->attach($productData);
        }
        return $saleOrder;
    }

    /**
     * create
     *
     * @param  array $params
     * @return Model
     */
    public function update(array $params, int $id): Model
    {
        $params['tax_info'] = json_encode($params['tax_data']);
        $params['tax_rate'] = $params['tax_data'] ? collect($params['tax_data'])->sum('rate') : 0;
        $saleOrder = parent::update($params, $id);
        $productData = [];
        foreach ($params['products'] as $product) {
            $productData[$product['id']] = [
                'quantity' => $product['order_quantity'],
                'unit_price' => $product['unit_price'],
                'discount_rate' => $product['discount_amount']
            ];
        }
        if ($productData) {
            $saleOrder->products()->sync($productData);
        }

        return $saleOrder;
    }

    /**
     * delete
     *
     * @param  int $id
     * @return bool|null
     */
    public function delete(int $id): bool|null
    {
        $saleOrder = parent::findById($id);
        if ($saleOrder['order_status'] == SaleOrderStatusEnum::DRAFT) {
            return parent::delete($id);
        }
        return false;
    }

    /**
     * updateStatus
     *
     * @param  array $params
     * @param  int $id
     * @return SaleOrder
     */
    public function updateStatus(array $params, int $id): SaleOrder
    {
        $saleOrder = parent::findById($id);
        $orderStatus = $params['order_status'];
        $currentOrderStatus = $saleOrder['order_status'];
        $receiptStatus = $saleOrder['receipt_status'];

        $statusConditions = [
            SaleOrderStatusEnum::CONFIRM => $currentOrderStatus != SaleOrderStatusEnum::DRAFT,
            SaleOrderStatusEnum::IN_TRANSIT => $currentOrderStatus != SaleOrderStatusEnum::CONFIRM || $receiptStatus != SaleOrderReceiptStatusEnum::DONE,
            SaleOrderStatusEnum::CANCEL => $currentOrderStatus != SaleOrderStatusEnum::CONFIRM,
            SaleOrderStatusEnum::DELIVERED => $currentOrderStatus != SaleOrderStatusEnum::IN_TRANSIT,
        ];

        if (isset($statusConditions[$orderStatus]) && $statusConditions[$orderStatus]) {
            return $saleOrder;
        }

        $saleOrder->update($params);
        if ($orderStatus == SaleOrderStatusEnum::CANCEL && $receiptStatus == SaleOrderReceiptStatusEnum::DONE) {
            $this->updateInventory($id, false);
        }
        return $saleOrder;
    }

    /**
     * updateInventory
     *
     * @param  int $saleOrderId
     * @param  bool $decrement
     * @return void
     */
    protected function updateInventory(int $saleOrderId, bool $isDecrement = true): void
    {
        $orderList = $this->getStockList($saleOrderId);
        if ($orderList) {
            foreach ($orderList as $item) {
                $productLocation = ProductLocation::findOrFail($item['id']);
                if ($isDecrement) {
                    $productLocation->decrement('quantity', $item['pick_quantity']);
                } else {
                    $productLocation->increment('quantity', $item['pick_quantity']);
                }
            }
        }
    }

    /**
     * validateROG
     *
     * @param  int $id
     * @return SaleOrder
     */
    public function validateROG(int $saleOrderId, array $params): SaleOrder
    {
        $saleOrder = SaleOrder::findOrFail($saleOrderId);
        if ($saleOrder['receipt_status'] == SaleOrderReceiptStatusEnum::DONE) {
            return $saleOrder;
        }
        if (!$this->checkAllStockValidated($saleOrderId)) {
            throw new Exception(__('message.allProductNeedValidate'));
        }
        if (!$this->checkQuantityAvailable($saleOrderId)) {
            throw new Exception(__('message.insufficientStock'));
        }
        $params['receipt_status'] = SaleOrderReceiptStatusEnum::DONE;
        $saleOrder->update($params);
        $this->updateInventory($saleOrderId);
        return $saleOrder;
    }

    /**
     * checkQuantityAvailable
     *
     * @param  int $saleOrderId
     * @return bool
     */
    protected function checkQuantityAvailable(int $saleOrderId): bool
    {
        $stockList = $this->getStockList($saleOrderId);
        foreach ($stockList as $stock) {
            if ($stock['quantity'] < $stock['pick_quantity']) {
                return false;
            }
        }
        return true;
    }

    /**
     * checkQuantityAvailable
     *
     * @param  int $saleOrderId
     * @return bool
     */
    protected function checkAllStockValidated(int $saleOrderId): bool
    {
        $stockList = SaleOrderDetail::select([
            'sale_order_details.id',
            'sale_order_details.quantity as order_quantity',
            DB::raw('SUM(sol.quantity) as total_pick_quantity')
        ])
            ->leftJoin('sale_order_locations as sol', 'sale_order_details.id', 'sol.sale_order_detail_id')
            ->where('sale_order_details.sale_order_id', $saleOrderId)
            ->groupBy('sale_order_details.id')->get()->toArray();
        foreach ($stockList as $stock) {
            if ($stock['order_quantity'] != $stock['total_pick_quantity']) {
                return false;
            }
        }
        return true;
    }

    /**
     * getStockList
     *
     * @param  int $saleOrderId
     * @return array
     */
    protected function getStockList(int $saleOrderId): array
    {
        return SaleOrderDetail::select([
            'pl.quantity',
            'sol.quantity as pick_quantity',
            'pl.id',
        ])
            ->join('sale_order_locations as sol', 'sale_order_details.id', 'sol.sale_order_detail_id')
            ->join('product_locations as pl', 'pl.id', 'sol.product_location_id')
            ->where('sale_order_details.sale_order_id', $saleOrderId)->get()->toArray();
    }

    /**
     * getSaleOrderDetails
     *
     * @param  int $saleOrderId
     * @return void
     */
    public function getSaleOrderDetails(int $saleOrderId): Collection
    {
        return $this->repository->getSaleOrderDetails($saleOrderId);
    }

    /**
     * generateCode
     *
     * @param  int $saleOrderId
     * @return string
     */
    public function generateCode($saleOrderId): string
    {
        return 'S' . str_pad($saleOrderId, 5, '0', STR_PAD_LEFT);
    }

    /**
     * generateInvoiceCode
     *
     * @param  int $saleOrderId
     * @return string
     */
    public function generateInvoiceCode($saleOrderId): string
    {
        return 'INV' . str_pad($saleOrderId, 4, '0', STR_PAD_LEFT);
    }

    /**
     * generateReceiptCode
     *
     * @param  SaleOrder $saleOrder
     * @return string
     */
    public function generateReceiptCode(SaleOrder $saleOrder): string
    {
        return implode('-', ['RG' . str_pad($saleOrder->id, 3, '0', STR_PAD_LEFT), 'OUT', $saleOrder->code]);
    }

    /**
     * getOrderStatusCount
     *
     * @return SaleOrder
     */
    public function getOrderStatusCount(): SaleOrder
    {
        return SaleOrder::select([
            DB::raw('COUNT(CASE WHEN sale_orders.order_status = 1 THEN 1 END) as draft_count'),
            DB::raw('COUNT(CASE WHEN sale_orders.order_status = 2 THEN 1 END) as confirmed_count'),
            DB::raw('COUNT(CASE WHEN sale_orders.order_status = 3 THEN 1 END) as in_transit_count'),
            DB::raw('COUNT(CASE WHEN sale_orders.order_status = 4 THEN 1 END) as delivered_count'),
            DB::raw('COUNT(CASE WHEN sale_orders.order_status = 5 THEN 1 END) as cancel_count')
        ])
            ->first();
    }

    /**
     * Get Invoice List
     *
     * @param  DatatableParams $datatableParams
     * @return Paginator|LengthAwarePaginator
     */
    public function getInvoiceList(DatatableParams $datatableParams): Paginator|LengthAwarePaginator
    {
        return $this->repository->getInvoiceList($datatableParams);
    }

    /**
     * Get Total Amount
     *
     * @param  string $id
     * @return array
     */
    public function getTotalAmount(string $id): array
    {
        $saleOrder = parent::findById($id);
        $totalAmount = 0;
        $saleOrderDetail = SaleOrderDetail::where('sale_order_id', $id)->get();
        foreach ($saleOrderDetail as $detail) {
            $totalAmount += $detail->quantity * $detail->unit_price * (100 - $detail->discount_rate) / 100;
        }
        if ($saleOrder?->tax_rate) {
            $totalAmount = $totalAmount * (1 + $saleOrder->tax_rate / 100);
        }
        $totalPaid = $saleOrder->registerPayments->sum('paid_amount');
        return [
            'totalAmount' => $totalAmount,
            'totalPaid' => $totalPaid
        ];
    }

    /**
     * addStock
     *
     * @param  array $params
     * @param  int $orderId
     * @param  int $productId
     * @return void
     */
    public function addStock(array $params, int $saleOrderId, int $productId): void
    {
        $saleOrderDetail = SaleOrderDetail::where([
            'sale_order_id' => $saleOrderId,
            'product_id' => $productId
        ])->first();
        $stock = [];
        foreach ($params['inventories'] as $value) {
            $stock[$value['id']] = [
                'quantity' => $value['select_quantity'],
            ];
        }
        $saleOrderDetail->productLocations()->sync($stock);
    }

    /**
     * getStockAssignmentData
     *
     * @param  int $saleOrderDetailId
     *      * @return ProductLocation|array
     */
    public function getStockAssignmentData(int $saleOrderDetailId): Collection|array
    {
        return ProductLocation::select([
            's.code as shelves_code',
            'w.code as warehouse_code',
            's.location',
            'product_locations.quantity',
            'product_locations.id',
            'po.batch_code',
            'po.received_date',
            'sol.quantity as pick_quantity',
        ])
            ->join('purchase_orders as po', 'po.id', 'product_locations.purchase_order_id')
            ->join('shelves as s', 's.id', 'product_locations.shelve_id')
            ->join('warehouses as w', 'w.id', 's.warehouse_id')
            ->join('sale_order_locations as sol', function ($join) use ($saleOrderDetailId) {
                $join->on('sol.product_location_id', '=', 'product_locations.id')
                    ->where('sol.sale_order_detail_id', '=', $saleOrderDetailId);
            })->get();
    }

    /**
     * Get Invoice PDF
     *
     * @param  string $id
     * @return array
     */
    public function getInvoicePDF(string $id): array
    {
        $data['title'] = "invoice";
        $data['saleOrder'] = SaleOrder::with([
            'customer' => function ($query) {
                $query->withTrashed();
            },
        ])->findOrFail($id);

        $data['saleOrderDetails'] = $this->getSaleOrderDetails($id);
        $data['registerPayments'] = RegisterPayment::where('sale_order_id', $id)->get();
        $totalAmount = $this->getTotalAmount($id);
        $data['totalAmountUnpaid'] = round($totalAmount['totalAmount'] - $totalAmount['totalPaid'], 2);
        $data['totalAmount'] = $totalAmount['totalAmount'];
        $customerTaxes = json_decode($data['saleOrder']->tax_info, true);
        $totalTax = 0;
        if ($customerTaxes) {
            $totalTax = array_sum(array_map(function ($tax) {
                return $tax['rate'];
            }, $customerTaxes));
        }
        $data['totalTax'] = $totalTax;
        $data['paymentTerm'] = SaleOrderHelper::calculatePaymentTerm($data['saleOrder']->created_at, $data['saleOrder']->payment_term)->format('Y-m-d');
        return $data;
    }

    /**
     * Send Invoice PDF
     *
     * @param  string $id
     * @return void
     */
    public function sendInvoicePDF(string $id): void
    {
        $data = $this->getInvoicePDF($id);
        SendInvoiceJob::dispatch($data);
    }

    public function export()
    {
        $export = new RevenueReportExport();
        return Excel::download($export, 'Revenue_Report.xlsx');

    }
}
