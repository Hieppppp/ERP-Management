<?php

namespace App\Services\Shelve;

use App\Services\BaseServiceInterface;

interface ShelveServiceInterface extends BaseServiceInterface
{
    /**
     * Get Detail
     *
     * @param  string $id
     * @return array
     */
    public function getDetail(string $id): array;
}
