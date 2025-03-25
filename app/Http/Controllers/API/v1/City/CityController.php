<?php

namespace App\Http\Controllers\API\v1\City;

use App\Http\Controllers\Controller;
use App\Http\Requests\City\CityCreateRequest;
use App\Http\Requests\City\CitySearchRequest;
use App\Http\Resources\City\CitySelectCollection;
use App\Services\City\CityServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CityController extends Controller
{
    protected CityServiceInterface $cityService;

    public function __construct(
        CityServiceInterface $cityService
    ) {
        $this->cityService = $cityService;
    }

    /**
     * search
     *
     * @param  CitySearchRequest $request
     * @return JsonResponse
     */
    public function search(CitySearchRequest $request): JsonResponse
    {
        $params = $request->validated();
        $cities = $this->cityService->search($params);
        $cities = new CitySelectCollection($cities);
        return $this->responseSuccessForSelect($cities->toArray($request), $params['page']);
    }

    /**
     * store
     *
     * @param  CityCreateRequest $request
     * @return JsonResponse
     */
    public function store(CityCreateRequest $request): JsonResponse
    {
        $params = $request->validated();
        $this->cityService->create($params);
        return $this->responseSuccess();
    }
}
