<?php

namespace App\Http\Controllers\API\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\Auth\LoginResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    protected User | null $user;
    /**
     * Handle an authentication attempt.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();
        if (Auth::attempt($credentials)) {
            $this->user = Auth::user();
            if ($this->user) {
                $token = $this->user->createToken('API Token');
                return $this->responseSuccess(new LoginResource($this->user, $token), __('Login success'));
            }
        }

        return $this->responseFail(__('Login error'), 401, $credentials);
    }
}
