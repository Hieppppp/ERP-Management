<?php

namespace App\Http\Resources\Auth;

use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Passport\PersonalAccessTokenResult;

class LoginResource extends JsonResource
{
    protected PersonalAccessTokenResult $token;

    /**
     * __construct
     *
     * @param User $resource
     * @param PersonalAccessTokenResult $token
     * @return void
     */
    public function __construct(
        User $resource,
        PersonalAccessTokenResult $token
    ) {
        parent::__construct($resource);
        $this->token = $token;
    }

    /**
     * toArray
     *
     * @param  Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'user' => new UserResource($this->resource),
            'token' => [
                'id' => $this->token->token->id,
                'name' => $this->token->token->name,
                'token' => $this->token->accessToken,
                'type' => "Bearer",
                'expires_at' => $this->token->token->expires_at,
            ]
        ];
    }
}
