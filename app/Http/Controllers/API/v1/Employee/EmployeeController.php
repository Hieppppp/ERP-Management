<?php

namespace App\Http\Controllers\API\v1\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\EmployeeCreateRequest;
use App\Http\Requests\Employee\EmployeeDataTableRequest;
use App\Http\Requests\Employee\EmployeeUpdateRequest;
use App\Services\Employee\EmployeeServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    protected EmployeeServiceInterface $employeeService;

    public function __construct(
        EmployeeServiceInterface $employeeService
    )
    {
        $this->employeeService = $employeeService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(EmployeeDataTableRequest $request): JsonResponse
    {
        $params = $request->validatedDatatable();
        $employee = $this->employeeService->paginate($params);
        return $this->responseSuccessDatatable($employee, $params->draw);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmployeeCreateRequest $request): JsonResponse
    {
        $params = $request->validated();
        $this->employeeService->create($params);
        return $this->responseSuccess();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeeUpdateRequest $request, string $id): JsonResponse
    {
        $params = $request->validated();
        try {
            $this->employeeService->update($params, $id);
            return $this->responseSuccess();
        } catch (ModelNotFoundException $e) {
            return $this->responseFail(trans('message.modal_not_found'));
        }
    }

        
    /**
     * destroy
     *
     * @param  string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $this->employeeService->delete($id);
            return $this->responseSuccess();
        } catch (ModelNotFoundException $e) {
            return $this->responseFail(trans('message.modal_not_found'));
        }
    }
}
