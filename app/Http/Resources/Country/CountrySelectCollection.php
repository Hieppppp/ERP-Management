<?php

namespace App\Http\Resources\Country;

use App\Http\Resources\BasePaginationCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CountrySelectCollection extends BasePaginationCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);
        $data['data'] = $this->collection->transform(function ($country) {
            return [
                'country_id' => $country->id,
                'value' => request('value') ? $country->id : $country->name,
                'text' => $country->name
            ];
        });
        return $data;
    }
}
