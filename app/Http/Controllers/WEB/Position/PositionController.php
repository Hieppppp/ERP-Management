<?php

namespace App\Http\Controllers\WEB\Position;

use App\Http\Controllers\Controller;
use App\Services\Position\PositionServiceInterface;
use Illuminate\Console\View\Components\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    protected PositionServiceInterface $positionService;

    public function __construct(
        PositionServiceInterface $positionService
    )
    {
        $this->positionService = $positionService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): Factory|View
    {
        return view("pages/position/index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Factory|View
    {
        return view("pages/position/create");
    }

   

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): Factory|JsonResponse|View
    {
        try {
            $params['position'] = $this->positionService->findById($id);
            return view("pages/position/edit", $params);
        } catch (ModelNotFoundException $e) {
            return $this->responseFail(trans('message.modal_not_found'));
        }
    }

   
}
