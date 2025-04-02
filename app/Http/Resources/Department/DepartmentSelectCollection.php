<?php

namespace App\Http\Resources\Department;

use App\Http\Resources\BasePaginationCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DepartmentSelectCollection extends BasePaginationCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function transformer(Request $request): array
    {
        $pagination = parent::toArray($request);
        $pagination['data'] = $this->collection->transform(function ($department) {
            return [
                'value' => $department->id,
                'text' => $department->name
            ];
        });
        return $pagination;
    }
}
