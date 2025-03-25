<?php

namespace App\Services\ReturnOrder;

use App\Common\Entity\DatatableParams;
use App\Enums\ReturnOrderStatusEnum;
use App\Exceptions\ReturnOrderModificationException;
use App\Jobs\SendReturnOrderMailJob;
use App\Models\ProductLocation;
use App\Models\PurchaseOrder;
use App\Models\ReturnOrder;
use App\Models\ReturnOrderDetails;
use App\Services\BaseService;
use App\Repositories\BaseRepository;
use App\Repositories\ReturnOrder\ReturnOrderRepositoryInterface;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class ReturnOrderService extends BaseService implements ReturnOrderServiceInterface
{
    /**
     * ReturnOrderRepositoryInterface
     *
     * ?return void
     */
    public function __construct(
        BaseRepository $repository
    ) {
        parent::__construct($repository);
    }

    public function create(array $params): Model
    {
        $params['code'] = $this->generateReturnOrderCode($params);
        $returnOrder = parent::create($params);

        foreach ($params['products'] as $returnOrderData) {
            $productData[$returnOrderData['product_location_id']] = [
                'demand_quantity' => $returnOrderData['demand_quantity'],
                'quantity' => 0
            ];
        }
        if ($productData) {
            $returnOrder->productLocations()->attach($productData);
        }
        $dataMail = $this->getReturnOrder($returnOrder->id);
        $dataMail['title'] = __('translation.returnOrder.returnOrder');
        SendReturnOrderMailJob::dispatch($dataMail);
        return $returnOrder;
    }

    public function update(array $params, int $id): Model
    {

        $returnOrder = parent::findById($id);
        if ($returnOrder->status !== ReturnOrderStatusEnum::READY) {
            throw new ReturnOrderModificationException(__('message.notUpdateReturnOrder'));
        }
        $returnOrder->update($params);

        if (array_key_exists('products', $params)) {
            foreach ($params['products'] as $returnOrderData) {
                $returnOrderDetail = ReturnOrderDetails::find($returnOrderData['return_order_detail_id']);
                $returnOrderDetail->update([
                    'quantity' => $returnOrderData['quantity']
                ]);
                ProductLocation::find($returnOrderDetail->product_location_id)->decrement('quantity', $returnOrderData['quantity']);
            }
        }

        return $returnOrder;
    }

    protected function generateReturnOrderCode($params): string
    {
        $code = '';

        $purchaseOrder = PurchaseOrder::with(['warehouse' => function ($query) {
            $query->withTrashed();
        },])->find($params['purchase_order_id']);
        $warehouseCode = $purchaseOrder->warehouse->code;
        $purchaseOrderCode = $purchaseOrder->code;
        $returnOrderCount = ReturnOrder::where('purchase_order_id', $purchaseOrder->id)->count();
        $returnOrderCode = str_pad($returnOrderCount + 1, 2, '0', STR_PAD_LEFT);

        $code = implode('-', [$warehouseCode, 'OUT', $purchaseOrderCode, $returnOrderCode]);
        return $code;
    }

    /**
     * Receipt Out
     *
     * @param  DatatableParams $params
     * @return Paginator
     */
    public function receiptOut(DatatableParams $params): Paginator|LengthAwarePaginator
    {
        return $this->repository->receiptOut($params);
    }

    /**
     * getReturnOrder
     *
     * @param  string $id
     * @return array
     */
    public function getReturnOrder(string $id): array
    {
        $returnOrder = $this->with([
            'purchaseOrder.supplier' => function ($query) {
                $query->withTrashed();
            },
            'purchaseOrder.warehouse' => function ($query) {
                $query->withTrashed();
            },
            'returnOrderDetails'
        ])->findOrFail($id);

        $products = $returnOrder->productLocations;

        $data['totalAmount'] = $products->sum(function ($product) {
            return $product->pivot->demand_quantity * $product->unit_cost;
        });
        $data['returnOrder'] = $returnOrder;
        $data['products'] = $products;
        return $data;
    }
}
