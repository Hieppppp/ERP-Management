<?php

namespace App\Http\Controllers\API\v1\Province;

use App\Http\Controllers\Controller;
use App\Http\Requests\Province\ProvinceCreateRequest;
use App\Http\Requests\Province\ProvinceSearchRequest;
use App\Http\Resources\Province\ProvinceSelectCollection;
use App\Services\Province\ProvinceServiceInterface;
use Illuminate\Http\JsonResponse;

class ProvinceController extends Controller
{
    protected ProvinceServiceInterface $provinceService;

    public function __construct(
        ProvinceServiceInterface $provinceService
    ) {
        $this->provinceService = $provinceService;
    }

    /**
     * search
     *
     * @param  ProvinceSearchRequest $request
     * @return JsonResponse
     */
    public function search(ProvinceSearchRequest $request): JsonResponse
    {
        $params = $request->validated();
        $provinces = $this->provinceService->search($params);
        $provinces = new ProvinceSelectCollection($provinces);
        return $this->responseSuccessForSelect($provinces->toArray($request), $params['page']);
    }

    /**
     * store
     *
     * @param  ProvinceCreateRequest $request
     * @return JsonResponse
     */
    public function store(ProvinceCreateRequest $request): JsonResponse
    {
        $params = $request->validated();
        $this->provinceService->create($params);
        return $this->responseSuccess();
    }
}
