<?php

namespace App\Services\PurchaseOrder;

use App\Common\Entity\DatatableParams;
use App\Enums\ActionLogEnum;
use App\Enums\PurchaseOrderStatusEnum;
use App\Exceptions\PurchaseOrderModificationException;
use App\Jobs\SendPurchaseOrderEmailJob;
use App\Models\PurchaseOrder;
use App\Services\BaseService;
use App\Repositories\BaseRepository;
use App\Repositories\PurchaseOrder\PurchaseOrderRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;

class PurchaseOrderService extends BaseService implements PurchaseOrderServiceInterface
{
    /**
     * @param PurchaseOrderRepository $repository
     *
     * @return void
     */
    public function __construct(
        public BaseRepository $repository
    ) {
        parent::__construct($repository);
    }

    /**
     * create
     *
     * @param  array $params
     * @return Model
     */
    public function create(array $params): Model
    {
        $params['status'] = PurchaseOrderStatusEnum::DRAFT;
        $params['created_by'] = Auth::id();
        $purchaseOrder = parent::create($params);
        if ($purchaseOrder && !empty($params['products'])) {
            $productData = [];
            foreach ($params['products'] as $product) {
                $productData[$product['id']] = [
                    'quantity' => $product['quantity'],
                    'unit_cost' => $product['unit_cost']
                ];
            }
            if ($productData) {
                $purchaseOrder->products()->attach($productData);
            }
        }
        $purchaseOrder->code = $this->createCode($purchaseOrder->id);
        $purchaseOrder->save();
        activity('default')
            ->performedOn($purchaseOrder)
            ->event(ActionLogEnum::CREATED)
            ->withProperties(['attributes' => $purchaseOrder->toArray()])
            ->log(ActionLogEnum::CREATED);
        if (!empty($params['is_send_order'])) {
            $this->sendPurchaseOrder($purchaseOrder->id);
        }
        return $purchaseOrder;
    }

    /**
     * update
     *
     * @param  array $params
     * @param  int $id
     * @return Model
     */
    public function update(array $params, int $id): Model
    {
        $params['updated_by'] = Auth::id();
        $purchaseOrder = $this->repository->findById($id);
        if ($purchaseOrder && $purchaseOrder->status !== PurchaseOrderStatusEnum::DRAFT) {
            throw new PurchaseOrderModificationException(__('message.notUpdatePurchaseOrder'));
        }
        $oldPurchaseOrder = $this->getDataForActivityLog($purchaseOrder);
        $purchaseOrder = parent::update($params, $id);
        if ($purchaseOrder && !empty($params['products'])) {
            $productData = [];
            foreach ($params['products'] as $product) {
                $productData[$product['id']] = [
                    'quantity' => $product['quantity'],
                    'unit_cost' => $product['unit_cost']
                ];
            }
            if ($productData) {
                $purchaseOrder->products()->sync($productData);
            }
        }
        $attributes = $this->getDataForActivityLog($purchaseOrder);
        activity('default')
            ->performedOn($purchaseOrder)
            ->event(ActionLogEnum::UPDATED)
            ->withProperties([
                'attributes' => $attributes,
                'old' => $oldPurchaseOrder
            ])
            ->log(ActionLogEnum::UPDATED);
        if (!empty($params['is_send_order'])) {
            $this->sendPurchaseOrder($purchaseOrder->id);
        }
        return $purchaseOrder;
    }

    /**
     * Get Total By Status
     *
     * @return array
     */
    public function getTotalByStatus(): array
    {
        return $this->repository->getTotalByStatus();
    }

    /**
     * delete
     *
     * @param  int $id
     * @return bool
     */
    public function delete(int $id): bool|null
    {
        $purchaseOrder = $this->repository->findById($id);
        if ($purchaseOrder && $purchaseOrder->status !== PurchaseOrderStatusEnum::DRAFT) {
            throw new PurchaseOrderModificationException(__('message.notDeletePurchaseOrder'));
        }
        activity('default')
            ->performedOn($purchaseOrder)
            ->event(ActionLogEnum::DELETED)
            ->log(ActionLogEnum::DELETED);
        return $this->repository->delete($id);
    }

