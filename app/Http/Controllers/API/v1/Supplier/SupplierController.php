<?php

namespace App\Http\Controllers\API\v1\Supplier;

use App\Http\Controllers\Controller;
use App\Http\Requests\Supplier\SupplierDatatableRequest;
use App\Http\Requests\Supplier\SupplierSearchRequest;
use App\Http\Requests\Supplier\SupplierShowRequest;
use App\Http\Resources\Supplier\SupplierSelectCollection;
use App\Services\Supplier\SupplierServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    protected SupplierServiceInterface $supplierService;

    public function __construct(
        SupplierServiceInterface $supplierService
    ) {
        $this->supplierService = $supplierService;
    }
    /**
     * Display a listing of the resource.
     *
     * @param  SupplierDatatableRequest $request
     * @return JsonResponse
     */
    public function index(SupplierDatatableRequest $request): JsonResponse
    {
        $params = $request->validatedDatatable();
        $supplier = $this->supplierService->paginate($params);
        return $this->responseSuccessDatatable($supplier, $params->draw);
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
            $this->supplierService->delete($id);
            return $this->responseSuccess();
        } catch (ModelNotFoundException $e) {
            return $this->responseFail(trans('message.modal_not_found'));
        }
    }

    /**
     * show
     *
     * @param  int $id
     * @return JsonResponse
     */
    public function show(SupplierShowRequest $request, int $id): JsonResponse
    {
        try {
            $params = $request->validated();
            $supplier = $this->supplierService->with(['products' => function ($query) use ($params) {
                if (!empty($params['product_ids'])) {
                    $productIds = explode(',', $params['product_ids']);
                    $query->whereIn('id', $productIds);
                }
            }])->find($id);
            return $this->responseSuccess($supplier);
        } catch (ModelNotFoundException $e) {
            return $this->responseFail(trans('message.modal_not_found'));
        }
    }

    /**
     * search
     *
     * @param  SupplierSearchRequest $request
     * @return JsonResponse
     */
    public function search(SupplierSearchRequest $request): JsonResponse
    {
        $params = $request->validated();
        $supplier = $this->supplierService->search($params);
        $supplier = new SupplierSelectCollection($supplier);
        return $this->responseSuccessForSelect($supplier->transformer($request), $params['page']);
    }
}
