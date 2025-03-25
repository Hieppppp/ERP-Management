<?php

namespace App\Services\Log;

use App\Services\BaseService;
use App\Repositories\BaseRepository;
use App\Repositories\Log\LogRepositoryInterface;

class LogService extends BaseService implements LogServiceInterface
{
    /**
     * LogRepositoryInterface
     *
     * ?return void
     */
    public function __construct(
        BaseRepository $repository
    ) {
        parent::__construct($repository);
    }
}
