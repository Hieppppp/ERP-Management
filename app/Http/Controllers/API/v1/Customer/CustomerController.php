<?php

namespace App\Http\Controllers\API\v1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerDatatableRequest;
use App\Http\Requests\Customer\CustomerEditRequest;
use App\Http\Requests\Customer\CustomerSearchRequest;
use App\Http\Resources\Customer\CustomerSelectCollection;
use App\Services\Customer\CustomerServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{
    protected CustomerServiceInterface $customerService;

    public function __construct(
        CustomerServiceInterface $customerService
    ) {
        $this->customerService = $customerService;
    }
    /**
     * Display a listing of the resource
     *
     * @param  CustomerDatatableRequest $request
     * @return JsonResponse
     */
    public function index(CustomerDatatableRequest $request): JsonResponse
    {
        $params = $request->validatedDatatable();
        $customer = $this->customerService->paginate($params);
        return $this->responseSuccessDatatable($customer, $params->draw);
    }
    
    /**
     * show
     *
     * @param  int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $customer = $this->customerService->findById($id);
            return $this->responseSuccess($customer);
        } catch (ModelNotFoundException $e) {
            return $this->responseFail(trans('message.model_not_found'));
        }
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->customerService->delete($id);
            return $this->responseSuccess();
        } catch (ModelNotFoundException $e) {
            return $this->responseFail(trans('message.model_not_found'));
        }
    }

    /**
     * search
     *
     * @param  CustomerSearchRequest $request
     * @return JsonResponse
     */
    public function search(CustomerSearchRequest $request): JsonResponse
    {
        $params = $request->validated();
        $customer = $this->customerService->search($params);
        $customer = new CustomerSelectCollection($customer);
        return $this->responseSuccessForSelect($customer->transformer($request), $params['page']);
    }
}
