<?php

namespace App\Console\Commands;

use App\Models\City;
use App\Models\Country;
use App\Models\Province;
use App\Models\Tax;
use Illuminate\Console\Command;

class SyncAddress extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-address';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Address';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $jsonString = file_get_contents(public_path('/assets/address/address.json'));
        $provinceData = json_decode($jsonString, true);
        $provinces = [];
        $cities = [];
        $taxProvinces = [];
        $provinceId = 1;
        $provinceInDatabase = Province::get();
        $cityInDatabase = City::get();
        $country = Country::where('name', 'Canada')->first();
        if (!$country) {
            $country = Country::create([
                'name' => 'Canada'
            ]);
        }
        if ($provinceInDatabase) {
            $provinceInDatabase = $provinceInDatabase->toArray();
            $provinceFinal = end($provinceInDatabase);
            if ($provinceFinal) {
                $provinceId = $provinceFinal['id'] + 1;
            }
        }
        if ($cityInDatabase) {
            $cityInDatabase = $cityInDatabase->toArray();
        }
        foreach ($provinceData as $province) {
            $taxProvinces[] = [
                'province' => $province['Province'],
                'tax' => $province['Tax']
            ];
            $provinceIdInDatabase = $this->getProvinceIdByName($province['Province'], $provinceInDatabase);
            if (
                ($provinceInDatabase && !in_array($province['Province'], array_column($provinceInDatabase, 'name')))
                || !$provinceInDatabase
            ) {
                $provinces[] = [
                    'id' => $provinceId,
                    'name' => $province['Province'],
                    'country_id' => $country->id
                ];
            }
            $city = [];
            if ($provinceIdInDatabase) {
                $city = $this->getCityByProvinceId($provinceIdInDatabase, $cityInDatabase);
            }
            $towns = array_unique(array_merge($province['City'], $province['Town']));
            if ($city) {
                $towns = array_diff($towns, array_column($city, 'name'));
            }
            foreach ($towns as $town) {
                $cities[] = [
                    'name' => $town,
                    'province_id' => $provinceIdInDatabase ?? $provinceId
                ];
            }
            if (!$provinceIdInDatabase) {
                $provinceId++;
            }
        }
        if ($provinces) {
            Province::insert($provinces);
        }
        if ($cities) {
            City::insert($cities);
        }
        if ($taxProvinces) {
            $this->insertProvinceTax($taxProvinces);
        }
        $this->info('Sync Success');
    }

    /**
     * Get Province Id By Name
     *
     * @param  string $name
     * @param  array|null $provinces
     * @return int|null
     */
    public function getProvinceIdByName(string $name, array|null $provinces): int | null
    {
        if (!$provinces) {
            return null;
        }
        $province = array_filter($provinces, function ($item) use ($name) {
            return $item['name'] === $name;
        });
        $province = reset($province);
        return $province ? $province['id'] : null;
    }

    /**
     * Get City By Province Id
     *
     * @param  int $provinceIds
     * @param  array $cities
     * @return array
     */
    public function getCityByProvinceId(int $provinceIds, array $cities): array
    {
        return array_filter($cities, function ($city) use ($provinceIds) {
            return $city['province_id'] === $provinceIds;
        });
    }

    /**
     * Insert Province Tax
     *
     * @param  array $taxProvinces
     * @return void
     */
    public function insertProvinceTax(array $taxProvinces)
    {
        if ($taxProvinces) {
            $provinces = Province::whereIn('name', array_column($taxProvinces, 'province'))->get();
            $taxes = Tax::all();
            $data = [];
            if ($provinces && $taxes) {
                foreach ($taxProvinces as $item) {
                    $provinceName = $item['province'];
                    $taxRates = $item['tax'];
                    $provinceId = null;
                    foreach ($provinces as $province) {
                        if ($province['name'] === $provinceName) {
                            $provinceId = $province['id'];
                            break;
                        }
                    }
                    foreach ($taxRates as $taxCode => $rate) {
                        $taxId = null;
                        foreach ($taxes as $tax) {
                            if ($tax['code'] === $taxCode) {
                                $taxId = $tax['id'];
                                break;
                            }
                        }

                        if ($provinceId !== null && $taxId !== null) {
                            $data[] = [
                                'province_id' => $provinceId,
                                'tax_id' => $taxId,
                                'rate' => $rate,
                            ];
                        }
                    }
                }
            }
            if ($data) {
                foreach ($provinces as $province) {
                    $dataSync = array_filter($data, function ($item) use ($province) {
                        return $item['province_id'] == $province['id'];
                    });
                    $province->taxes()->sync($dataSync);
                }
            }
        }
    }
}
