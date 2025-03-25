<?php

namespace App\Http\Resources\City;

use App\Http\Resources\BasePaginationCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CitySelectCollection extends BasePaginationCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);
        $data['data'] = $this->collection->transform(function ($province) {
            return [
                'value' => $province->name,
                'text' => $province->name
            ];
        });
        return $data;
    }
}
