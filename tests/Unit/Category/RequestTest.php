<?php

use App\Helpers\CustomRoute;
use App\Http\Requests\Category\CategoryCreateRequest;
use App\Http\Requests\Category\CategoryUpdateRequest;
use Illuminate\Support\Facades\Validator;

//Create
it('Request - Category/CategoryCreateRequest - Check passes validation when data is valid', function () {
    $validator = Validator::make([
        'name' => 'Food',
        'description' => 'Noodles'
    ], (new CategoryCreateRequest())->rules());
    $this->assertFalse($validator->fails());
});

it('Request - Category/CategoryCreateRequest - Check fail validation when data is not valid', function () {
    $validator = Validator::make([], (new CategoryCreateRequest())->rules());
    $this->assertTrue($validator->fails());
    $this->assertTrue($validator->errors()->has([
        'name'
    ]));

    //check unique name
    $validator2 = Validator::make([
        'name' => 'Vehicle',
    ], (new CategoryCreateRequest())->rules());
    $this->assertTrue($validator2->fails());
    $this->assertTrue($validator2->errors()->has([
        'name'
    ]));

    //check max length Description and name
    $validator3 = Validator::make([
        'name' => Str::random(200),
        'description' => Str::random(2000),
    ], (new CategoryCreateRequest())->rules());
    $this->assertTrue($validator3->fails());
    $this->assertTrue($validator3->errors()->has([
        'name',
        'description'
    ]));
});

//update

it('Request - Category/CategoryUpdateRequest - Check passes validation when data is valid', function () {
    $request = new CategoryUpdateRequest();
    $request->setRouteResolver(function () {
        return new CustomRoute(['id' => 1]);
    });
    $validator = Validator::make([
        'name' => 'Trousers',
        'description' => 'Noodles'
    ], ($request)->rules());
    $this->assertFalse($validator->fails());
});

it('Request - Category/CategoryUpdateRequest - Check fail validation when data is not valid', function () {
    $validator = Validator::make([], (new CategoryUpdateRequest())->rules());
    $this->assertTrue($validator->fails());
    $this->assertTrue($validator->errors()->has([
        'name'
    ]));

    $request = new CategoryUpdateRequest();
    $request->setRouteResolver(function () {
        return new CustomRoute(['id' => 1]);
    });
    //check unique name
    $validator2 = Validator::make([
        'name' => 'Vehicle',
    ], ($request)->rules());
    $this->assertTrue($validator2->fails());
    $this->assertTrue($validator2->errors()->has([
        'name'
    ]));
});
