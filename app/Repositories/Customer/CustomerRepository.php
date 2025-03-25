<?php

namespace App\Repositories\Customer;

use App\Common\Entity\DatatableParams;
use App\Repositories\BaseRepository;
use App\Models\Customer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class CustomerRepository extends BaseRepository implements CustomerRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Customer::class);
    }

    /**
     * paginate
     *
     * @param  DatatableParams $params
     * @return Paginator|LengthAwarePaginator
     */
    public function paginate(DatatableParams $params): Paginator|LengthAwarePaginator
    {
        $query = $this->getModel()
            ->select([
                'customers.*',
                DB::raw("concat(customers.first_name, ' ', customers.last_name) as name"),
                DB::raw("concat(customers.detail_address, ', ', customers.city, ', ', customers.province, ', ', customers.country) as address"),
                DB::raw(
                    "JSON_ARRAYAGG(
                        JSON_OBJECT(
                            'code', t.code,
                            'rate', tp.rate
                        )
                    ) as taxes"
                )
            ])
            ->leftJoin('provinces as p', 'p.name', 'customers.province')
            ->leftJoin('tax_provinces as tp', 'tp.province_id', 'p.id')
            ->leftJoin('taxes as t', 't.id', 'tp.tax_id')
            ->groupBy('customers.id');
        if ($params->search) {
            $query = $query->where(function ($query) use ($params) {
                $query->where('customers.code', 'like', "%{$params->search}%")
                    ->orWhere(DB::raw("concat(customers.first_name, ' ', customers.last_name)"), 'like', "%{$params->search}%")
                    ->orWhere('customers.email', 'like', "%{$params->search}%")
                    ->orWhere('customers.phone', 'like', "%{$params->search}%")
                    ->orWhere(DB::raw("concat(customers.detail_address, ', ', customers.city, ', ', customers.province, ', ', customers.country)"), 'like', "%{$params->search}%")
                    ->orWhere('customers.created_at', 'like', "%{$params->search}%")
                    ->orWhere('customers.postal_code', 'like', "%{$params->search}%");
            });
        }

        if (!empty($params->searchColumns['code'])) {
            $query = $query->where('customers.code', 'like', "%{$params->searchColumns['code']}%");
        }

        if (!empty($params->searchColumns['name'])) {
            $query = $query->where(DB::raw("concat(customers.first_name, ' ', customers.last_name)"), 'like', "%{$params->searchColumns['name']}%");
        }

        if (!empty($params->searchColumns['email'])) {
            $query = $query->where('customers.email', 'like', "%{$params->searchColumns['email']}%");
        }

        if (!empty($params->searchColumns['phone'])) {
            $query = $query->where('customers.phone', 'like', "%{$params->searchColumns['phone']}%");
        }

        if (!empty($params->searchColumns['created_at'])) {
            $query = $query->where('customers.created_at', 'like', "%{$params->searchColumns['created_at']}%");
        }

        if (!empty($params->searchColumns['address'])) {
            $query = $query->where(DB::raw("concat(customers.detail_address, ', ',  customers.city, ', ', customers.province, ', ', customers.country)"), 'like', "%{$params->searchColumns['address']}%");
        }

        if (!empty($params->searchColumns['postal_code'])) {
            $query = $query->where('customers.postal_code', 'like', "%{$params->searchColumns['postal_code']}%");
        }

        if ($params->order) {
            $orderType = $params->order['type'] ?? 'asc';
            $orderBy = $params->order['field'];
            if ($orderBy == 'name') {
                $orderBy = DB::raw("concat(customers.first_name, ' ', customers.last_name)");
            }
            if ($orderBy == 'address') {
                $orderBy = DB::raw("concat(customers.detail_address, ', ', customers.city, ', ', customers.province, ', ', customers.country)");
            }
            if ($orderBy == 'created_at') {
                $orderBy = 'customers.created_at';
            }
            $query = $query->orderBy($orderBy, $orderType);
        }

        return $query->paginate($params->length, ['*'], 'page', $params->page);
    }

    /**
     * search
     *
     * @param  array $params
     * @return LengthAwarePaginator
     */
    public function search(array $params): LengthAwarePaginator
    {
        $customer = $this->getModel()->select(['*']);
        if (!empty($params['search'])) {
            $customer = $customer->where(DB::raw("concat(first_name, ' ' , last_name)"), 'like', "%{$params['search']}%");
        }
        return $customer->paginate();
    }
}
