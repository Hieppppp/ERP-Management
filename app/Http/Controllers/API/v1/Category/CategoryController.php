<?php

namespace App\Http\Controllers\API\v1\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CategoryCreateRequest;
use App\Http\Requests\Category\CategoryDatatableRequest;
use App\Http\Requests\Category\CategorySearchRequest;
use App\Http\Requests\Category\CategoryUpdateRequest;
use App\Http\Resources\Category\CategorySelectCollection;
use App\Services\Category\CategoryServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected CategoryServiceInterface $categoryService;

    public function __construct(
        CategoryServiceInterface $categoryService
    ) {
        $this->categoryService = $categoryService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(CategoryDatatableRequest $request): JsonResponse
    {
        $params = $request->validatedDatatable();
        $user = $this->categoryService->paginate($params);
        return $this->responseSuccessDatatable($user, $params->draw);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  mixed $request
     * @return JsonResponse
     */
    public function store(CategoryCreateRequest $request): JsonResponse
    {
        $params = $request->validated();
        $this->categoryService->create($params);
        return $this->responseSuccess();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  CategoryUpdateRequest $request
     * @param  int $id
     * @return JsonResponse
     */
    public function update(CategoryUpdateRequest $request, int $id): JsonResponse
    {
        $params = $request->validated();
        try {
            $this->categoryService->update($params, $id);
            return $this->responseSuccess();
        } catch (ModelNotFoundException $e) {
            return $this->responseFail(trans('message.modal_not_found'));
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
            $this->categoryService->delete($id);
            return $this->responseSuccess();
        } catch (ModelNotFoundException $e) {
            return $this->responseFail(trans('message.modal_not_found'));
        }
    }

    /**
     * search
     *
     * @param  CategorySearchRequest $request
     * @return JsonResponse
     */
    public function search(CategorySearchRequest $request): JsonResponse
    {
        $params = $request->validated();
        $categories = $this->categoryService->search($params);
        $categories = new CategorySelectCollection($categories);
        return $this->responseSuccessForSelect($categories->transformer($request), $params['page']);
    }
}
