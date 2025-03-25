<?php

namespace App\Http\Controllers\API\v1\ReturnOrder;

use App\Http\Controllers\Controller;
use App\Http\Requests\PurchaseOrder\ReceiptDatatableRequest;
use App\Http\Requests\ReturnOrder\ReturnOrderCreateRequest;
use App\Http\Requests\ReturnOrder\ReturnOrderUpdateRequest;
use App\Services\ReturnOrder\ReturnOrderServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReturnOrderController extends Controller
{
    protected ReturnOrderServiceInterface $returnOrderService;

    public function __construct(
        ReturnOrderServiceInterface $returnOrderService
    ) {
        $this->returnOrderService = $returnOrderService;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return JsonResponse
     */
    public function store(ReturnOrderCreateRequest $request): JsonResponse
    {
        $data = $request->validated();
        $returnOrders = $this->returnOrderService->create($data);
        return $this->responseSuccess($returnOrders);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  string $id
     * @return JsonResponse
     */
    public function update(ReturnOrderUpdateRequest $request, string $id): JsonResponse
    {
        $data = $request->validated();
        $returnOrders = $this->returnOrderService->update($data, $id);
        return $this->responseSuccess($returnOrders);
    }

    /**
     * Receipt Out
     *
     * @param  ReceiptDatatableRequest $request
     * @return JsonResponse
     */
    public function receiptOut(ReceiptDatatableRequest $request): JsonResponse
    {
        $params = $request->validatedDatatable();
        $returnOrders = $this->returnOrderService->receiptOut($params);
        return $this->responseSuccessDatatable($returnOrders, $params->draw);
    }
}
