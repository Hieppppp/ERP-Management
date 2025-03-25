<?php

namespace App\Services\Supplier;

use App\Helpers\FileHelper;
use App\Models\Product;
use App\Services\BaseService;
use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Repositories\Supplier\SupplierRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SupplierService extends BaseService implements SupplierServiceInterface
{
    /**
     * @param SupplierRepository $repository
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
        if (!empty($params['logo'])) {
            $logo = FileHelper::uploadFile($params['logo'], 'suppliers');
            $params['logo'] = $logo['fileName'];
        }
        $supplier = parent::create($params);
        $supplier->code = $this->createCode($supplier->id);
        $supplier->disableLogging()->save();
        return $supplier;
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
        if (!empty($params['logo'])) {
            $supplier = $this->findById($id);
            $oldLogo = $supplier->logo;
            $logo = FileHelper::uploadFile($params['logo'], 'suppliers');
            if ($oldLogo) {
                FileHelper::deleteFile($oldLogo, 'suppliers');
            }
            $params['logo'] = $logo['fileName'];
        }
        return parent::update($params, $id);
    }

    /**
     * search
     *
     * @param  array $params
     * @return LengthAwarePaginator
     */
    public function search(array $params): LengthAwarePaginator
    {
        return $this->repository->search($params);
    }

    /**
     * Get Detail
     *
     * @param  string $id
     * @return array
     */
    public function getDetail(string $id): array
    {
        $supplier = $this->findById($id);
        $products = Product::with(['category', 'unit', 'images'])->join('product_suppliers', 'products.id', '=', 'product_suppliers.product_id')
            ->leftJoin('product_locations', 'products.id', '=', 'product_locations.product_id')
            ->leftJoin('purchase_orders', 'product_locations.purchase_order_id', '=', 'purchase_orders.id')
            ->where('product_suppliers.supplier_id', $id)
            ->where(function ($query) use ($id) {
                $query->where('purchase_orders.supplier_id', $id)
                    ->orWhereNull('purchase_orders.supplier_id');
            })
            ->select('products.*', 'product_suppliers.sku', 'product_suppliers.unit_cost', DB::raw('COALESCE(SUM(product_locations.quantity), 0) as total_quantity'))
            ->groupBy('products.id')
            ->get();
        $activities = $supplier->activities()->with(['causer' => function ($query) {
            $query->withTrashed();
        }])->orderBy('id', 'desc')->get();
        return ['supplier' => $supplier, 'products' => $products, 'activities' => $activities];
    }

    /**
     * createCode
     *
     * @param  int $supplierId
     * @return string
     */
    protected function createCode(int $supplierId): string
    {
        return 'SUP' . str_pad($supplierId, 3, '0', STR_PAD_LEFT);
    }
}
