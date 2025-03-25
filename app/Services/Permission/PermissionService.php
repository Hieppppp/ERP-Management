<?php

namespace App\Services\Permission;

use App\Services\BaseService;
use App\Repositories\BaseRepository;
use App\Repositories\Permission\PermissionRepositoryInterface;

class PermissionService extends BaseService implements PermissionServiceInterface
{
    /**
     * PermissionRepositoryInterface
     *
     * ?return void
     */
    public function __construct(
        BaseRepository $repository
    ) {
        parent::__construct($repository);
    }
}
