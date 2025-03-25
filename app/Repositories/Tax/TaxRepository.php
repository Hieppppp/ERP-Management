<?php

namespace App\Repositories\Tax;

use App\Repositories\BaseRepository;
use App\Models\Tax;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TaxRepository extends BaseRepository implements TaxRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Tax::class);
    }

    /**
     * search
     *
     * @param  array $params
     * @return LengthAwarePaginator
     */
    public function search(array $params): LengthAwarePaginator
    {
        $tax = $this->getModel()->select(["*"]);
        if (!empty($params['search'])) {
            $tax = $tax->where('code', 'like', "%{$params['search']}%");
        }
        return $tax->paginate();
    }
}
