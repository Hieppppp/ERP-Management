<?php

namespace App\Services\User;

use App\Enums\ActionLogEnum;
use App\Enums\UserRole;
use App\Helpers\FileHelper;
use App\Models\User;
use App\Repositories\BaseRepository;
use App\Repositories\Permission\PermissionRepository;
use App\Repositories\Permission\PermissionRepositoryInterface;
use App\Services\BaseService;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserService extends BaseService implements UserServiceInterface
{
    protected PermissionRepositoryInterface $permissionRepository;
    /**
     * UserRepository
     *
     * @return void
     */
    public function __construct(
        BaseRepository $repository,
        PermissionRepositoryInterface $permissionRepository
    ) {
        parent::__construct($repository);
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * create
     *
     * @param  array $params
     * @return User
     */
    public function create(array $params): User
    {
        $permissionIds = [];
        if (!empty($params['permission_ids'])) {
            $permissionIds = $params['permission_ids'];
            unset($params['permission_ids']);
        }
        $user = parent::create($params);
        if ($user && $permissionIds) {
            $user->permissions()->attach($permissionIds);
        }
        return $user;
    }

    /**
     * update
     *
     * @param  array $params
     * @param  int $id
     * @return Model
     */
    public function update(array $params, int $id): Model
    {
        $oldData = $this->repository->findById($id);
        $permissionIds = [];
        if (!empty($params['permission_ids'])) {
            $permissionIds = $params['permission_ids'];
            unset($params['permission_ids']);
        }
        $user = User::findOrFail($id);
        $user->disableLogging();
        $user->update($params);
        $attributes = $user->getChanges();
        $olds = [];
        foreach ($attributes as $key => $value) {
            $olds[$key] = $oldData[$key];
        }
        if ($user) {
            $oldPermission = $user->permissions()->pluck('id')->toArray();
            if (array_diff($permissionIds, $oldPermission) || array_diff($oldPermission, $permissionIds)) {
                $attributes['permission_ids'] = array_diff($permissionIds, $oldPermission);
                $olds['permission_ids'] = array_diff($oldPermission, $permissionIds);
            }
            $user->permissions()->sync($permissionIds);
        }
        unset($olds['updated_at']);
        unset($attributes['updated_at']);
        if ($attributes && $olds) {
            activity('default')
                ->performedOn($user)
                ->event(ActionLogEnum::UPDATED)
                ->withProperties(['attributes' => $attributes, 'old' => $olds])
                ->log(ActionLogEnum::UPDATED);
        }
        return $user;
    }

    /**
     * Update Avatar
     *
     * @param  array $params
     * @return Model
     */
    public function updateAvatar(array $params): Model
    {
        $user = Auth::user();
        $oldAvatar = $user->avatar;
        $image = FileHelper::uploadFile($params['avatar'], 'avatar');
        $user =  $this->repository->update($user->id, [
            'avatar' => $image['fileName']
        ]);
        if ($oldAvatar) {
            FileHelper::deleteFile($oldAvatar, 'avatar');
        }
        return $user;
    }

    public function delete(int $id): bool|null
    {
        $currentUser = Auth::user();
        $userToDelete = $this->repository->findById($id);

        if ($userToDelete->role === UserRole::ADMIN) {
            if ($currentUser->role !== UserRole::SUPPER_ADMIN) {
                throw new Exception('Unauthorized');
            }
        }
        return $this->repository->delete($id);
    }
}
