<?php

namespace App\Http\Controllers\WEB\Employee;

use App\Http\Controllers\Controller;
use App\Services\Employee\EmployeeServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
    public function index()
    {
        return view("pages/employee/index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("pages/employee/create");
    }

   

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $employee = $this->employeeService->findById($id);
            return view('pages/employee/edit', ['employee' => $employee]);
        } catch (ModelNotFoundException $e) {
            return $this->responseFail(trans('message.modal_not_found'));
        }
    }

   
}
