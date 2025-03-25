<?php

namespace App\Services\Inventory;

use App\Enums\ActionLogEnum;
use App\Enums\PurchaseOrderStatusEnum;
use App\Models\InventoryLog;
use App\Models\ProductLocation;
use App\Models\ProductPurchaseOrder;
use App\Models\ProductSupplier;
use App\Models\PurchaseOrder;
use App\Models\PurchaseProductShelve;
use App\Services\BaseService;
use App\Repositories\BaseRepository;
use App\Services\PurchaseOrder\PurchaseOrderServiceInterface;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class InventoryService extends BaseService implements InventoryServiceInterface
{
    protected PurchaseOrderServiceInterface $purchaseOrderService;

    public function __construct(
        BaseRepository $repository,
        PurchaseOrderServiceInterface $purchaseOrderService
    ) {
        parent::__construct($repository);
        $this->purchaseOrderService = $purchaseOrderService;
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
        $inventory = parent::findById($id);
        $inventory['quantity'] = $params['new_quantity'];
        $inventory->save();
        if ($params['current_quantity'] != $params['new_quantity']) {
            InventoryLog::create([
                'user_id' => Auth::id(),
                'product_location_id' => $id,
                'product_id' => $inventory->product_id,
                'before_quantity' => $params['current_quantity'],
                'after_quantity' => $params['new_quantity'],
                'action' => ActionLogEnum::UPDATED,
                'note' => $params['note']
            ]);
        }
        return $inventory;
    }

    /**
     * create
     *
     * @param  array $params
     * @return Model
     * @throws Exception
     */
    public function create(array $params): Model
    {
        $productId = $params['product_id'];
        $supplierId = $params['supplier_id'];
        $warehouseId = $params['warehouse_id'];

        $purchaseOrder = PurchaseOrder::create([
            'status' => PurchaseOrderStatusEnum::DONE,
            'supplier_id' => $supplierId,
            'warehouse_id' => $warehouseId,
            'created_by' => Auth::id(),
            'scheduled_date' => date('Y-m-d'),
            'received_date' => date('Y-m-d'),
        ]);
        $purchaseOrderId = $purchaseOrder->id;
        $purchaseOrder->code = $this->purchaseOrderService->createCode($purchaseOrderId);
        $purchaseOrder->receipt_code = $this->purchaseOrderService->createReceiptCode($purchaseOrder);
        $purchaseOrder->batch_code = $this->purchaseOrderService->createBatchCode($purchaseOrderId);
        $purchaseOrder->save();

        $productSupplier = ProductSupplier::where([
            'product_id' => $productId,
            'supplier_id' => $supplierId
        ])->first();

        if (!$productSupplier) {
            throw new Exception(__('message.notFound', ['field' => __('translation.menu.supplier')]));
        }
        $unitCost = $productSupplier->unit_cost;
        $totalQuantity = 0;

        foreach ($params['shelves'] as $shelve) {
            $totalQuantity += $shelve['quantity'];
            PurchaseProductShelve::create([
                'purchase_order_id' => $purchaseOrderId,
                'product_id' => $productId,
                'shelve_id' => $shelve['id'],
                'quantity' => $shelve['quantity']
            ]);

            $productLocation = ProductLocation::create([
                'product_id' => $productId,
                'shelve_id' => $shelve['id'],
                'purchase_order_id' => $purchaseOrderId,
                'quantity' => $shelve['quantity'],
                'unit_cost' => $unitCost
            ]);

            InventoryLog::create([
                'user_id' => Auth::id(),
                'product_location_id' => $productLocation->id,
                'product_id' => $productId,
                'before_quantity' => 0,
                'after_quantity' => $shelve['quantity'],
                'action' => ActionLogEnum::CREATED
            ]);
        }

        ProductPurchaseOrder::create([
            'purchase_order_id' => $purchaseOrderId,
            'product_id' => $productId,
            'quantity' => $totalQuantity,
            'unit_cost' => $unitCost,
            'received_quantity' => $totalQuantity
        ]);

        activity('default')
            ->performedOn($purchaseOrder)
            ->event(ActionLogEnum::CREATED)
            ->withProperties(['attributes' => $purchaseOrder->toArray()])
            ->log(ActionLogEnum::CREATED_MANUALLY);
        return $purchaseOrder;
    }

    /**
     * movement
     *
     * @param  array $params
     * @param  int $productLocationId
     * @return bool
     */
    public function movement(array $params, int $productLocationId): bool
    {
        $productLocation = ProductLocation::findOrFail($productLocationId);
        $dataInventoryLog = [];
        $totalQuantityMoved = 0;
        if ($productLocation) {
            foreach ($params['shelves'] as $shelve) {
                $productLocationItem = ProductLocation::where([
                    'product_id' => $productLocation->product_id,
                    'shelve_id' => $shelve['id'],
                    'purchase_order_id' => $productLocation->purchase_order_id
                ])->first();
                if ($productLocationItem) {
                    $currentQuantityItem = $productLocationItem->quantity;
                    $newQuantity = $currentQuantityItem + $shelve['quantity'];
                    $productLocationItem->quantity = $newQuantity;
                    $productLocationItem->save();
                    $dataInventoryLog[] = [
                        'user_id' => Auth::id(),
                        'product_location_id' => $productLocationItem->id,
                        'product_id' => $productLocationItem->product_id,
                        'before_quantity' => $currentQuantityItem,
                        'after_quantity' => $newQuantity,
                        'action' => ActionLogEnum::MOVEMENT,
                        'parent_product_location_id' => $productLocationId,
                        'created_at' => now(),

                    ];
                } else {
                    $productLocationItem = ProductLocation::create([
                        'product_id' => $productLocation->product_id,
                        'purchase_order_id' => $productLocation->purchase_order_id,
                        'shelve_id' => $shelve['id'],
                        'quantity' => $shelve['quantity'],
                        'unit_cost' => $productLocation->unit_cost
                    ]);
                    $dataInventoryLog[] = [
                        'user_id' => Auth::id(),
                        'product_location_id' => $productLocationItem->id,
                        'product_id' => $productLocationItem->product_id,
                        'before_quantity' => 0,
                        'after_quantity' => $shelve['quantity'],
                        'action' => ActionLogEnum::MOVEMENT,
                        'parent_product_location_id' => $productLocationId,
                        'created_at' => now(),
                    ];
                }
                $totalQuantityMoved += $shelve['quantity'];
            }
        }
        $productLocation->quantity -= $totalQuantityMoved;
        $productLocation->save();
        $dataInventoryLog[] = [
            'user_id' => Auth::id(),
            'product_location_id' => $productLocationId,
            'product_id' => $productLocation->product_id,
            'before_quantity' => $productLocation->quantity,
            'after_quantity' => $productLocation->quantity - $totalQuantityMoved,
            'action' => ActionLogEnum::MOVEMENT,
            'parent_product_location_id' => null,
            'created_at' => now(),
        ];
        return InventoryLog::insert($dataInventoryLog);
    }
}
