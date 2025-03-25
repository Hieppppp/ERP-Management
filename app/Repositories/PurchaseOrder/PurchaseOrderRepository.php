<?php

namespace App\Repositories\PurchaseOrder;

use App\Common\Entity\DatatableParams;
use App\Enums\PurchaseOrderStatusEnum;
use App\Models\ActivityLogs;
use App\Models\Product;
use App\Models\ProductLocation;
use App\Models\ProductPurchaseOrder;
use App\Repositories\BaseRepository;
use App\Models\PurchaseOrder;
use App\Models\PurchaseProductShelve;
use Illuminate\Pagination\Paginator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class PurchaseOrderRepository extends BaseRepository implements PurchaseOrderRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(PurchaseOrder::class);
    }

    /**
     * paginate
     *
     * @param  DatatableParams $params
     * @return Paginator|LengthAwarePaginator
     */
    public function paginate(DatatableParams $params): Paginator|LengthAwarePaginator
    {
        $query = $this->getModel()->select(
            [
                'purchase_orders.id',
                'purchase_orders.code',
                'scheduled_date',
                'purchase_orders.created_at',
                'suppliers.name as supplier_name',
                DB::raw(sprintf(
                    "(case
                            when purchase_orders.status in ('%s', '%s') then sum(ppo.received_quantity * ppo.unit_cost)
                            else sum(ppo.quantity * ppo.unit_cost)
                            end) as total_amount",
                    PurchaseOrderStatusEnum::PENDING_SHELVE,
                    PurchaseOrderStatusEnum::DONE
                )),
                'purchase_orders.status',
                "warehouses.name as warehouse_name",
            ]
        )
            ->join('suppliers', 'suppliers.id', 'purchase_orders.supplier_id')
            ->leftJoin('product_purchase_orders as ppo', 'ppo.purchase_order_id', 'purchase_orders.id')
            ->leftJoin('warehouses', 'warehouses.id', 'purchase_orders.warehouse_id');
        if ($params->search) {
            $query = $query->where(function ($query) use ($params) {
                $query->where('purchase_orders.code', 'like', "%{$params->search}%")
                    ->orWhere('scheduled_date', 'like', "%{$params->search}%")
                    ->orWhere('purchase_orders.created_at', 'like', "%{$params->search}%")
                    ->orWhere('suppliers.name', 'like', "%{$params->search}%")
                    ->orWhere('purchase_orders.status', 'like', "%{$params->search}%")
                    ->orWhere('warehouses.name', 'like', "%{$params->search}%");
            });
        }
        if (!empty($params->searchColumns['code'])) {
            $query = $query->where('purchase_orders.code', 'like', "%{$params->searchColumns['code']}%");
        }
        if (!empty($params->searchColumns['scheduled_date'])) {
            $query = $query->where('scheduled_date', 'like', "%{$params->searchColumns['scheduled_date']}%");
        }
        if (!empty($params->searchColumns['created_at'])) {
            $query = $query->where('purchase_orders.created_at', 'like', "%{$params->searchColumns['created_at']}%");
        }
        if (!empty($params->searchColumns['total_amount'])) {
            $query = $query->having(DB::raw(sprintf(
                "(case
                        when purchase_orders.status in ('%s', '%s') then sum(ppo.received_quantity * ppo.unit_cost)
                        else sum(ppo.quantity * ppo.unit_cost)
                        end)",
                PurchaseOrderStatusEnum::PENDING_SHELVE,
                PurchaseOrderStatusEnum::DONE
            )), '=', $params->searchColumns['total_amount']);
        }
        if (!empty($params->searchColumns['supplier_name'])) {
            $query = $query->where('suppliers.name', 'like', "%{$params->searchColumns['supplier_name']}%");
        }
        if (!empty($params->searchColumns['warehouse_name'])) {
            $query = $query->where('warehouses.name', 'like', "%{$params->searchColumns['warehouse_name']}%");
        }
        if (!empty($params->searchColumns['status'])) {
            $query = $query->where('purchase_orders.status', $params->searchColumns['status']);
        }
        if ($params->order) {
            $orderType = $params->order['type'] ?? 'asc';
            $orderBy = $params->order['field'];
            if ($orderBy == 'created_at') {
                $orderBy = 'purchase_orders.created_at';
            }
            if ($orderBy == 'supplier_name') {
                $orderBy = 'suppliers.name';
            }
            if ($orderBy == 'warehouse_name') {
                $orderBy = 'warehouses.name';
            }
            if ($orderBy == 'total_amount') {
                $orderBy = DB::raw('sum(ppo.quantity * ppo.unit_cost)');
            }
            $query = $query->orderBy($orderBy, $orderType);
        }
        $query = $query->groupBy('purchase_orders.id');
        return $query->paginate($params->length, $params->columns, 'page', $params->page);
    }

    /**
     * Get Total By Status
     *
     * @return array
     */
    public function getTotalByStatus(): array
    {
        return $this->getModel()->select(
            [
                DB::raw('count(id) as total'),
                'status'
            ]
        )
            ->groupBy('status')
            ->get()
            ->keyBy('status')
            ->toArray();
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
        $purchaseOrder = $this->findById($purchaseOrderId);
        if (!$purchaseOrder) {
            return false;
        }
        $unitCost = ProductPurchaseOrder::where('purchase_order_id', $purchaseOrderId)
            ->where('product_id', $productId)->first()->unit_cost;
        $purchaseProductShelveData = [];
        $productLocationData = [];
        foreach ($params['shelves'] as $shelve) {
            $purchaseProductShelveData = [
                'purchase_order_id' => $purchaseOrder->id,
                'product_id' => $productId,
                'shelve_id' => $shelve['id'],
                'quantity' => $shelve['quantity'],
                'created_at' => now(),
                'updated_at' => now()
            ];
            $productLocationData[] = [
                ...$purchaseProductShelveData,
                'unit_cost' => $unitCost
            ];
        }
        if ($purchaseProductShelveData && $productLocationData) {
            PurchaseProductShelve::insert($purchaseProductShelveData);
            return ProductLocation::insert($productLocationData);
        }
        return false;
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
        return $this->getModel()->with([
            'supplier' => function ($query) {
                $query->withTrashed();
            },
            'products' => function ($query) use ($productId) {
                $query->where('products.id', $productId);
            },
            'purchaseProductShelves' => function ($query) use ($productId) {
                $query->where('product_id', $productId);
            },
            'purchaseProductShelves.shelve',
            'products.suppliers'
        ])
            ->whereHas('products', function ($query) use ($productId) {
                $query->where('products.id', $productId);
            })->findOrFail($purchaseOrderId);
    }

    /**
     * Get Activity Purchase Order
     *
     * @param  string $id
     * @return array
     */
    public function getActivityPurchaseOrder(string $id): array
    {
        return ActivityLogs::select([
            '*'
        ])
            ->with('user')
            ->where('subject_id', $id)
            ->where('subject_type', PurchaseOrder::class)
            ->orderBy('id', 'desc')
            ->get()->toArray();
    }

    /**
     * Get List Batch
     *
     * @param  DatatableParams $params
     * @return LengthAwarePaginator|Paginator
     */
    public function getListBatch(DatatableParams $params): LengthAwarePaginator|Paginator
    {
        $pendingData = ProductPurchaseOrder::select('purchase_order_id', DB::raw('SUM(received_quantity) as total_received_quantity'))
            ->groupBy('purchase_order_id');

        $doneData = ProductLocation::select('purchase_order_id', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('purchase_order_id');

        $query = $this->getModel()->select(
            [
                'purchase_orders.id',
                'purchase_orders.batch_code as batchCode',
                'purchase_orders.received_date',
                'suppliers.name as supplier_name',
                "warehouses.name as storage_location",
                DB::raw(sprintf(
                    "(case
                        WHEN purchase_orders.status = '%s' THEN pending_data.total_received_quantity
                        WHEN purchase_orders.status = '%s' THEN done_data.total_quantity
                        ELSE 0
                    end) as number_of_products",
                    PurchaseOrderStatusEnum::PENDING_SHELVE,
                    PurchaseOrderStatusEnum::DONE
                )),
            ]
        )
            ->join('suppliers', 'suppliers.id', 'purchase_orders.supplier_id')
            ->leftJoin('warehouses', 'warehouses.id', 'purchase_orders.warehouse_id')
            ->leftJoinSub($pendingData, 'pending_data', function ($join) {
                $join->on('purchase_orders.id', '=', 'pending_data.purchase_order_id');
            })
            ->leftJoinSub($doneData, 'done_data', function ($join) {
                $join->on('purchase_orders.id', '=', 'done_data.purchase_order_id');
            })
            ->whereIn('purchase_orders.status', [PurchaseOrderStatusEnum::DONE, PurchaseOrderStatusEnum::PENDING_SHELVE]);
        if ($params->search) {
            $query = $query->where(function ($query) use ($params) {
                $query->where('purchase_orders.batch_code', 'like', "%{$params->search}%")
                    ->orWhere('purchase_orders.received_date', 'like', "%{$params->search}%")
                    ->orWhere('suppliers.name', 'like', "%{$params->search}%")
                    ->orWhere('warehouses.name', 'like', "%{$params->search}%");
            });
        }
        if (!empty($params->searchColumns['code'])) {
            $query = $query->where('purchase_orders.batch_code', 'like', "%{$params->searchColumns['code']}%");
        }
        if (!empty($params->searchColumns['received_date'])) {
            $query = $query->where('purchase_orders.received_date', 'like', "%{$params->searchColumns['received_date']}%");
        }
        if (!empty($params->searchColumns['supplier_name'])) {
            $query = $query->where('suppliers.name', 'like', "%{$params->searchColumns['supplier_name']}%");
        }
        if (!empty($params->searchColumns['storage_location'])) {
            $query = $query->where('warehouses.name', 'like', "%{$params->searchColumns['storage_location']}%");
        }
        if (!empty($params->searchColumns['number_of_products'])) {
            $query = $query->where(DB::raw(sprintf(
                "(case
                        WHEN purchase_orders.status = '%s' THEN pending_data.total_received_quantity
                        WHEN purchase_orders.status = '%s' THEN done_data.total_quantity
                        ELSE 0
                        end)",
                PurchaseOrderStatusEnum::PENDING_SHELVE,
                PurchaseOrderStatusEnum::DONE
            )), $params->searchColumns['number_of_products']);
        }
        if ($params->order) {
            $orderType = $params->order['type'] ?? 'asc';
            $orderBy = $params->order['field'];
            if ($orderBy == 'code') {
                $orderBy = 'purchase_orders.batch_code';
            }
            if ($orderBy == 'received_date') {
                $orderBy = 'purchase_orders.received_date';
            }
            if ($orderBy == 'supplier_name') {
                $orderBy = 'suppliers.name';
            }
            if ($orderBy == 'storage_location') {
                $orderBy = 'warehouses.name';
            }
            if ($orderBy == 'number_of_products') {
                $orderBy = DB::raw(sprintf(
                    "(case
                        WHEN purchase_orders.status = '%s' THEN pending_data.total_received_quantity
                        WHEN purchase_orders.status = '%s' THEN done_data.total_quantity
                        ELSE 0
                    end)",
                    PurchaseOrderStatusEnum::PENDING_SHELVE,
                    PurchaseOrderStatusEnum::DONE
                ));
            }
            $query = $query->orderBy($orderBy, $orderType);
        }
        $query = $query->groupBy(['purchase_orders.id']);
        return $query->paginate($params->length, $params->columns, 'page', $params->page);
    }

    /**
     * Get Batch
     *
     * @param  string $id
     * @return PurchaseOrder
     */
    public function getDetailBatch(string $id): PurchaseOrder
    {
        $batch = $this->getModel()->select(
            [
                'purchase_orders.id',
                'purchase_orders.received_date',
                'purchase_orders.scheduled_date',
                'purchase_orders.created_at',
                'purchase_orders.status',
                'purchase_orders.code',
                'purchase_orders.batch_code',
                'suppliers.name as supplier_name',
                "warehouses.name as storage_location",
            ]
        )
            ->join('suppliers', 'suppliers.id', 'purchase_orders.supplier_id')
            ->join('warehouses', 'warehouses.id', 'purchase_orders.warehouse_id')
            ->whereIn('purchase_orders.status', [PurchaseOrderStatusEnum::DONE, PurchaseOrderStatusEnum::PENDING_SHELVE])
            ->where('purchase_orders.id', $id)
            ->first();
        $products = DB::table('product_purchase_orders as ppo')
            ->select(
                'products.id',
                'products.code',
                'products.name',
                'products.deleted_at',
                'ppo.unit_cost',
                'ppo.received_quantity',
                'units.name as unit_name',
                'categories.name as category_name',
                DB::raw("concat(images.folder, '/', images.name)as image"),
                DB::raw(sprintf(
                    "(case
                                 when purchase_orders.status = '%s' then ppo.received_quantity
                                 else (select sum(pl.quantity) from product_locations as pl 
                                 where pl.purchase_order_id = %s and pl.product_id = products.id)
                                 end) as quantity",
                    PurchaseOrderStatusEnum::PENDING_SHELVE,
                    $id
                ))
            )
            ->join('purchase_orders', 'purchase_orders.id', 'ppo.purchase_order_id')
            ->join('products', 'products.id', 'ppo.product_id')
            ->leftJoin('images', 'images.entity_id', 'products.id')
            ->join('units', 'units.id', 'products.unit_id')
            ->join('categories', 'categories.id', 'products.category_id')
            ->where('ppo.purchase_order_id', $batch->id)
            ->get();

        $batch->products = $products;
        return $batch;
    }

    public function getReceiptIn(DatatableParams $params): LengthAwarePaginator|Paginator
    {
        $query = PurchaseOrder::select([
            'purchase_orders.id',
            'purchase_orders.created_at',
            'purchase_orders.status',
            'suppliers.name as supplier_name',
            DB::raw('CONCAT(warehouses.detail_address, ", ", warehouses.city, ", ", warehouses.province, ", ", warehouses.country) as shipping_address'),
            'purchase_orders.receipt_code',
            DB::raw('sum(product_purchase_orders.quantity) as quantity_demand'),
            DB::raw(sprintf("
            (case
                when purchase_orders.status in ('%s', '%s') then sum(product_purchase_orders.received_quantity)
                else null
            end)
            as quantity_received", PurchaseOrderStatusEnum::DONE, PurchaseOrderStatusEnum::PENDING_SHELVE))
        ])
            ->join('suppliers', 'suppliers.id', 'purchase_orders.supplier_id')
            ->join('product_purchase_orders', 'product_purchase_orders.purchase_order_id', 'purchase_orders.id')
            ->join('warehouses', 'warehouses.id', 'purchase_orders.warehouse_id')
            ->where('purchase_orders.status', '!=', PurchaseOrderStatusEnum::DRAFT)
            ->groupBy('purchase_orders.id');
        if ($params->search) {
            $query = $query->where(function ($query) use ($params) {
                $query->where('purchase_orders.receipt_code', 'like', "%{$params->search}%")
                    ->orWhere('suppliers.name', 'like', "%{$params->search}%")
                    ->orWhere(DB::raw('CONCAT(warehouses.detail_address, ", ", warehouses.city, ", ", warehouses.province, ", ", warehouses.country)'), 'like', "%{$params->search}%");
            });
        }
        if (!empty($params->searchColumns['receipt_code'])) {
            $query = $query->where('purchase_orders.receipt_code', 'like', "%{$params->searchColumns['receipt_code']}%");
        }
        if (!empty($params->searchColumns['shipping_address'])) {
            $query = $query->where(DB::raw('CONCAT(warehouses.detail_address, ", ", warehouses.city, ", ", warehouses.province, ", ", warehouses.country)'), 'like', "%{$params->searchColumns['shipping_address']}%");
        }
        if (!empty($params->searchColumns['supplier_name'])) {
            $query = $query->where('suppliers.name', 'like', "%{$params->searchColumns['supplier_name']}%");
        }
        if (!empty($params->searchColumns['status'])) {
            $status = [$params->searchColumns['status']];
            if ($status == PurchaseOrderStatusEnum::DONE) {
                $status = [
                    PurchaseOrderStatusEnum::DONE,
                    PurchaseOrderStatusEnum::PENDING_SHELVE
                ];
            }
            $query = $query->whereIn('purchase_orders.status', $status);
        }
        if ($params->order) {
            $orderType = $params->order['type'] ?? 'asc';
            $orderBy = $params->order['field'];
            if ($orderBy == 'receipt_code') {
                $orderBy = 'purchase_orders.receipt_code';
            }
            if ($orderBy == 'created_at') {
                $orderBy = 'purchase_orders.created_at';
            }
            if ($orderBy == 'supplier_name') {
                $orderBy = 'suppliers.name';
            }
            if ($orderBy == 'shipping_address') {
                $orderBy = DB::raw('CONCAT(warehouses.detail_address, ", ", warehouses.city, ", ", warehouses.province, ", ", warehouses.country)');
            }
            if ($orderBy == 'quantity_demand') {
                $orderBy = DB::raw('sum(product_purchase_orders.quantity)');
            }
            if ($orderBy == 'quantity_received') {
                $orderBy = DB::raw('sum(product_purchase_orders.received_quantity)');
            }
            $query = $query->orderBy($orderBy, $orderType);
        }
        return $query->paginate($params->length, $params->columns, 'page', $params->page);
    }

    /**
     * getPurchaseOrderProduct
     *
     * @param  int $id
     * @return Collection
     */
    public function getPurchaseOrderProduct(int $id): Collection
    {
        $product = Product::with(['category', 'unit', 'suppliers', 'images'])
            ->withTrashed()
            ->select([
                'products.*',
                'ppo.*',
                DB::raw('COALESCE(sum(rod.quantity), 0) as returned_quantity')
            ])
            ->join('product_purchase_orders as ppo', 'products.id', 'ppo.product_id')
            ->leftJoin('product_locations as pl', function ($join) use ($id) {
                $join->on('pl.product_id', '=', 'products.id')
                    ->where('pl.purchase_order_id', '=', $id);
            })
            ->leftJoin('return_order_details as rod', 'rod.product_location_id', 'pl.id')
            ->where('ppo.purchase_order_id', $id)
            ->groupBy('products.id')
            ->get();

        return $product;
    }
}
