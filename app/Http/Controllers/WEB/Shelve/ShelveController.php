<?php

namespace App\Http\Controllers\WEB\Shelve;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shelve\ShelveCreateRequest;
use App\Http\Requests\Shelve\ShelveUpdateRequest;
use App\Models\Warehouse;
use App\Services\Shelve\ShelveServiceInterface;
use Illuminate\Console\View\Components\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class ShelveController extends Controller
{

    protected ShelveServiceInterface $shelveService;

    public function __construct(
        ShelveServiceInterface $shelveService
    ) {
        $this->shelveService = $shelveService;
    }

    /**
     * index
     *
     * @return View|Factory
     */
    public function index(): View|Factory
    {
        return view('pages/shelve/index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View|Factory
    {
        $warehouseId = request()->get('warehouseId');
        $data = [];
        if ($warehouseId) {
            $data['warehouse'] = Warehouse::findOrFail($warehouseId);
        }
        return view('pages/shelve/create', $data);
    }

    /**
     * store
     *
     * @param  ShelveCreateRequest $request
     * @return Redirector|RedirectResponse
     */
    public function store(ShelveCreateRequest $request): Redirector|RedirectResponse
    {
        $params = $request->validated();
        $this->shelveService->create($params);
        return redirect()->to('/shelve');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $id
     * @return View|Factory|JsonResponse
     */
    public function edit(string $id): View|Factory|JsonResponse
    {
        try {
            $data['shelve'] = $this->shelveService->with(['warehouses'])->findOrFail($id)->toArray();
            return view('pages/shelve/edit', $data);
        } catch (ModelNotFoundException $e) {
            return $this->responseFail(trans('message.modal_not_found'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ShelveUpdateRequest $request
     * @param  int $id
     * @return Redirector|RedirectResponse
     */
    public function update(ShelveUpdateRequest $request, int $id): Redirector|RedirectResponse
    {
        $params = $request->validated();
        $this->shelveService->update($params, $id);
        return redirect()->to('/shelve');
    }

    /**
     * select
     *
     * @return View
     */
    public function select(): View|Factory
    {
        return view('pages/shelve/select');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $id
     * @return View|Factory
     */
    public function show(string $id): View|Factory
    {
        $data = $this->shelveService->getDetail($id);
        return view('pages/shelve/detail', $data);
    }
}
