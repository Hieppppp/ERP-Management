<?php

namespace App\Http\Controllers\WEB\SaleOrder;

use App\Enums\SaleOrderReceiptStatusEnum;
use App\Enums\SaleOrderStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\ProductLocation;
use App\Models\RegisterPayment;
use App\Models\SaleOrder;
use App\Models\SaleOrderDetail;
use App\Services\SaleOrder\SaleOrderServiceInterface;
use Illuminate\Console\View\Components\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SaleOrderController extends Controller
{
    protected SaleOrderServiceInterface $saleOrderService;

    public function __construct(
        SaleOrderServiceInterface $saleOrderService
    ) {
        $this->saleOrderService = $saleOrderService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View|Factory
    {
        $data['saleOrderCount'] = $this->saleOrderService->getOrderStatusCount();
        return view('pages/sale-order/index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View|Factory
     */
    public function create(): View|Factory
    {
        return view('pages/sale-order/create');
    }

    /**
     * invoice
     *
     * @return View|Factory
     */
    public function invoice(): View|Factory
    {
        return view('pages/invoice/index');
    }

    /**
     * Invoice Detail
     *
     * @param  string $invoice
     * @return View|Factory
     */
    public function invoiceDetail(string $invoice): View|Factory
    {
        $data['saleOrder'] = SaleOrder::with([
            'customer' => function ($query) {
                $query->withTrashed();
            },
        ])->findOrFail($invoice);

        $data['saleOrderDetails'] = $this->saleOrderService->getSaleOrderDetails($invoice);
        $data['registerPayments'] = RegisterPayment::where('sale_order_id', $invoice)->get();
        $totalAmount = $this->saleOrderService->getTotalAmount($invoice);
        $data['totalAmountUnpaid'] = round($totalAmount['totalAmount'] - $totalAmount['totalPaid'], 2);
        return view('pages/invoice/detail', $data);
    }

    /**
     * Register Payment
     *
     * @param string $id
     * @return View|Factory
     */
    public function registerPayment(string $id): View|Factory
    {
        $totalAmount = $this->saleOrderService->getTotalAmount($id);
        $data['totalAmountUnpaid'] = round($totalAmount['totalAmount'] - $totalAmount['totalPaid'], 2);
        $data['saleOrder'] = SaleOrder::with('customer')->findOrFail($id);
        return view('pages/invoice/register-payment', $data);
    }

    /**
     * edit
     *
     * @param  int $id
     * @return View|Factory|RedirectResponse
     */
    public function edit(int $id): View|Factory|RedirectResponse
    {
        $data['saleOrder'] = SaleOrder::with([
            'customer' => function ($query) {
                $query->withTrashed();
            },
            'warehouse' => function ($query) {
                $query->withTrashed();
            },
        ])->findOrFail($id);
        if ($data['saleOrder']['order_status'] != SaleOrderStatusEnum::DRAFT) {
            return redirect()->route('sale-order.show', ['sale_order' => $id]);
        }
        $data['saleOrderDetails'] = $this->saleOrderService->getSaleOrderDetails($id);
        return view('pages/sale-order/edit', $data);
    }

    /**
     * edit
     *
     * @param  int $id
     * @return View
     */
    public function show(int $id): View|Factory
    {
        $data['saleOrder'] = SaleOrder::with([
            'customer' => function ($query) {
                $query->withTrashed();
            },
            'warehouse' => function ($query) {
                $query->withTrashed();
            },
        ])->findOrFail($id);

        $data['saleOrderDetails'] = $this->saleOrderService->getSaleOrderDetails($id);
        return view('pages/sale-order/detail', $data);
    }

    /**
     * edit
     *
     * @param  int $id
     * @return View
     */
    public function receipt(int $id): View|Factory
    {
        $data['saleOrder'] = SaleOrder::with([
            'customer' => function ($query) {
                $query->withTrashed();
            },
        ])->findOrFail($id);

        $data['saleOrderDetails'] = $this->saleOrderService->getSaleOrderDetails($id);
        return view('pages/sale-order/receipt', $data);
    }

    /**
     * selectStock
     *
     * @param  int $id
     * @param  int $productId
     * @return View|Factory
     */
    public function selectStock(int $saleOrderId, int $productId): View|Factory
    {
        $saleOrderDetail = SaleOrderDetail::with([
            'product' => function ($query) {
                $query->withTrashed();
            },
            'saleOrder'
        ])->where([
            'sale_order_id' => $saleOrderId,
            'product_id' => $productId,
        ])->firstOrFail();
        $data['saleOrderDetail'] = $saleOrderDetail;
        $data['productInventory'] = $this->saleOrderService->getStockAssignmentData($saleOrderDetail['id']);
        if ($saleOrderDetail['saleOrder']['receipt_status'] == SaleOrderReceiptStatusEnum::DONE) {
            return view('pages/sale-order/stock-detail', $data);
        }
        return view('pages/sale-order/select-stock', $data);
    }
    public function exportRevenueReport()
    {
        return $this->saleOrderService->export();
    }
}
