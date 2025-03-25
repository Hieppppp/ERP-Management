<?php

namespace App\Repositories\City;

use App\Repositories\BaseRepository;
use App\Models\City;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CityRepository extends BaseRepository implements CityRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(City::class);
    }

    /**
     * search
     *
     * @param  array $params
     * @return LengthAwarePaginator
     */
    public function search(array $params): LengthAwarePaginator
    {
        $cities = $this->getModel()->select('cities.*');
        if (!empty($params['search'])) {
            $cities = $cities->where('name', 'like', "%{$params['search']}%");
        }
        if (!empty($params['provinceId'])) {
            $cities = $cities->where('province_id',  $params['provinceId']);
        }
        if (!empty($params['provinceName']) && !empty($params['countryName'])) {
            $cities = $cities->join('provinces as p', 'p.id', 'cities.province_id')
                ->join('countries as c', 'c.id', 'p.country_id')
                ->where('p.name', $params['provinceName'])
                ->where('c.name', $params['countryName']);
        }


        return $cities->paginate();
    }
}
