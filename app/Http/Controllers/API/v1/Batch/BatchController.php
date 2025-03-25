<?php

namespace App\Http\Controllers\API\v1\Batch;

use App\Http\Controllers\Controller;
use App\Http\Requests\Batch\BatchDatatableRequest;
use App\Services\PurchaseOrder\PurchaseOrderServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BatchController extends Controller
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
     * @return JsonResponse
     */
    public function index(BatchDatatableRequest $request): JsonResponse
    {
        $params = $request->validatedDatatable();
        $batch = $this->purchaseOrderService->getListBatch($params);
        return $this->responseSuccessDatatable($batch, $params->draw);
    }
}
