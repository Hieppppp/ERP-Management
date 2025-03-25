<?php

namespace App\Http\Controllers\WEB\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\CallbackFunction\CallbackFunctionRequest;
use App\Services\Category\CategoryServiceInterface;
use Illuminate\Console\View\Components\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

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
     * @return View|Factory
     */
    public function index(): View|Factory
    {
        return view('pages/category/index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View|Factory
     */
    public function create(): View|Factory
    {
        return view('pages/category/create');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return View
     */
    public function edit(int $id): View|Factory|JsonResponse
    {
        try {
            $params['category'] = $this->categoryService->findById($id);
            return view('pages/category/edit', $params);
        } catch (ModelNotFoundException $e) {
            return $this->responseFail(trans('message.modal_not_found'));
        }
    }
}
