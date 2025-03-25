<?php

namespace App\Http\Controllers\API\v1\Validate;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ValidateController extends Controller
{
    /**
     * Check Validate
     *
     * @param  Request $request
     * @return JsonResponse
     */
    public function checkValidate(Request $request): JsonResponse
    {
        $params = $request->all();
        $rules = [
            'key' => $params['rule'],
        ];
        $request->validate($rules);
        return $this->responseSuccess();
    }

    /**
     * Check Password
     *
     * @param  Request $request
     * @return JsonResponse
     */
    public function checkPassword(Request $request): JsonResponse
    {
        $params = $request->password;
        $checkPassword = Hash::check($params, Auth::user()->password);
        if (!$checkPassword) {
            return $this->responseFail('', 422);
        }
        return $this->responseSuccess();
    }
}
