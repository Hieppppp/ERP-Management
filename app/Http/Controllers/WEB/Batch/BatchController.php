<?php

namespace App\Http\Controllers\WEB\Batch;

use App\Enums\PurchaseOrderStatusEnum;
use App\Http\Controllers\Controller;
use App\Services\PurchaseOrder\PurchaseOrderServiceInterface;
use Illuminate\Console\View\Components\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
     * index
     *
     * @return View|Factory
     */
    public function index(): View|Factory
    {
        return view('pages/batch/index');
    }

    /**
     * show
     *
     * @param  string $id
     * @return View|Factory
     */
    public function show(string $id): View|Factory
    {
        $purchaseOrder = $this->purchaseOrderService->findById($id);
        if (!$purchaseOrder || !in_array($purchaseOrder->status, [PurchaseOrderStatusEnum::PENDING_SHELVE, PurchaseOrderStatusEnum::DONE])) {
            throw new ModelNotFoundException();
        }
        $batch = $this->purchaseOrderService->getDetailBatch($id);
        return view('pages/batch/show', ['batch' => $batch]);
    }
}
