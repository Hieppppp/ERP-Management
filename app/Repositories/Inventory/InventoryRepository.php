<?php

namespace App\Repositories\Inventory;

use App\Models\ProductLocation;
use App\Repositories\BaseRepository;
class InventoryRepository extends BaseRepository implements InventoryRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(ProductLocation::class);
    }
}