    /**
     * Send Purchase Order
     *
     * @param  string $id
     * @return bool
     */
    public function sendPurchaseOrder(string $id): bool
    {
        $purchaseOrder = $this->repository->findById($id);
        $dataMail = $this->getDetailPurchaseOrder($id);
        $dataMail['title'] = __('translation.purchaseOrder.purchaseOrder');
        if ($purchaseOrder->status !== PurchaseOrderStatusEnum::DRAFT) {
            throw new PurchaseOrderModificationException(__('message.notSendPurchaseOrder'));
        }
        $this->updateUnitCostForPurchaseOrder($purchaseOrder);
        $purchaseOrder->status = PurchaseOrderStatusEnum::PENDING;
        $purchaseOrder->send_date = date('Y-m-d H:i:s');
        $purchaseOrder->receipt_code = $this->createReceiptCode($purchaseOrder);
        activity('default')
            ->performedOn($purchaseOrder)
            ->event(ActionLogEnum::SENDED)
            ->log(ActionLogEnum::SENDED);
        SendPurchaseOrderEmailJob::dispatch($dataMail);
        return $purchaseOrder->save();
    }

    /**
     * Update Unit Cost For Purchase Order
     *
     * @param  PurchaseOrder $purchaseOrder
     * @return void
     */
    public function updateUnitCostForPurchaseOrder(PurchaseOrder $purchaseOrder): void
    {
        if ($purchaseOrder && $purchaseOrder->status === PurchaseOrderStatusEnum::DRAFT && $purchaseOrder->products) {
            $products = $purchaseOrder->products;
            foreach ($products as $product) {
                $supplierProduct = $product->suppliers()->where('supplier_id', $purchaseOrder->supplier_id)->first();
                if ($supplierProduct) {
                    $product->pivot->unit_cost = $supplierProduct->pivot->unit_cost;
                    $product->pivot->save();
                }
            }
        }
    }

    /**
     * Receive Product
     *
     * @param  array $params
     * @param  string $id
     * @return bool
     */
    public function receiveProduct(array $params, string $id): bool
    {
        $purchaseOrder = $this->repository->findById($id);
        if ($purchaseOrder && $purchaseOrder->status !== PurchaseOrderStatusEnum::PENDING) {
            throw new PurchaseOrderModificationException(__('message.notReceivePurchaseOrder'));
        }
        if ($purchaseOrder && !empty($params['products'])) {
            $productData = [];
            foreach ($params['products'] as $product) {
                $productData[$product['id']] = [
                    'received_quantity' => $product['received_quantity']
                ];
            }
            if ($productData) {
                $purchaseOrder->products()->sync($productData);
            }
        }
        if ($purchaseOrder->supplier) {
            $dataSku = [];
            foreach ($params['products'] as $product) {
                if (!empty($product['sku'])) {
                    $dataSku[$product['id']] = ['sku' => $product['sku']];
                }
            }
            $purchaseOrder->supplier->products()->syncWithoutDetaching($dataSku);
        }
        $batchCode = $this->createBatchCode($purchaseOrder->id);
        $purchaseOrder->status = PurchaseOrderStatusEnum::PENDING_SHELVE;
        $purchaseOrder->received_note = $params['received_note'] ?? '';
        $purchaseOrder->received_date = date('Y-m-d H:i:s');
        $purchaseOrder->batch_code = $batchCode;
        activity('default')
            ->performedOn($purchaseOrder)
            ->event(ActionLogEnum::RECEIVED)
            ->withProperties([
                'batchCode' => $batchCode
            ])
            ->log(ActionLogEnum::RECEIVED);
        return $purchaseOrder->save();
    }

