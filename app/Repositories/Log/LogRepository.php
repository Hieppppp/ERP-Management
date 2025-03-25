<?php

namespace App\Repositories\Log;

use App\Common\Entity\DatatableParams;
use App\Repositories\BaseRepository;
use App\Models\ActivityLogs;
use Illuminate\Pagination\Paginator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class LogRepository extends BaseRepository implements LogRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(ActivityLogs::class);
    }

    /**
     * paginate
     *
     * @param  DatatableParams $params
     * @return Paginator|LengthAwarePaginator
     */
    public function paginate(DatatableParams $params): Paginator|LengthAwarePaginator
    {
        $query = $this->getModel()->select(
            [
                'activity_log.*',
                'activity_log.id',
                DB::raw('PaddedOrOriginalIfShorter(activity_log.id, 3, "0") as code'),
                'activity_log.created_at',
                'activity_log.subject_type as module',
                'activity_log.causer_id',
                'users.username as user',
                'activity_log.event as action'
            ]
        )->with('subject')
            ->leftJoin('users', 'users.id', 'activity_log.causer_id');

        if ($params->search) {
            $query = $query->where(function ($query) use ($params) {
                $query->where(DB::raw('PaddedOrOriginalIfShorter(activity_log.id, 3, "0") COLLATE utf8mb4_unicode_ci'), 'like', "%{$params->search}%")
                    ->orWhere('users.username', 'like', "%{$params->search}%");
            });
        }
        if (!empty($params->searchColumns['id'])) {
            $query = $query->where(DB::raw('PaddedOrOriginalIfShorter(activity_log.id, 3, "0") COLLATE utf8mb4_unicode_ci'), 'like', "%{$params->searchColumns['id']}%");
        }
        if (!empty($params->searchColumns['user'])) {
            $query = $query->where('users.username', 'like', "%{$params->searchColumns['user']}%");
        }
        if (!empty($params->searchColumns['created_at'])) {
            $query = $query->where('activity_log.created_at', 'like', "%{$params->searchColumns['created_at']}%");
        }
        if (!empty($params->searchColumns['module'])) {
            $query = $query->where('activity_log.subject_type', $params->searchColumns['module']);
        }
        if (!empty($params->searchColumns['action'])) {
            $query = $query->where('activity_log.event', $params->searchColumns['action']);
        }
        if ($params->order) {
            $orderType = $params->order['type'] ?? 'asc';
            $orderBy = $params->order['field'];
            $query = $query->orderBy($orderBy, $orderType);
        }
        return $query->paginate($params->length, $params->columns, 'page', $params->page);
    }
}
