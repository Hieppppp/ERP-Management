<?php

namespace App\Http\Requests;

use App\Common\Entity\DatatableParams;
use Exception;

abstract class BaseDatatableRequest extends BaseRequest
{
    protected DatatableParams $datatableParams;

    protected array $rulesDefault = [
        'draw' => 'required',
        'start' => 'required|integer',
        'length' => 'required|integer',
        'search' => 'required',
        'columns' => 'required',
        'order' => 'required'
    ];

    public function __construct()
    {
        $this->rulesDefault = array_merge($this->rulesDefault, $this->rules());
        $this->datatableParams = new DatatableParams();
    }

    protected function setSearch($params): void
    {
        $this->datatableParams->search = $params['search']['value'];
    }

    protected function setDraw($params): void
    {
        $this->datatableParams->draw = $params['draw'];
    }

    protected function setOrder($params): void
    {
        if (isset($params['order'][0]['column'])) {
            $columnIndex = $params['order'][0]['column'];
            $this->datatableParams->order = [
                'field' => $params['columns'][$columnIndex]['name'],
                'type' => $params['order'][0]['dir']
            ];
        }
    }

    protected function setSearchColumns($params): void
    {
        $data = [];
        foreach ($params['columns'] as $key => $column) {
            if (!empty($column['name'])) {
                $data[$column['name']] = $column['search']['value'];
            }
        }
        $this->datatableParams->searchColumns = $data;
    }

    protected function setPage($params): void
    {
        $start = $params['start'];
        $length = $params['length'];
        $this->datatableParams->page = (int)$start > 0 ? ($start / $length) + 1 : 1;
    }

    protected function setLength($params): void
    {
        $length = $params['length'];
        $this->datatableParams->length = (int)$length > 0 ? $length : $this->datatableParams->length;
    }

    protected function setParams($params): void
    {
        $this->datatableParams->params = $params;
    }

    protected function setColumns($params): void
    {
        $this->datatableParams->columns = array_filter(array_column($params['columns'], 'name'));
    }

    private function setProperties($params)
    {
        $properties = $this->getProperties();
        foreach ($properties as $property) {
            $setterMethod = 'set' . ucfirst($property);
            if (method_exists($this, $setterMethod)) {
                $this->$setterMethod($params);
            } else {
                throw new Exception("Setter method $setterMethod does not exist.");
            }
        }
    }

    private function getProperties(): array
    {
        $properties = [];
        $reflection = new \ReflectionClass($this->datatableParams);

        foreach ($reflection->getProperties(\ReflectionProperty::IS_PROTECTED) as $property) {
            $property->setAccessible(true);
            $properties[] = $property->getName();
        }

        return $properties;
    }

    public function validatedDatatable(): DatatableParams | bool
    {
        $params = data_get($this->validate($this->rulesDefault), null);
        $params = $this->setProperties($params);
        return $this->datatableParams;
    }

    /**
     * Get Datatable Params With Params
     *
     * @param  array $params
     * @return DatatableParams
     */
    public function getDatatableParamsWithParams(array $params): DatatableParams
    {
        $params = $this->setProperties($params);
        return $this->datatableParams;
    }
}
