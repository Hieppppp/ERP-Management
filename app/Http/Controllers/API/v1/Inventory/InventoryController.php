<?php

namespace App\Http\Controllers\API\v1\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\InventoryCreateRequest;
use App\Http\Requests\Inventory\InventoryUpdateRequest;
use App\Http\Requests\Inventory\MovementRequest;
use App\Services\Inventory\InventoryServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    protected InventoryServiceInterface $inventoryService;

    public function __construct(
        InventoryServiceInterface $inventoryService
    ) {
        $this->inventoryService = $inventoryService;
    }

    /**
     * update
     *
     * @param  InventoryUpdateRequest $request
     * @param  int $id
     * @return JsonResponse
     */
    public function update(InventoryUpdateRequest $request, int $id): JsonResponse
    {
        $params = $request->validated();
        $this->inventoryService->update($params, $id);
        return $this->responseSuccess();
    }

    /**
     * store
     *
     * @param  InventoryCreateRequest $request
     * @return JsonResponse
     */
    public function store(InventoryCreateRequest $request): JsonResponse
    {
        $params = $request->validated();
        DB::beginTransaction();
        try {
            $this->inventoryService->create($params);
            DB::commit();
            return $this->responseSuccess();
        } catch (\Exception $e) {
            DB::rollback();
            return $this->responseFail($e->getMessage());
        }
    }

    /**
     * movement
     *
     * @param  MovementRequest $request
     * @param  string $productLocationId
     * @return JsonResponse
     */
    public function movement(MovementRequest $request, string $productLocationId): JsonResponse
    {
        $params = $request->validated();
        $this->inventoryService->movement($params, $productLocationId);
        return $this->responseSuccess();
    }
}
