<?php

use App\Http\Requests\Category\CategoryDatatableRequest;
use App\Common\Entity\DatatableParams;
use App\Enums\ActionLogEnum;
use App\Models\ActivityLogs;
use App\Models\Category;
use App\Services\Category\CategoryServiceInterface;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;

beforeEach(function () {
    $this->categoryService = app(CategoryServiceInterface::class);
});

it('Category/Paginate - Check the function runs successfully and check order by', function () {
    $datatableRequest = new CategoryDatatableRequest();
    $dataValidate = DatatableParams::createDatatableParams(
        [
            'id',
            'name',
            'description'
        ],
        [
            'orders' => [
                'id' => 'asc'
            ],
            'length' => 5
        ]
    );
    $params = $datatableRequest->getDatatableParamsWithParams($dataValidate);
    $data = $this->categoryService->paginate($params);
    //check data type
    $this->assertThat(
        $data,
        $this->logicalOr(
            $this->isInstanceOf(LengthAwarePaginator::class),
            $this->isInstanceOf(Paginator::class)
        )
    );
    $data = $data->jsonSerialize();
    //check order by
    $sortedData = $data['data'];
    usort($sortedData, function ($a, $b) {
        return $a['id'] <=> $b['id'];
    });
    $this->assertEquals($sortedData, $data['data']);


    //check total data
    $this->assertLessThanOrEqual(5, count($data['data']));
});

it('Category/Paginate - Test the filter feature', function () {
    $datatableRequest = new CategoryDatatableRequest();
    $dataValidate = DatatableParams::createDatatableParams(
        [
            'id',
            'name',
            'description'
        ],
        [
            'searchFields' => [
                'id' => 1
            ],
            'orders' => [
                'id' => 'asc'
            ],
        ]
    );
    $params = $datatableRequest->getDatatableParamsWithParams($dataValidate);
    $data = $this->categoryService->paginate($params);
    //check data type
    $this->assertThat(
        $data,
        $this->logicalOr(
            $this->isInstanceOf(LengthAwarePaginator::class),
            $this->isInstanceOf(Paginator::class)
        )
    );
    $data = $data->jsonSerialize();
    $this->assertTrue($data['data'][0]['id'] == 1);
});

it('Category/Paginate - Test the search feature', function () {
    $datatableRequest = new CategoryDatatableRequest();
    $dataValidate = DatatableParams::createDatatableParams(
        [
            'id',
            'name',
            'description'
        ],
        [
            'globalSearch' => 'Trou',
        ]
    );
    $params = $datatableRequest->getDatatableParamsWithParams($dataValidate);
    $data = $this->categoryService->paginate($params);
    //check data type
    $this->assertThat(
        $data,
        $this->logicalOr(
            $this->isInstanceOf(LengthAwarePaginator::class),
            $this->isInstanceOf(Paginator::class)
        )
    );
    $categories = $data->jsonSerialize();
    $expect2 = false;
    foreach ($categories['data'] as $category) {
        if (
            preg_match('/Trou/', strval($category['name'])) ||
            preg_match('/Trou/', strval($category['id'])) ||
            preg_match('/Trou/', strval($category['description']))
        ) {
            $expect2 = true;
            break;
        }
    }
    //check data by name
    $this->assertTrue($expect2);
});

it('Category/Create - Check create feature', function () {
    $dataCreate = [
        'name' => 'Food',
        'description' => 'Noodles'
    ];
    $category = $this->categoryService->create($dataCreate);
    // check data type
    $this->assertInstanceOf(Category::class, $category);
    // check data return
    $this->assertEquals($category->name, 'Food');
    // check data in database
    $categoryDatabase = Category::where('name', 'Food')->first();
    $this->assertNotNull($categoryDatabase);
    //check log
    $this->assertEquals(ActionLogEnum::CREATED, $categoryDatabase->activities->first()->event);
});

it('Category/Show - Check category not found', function () {
    expect(fn () => $this->categoryService->findById(10000))->toThrow(ModelNotFoundException::class);
});

it('Category/Show - Check success', function () {
    $categoryDatabase = Category::find(2);
    $categoryService = $this->categoryService->findById(2);
    $this->assertEquals($categoryDatabase, $categoryService);
});

it('Category/Update - Check Category not found', function () {
    $dataUpdate = [
        'name' => "Vehicle",
    ];
    expect(fn () => $this->categoryService->update($dataUpdate, 10000))->toThrow(ModelNotFoundException::class);
});

it('Category/Update - Check update feature', function () {
    $dataUpdate = [
        'name' => "Vehicle",
    ];
    $category = $this->categoryService->update($dataUpdate, 3);
    // check data type
    $this->assertInstanceOf(Category::class, $category);
    // check data in database
    $categoryInDatabase = Category::find(3);
    $this->assertEquals($category->name, $categoryInDatabase->name);
    //check log
    $this->assertEquals(ActionLogEnum::UPDATED, $categoryInDatabase->activities->first()->event);
});
it('Category/Delete - Check category not found', function () {
    expect(fn () => $this->categoryService->delete(10000))->toThrow(ModelNotFoundException::class);
});

it('Category/Delete - Check success', function () {
    $this->assertTrue($this->categoryService->delete(2));
    $category = Category::find(2);
    $this->assertNull($category);
    //check log and check soft delete
    $category = Category::withTrashed()->find(2);
    $this->assertNotNull($category);
    $this->assertEquals(ActionLogEnum::DELETED, $category->activities->first()->event);
});
