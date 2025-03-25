<?php

use App\Helpers\CustomRoute;
use App\Http\Requests\Supplier\SupplierCreateRequest;
use App\Http\Requests\Supplier\SupplierEditRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;

beforeEach(function () {
    createPersonalAccessClient();
});

//Create
it('Request - Supplier/SupplierCreateRequest - Check passes validation when data is valid', function () {
    $file = UploadedFile::fake()->image('test.jpg', 600, 600)->size(1024);
    $validator = Validator::make([
        'name' => 'supplier 1',
        'country' => 'Canada',
        'detail_address' => 'British Columbia',
        'city' => 'Grande Prairie',
        'province' => 'British Columbia',
        'email' => 'suppliertest@gmail.com',
        'phone' => "+12505550199",
        'postal_code' => "A1A 1A1",
        'logo' => $file
    ], (new SupplierCreateRequest())->rules());
    $this->assertFalse($validator->fails());
});

it('Request - Supplier/SupplierCreateRequest - Check fail validation when data is not valid', function () {
    $validator = Validator::make([], (new SupplierCreateRequest())->rules());
    $this->assertTrue($validator->fails());
    $this->assertTrue($validator->errors()->has([
        'name',
        'country',
        'detail_address',
        'city',
        'province',
        'phone',
        'email',
    ]));
    //check unique email
    $validator = Validator::make([
        'email' => 'supplier1@gmail.com'
    ], (new SupplierCreateRequest())->rules());
    $this->assertTrue($validator->fails());
    $this->assertTrue($validator->errors()->has([
        'email'
    ]));

    //check format site
    $validator = Validator::make([
        'site' => 'wefwef'
    ], (new SupplierCreateRequest())->rules());
    $this->assertTrue($validator->fails());
    $this->assertTrue($validator->errors()->has([
        'site'
    ]));

    //check format logo
    $file = UploadedFile::fake()->image('test.txt', 600, 600)->size(1024);
    $validator = Validator::make([
        'logo' => $file
    ], (new SupplierCreateRequest())->rules());
    $this->assertTrue($validator->fails());
    $this->assertTrue($validator->errors()->has([
        'logo'
    ]));
});

//update
it('Request - Supplier/SupplierUpdateRequest - Check passes validation when data is valid', function () {
    $validator = Validator::make([
        'name' => 'supplier 1',
        'country' => 'Canada',
        'detail_address' => 'British Columbia',
        'city' => 'Grande Prairie',
        'province' => 'British Columbia',
        'email' => 'suppliertest@gmail.com',
        'phone' => "+12505550199",
        'postal_code' => "A1A 1A1",
    ], (new SupplierEditRequest())->rules());
    expect($validator->fails())->toBeFalse();
});

it('Request - Supplier/SupplierUpdateRequest - Check fail validation when data is not valid', function () {
    $request = new SupplierEditRequest();
    $request->setRouteResolver(function () {
        return new CustomRoute(['supplier' => 1]);
    });
    $validator = Validator::make([], ($request)->rules());
    $this->assertTrue($validator->fails());
    $this->assertTrue($validator->errors()->has([
        'name',
        'country',
        'detail_address',
        'city',
        'province',
        'phone',
        'email'
    ]));
    // //check unique email
    $validator = Validator::make([
        'email' => 'supplier2@gmail.com',
    ], ($request)->rules());
    $this->assertTrue($validator->fails());
    $this->assertTrue($validator->errors()->has([
        'email'
    ]));

    //check format site
    $validator = Validator::make([
        'site' => 'wefwef'
    ], ($request)->rules());
    $this->assertTrue($validator->fails());
    $this->assertTrue($validator->errors()->has([
        'site'
    ]));

    //check format logo
    $file = UploadedFile::fake()->image('test.txt', 600, 600)->size(1024);
    $validator = Validator::make([
        'logo' => $file
    ], ($request)->rules());
    $this->assertTrue($validator->fails());
    $this->assertTrue($validator->errors()->has([
        'logo'
    ]));
});
