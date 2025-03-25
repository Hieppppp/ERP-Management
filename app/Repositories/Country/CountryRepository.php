<?php

namespace App\Repositories\Country;

use App\Repositories\BaseRepository;
use App\Models\Country;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CountryRepository extends BaseRepository implements CountryRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Country::class);
    }

    /**
     * search
     *
     * @param  array $params
     * @return LengthAwarePaginator
     */
    public function search(array $params): LengthAwarePaginator
    {
        $countries = $this->getModel()->select('*');
        if (!empty($params['search'])) {
            $countries = $countries->where('name', 'like', "%{$params['search']}%");
        }
        return $countries->paginate();
    }
}
