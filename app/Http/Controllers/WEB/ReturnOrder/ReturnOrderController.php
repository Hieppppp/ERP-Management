<?php

namespace App\Http\Controllers\WEB\ReturnOrder;

use App\Enums\ReturnOrderStatusEnum;
use App\Http\Controllers\Controller;
use App\Services\PurchaseOrder\PurchaseOrderServiceInterface;
use App\Services\ReturnOrder\ReturnOrderServiceInterface;
use Illuminate\Console\View\Components\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ReturnOrderController extends Controller
{
    protected PurchaseOrderServiceInterface $purchaseOrderService;

    protected ReturnOrderServiceInterface $returnOrderService;

    public function __construct(
        PurchaseOrderServiceInterface $purchaseOrderService,
        ReturnOrderServiceInterface $returnOrderService
    ) {
        $this->purchaseOrderService = $purchaseOrderService;
        $this->returnOrderService = $returnOrderService;
    }

    public function create($id): View|Factory
    {
        $data['purchaseOrder'] = $this->purchaseOrderService->with([
            'supplier' => function ($query) {
                $query->withTrashed();
            },
            'productLocations.shelve' => function ($query) {
                $query->withTrashed();
            },
            'productLocations.product' => function ($query) {
                $query->withTrashed()->with(['unit', 'images', 'category']);
            },
        ])
            ->findOrFail($id);
        return view('pages/return-order/create', $data);
    }

    public function show(string $id): View|Factory|RedirectResponse
    {
        $returnOrder = $this->returnOrderService->with([
            'purchaseOrder.supplier' => function ($query) {
                $query->withTrashed();
            },
            'returnOrderDetails.productLocations.shelve' => function ($query) {
                $query->withTrashed();
            },
            'returnOrderDetails.productLocations.product' => function ($query) {
                $query->withTrashed()->with(['unit', 'images', 'category']);
            }
        ])
            ->findOrFail($id);
        if ($returnOrder->status === ReturnOrderStatusEnum::READY) {
            return redirect()->route('return-order.confirm', ['id' => $id]);
        }
        $data['returnOrder'] = $returnOrder;
        $data['purchaseOrder'] = $returnOrder->purchaseOrder;
        $data['returnOrderDetails'] = $returnOrder->returnOrderDetails;
        return view('pages/return-order/detail', $data);
    }

    public function confirm(string $id): View|Factory|RedirectResponse
    {
        $returnOrder = $this->returnOrderService->with([
            'purchaseOrder.supplier' => function ($query) {
                $query->withTrashed();
            },
            'returnOrderDetails.productLocations.shelve' => function ($query) {
                $query->withTrashed();
            },
            'returnOrderDetails.productLocations.product' => function ($query) {
                $query->withTrashed()->with(['unit', 'images', 'category']);
            }
        ])
            ->findOrFail($id);
        if ($returnOrder->status !== ReturnOrderStatusEnum::READY) {
            return redirect()->route('return-order.show', ['id' => $id]);
        }
        $data['returnOrder'] = $returnOrder;
        $data['purchaseOrder'] = $returnOrder->purchaseOrder;
        $data['returnOrderDetails'] = $returnOrder->returnOrderDetails;
        return view('pages/return-order/confirm', $data);
    }
}
