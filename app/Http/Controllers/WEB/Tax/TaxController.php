<?php

namespace App\Http\Controllers\WEB\Tax;

use App\Http\Controllers\Controller;
use App\Services\Tax\TaxServiceInterface;
use Illuminate\Console\View\Components\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class TaxController extends Controller
{
    protected TaxServiceInterface $taxService;

    public function __construct(
        TaxServiceInterface $taxService
    ) {
        $this->taxService = $taxService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return View|Factory
     */
    public function index(): View|Factory
    {
        return view('pages/tax/index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View|Factory
     */
    public function create(): View|Factory
    {
        return view('pages/tax/create');
    }

    /**
     * Show the form for editing the specified resource
     *
     * @param  int $id
     * @return View|Factory|JsonResponse
     */
    public function edit(int $id): View|Factory|JsonResponse
    {
        try {
            $params['tax'] = $this->taxService->findById($id);
            return view('pages/tax/edit', $params);
        } catch (ModelNotFoundException $e) {
            return $this->responseFail(trans('message.modal_not_found'));
        }
    }
}
