<?php

namespace App\Repositories\User;

use App\Enums\UserRole;
use App\Common\Entity\DatatableParams;
use App\Repositories\BaseRepository;
use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{

    public function __construct()
    {
        parent::__construct(User::class);
    }

    /**
     * paginate
     *
     * @param  DatatableParams $params $params
     * @return Paginator|LengthAwarePaginator
     */
    public function paginate(DatatableParams $params): Paginator|LengthAwarePaginator
    {
        $query = $this->getModel()->append('profile_picture')
            ->where('users.role', '!=', UserRole::SUPPER_ADMIN);
        if ($params->search) {
            $query = $query->where(function ($query) use ($params) {
                $query->where('users.id', 'like', "%{$params->search}%")
                    ->orWhere('username', 'like', "%{$params->search}%")
                    ->orWhere('email', 'like', "%{$params->search}%")
                    ->orWhere('created_at', 'like', "%{$params->search}%");
            });
        }

        if (!empty($params->searchColumns['id'])) {
            $query = $query->where('users.id', 'like', "%{$params->searchColumns['id']}%");
        }

        if (!empty($params->searchColumns['username'])) {
            $query = $query->where('username', 'like', "%{$params->searchColumns['username']}%");
        }

        if (!empty($params->searchColumns['email'])) {
            $query = $query->where('email', 'like', "%{$params->searchColumns['email']}%");
        }

        if (!empty($params->searchColumns['created_at'])) {
            $query = $query->where('created_at', 'like', "%{$params->searchColumns['created_at']}%");
        }

        if (!empty($params->searchColumns['role'])) {
            $query = $query->where('users.role', 'like', "%{$params->searchColumns['role']}%");
        }

        if ($params->order) {
            $orderType = $params->order['type'] ?? 'asc';
            $orderBy = $params->order['field'];
            $query = $query->orderBy($orderBy, $orderType);
        }

        return $query->paginate($params->length, ['*'], 'page', $params->page);
    }
}
