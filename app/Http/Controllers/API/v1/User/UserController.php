<?php

namespace App\Http\Controllers\API\v1\User;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateAvatarRequest;
use App\Http\Requests\User\UserDatatableRequest;
use App\Models\User;
use App\Services\User\UserServiceInterface;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected UserServiceInterface $userService;

    public function __construct(
        UserServiceInterface $userService
    ) {
        $this->userService = $userService;
    }

    /**
     * index
     *
     * @param  UserDatatableRequest $userDatatableRequest
     * @return JsonResponse
     */
    public function index(UserDatatableRequest $userDatatableRequest): JsonResponse
    {
        $params = $userDatatableRequest->validatedDatatable();
        $user = $this->userService->paginate($params);
        return $this->responseSuccessDatatable($user, $params->draw);
    }

    /**
     * delete
     *
     * @param  int $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        try {
            $this->userService->delete($id);
            return $this->responseSuccess();
        } catch (ModelNotFoundException $e) {
            return $this->responseFail(trans('message.model_not_found'));
        } catch (Exception $e) {
            return $this->responseFail(trans($e->getMessage()));
        }
    }

    /**
     * Update Avatar
     *
     * @param  UpdateAvatarRequest $request
     * @return JsonResponse
     */
    public function updateAvatar(UpdateAvatarRequest $request): JsonResponse
    {
        $params = $request->validated();
        $user = $this->userService->updateAvatar($params);
        if ($user) {
            return $this->responseSuccess(null, __('message.success'));
        }
        return $this->responseFail(__('message.fail'), 400);
    }
}
