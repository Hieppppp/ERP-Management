<?php

namespace App\Repositories\Category;

use App\Common\Entity\DatatableParams;
use App\Repositories\BaseRepository;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Pagination\Paginator as PaginationPaginator;
use Illuminate\Support\Facades\DB;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Category::class);
    }

    /**
     * paginate
     *
     * @param  DatatableParams $params
     * @return Paginator|LengthAwarePaginator
     */
    public function paginate(DatatableParams $params): PaginationPaginator|LengthAwarePaginator
    {
        $query = $this->getModel()->select([
            'categories.*',
            'product_count as product_quantity'
        ])
            ->leftJoinSub(
                Product::select('category_id', DB::raw('COUNT(id) as product_count'))
                    ->groupBy('category_id'),
                'product_counts',
                function ($join) {
                    $join->on('categories.id', '=', 'product_counts.category_id');
                }
            );
        if ($params->search) {
            $query = $query->where(function ($query) use ($params) {
                $query->where('categories.id', 'like', "%{$params->search}%")
                    ->orWhere('categories.name', 'like', "%{$params->search}%")
                    ->orWhere('categories.description', 'like', "%{$params->search}%")
                    ->orWhere('categories.created_at', 'like', "%{$params->search}%")
                    ->orWhere('product_counts.product_count', 'like', "%{$params->search}%");
            });
        }

        if (!empty($params->searchColumns['id'])) {
            $query = $query->where('categories.id', 'like', "%{$params->searchColumns['id']}%");
        }

        if (!empty($params->searchColumns['name'])) {
            $query = $query->where('categories.name', 'like', "%{$params->searchColumns['name']}%");
        }

        if (!empty($params->searchColumns['product_quantity'])) {
            $query = $query->having('product_counts.product_count', 'like', "%{$params->searchColumns['product_quantity']}%");
        }

        if (!empty($params->searchColumns['description'])) {
            $query = $query->where('categories.description', 'like', "%{$params->searchColumns['description']}%");
        }

        if (!empty($params->searchColumns['created_at'])) {
            $query = $query->where('categories.created_at', 'like', "%{$params->searchColumns['created_at']}%");
        }
        $query = $query->groupBy('categories.id');
        if ($params->order) {
            $orderType = $params->order['type'] ?? 'asc';
            $orderBy = $params->order['field'];
            if ($orderBy == 'product_quantity') {
                $orderBy = 'product_counts.product_count';
            }
            $query = $query->orderBy($orderBy, $orderType);
        }

        return $query->paginate($params->length, $params->columns, 'page', $params->page);
    }

    /**
     * search
     *
     * @param  array $params
     * @return LengthAwarePaginator
     */
    public function search(array $params): LengthAwarePaginator
    {
        $category = $this->getModel()->select(["*"]);
        if (!empty($params['search'])) {
            $category = $category->where('name', 'like', "%{$params['search']}%");
        }
        return $category->paginate();
    }
}
