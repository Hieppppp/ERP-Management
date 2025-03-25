<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Request;
use App\Http\Resources\BasePaginationCollection;

class CustomerSelectCollection extends BasePaginationCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function transformer(Request $request): array
    {
        $pagination = parent::toArray($request);
        $pagination['data'] = $this->collection->transform(function ($customer) {
            return [
                'value' => $customer->id,
                'text' => "{$customer->first_name} {$customer->last_name}",
            ];
        });
        return $pagination;
    }
}
