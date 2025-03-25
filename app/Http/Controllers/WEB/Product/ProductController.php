<?php

namespace App\Http\Controllers\WEB\Product;

use App\Http\Controllers\Controller;
use App\Models\InventoryLog;
use App\Models\Product;
use App\Models\ProductLocation;
use App\Services\Product\ProductServiceInterface;
use Illuminate\Console\View\Components\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected ProductServiceInterface $productService;

    public function __construct(
        ProductServiceInterface $productService
    ) {
        $this->productService = $productService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): View|Factory
    {
        return view('pages/product/index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View|Factory
     */
    public function create(): View|Factory
    {
        return view('pages/product/create');
    }

    /**
     * select
     *
     * @return View
     */
    public function select(): View|Factory
    {
        return view('pages/product/select');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $id
     * @return View|Factory
     */
    public function edit(string $id): View|Factory
    {
        $data['product'] = $this->productService->findById($id);
        $data['activityLogs'] = $this->productService->getActivityProduct($id);
        return view('pages/product/edit', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $id
     * @return View|Factory
     */
    public function show(string $id): View|Factory
    {
        $data['product'] = $this->productService->findById($id);
        $data['activityLogs'] = $this->productService->getActivityProduct($id);
        return view('pages/product/detail', $data);
    }

    /**
     * Show the inventory list
     *
     * @param  string $id
     * @return View|Factory
     */
    public function inventory(string $id): View|Factory
    {
        $data['product'] = Product::with('inventoryLogs')->findOrFail($id);
        return view('pages/inventory/index', $data);
    }

    /**
     * movement
     *
     * @param  string $id
     * @return View|Factory
     */
    public function movement(string $id): View|Factory
    {
        $data['productLocation'] = ProductLocation::with(['shelve.warehouses'])->findOrFail($id);
        return view('pages/inventory/movement', $data);
    }
}
