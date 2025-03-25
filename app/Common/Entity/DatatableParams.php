<?php

namespace App\Common\Entity;

class DatatableParams
{
    protected string|null $search = '';

    protected array $order = [];

    protected array $searchColumns = [];

    protected int $page = 0;

    protected int $length = 10;

    protected int $draw = 1;

    protected array $params = [];

    protected array $columns = [];

    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        } else {
            return isset($this->params[$name]) ? $this->params[$name] : '';
        }
    }

    public function __set($key, $value)
    {
        $this->$key = $value;
    }

    /**
     * createDatatableParams
     *
     * @param  array $fields
     * @param  array $params
     * @return array
     */
    public static function createDatatableParams(array $fields, array $params): array
    {
        $searchFields = $params['searchFields'] ?? [];
        $globalSearch = $params['globalSearch'] ?? null;
        $orders = $params['orders'] ?? [];
        $length = $params['length'] ?? 10;
        $additionalFields = $params['additionalFields'] ?? [];
        $start = $params['start'] ?? 0;

        $columns = [];
        foreach ($fields as $field) {
            $searchValue = isset($searchFields[$field]) ? $searchFields[$field] : null;

            $columns[] = [
                "data" => $field,
                "name" => $field,
                "searchable" => "true",
                "orderable" => $field !== 'function' ? "true" : "false",
                "search" => [
                    "value" => $searchValue,
                    "regex" => "false"
                ]
            ];
        }

        $orderArray = [];
        foreach ($orders as $key => $value) {
            if (is_numeric($key)) {
                $orderArray[] = [
                    "column" => array_search($value, $fields),
                    "dir" => "asc"
                ];
            } else {
                $orderArray[] = [
                    "column" => array_search($key, $fields),
                    "dir" => $value
                ];
            }
        }

        $params = [
            "draw" => "1",
            "start" => $start,
            "length" => $length,
            "search" => [
                "value" => $globalSearch,
                "regex" => "false"
            ],
            "columns" => $columns,
            "order" => $orderArray
        ];
        foreach ($additionalFields as $key => $value) {
            $params[$key] = $value;
        }

        return $params;
    }
}
