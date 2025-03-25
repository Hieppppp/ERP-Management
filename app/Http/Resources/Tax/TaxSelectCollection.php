<?php

namespace App\Http\Resources\Tax;

use App\Http\Resources\BasePaginationCollection;
use Illuminate\Http\Request;

class TaxSelectCollection extends BasePaginationCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function transformer(Request $request): array
    {
        $data = parent::toArray($request);
        $data['data'] = $this->collection->transform(function ($tax) {
            return [
                'value' => $tax->id,
                'text' => $tax->code
            ];
        });
        return $data;
    }
}
