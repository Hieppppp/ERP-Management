<?php

namespace App\Http\Resources\Supplier;

use App\Http\Resources\BasePaginationCollection;
use Illuminate\Http\Request;

class SupplierSelectCollection extends BasePaginationCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function transformer(Request $request): array
    {
        $pagination = parent::toArray($request);
        $pagination['data'] = $this->collection->transform(function ($supplier) {
            return [
                'value' => $supplier->id,
                'text' => $supplier->name
            ];
        });
        return $pagination;
    }
}
