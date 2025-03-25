<?php

namespace App\Http\Controllers\API\v1\Product;

use App\Enums\ActionLogEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductCreateRequest;
use App\Http\Requests\Product\ProductDatatableRequest;
use App\Http\Requests\Product\ProductUpdateRequest;
use App\Services\Product\ProductServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected ProductServiceInterface $productService;

    public function __construct(
        ProductServiceInterface $productService
    ) {
        $this->productService = $productService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  ProductDatatableRequest $request
     * @return JsonResponse
     */
    public function index(ProductDatatableRequest $request): JsonResponse
    {
        $params = $request->validatedDatatable();
        $products = $this->productService->paginate($params);
        return $this->responseSuccessDatatable($products, $params->draw);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ProductCreateRequest $request
     * @return JsonResponse
     */
    public function store(ProductCreateRequest $request): JsonResponse
    {
        $params = $request->validated();
        $this->productService->create($params);
        return $this->responseSuccess();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ProductUpdateRequest $request
     * @param  int $id
     * @return JsonResponse
     */
    public function update(ProductUpdateRequest $request, int $id): JsonResponse
    {
        $params = $request->validated();
        $this->productService->update($params, $id);
        return $this->responseSuccess();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->productService->delete($id);
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
    public function show(int $id): JsonResponse
    {
        try {
            $product = $this->productService->findById($id);
            return $this->responseSuccess($product);
        } catch (ModelNotFoundException $e) {
            return $this->responseFail(trans('message.modal_not_found'));
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @param  ProductDatatableRequest $request
     * @return JsonResponse
     */
    public function inventory(ProductDatatableRequest $request, int $id): JsonResponse
    {
        $params = $request->validatedDatatable();
        $products = $this->productService->inventory($params, $id);
        return $this->responseSuccessDatatable($products, $params->draw);
    }
}
