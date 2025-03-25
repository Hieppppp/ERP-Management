<?php

use App\Helpers\CustomRoute;
use App\Http\Requests\Unit\UnitCreateRequest;
use App\Http\Requests\Unit\UnitUpdateRequest;
use Illuminate\Support\Facades\Validator;

//Create
it('Request - Unit/UnitCreateRequest - Check passes validation when data is valid', function () {
    $validator = Validator::make([
        'name' => 'microgram',
        'symbol' => 'µg',
    ], (new UnitCreateRequest())->rules());
    $this->assertFalse($validator->fails());
});

it('Request - Unit/UnitCreateRequest - Check fail validation when data is not valid', function () {
    $validator = Validator::make([], (new UnitCreateRequest())->rules());
    $this->assertTrue($validator->fails());
    $this->assertTrue($validator->errors()->has([
        'name',
        'symbol'
    ]));
    //check unique symbol
    $validator = Validator::make([
        'name' => 'Kilogram 1',
        'symbol' => 'kg'
    ], (new UnitCreateRequest())->rules());
    $this->assertTrue($validator->fails());
    $this->assertTrue($validator->errors()->has([
        'symbol'
    ]));
});

//update

it('Request - Unit/UnitUpdateRequest - Check passes validation when data is valid', function () {
    $request = new UnitUpdateRequest();
    $request->setRouteResolver(function () {
        return new CustomRoute(['id' => 1]);
    });
    $validator = Validator::make([
        'name' => 'microgram',
        'symbol' => 'µg',
    ], ($request)->rules());
    $this->assertFalse($validator->fails());
});

it('Request - Unit/UnitUpdateRequest - Check fail validation when data is not valid', function () {
    $request = new UnitUpdateRequest();
    $request->setRouteResolver(function () {
        return new CustomRoute(['id' => 1]);
    });
    $validator = Validator::make([], ($request)->rules());
    $this->assertTrue($validator->fails());
    $this->assertTrue($validator->errors()->has([
        'name',
        'symbol'
    ]));
    //check unique symbol
    $validator = Validator::make([
        'name' => 'microgram',
        'symbol' => 'm',
    ], ($request)->rules());
    $this->assertTrue($validator->fails());
    $this->assertTrue($validator->errors()->has([
        'symbol'
    ]));
});
