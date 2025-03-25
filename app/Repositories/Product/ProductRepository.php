<?php

namespace App\Repositories\Product;

use App\Common\Entity\DatatableParams;
use App\Repositories\BaseRepository;
use App\Models\Product;
use App\Models\ProductLocation;
use App\Models\Shelve;
use Illuminate\Pagination\Paginator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Product::class);
    }

    /**
     * paginate
     *
     * @param  DatatableParams $params
     * @return Paginator
     */
    public function paginate(DatatableParams $params): Paginator|LengthAwarePaginator
    {
        $query = $this->getModel()->with('images');
        $query = $query->select([
            'products.*',
            'ct.name as category_name',
            DB::raw("concat(u.name, '(', u.symbol, ')') as unit_name"),
            DB::raw("COALESCE(SUM(pl.quantity), 0) as quantity")
        ]);
        $query = $query->leftJoin('categories as ct', 'ct.id', 'products.category_id')
            ->leftJoin('units as u', 'u.id', 'products.unit_id')
            ->leftJoin('product_locations as pl', 'pl.product_id', 'products.id')
            ->where('products.deleted_at', null);

        if ($params->search) {
            $query = $query->where(function ($query) use ($params) {
                $query->where('products.code', 'like', "%{$params->search}%")
                    ->orWhere('products.name', 'like', "%{$params->search}%")
                    ->orWhere('products.description', 'like', "%{$params->search}%")
                    ->orWhere('ct.name', 'like', "%{$params->search}%")
                    ->orWhere('products.unit_price', 'like', "%{$params->search}%")
                    ->orWhere('products.sku', 'like', "%{$params->search}%")
                    ->orWhere('u.name', 'like', "%{$params->search}%");
            });
        }
        if (!empty($params->params['excludeProductIds'])) {
            $excludeProductIds = explode(',', $params->params['excludeProductIds']);
            $query = $query->whereNotIn('products.id', $excludeProductIds);
        }
        if (!empty($params->params['includeProductIds'])) {
            $includeProductIds = explode(',', $params->params['includeProductIds']);
            $query = $query->whereIn('products.id', $includeProductIds);
        }
        if (!empty($params->params['includeSupplierIds'])) {
            $includeSupplierIds = explode(',', $params->params['includeSupplierIds']);
            $query = $query->with('suppliers')->whereHas('suppliers', function ($query) use ($includeSupplierIds) {
                $query->whereIn('id', $includeSupplierIds);
            });
        }

        if (!empty($params->searchColumns['name'])) {
            $query = $query->where('products.name', 'like', "%{$params->searchColumns['name']}%");
        }
        if (!empty($params->searchColumns['code'])) {
            $query = $query->where('products.code', 'like', "%{$params->searchColumns['code']}%");
        }
        if (!empty($params->searchColumns['description'])) {
            $query = $query->where('products.description', 'like', "%{$params->searchColumns['description']}%");
        }
        if (!empty($params->searchColumns['sku'])) {
            $query = $query->where('products.sku', 'like', "%{$params->searchColumns['sku']}%");
        }
        if (!empty($params->searchColumns['category_name'])) {
            $query = $query->where('ct.name', 'like', "%{$params->searchColumns['category_name']}%");
        }
        if (!empty($params->searchColumns['unit_price'])) {
            $query = $query->where('products.unit_price', 'like', "%{$params->searchColumns['unit_price']}%");
        }
        if (!empty($params->searchColumns['unit_name'])) {
            $query = $query->where('u.name', 'like', "%{$params->searchColumns['unit_name']}%");
        }
        if (!empty($params->searchColumns['max_quantity'])) {
            $query = $query->where('products.max_quantity', 'like', "%{$params->searchColumns['max_quantity']}%");
        }
        if (!empty($params->searchColumns['min_quantity'])) {
            $query = $query->where('products.min_quantity', 'like', "%{$params->searchColumns['min_quantity']}%");
        }

        if ($params->filters) {
            foreach ($params->filters as $filter) {
                if ($filter['column'] == 'quantity') {
                    $conditions = [
                        'optimal' => [
                            ['>', 'min_quantity + 20']
                        ],
                        'approaching' => [
                            ['>', 'min_quantity + 10'],
                            ['<=', 'min_quantity + 20']
                        ],
                        'reached' => [
                            ['>', 'min_quantity'],
                            ['<=', 'min_quantity + 10']
                        ],
                        'below' => [
                            ['<=', 'min_quantity']
                        ],
                    ];

                    if (array_key_exists($filter['condition'], $conditions)) {
                        $condition = $conditions[$filter['condition']];
                        foreach ($condition as $key => $val) {
                            $query = $query->having('quantity', $val[0], DB::raw($val[1]));
                        }
                    }
                } else if ($filter['column'] == 'supplier') {
                    $supplierId = $filter['value'];
                    if ($filter['condition'] == 'in') {
                        $query = $query->whereHas('suppliers', function ($query) use ($supplierId) {
                            $query->where('suppliers.id', $supplierId);
                        });
                    }
                    if ($filter['condition'] == 'notIn') {
                        $query = $query->whereDoesntHave('suppliers', function ($query) use ($supplierId) {
                            $query->where('suppliers.id', $supplierId);
                        });
                    }
                } else {
                    $query = $query->where($filter['column'], $filter['condition'], $filter['value']);
                }
            }
        }

        if ($params->order) {
            $orderType = $params->order['type'] ?? 'asc';
            $orderBy = $params->order['field'];
            if ($orderBy == 'unit_name') {
                $orderBy = 'u.name';
            }
            if ($orderBy == 'category_name') {
                $orderBy = 'ct.name';
            }
            $query = $query->orderBy($orderBy, $orderType);
        }
        $query = $query->groupBy('products.id');
        return $query->paginate($params->length, ['*'], 'page', $params->page);
    }

    /**
     * Find By Id
     *
     * @param  string|int $id
     * @param  array $columns
     * @return Collection|Model
     */
    public function findById(string|int $id, array $columns = ['*']): Collection|Model
    {
        $product = $this->getModel()
            ->with([
                'parentProducts' => function ($query) {
                    $query->select('products.*')
                        ->leftJoin('product_locations as pl', 'pl.product_id', 'products.id')
                        ->selectRaw('COALESCE(sum(pl.quantity), 0) as quantity')
                        ->groupBy('products.id');
                },
                'childProducts',
                'suppliers',
                'images',
                'parentProducts.images',
                'childProducts.images'
            ])
            ->select([
                'products.*',
                'ct.name as category_name',
                DB::raw("concat(u.name, '(', u.symbol, ')') as unit_name"),
                DB::raw("COALESCE(sum(pl.quantity), 0) as quantity"),
            ])
            ->join('categories as ct', 'ct.id', 'products.category_id')
            ->join('units as u', 'u.id', 'products.unit_id')
            ->leftJoin('product_locations as pl', 'pl.product_id', 'products.id')
            ->groupBy('products.id')
            ->findOrFail($id);
        $shelves = Shelve::withTrashed()->select([
            'shelves.id',
            'shelves.code',
            'shelves.name',
            'warehouses.name as warehouse_name',
            'shelves.warehouse_id',
            'shelves.location',
            DB::raw("COALESCE(sum(plq.quantity), 0) as quantity")
        ])
            ->join('product_locations as plq', 'plq.shelve_id', 'shelves.id')
            ->join('warehouses', 'warehouses.id', 'shelves.warehouse_id')
            ->where('plq.product_id', $id)
            ->groupBy('shelves.id')
            ->having('quantity', '>', 0)
            ->get();
        $product->shelves = $shelves;
        return $product;
    }

    /**
     * inventory
     *
     * @param  DatatableParams $params
     * @param  int $id
     * @return Paginator
     */
    public function inventory(DatatableParams $params, int $id): Paginator|LengthAwarePaginator
    {
        $query = ProductLocation::select([
            's.code as shelves_code',
            's.name as shelves_name',
            'w.name as warehouse_name',
            'w.code as warehouse_code',
            's.location',
            'sup.name as supplier_name',
            'product_locations.quantity',
            'product_locations.updated_at as last_updated_date',
            'product_locations.id',
            's.id as shelve_id',
            'w.id as warehouse_id',
            'sup.id as supplier_id',
            'po.batch_code',
            'po.received_date'
        ])
            ->join('purchase_orders as po', 'po.id', 'product_locations.purchase_order_id')
            ->join('suppliers as sup', 'sup.id', 'po.supplier_id')
            ->join('shelves as s', 's.id', 'product_locations.shelve_id')
            ->join('warehouses as w', 'w.id', 's.warehouse_id')
            ->where('product_locations.product_id', $id)
            ->where('product_locations.quantity', '>', 0);
        if ($params->warehouseId) {
            $query->where('w.id', $params->warehouseId);
        }
        if ($params->search) {
            $query = $query->where(function ($query) use ($params) {
                $query->where('s.code', 'like', "%{$params->search}%");
                foreach ($params->columns as $column) {
                    if ($column == 'warehouse_name') {
                        $query->orWhere('w.name', 'like', "%{$params->search}%");
                    }
                    if ($column == 'location') {
                        $query->orWhere('s.location', 'like', "%{$params->search}%");
                    }
                    if ($column == 'supplier_name') {
                        $query->orWhere('sup.name', 'like', "%{$params->search}%");
                    }
                    if ($column == 'quantity') {
                        $query->orWhere('product_locations.quantity', 'like', "%{$params->search}%");
                    }
                    if ($column == 'last_updated_date') {
                        $query->orWhere('product_locations.updated_at', 'like', "%{$params->search}%");
                    }
                    if ($column == 'batch_code') {
                        $query->orWhere('po.batch_code', 'like', "%{$params->search}%");
                    }
                    if ($column == 'received_date') {
                        $query->orWhere('po.received_date', 'like', "%{$params->search}%");
                    }
                }
            });
        }

        if (!empty($params->searchColumns['shelves_code'])) {
            $query = $query->where('s.code', 'like', "%{$params->searchColumns['shelves_code']}%");
        }

        if (!empty($params->searchColumns['warehouse_name'])) {
            $query = $query->where('w.name', 'like', "%{$params->searchColumns['warehouse_name']}%");
        }

        if (!empty($params->searchColumns['location'])) {
            $query = $query->where('s.location', 'like', "%{$params->searchColumns['location']}%");
        }

        if (!empty($params->searchColumns['supplier_name'])) {
            $query = $query->where('sup.name', 'like', "%{$params->searchColumns['supplier_name']}%");
        }

        if (!empty($params->searchColumns['quantity'])) {
            $query = $query->where('product_locations.quantity', 'like', "%{$params->searchColumns['quantity']}%");
        }

        if (!empty($params->searchColumns['last_updated_date'])) {
            $query = $query->where('product_locations.updated_at', 'like', "%{$params->searchColumns['last_updated_date']}%");
        }

        if (!empty($params->searchColumns['batch_code'])) {
            $query = $query->where('po.batch_code', 'like', "%{$params->searchColumns['batch_code']}%");
        }

        if (!empty($params->searchColumns['received_date'])) {
            $query = $query->where('po.received_date', 'like', "%{$params->searchColumns['received_date']}%");
        }

        if ($params->order) {
            $orderType = $params->order['type'] ?? 'asc';
            $orderBy = $params->order['field'];
            $query = $query->orderBy($orderBy, $orderType);
        }

        return $query->paginate($params->length, ['*'], 'page', $params->page);
    }
}
