<?php

namespace App\Http\Controllers\API\v1\Tax;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tax\TaxCreateRequest;
use App\Http\Requests\Tax\TaxDatatableRequest;
use App\Http\Requests\Tax\TaxSearchRequest;
use App\Http\Requests\Tax\TaxUpdateRequest;
use App\Http\Resources\Tax\TaxSelectCollection;
use App\Services\Tax\TaxServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class TaxController extends Controller
{
    protected TaxServiceInterface $taxService;

    public function __construct(
        TaxServiceInterface $taxService
    ) {
        $this->taxService = $taxService;
    }

    /**
     * list user
     *
     * @return JsonResponse
     */
    public function index(TaxDatatableRequest $request): JsonResponse
    {
        $params = $request->validatedDatatable();
        $taxes = $this->taxService->paginate($params);
        return $this->responseSuccessDatatable($taxes, $params->draw);
    }

    /**
     * delete
     *
     * @param  int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->taxService->delete($id);
            return $this->responseSuccess();
        } catch (ModelNotFoundException $e) {
            return $this->responseFail(trans('message.modal_not_found'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  TaxCreateRequest $request
     * @return JsonResponse
     */
    public function store(TaxCreateRequest $request): JsonResponse
    {
        $params = $request->validated();
        $this->taxService->create($params);
        return $this->responseSuccess();
    }

    /**
     * update
     *
     * @param  TaxUpdateRequest $request
     * @param  int $id
     * @return JsonResponse
     */
    public function update(TaxUpdateRequest $request, int $id): JsonResponse
    {
        $params = $request->validated();
        try {
            $this->taxService->update($params, $id);
            return $this->responseSuccess();
        } catch (ModelNotFoundException $e) {
            return $this->responseFail(trans('message.modal_not_found'));
        }
    }

    /**
     * search
     *
     * @param  TaxSearchRequest $request
     * @return JsonResponse
     */
    public function search(TaxSearchRequest $request): JsonResponse
    {
        $params = $request->validated();
        $taxes = $this->taxService->search($params);
        $taxes = new TaxSelectCollection($taxes);
        return $this->responseSuccessForSelect($taxes->transformer($request), $params['page']);
    }
}