    /**
     * Cancel Purchase Order
     *
     * @param  string $id
     * @return bool
     */
    public function cancelPurchaseOrder(string $id): bool
    {
        $purchaseOrder = $this->repository->findById($id);
        if ($purchaseOrder && $purchaseOrder->status !== PurchaseOrderStatusEnum::PENDING) {
            throw new PurchaseOrderModificationException(__('message.notCancelPurchaseOrder'));
        }
        $purchaseOrder->status = PurchaseOrderStatusEnum::CANCEL;
        activity('default')
            ->performedOn($purchaseOrder)
            ->event(ActionLogEnum::CANCELED)
            ->log(ActionLogEnum::CANCELED);
        return $purchaseOrder->save();
    }

    /**
     * Put Product On Shelf
     *
     * @param  string $purchaseOrderId
     * @param  string $productId
     * @param  array $params
     * @return bool
     */
    public function putProductOnShelf(string $purchaseOrderId, string $productId, array $params): bool
    {
        $purchaseOrder = $this->repository->findById($purchaseOrderId);
        if ($purchaseOrder->status !== PurchaseOrderStatusEnum::PENDING_SHELVE) {
            throw new PurchaseOrderModificationException(__('message.purchaseOrderNotReceived'));
        }
        return $this->repository->putProductOnShelf($purchaseOrderId, $productId, $params);
    }

    /**
     * Get Detail Purchase Product Shelve
     *
     * @param  string $purchaseOrderId
     * @param  string $productId
     * @return PurchaseOrder
     */
    public function getDetailPurchaseProductShelve(string $purchaseOrderId, string $productId): PurchaseOrder
    {
        return $this->repository->getDetailPurchaseProductShelve($purchaseOrderId, $productId);
    }

    /**
     * Finish Purchase Order
     *
     * @param  string $id
     * @return bool
     */
    public function finishPurchaseOrder(string $id): bool
    {
        $purchaseOrder = $this->repository->findById($id);
        if ($purchaseOrder->status !== PurchaseOrderStatusEnum::PENDING_SHELVE) {
            throw new PurchaseOrderModificationException(__('message.purchaseOrderNotReceived'));
        }
        foreach ($purchaseOrder->products as $product) {
            if ($product->pivot->received_quantity > 0) {
                $exists = false;
                foreach ($purchaseOrder->purchaseProductShelves as $item) {
                    if ($item->product_id == $product->id) {
                        $exists = true;
                        break;
                    }
                }
                if (!$exists) {
                    throw new PurchaseOrderModificationException(__('message.productNotPutOnShelf'));
                }
            }
        }
        $purchaseOrder->purchaseProductShelves;
        $purchaseOrder->status = PurchaseOrderStatusEnum::DONE;
        activity('default')
            ->performedOn($purchaseOrder)
            ->event(ActionLogEnum::DONE)
            ->log(ActionLogEnum::DONE);
        return $purchaseOrder->save();
    }

    /**
     * Get Activity Purchase Order
     *
     * @param  string $id
     * @return array
     */
    public function getActivityPurchaseOrder(string $id): array
    {
        $activityLogs = $this->repository->getActivityPurchaseOrder($id);
        foreach ($activityLogs as $key => $activityLog) {
            if ($activityLog['event'] === ActionLogEnum::UPDATED) {
                $activityLogs[$key]['productDifference'] = $this->getProductDifference($activityLog['properties']['old']['products'], $activityLog['properties']['attributes']['products']);
            }
        }
        return $activityLogs;
    }

    /**
     * Get Data For Activity Log
     *
     * @param  PurchaseOrder $purchaseOrder
     * @return array
     */
    public function getDataForActivityLog(PurchaseOrder $purchaseOrder): array
    {
        $data = $purchaseOrder->toArray();
        $data['supplier'] = $purchaseOrder->supplier;
        $data['warehouse'] = $purchaseOrder->warehouse;
        $data['products'] = $purchaseOrder->products->toArray();
        return $data;
    }

