<?php

namespace App\Services\Position;

use App\Services\BaseService;
use App\Repositories\BaseRepository;
use App\Repositories\Position\PositionRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class PositionService extends BaseService implements PositionServiceInterface
{
    /**
     * PositionRepositoryInterface
     *
     * ?return void
     */
    public function __construct(
        BaseRepository $repository
    ) {
        parent::__construct($repository);
    }

    public function create(array $params): Model
    {
        $position = parent::create($params);
        $position->code = $this->createCode($position->id);
        $position->disableLogging()->save();
        return $position;
    }

    protected function createCode(int $positionId): string
    {
        return 'POS' . str_pad($positionId, 3, '0', STR_PAD_LEFT);
    }

    public function search(array $params): LengthAwarePaginator
    {
        return $this->repository->search($params);
    }
}
