<?php

namespace App\Repositories\Province;

use App\Repositories\BaseRepository;
use App\Models\Province;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use phpseclib3\File\ASN1\Maps\CountryName;

class ProvinceRepository extends BaseRepository implements ProvinceRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Province::class);
    }

    /**
     * search
     *
     * @param  array $params
     * @return LengthAwarePaginator
     */
    public function search(array $params): LengthAwarePaginator
    {
        $provinces = $this->getModel()->select('provinces.*');
        if (!empty($params['search'])) {
            $provinces = $provinces->where('name', 'like', "%{$params['search']}%");
        }
        if (!empty($params['countryId'])) {
            $provinces = $provinces->where('country_id',  $params['countryId']);
        }
        if (!empty($params['countryName'])) {
            $provinces = $provinces->join('countries as c', 'c.id', 'provinces.country_id')
                ->where('c.name', $params['countryName']);
        }
        return $provinces->paginate();
    }
}
