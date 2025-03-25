<?php

namespace App\Services\User;

use App\Models\User;
use App\Services\BaseServiceInterface;
use Illuminate\Database\Eloquent\Model;

interface UserServiceInterface extends BaseServiceInterface
{
    /**
     * Update Avatar
     *
     * @param  array $params
     * @return Model
     */
    public function updateAvatar(array $params): Model;
}
