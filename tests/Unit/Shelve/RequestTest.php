<?php

use App\Http\Requests\Shelve\ShelveCreateRequest;
use App\Http\Requests\Shelve\ShelveUpdateRequest;
use Illuminate\Support\Facades\Validator;

//Create
it('Request - Shelve/ShelveCreateRequest - Check passes validation when data is valid', function () {
    $validator = Validator::make([
        'name' => 'shelf 1',
        'location' => 'In the middle of the warehouse',
        'warehouse_id' => 1,
    ], (new ShelveCreateRequest())->rules());
    expect($validator->fails())->toBeFalse();
});

it('Request - Shelve/ShelveCreateRequest - Check fail validation when data is not valid', function () {
    $validator = Validator::make([], (new ShelveCreateRequest())->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has([
        'name',
        'warehouse_id'
    ]))->toBeTrue();

    //check warehouse not found
    $validator2 = Validator::make([
        'warehouse' => 100000,
        'name' => 'shelf 1'
    ], (new ShelveCreateRequest())->rules());

    expect($validator2->errors()->has([
        'warehouse_id'
    ]))->toBeTrue();
});

//update

it('Request - Shelve/ShelveUpdateRequest - Check passes validation when data is valid', function () {
    $validator = Validator::make([
        'name' => 'shelf 10000',
        'location' => 'In the middle of the warehouse',
        'warehouse_id' => 2,
    ], (new ShelveUpdateRequest())->rules());
    expect($validator->fails())->toBeFalse();
});

it('Request - Shelve/ShelveUpdateRequest - Check fail validation when data is not valid', function () {
    $validator = Validator::make([], (new ShelveUpdateRequest())->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has([
        'name',
        'warehouse_id'
    ]))->toBeTrue();

    //check warehouse not found
    $validator2 = Validator::make([
        'warehouse' => 100000,
        'name' => 'shelf 1'
    ], (new ShelveCreateRequest())->rules());

    expect($validator2->errors()->has([
        'warehouse_id'
    ]))->toBeTrue();
});
