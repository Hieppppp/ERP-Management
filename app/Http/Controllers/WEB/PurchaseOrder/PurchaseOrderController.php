<?php

namespace App\Http\Controllers\WEB\PurchaseOrder;

use App\Enums\PurchaseOrderStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Supplier;
use App\Services\PurchaseOrder\PurchaseOrderServiceInterface;
use Illuminate\Console\View\Components\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

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
     * @return View|Factory
     */
    public function index(): View|Factory
    {
        $purchaseOrderCount = $this->purchaseOrderService->getTotalByStatus();
        return view('pages/purchase-order/index', ['purchaseOrderCount' => $purchaseOrderCount]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View|Factory
     */
    public function create(Request $request): View|Factory
    {
        $supplier = null;
        if ($request->has('supplierId')) {
            $supplierId = $request->query('supplierId');
            $supplier = Supplier::findOrFail($supplierId);
        }
        
        $product = null;
        if ($request->has('productId')) {
            $productId = $request->query('productId');
            $product = Product::with('images', 'unit', 'category', 'suppliers')->findOrFail($productId);
        }
        return view('pages/purchase-order/create', [ 'supplier' => $supplier, 'product' => $product]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $id
     * @return View|Factory
     */
    public function edit(string $id): View|Factory
    {
        $purchaseOrder = $this->purchaseOrderService->with(['products.images', 'products.category', 'products.unit', 'supplier', 'warehouse', 'products.suppliers'])->findOrFail($id);
        $data['totalAmount'] = $purchaseOrder->products->sum(function ($product) {
            return $product->pivot->quantity * $product->pivot->unit_cost;
        });
        $data['purchaseOrder'] = $purchaseOrder;
        $data['activityLogs'] = $this->purchaseOrderService->getActivityPurchaseOrder($id);
        return view('pages/purchase-order/edit', $data);
    }

    /**
     * show
     *
     * @param  string $id
     * @return View|Factory
     */
    public function show(string $id): View|Factory
    {
        $data = $this->purchaseOrderService->getDetailPurchaseOrder($id);
        $data['activityLogs'] = $this->purchaseOrderService->getActivityPurchaseOrder($id);
        return view('pages/purchase-order/detail', $data);
    }

    /**
     * receive
     *
     * @param  string $id
     * @return View|Factory|Redirector|RedirectResponse
     */
    public function receive(string $id): View|Factory|Redirector|RedirectResponse
    {
        $purchaseOrder = $this->purchaseOrderService->with([
            'warehouse' => function ($query) {
                $query->withTrashed();
            },
            'supplier' => function ($query) {
                $query->withTrashed();
            },
            'purchaseProductShelves'
        ])->findOrFail($id);
        if (in_array($purchaseOrder->status, [PurchaseOrderStatusEnum::DRAFT])) {
            return redirect()->to('purchase-order/' . $purchaseOrder->id);
        }
        $data['purchaseOrder'] = $purchaseOrder;
        $data['purchaseOrder']['products'] = $this->purchaseOrderService->getPurchaseOrderProduct($id);
        return view('pages/purchase-order/receive', $data);
    }

    /**
     * Put Product On Shelf
     *
     * @param  string $purchaseOrder
     * @param  string $product
     * @return View|Factory
     */
    public function putProductOnShelf(string $purchaseOrderId, string $productId): View|Factory
    {
        $purchaseOrder = $this->purchaseOrderService->getDetailPurchaseProductShelve($purchaseOrderId, $productId);
        if (!$purchaseOrder->purchaseProductShelves->isEmpty()) {
            return view('pages/purchase-order/put-product-detail', ['purchaseOrder' => $purchaseOrder]);
        }
        return view('pages/purchase-order/put-product', ['purchaseOrder' => $purchaseOrder]);
    }

    /**
     * receipt
     *
     * @return View|Factory
     */
    public function receipt(): View|Factory
    {
        return view('pages/receipt/index');
    }
}
