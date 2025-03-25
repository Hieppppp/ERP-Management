<?php

use App\Http\Requests\Warehouse\WarehouseCreateRequest;
use App\Http\Requests\Warehouse\WarehouseUpdateRequest;
use Illuminate\Support\Facades\Validator;

//Create
it('Request - Warehouse/WarehouseCreateRequest - Check passes validation when data is valid', function () {
    $validator = Validator::make([
        'name' => 'warehouse 1',
        'country' => 'Canada',
        'detail_address' => 'British Columbia',
        'city' => 'Grande Prairie',
        'province' => 'British Columbia',
        'postal_code' => 100000,
        'contact' => '123456789'
    ], (new WarehouseCreateRequest())->rules());
    expect($validator->fails())->toBeFalse();
});

it('Request - Warehouse/WarehouseCreateRequest - Check fail validation when data is not valid', function () {
    $validator = Validator::make([], (new WarehouseCreateRequest())->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has([
        'name',
        'country',
        'detail_address',
        'city',
        'province',
        'postal_code',
        'contact'
    ]))->toBeTrue();
});

//update

it('Request - Warehouse/WarehouseUpdateRequest - Check passes validation when data is valid', function () {
    $validator = Validator::make([
        'name' => 'warehouse 1',
        'country' => 'Canada',
        'detail_address' => 'Grande Prairie',
        'city' => 'Grande Prairie',
        'province' => 'British Columbia',
        'postal_code' => 100000,
        'contact' => '123456789'
    ], (new WarehouseUpdateRequest())->rules());
    expect($validator->fails())->toBeFalse();
});

it('Request - Warehouse/WarehouseUpdateRequest - Check fail validation when data is not valid', function () {
    $validator = Validator::make([], (new WarehouseUpdateRequest())->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has([
        'name',
        'country',
        'detail_address',
        'city',
        'province',
        'postal_code',
        'contact'
    ]))->toBeTrue();
});
