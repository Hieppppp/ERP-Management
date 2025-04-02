<?php

namespace App\Http\Controllers\API\v1\Department;

use App\Http\Controllers\Controller;
use App\Http\Requests\Department\DepartmentCreateRequest;
use App\Http\Requests\Department\DepartmentDataTableRequest;
use App\Http\Requests\Department\DepartmentSearchRequest;
use App\Http\Requests\Department\DepartmentUpdateRequest;
use App\Http\Resources\Department\DepartmentSelectCollection;
use App\Services\Department\DepartmentServiceInterface;
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
    public function index(DepartmentDataTableRequest $request): JsonResponse
    {
        $params = $request->validatedDatatable();
        $department = $this->departmentService->paginate($params);
        return $this->responseSuccessDatatable($department, $params->draw);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DepartmentCreateRequest $request): JsonResponse
    {
        $params = $request->validated();
        $this->departmentService->create($params);
        return $this->responseSuccess();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DepartmentUpdateRequest $request, string $id)
    {
        $params = $request->validated();
        try {
            $this->departmentService->update($params, $id);
            return $this->responseSuccess();
        } catch (ModelNotFoundException $e) {
            return $this->responseFail(trans('message.modal_not_found'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $this->departmentService->delete($id);
            return $this->responseSuccess();
        } catch (ModelNotFoundException $e) {
            return $this->responseFail(trans('message.modal_not_found'));
        }
    }

    public function search(DepartmentSearchRequest $request): JsonResponse
    {
        $params = $request->validated();
        $departments = $this->departmentService->search($params);
        $departments = new DepartmentSelectCollection($departments);
        return $this->responseSuccessForSelect($departments->transformer($request), $params['page']);
    }
}
