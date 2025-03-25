<?php

namespace App\Http\Resources\Unit;

use App\Http\Resources\BasePaginationCollection;
use Illuminate\Http\Request;

class UnitSelectCollection extends BasePaginationCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function transformer(Request $request): array
    {
        $data = parent::toArray($request);
        $data['data'] = $this->collection->transform(function ($unit) {
            return [
                'value' => $unit->id,
                'text' => $unit->name
            ];
        });
        return $data;
    }
}
