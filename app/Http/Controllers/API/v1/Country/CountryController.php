<?php

namespace App\Http\Controllers\API\v1\Country;

use App\Http\Controllers\Controller;
use App\Http\Requests\Country\CountryCreateRequest;
use App\Http\Requests\Country\CountrySearchRequest;
use App\Http\Resources\Country\CountrySelectCollection;
use App\Services\Country\CountryServiceInterface;
use Illuminate\Http\JsonResponse;

class CountryController extends Controller
{
    protected CountryServiceInterface $countryService;

    public function __construct(
        CountryServiceInterface $countryService
    ) {
        $this->countryService = $countryService;
    }

    /**
     * search
     *
     * @param  CountrySearchRequest $request
     * @return JsonResponse
     */
    public function search(CountrySearchRequest $request): JsonResponse
    {
        $params = $request->validated();
        $countries = $this->countryService->search($params);
        $countries = new CountrySelectCollection($countries);
        return $this->responseSuccessForSelect($countries->toArray($request), $params['page']);
    }

    /**
     * store
     *
     * @param  CountryCreateRequest $request
     * @return JsonResponse
     */
    public function store(CountryCreateRequest $request): JsonResponse
    {
        $params = $request->validated();
        $this->countryService->create($params);
        return $this->responseSuccess();
    }
}
