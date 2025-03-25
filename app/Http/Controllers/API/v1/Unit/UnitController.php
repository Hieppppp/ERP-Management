<?php

namespace App\Http\Controllers\API\v1\Unit;

use App\Http\Controllers\Controller;
use App\Http\Requests\Unit\UnitCreateRequest;
use App\Http\Requests\Unit\UnitDatatableRequest;
use App\Http\Requests\Unit\UnitSearchRequest;
use App\Http\Requests\Unit\UnitUpdateRequest;
use App\Http\Resources\Unit\UnitSelectCollection;
use App\Services\Unit\UnitServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class UnitController extends Controller
{
    protected UnitServiceInterface $unitService;

    public function __construct(
        UnitServiceInterface $unitService
    ) {
        $this->unitService = $unitService;
    }

    /**
     * list user
     *
     * @return JsonResponse
     */
    public function index(UnitDatatableRequest $unitDatatable): JsonResponse
    {
        $params = $unitDatatable->validatedDatatable();
        $units = $this->unitService->paginate($params);
        return $this->responseSuccessDatatable($units, $params->draw);
    }

    /**
     * destroy
     *
     * @param  int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->unitService->delete($id);
            return $this->responseSuccess();
        } catch (ModelNotFoundException $e) {
            return $this->responseFail(trans('message.modal_not_found'));
        }
    }

    /**
     * store
     *
     * @param  UnitCreateRequest $request
     * @return JsonResponse
     */
    public function store(UnitCreateRequest $request): JsonResponse
    {
        $params = $request->validated();
        $this->unitService->create($params);
        return $this->responseSuccess();
    }

    /**
     * update
     *
     * @param  UnitUpdateRequest $request
     * @param  string $id
     * @return JsonResponse
     */
    public function update(UnitUpdateRequest $request, string $id): JsonResponse
    {
        $params = $request->validated();
        try {
            $this->unitService->update($params, $id);
            return $this->responseSuccess();
        } catch (ModelNotFoundException $e) {
            return $this->responseFail(trans('message.modal_not_found'));
        }
    }

    /**
     * search
     *
     * @param  UnitSearchRequest $request
     * @return JsonResponse
     */
    public function search(UnitSearchRequest $request): JsonResponse
    {
        $params = $request->validated();
        $units = $this->unitService->search($params);
        $units = new UnitSelectCollection($units);
        return $this->responseSuccessForSelect($units->transformer($request), $params['page']);
    }
}