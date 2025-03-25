<?php

namespace App\Http\Controllers\API\v1\Warehouse;

use App\Http\Controllers\Controller;
use App\Http\Requests\Search\SearchRequest;
use App\Http\Requests\Warehouse\WarehouseCreateRequest;
use App\Http\Requests\Warehouse\WarehouseDatatableRequest;
use App\Http\Requests\Warehouse\WarehouseUpdateRequest;
use App\Http\Resources\WarehouseSelectCollection;
use App\Services\Warehouse\WarehouseServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    protected WarehouseServiceInterface $warehouseService;

    public function __construct(
        WarehouseServiceInterface $warehouseService
    ) {
        $this->warehouseService = $warehouseService;
    }

    /**
     * list user
     *
     * @return JsonResponse
     */
    public function index(WarehouseDatatableRequest $warehouseDatatable): JsonResponse
    {
        $params = $warehouseDatatable->validatedDatatable();
        $user = $this->warehouseService->paginate($params);
        return $this->responseSuccessDatatable($user, $params->draw);
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
            $this->warehouseService->delete($id);
            return $this->responseSuccess();
        } catch (ModelNotFoundException $e) {
            return $this->responseFail(trans('message.modal_not_found'));
        }
    }

    /**
     * search
     *
     * @param  SearchRequest $request
     * @return JsonResponse
     */
    public function search(SearchRequest $request): JsonResponse
    {
        $params = $request->validated();
        $warehouse = $this->warehouseService->search($params);
        $warehouse = new WarehouseSelectCollection($warehouse);
        return $this->responseSuccessForSelect($warehouse->toArray($request), $params['page']);
    }

    /**
     * store
     *
     * @param  WarehouseCreateRequest $request
     * @return JsonResponse
     */
    public function store(WarehouseCreateRequest $request): JsonResponse
    {
        $params = $request->validated();
        $this->warehouseService->create($params);
        return $this->responseSuccess();
    }

    /**
     * update
     *
     * @param  WarehouseUpdateRequest $request
     * @param  string $id
     * @return JsonResponse
     */
    public function update(WarehouseUpdateRequest $request, string $id): JsonResponse
    {
        $params = $request->validated();
        try {
            $this->warehouseService->update($params, $id);
            return $this->responseSuccess();
        } catch (ModelNotFoundException $e) {
            return $this->responseFail(trans('message.modal_not_found'));
        }
    }
}
