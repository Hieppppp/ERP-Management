<?php

namespace App\Http\Controllers\WEB\Supplier;

use App\Http\Controllers\Controller;
use App\Http\Requests\Supplier\SupplierCreateRequest;
use App\Http\Requests\Supplier\SupplierEditRequest;
use App\Services\Supplier\SupplierServiceInterface;
use Illuminate\Console\View\Components\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;

class SupplierController extends Controller
{
    protected SupplierServiceInterface $supplierService;

    public function __construct(
        SupplierServiceInterface $supplierService
    ) {
        $this->supplierService = $supplierService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return View|Factory
     */
    public function index(): View|Factory
    {
        return view('pages/supplier/index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View|Factory
     */
    public function create(): View|Factory
    {
        return view('pages/supplier/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  SupplierCreateRequest $request
     * @return Redirector|RedirectResponse
     */
    public function store(SupplierCreateRequest $request): Redirector|RedirectResponse
    {
        $params = $request->validated();
        $this->supplierService->create($params);
        return redirect()->to('/supplier');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $id
     * @return View|Factory
     */
    public function edit(string $id): View|Factory
    {
        $supplier = $this->supplierService->findById($id);
        $data['supplier'] = $supplier;
        $data['activities'] = $supplier->activities()->with(['causer' => function ($query) {
            $query->withTrashed();
        }])->orderBy('id', 'desc')->get();
        return view('pages/supplier/edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  SupplierEditRequest $request
     * @param  int $id
     * @return Redirector|RedirectResponse
     */
    public function update(SupplierEditRequest $request, int $id): Redirector|RedirectResponse
    {
        $params = $request->validated();
        $this->supplierService->update($params, $id);
        return redirect()->to('/supplier');
    }

    /**
     * select
     *
     * @return View
     */
    public function select(Request $request): View|Factory
    {
        $productId = '';
        if ($request->has('productId')) {
            $productId = $request->query('productId');
        }
        return view('pages/supplier/select', ['productId' => $productId]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $id
     * @return View|Factory
     */
    public function show(string $id): View|Factory
    {
        $data = $this->supplierService->getDetail($id);
        return view('pages/supplier/detail', $data);
    }
}
