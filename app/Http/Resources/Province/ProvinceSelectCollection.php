<?php

namespace App\Http\Resources\Province;

use App\Http\Resources\BasePaginationCollection;
use Illuminate\Http\Request;

class ProvinceSelectCollection extends BasePaginationCollection
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
                'province_id' => $province->id,
                'value' => request('value') ? $province->id : $province->name,
                'text' => $province->name
            ];
        });
        return $data;
    }
}
