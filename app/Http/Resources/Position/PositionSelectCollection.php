<?php

namespace App\Http\Resources\Position;

use App\Http\Resources\BasePaginationCollection;
use Illuminate\Http\Request;


class PositionSelectCollection extends BasePaginationCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function transformer(Request $request): array
    {
        $pagination = parent::toArray($request);
        $pagination['data'] = $this->collection->transform(function($position) {
            return [
                'value' => $position->id,
                'text' => $position->name
            ];
        });
        return $pagination;
    }
}
