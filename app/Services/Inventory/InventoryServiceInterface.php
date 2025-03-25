<?php

namespace App\Services\Inventory;

use App\Services\BaseServiceInterface;

interface InventoryServiceInterface extends BaseServiceInterface
{
    /**
     * movement
     *
     * @param  array $params
     * @param  int $productLocationId
     * @return bool
     */
    public function movement(array $params, int $productLocationId): bool;
}
