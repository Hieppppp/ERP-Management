<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

abstract class BaseExport implements FromArray, WithHeadings
{
    protected array $dataExport;
    protected array $headerExport;
    public function __construct(array $dataExport, array $headerExport)
    {
        $this->dataExport = $dataExport;
        $this->headerExport = $headerExport;
    }

    public function array(): array
    {
        return array_map(function ($row) {
            $result = [];
            foreach (array_keys($this->headerExport) as $key) {
                $result[] = $row[$key] ?? '';
            }
            return $result;
        }, $this->dataExport);
    }

    public function headings(): array
    {
        return array_values($$this->headerExport);
    }
}
