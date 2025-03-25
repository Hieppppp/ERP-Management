<?php

namespace App\Http\Controllers\WEB\Warehouse;

use App\Http\Controllers\Controller;
use App\Http\Requests\Warehouse\WarehouseCreateRequest;
use App\Http\Requests\Warehouse\WarehouseUpdateRequest;
use App\Services\Warehouse\WarehouseServiceInterface;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class WarehouseController extends Controller
{

    protected WarehouseServiceInterface $warehouseService;

    public function __construct(
        WarehouseServiceInterface $warehouseService
    ) {
        $this->warehouseService = $warehouseService;
    }

    /**
     * index
     *
     * @return View|Factory
     */
    public function index(): View|Factory
    {
        return view('pages/warehouse/index');
    }

    /**
     * create
     *
     * @return View
     */
    public function create(): View|Factory
    {
        return view('pages/warehouse/create');
    }

    /**
     * store
     *
     * @param  WarehouseCreateRequest $request
     * @return Redirector|RedirectResponse
     */
    public function store(WarehouseCreateRequest $request): Redirector|RedirectResponse
    {
        $params = $request->validated();
        $this->warehouseService->create($params);
        return redirect()->to('/warehouse');
    }

    /**
     * edit
     *
     * @param  int $id
     * @return View|Factory|JsonResponse
     */
    public function edit(int $id): View|Factory|JsonResponse
    {
        try {
            $warehouse = $this->warehouseService->findById($id);
            return view('pages/warehouse/edit', ['warehouse' => $warehouse]);
        } catch (ModelNotFoundException $e) {
            return $this->responseFail(trans('message.modal_not_found'));
        }
    }

    /**
     * select
     *
     * @return View
     */
    public function select(): View|Factory
    {
        return view('pages/warehouse/select');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $id
     * @return View|Factory
     */
    public function show(string $id): View|Factory
    {
        $warehouse = $this->warehouseService->getDetail($id);
        return view('pages/warehouse/detail', ['warehouse' => $warehouse]);
    }
}
