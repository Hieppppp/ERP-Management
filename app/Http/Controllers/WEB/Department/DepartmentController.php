<?php

namespace App\Http\Controllers\WEB\Department;

use App\Http\Controllers\Controller;
use App\Services\Department\DepartmentServiceInterface;
use Illuminate\Console\View\Components\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    protected DepartmentServiceInterface $departmentService;

    public function __construct(
        DepartmentServiceInterface $departmentService
    )
    {
        $this->departmentService = $departmentService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): Factory|View
    {
        return view("pages/department/index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Factory|View
    {
        return view('pages/department/create');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): Factory|JsonResponse|View
    {
        try {
            $params['department'] = $this->departmentService->findById($id);
            return view("pages/department/edit", $params);
        } catch (ModelNotFoundException $e) {
            return $this->responseFail(trans('message.modal_not_found'));
        }
    }

   
}
