<?php

namespace App\Http\Controllers\WEB\Unit;

use App\Http\Controllers\Controller;
use App\Services\Unit\UnitServiceInterface;
use Illuminate\Console\View\Components\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class UnitController extends Controller
{

    protected UnitServiceInterface $unitService;

    public function __construct(
        UnitServiceInterface $unitService
    ) {
        $this->unitService = $unitService;
    }

    /**
     * index
     *
     * @return View|Factory
     */
    public function index(): View|Factory
    {
        return view('pages/unit/index');
    }

    /**
     * create
     *
     * @return View|Factory
     */
    public function create(): View|Factory
    {
        return view('pages/unit/create');
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
            $params['unit'] = $this->unitService->findById($id);
            return view('pages/unit/edit', $params);
        } catch (ModelNotFoundException $e) {
            return $this->responseFail(trans('message.modal_not_found'));
        }
    }
}
