<?php

namespace App\Repositories\Permission;

use App\Repositories\BaseRepository;
use App\Models\Permission;

class PermissionRepository extends BaseRepository implements PermissionRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Permission::class);
    }
}