    /**
     * Get Product Difference
     *
     * @param  array $oldProducts
     * @param  array $newProducts
     * @return array
     */
    public function getProductDifference(array $oldProducts, array $newProducts): array
    {
        $result = [
            'update' => [],
            'create' => [],
            'delete' => [],
        ];

        $oldProductMap = collect($oldProducts)->keyBy('id');
        $newProductMap = collect($newProducts)->keyBy('id');
        $oldProductMap->each(function ($oldProduct, $id) use ($newProductMap, &$result) {
            if ($newProductMap->has($id)) {
                if ($oldProduct['pivot']['quantity'] != $newProductMap[$id]['pivot']['quantity']) {
                    $result['update'][] = [
                        'id' => $id,
                        'name' => $oldProduct['name'],
                        'old_quantity' => $oldProduct['pivot']['quantity'],
                        'new_quantity' => $newProductMap[$id]['pivot']['quantity']
                    ];
                }
            } else {
                $result['delete'][] = $oldProduct;
            }
        });
        $newProductMap->each(function ($newProduct, $id) use ($oldProductMap, &$result) {
            if (!$oldProductMap->has($id)) {
                $result['create'][] = $newProduct;
            }
        });

        return $result;
    }

    /**
     * Get List Batch
     *
     * @param  DatatableParams $params
     * @return Paginator|LengthAwarePaginator
     */
    public function getListBatch(DatatableParams $params): Paginator|LengthAwarePaginator
    {
        return $this->repository->getListBatch($params);
    }

    /**
     * Get Detail Batch
     *
     * @param  string $id
     * @return PurchaseOrder
     */
    public function getDetailBatch(string $id): PurchaseOrder
    {
        $batch = $this->repository->getDetailBatch($id);
        return $batch;
    }

    /**
     * Get Receive Of Goods
     *
     * @param  DatatableParams $params
     * @return Paginator|LengthAwarePaginator
     */
    public function getReceiptIn(DatatableParams $params): Paginator|LengthAwarePaginator
    {
        return $this->repository->getReceiptIn($params);
    }

    /**
     * CreateCode
     *
     * @param  int $purchaseOrderId
     * @return string
     */
    public function createCode(int $purchaseOrderId): string
    {
        return 'P' . str_pad($purchaseOrderId, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Create Receipt Code
     *
     * @param  PurchaseOrder $purchaseOrder
     * @return string
     */
    public function createReceiptCode(PurchaseOrder $purchaseOrder): string
    {
        return implode('-', [$purchaseOrder->warehouse->code, 'IN', $purchaseOrder->code]);
    }

    /**
     * Create Batch Code
     *
     * @param  int $purchaseOrderId
     * @return string
     */
    public function createBatchCode(int $purchaseOrderId): string
    {
        return 'BATCH' . str_pad($purchaseOrderId, 4, '0', STR_PAD_LEFT);
    }

    /**
     * getPurchaseOrderProduct
     *
     * @param  int $id
     * @return Collection
     */
    public function getPurchaseOrderProduct(int $id): Collection
    {
        return $this->repository->getPurchaseOrderProduct($id);
    }

    /**
     * Get Detail Purchase Order
     *
     * @param  string $id
     * @return array
     */
    public function getDetailPurchaseOrder(string $id): array
    {
        $purchaseOrder = $this->with([
            'warehouse' => function ($query) {
                $query->withTrashed();
            },
            'supplier' => function ($query) {
                $query->withTrashed();
            },
            'purchaseProductShelves',
            'returnOrders'
        ])->findOrFail($id);

        $products = $this->getPurchaseOrderProduct($id);

        $data['totalAmount'] = $products->sum(function ($product) use ($purchaseOrder) {
            if (in_array($purchaseOrder->status, [PurchaseOrderStatusEnum::DONE, PurchaseOrderStatusEnum::PENDING_SHELVE])) {
                return ($product->received_quantity - $product->returned_quantity) * $product->unit_cost;
            }
            return $product->quantity * $product->unit_cost;
        });

        $data['purchaseOrder'] = $purchaseOrder;
        $data['products'] = $products;
        return $data;
    }
}
