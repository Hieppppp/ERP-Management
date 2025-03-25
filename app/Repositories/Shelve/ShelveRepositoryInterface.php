<?php

namespace App\Repositories\Shelve;

use App\Repositories\BaseRepositoryInterface;

interface ShelveRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get Detail
     *
     * @param  string $id
     * @return array
     */
    public function getDetail(string $id): array;
}
