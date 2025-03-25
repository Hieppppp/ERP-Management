<?php

namespace App\Http\Resources\Category;

use App\Http\Resources\BasePaginationCollection;
use Illuminate\Http\Request;

class CategorySelectCollection extends BasePaginationCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function transformer(Request $request): array
    {
        $pagination = parent::toArray($request);
        $pagination['data'] = $this->collection->transform(function ($category) {
            return [
                'value' => $category->id,
                'text' => $category->name
            ];
        });
        return $pagination;
    }
}
