<?php

namespace App\Http\Controllers\API\v1\PurchaseOrder;

use App\Http\Controllers\Controller;
use App\Http\Requests\PurchaseOrder\PurchaseOrderCreateRequest;
use App\Http\Requests\PurchaseOrder\PurchaseOrderDatatableRequest;
use App\Http\Requests\PurchaseOrder\PurchaseOrderReceiveProductRequest;
use App\Http\Requests\PurchaseOrder\PurchaseOrderUpdateRequest;
use App\Http\Requests\PurchaseOrder\PutProductRequest;
use App\Http\Requests\PurchaseOrder\ReceiptDatatableRequest;
use App\Services\PurchaseOrder\PurchaseOrderServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    protected PurchaseOrderServiceInterface $purchaseOrderService;

    public function __construct(
        PurchaseOrderServiceInterface $purchaseOrderService
    ) {
        $this->purchaseOrderService = $purchaseOrderService;
    }
    /**
     * Display a listing of the resource.
     *
     * @param  PurchaseOrderDatatableRequest $request
     * @return JsonResponse
     */
    public function index(PurchaseOrderDatatableRequest $request): JsonResponse
    {
        $params = $request->validatedDatatable();
        $purchaseOrders = $this->purchaseOrderService->paginate($params);
        return $this->responseSuccessDatatable($purchaseOrders, $params->draw);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PurchaseOrderCreateRequest $request
     * @return JsonResponse
     */
    public function store(PurchaseOrderCreateRequest $request): JsonResponse
    {
        $params = $request->validated();
        $purchaseOrder = $this->purchaseOrderService->create($params);
        return $this->responseSuccess($purchaseOrder);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  PurchaseOrderUpdateRequest $request
     * @param  string $id
     * @return JsonResponse
     */
    public function update(PurchaseOrderUpdateRequest $request, string $id): JsonResponse
    {
        $params = $request->validated();
        $purchaseOrder = $this->purchaseOrderService->update($params, $id);
        return $this->responseSuccess($purchaseOrder);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        $purchaseOrder = $this->purchaseOrderService->delete($id);
        return $this->responseSuccess($purchaseOrder);
    }

    /**
     * Send Purchase Order
     *
     * @param  string $id
     * @return JsonResponse
     */
    public function sendPurchaseOrder(string $id): JsonResponse
    {
        $purchaseOrder = $this->purchaseOrderService->sendPurchaseOrder($id);
        return $this->responseSuccess($purchaseOrder);
    }

    /**
     * Receive Product
     *
     * @param  PurchaseOrderReceiveProductRequest $request
     * @param  string $id
     * @return JsonResponse
     */
    public function receiveProduct(PurchaseOrderReceiveProductRequest $request, string $id): JsonResponse
    {
        $params = $request->validated();
        $purchaseOrder = $this->purchaseOrderService->receiveProduct($params, $id);
        return $this->responseSuccess($purchaseOrder);
    }

    /**
     * Put Product On Shelf
     *
     * @param  PutProductRequest $request
     * @return JsonResponse
     */
    public function putProductOnShelf(PutProductRequest $request, string $purchaseOrderId, string $productId): JsonResponse
    {
        $params = $request->validated();
        $purchaseOrder = $this->purchaseOrderService->putProductOnShelf($purchaseOrderId, $productId, $params);
        return $this->responseSuccess($purchaseOrder);
    }

    /**
     * Cancel Purchase Order
     *
     * @param  string $id
     * @return JsonResponse
     */
    public function cancelPurchaseOrder(string $id): JsonResponse
    {
        $purchaseOrder = $this->purchaseOrderService->cancelPurchaseOrder($id);
        return $this->responseSuccess($purchaseOrder);
    }

    /**
     * Finish Purchase Order
     *
     * @param  string $id
     * @return JsonResponse
     */
    public function finishPurchaseOrder(string $id): JsonResponse
    {
        $purchaseOrder = $this->purchaseOrderService->finishPurchaseOrder($id);
        return $this->responseSuccess($purchaseOrder);
    }

    /**
     * Get Receipt
     *
     * @param  ReceiptDatatableRequest $request
     * @return JsonResponse
     */
    public function getReceipt(ReceiptDatatableRequest $request): JsonResponse
    {
        $params = $request->validatedDatatable();
        $purchaseOrders = $this->purchaseOrderService->getReceiptIn($params);
        return $this->responseSuccessDatatable($purchaseOrders, $params->draw);
    }
}
