<?php

use App\Helpers\CustomRoute;
use App\Http\Requests\Customer\CustomerCreateRequest;
use App\Http\Requests\Customer\CustomerEditRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;

beforeEach(function () {
    createPersonalAccessClient();
});

//Create
it('Request - Customer/CustomerCreateRequest - Check passes validation when data is valid', function () {
    $file = UploadedFile::fake()->image('test.png', 600, 600)->size(1024);
    $validator = Validator::make([
        'first_name' => 'FirstName',
        'last_name' => 'LastName',
        'email' => 'customer.example@gmail.com',
        'phone' => '+12505550190',
        'detail_address' => 'Prairie',
        'postal_code' => '1000',
        'country' => 'Canada',
        'province' => 'British Columbia',
        'city' => 'Grande Prairie',
        'company_email' => 'companyexample@gmail.com',
        'avatar' => $file
    ], (new CustomerCreateRequest())->rules());
    $this->assertFalse($validator->fails());
});

it('Request - Customer/CustomerCreateRequest - Check fail validation when data is not valid', function () {
    $validator = Validator::make([], (new CustomerCreateRequest())->rules());
    $this->assertTrue($validator->fails());
    $this->assertTrue($validator->errors()->has([
        'first_name',
        'last_name',
        'email',
        'phone',
        'detail_address',
        'postal_code',
        'country',
        'province',
        'city',
        'company_email',
    ]));
    //check unique email
    $validator = Validator::make([
        'email' => 'customer1@gmail.com',
        'company_email' => 'company01@gmail.com'
    ], (new CustomerCreateRequest())->rules());
    $this->assertTrue($validator->fails());
    $this->assertTrue($validator->errors()->has('email'));
    $this->assertTrue($validator->errors()->has('company_email'));
    //check format avatar
    $file = UploadedFile::fake()->image('test.txt', 600, 600)->size(1024);
    $validator = Validator::make([
        'avatar' => $file
    ], (new CustomerCreateRequest())->rules());
    $this->assertTrue($validator->fails());
    $this->assertTrue($validator->errors()->has([
        'avatar'
    ]));
});

//update
it('Request - Customer/CustomerUpdateRequest - Check passes validation when data is valid', function () {
    $validator = Validator::make([
        'first_name' => 'Customer',
        'last_name' => '1',
        'email' => 'customertest@gmail.com',
        'phone' => "+12505550190",
        'postal_code' => 1000,
        'country' => 'Canada',
        'province' => 'British Columbia',
        'city' => 'Grande Prairie',
        'detail_address' => 'Prairie',
        'company_email' => 'company.test@gmail.com',
    ], (new CustomerEditRequest())->rules());
    expect($validator->fails())->toBeFalse();
});

it('Request - Customer/CustomerUpdateRequest - Check fail validation when data is not valid', function () {
    $request = new CustomerEditRequest();
    $request->setRouteResolver(function () {
        return new CustomRoute(['customer' => 1]);
    });
    $validator = Validator::make([], (new CustomerCreateRequest())->rules());
    $this->assertTrue($validator->fails());
    $this->assertTrue($validator->errors()->has([
        'first_name',
        'last_name',
        'email',
        'phone',
        'detail_address',
        'postal_code',
        'country',
        'province',
        'city',
        'company_email',
    ]));
    //check unique email
    $validator = Validator::make([
        'email' => 'customer1@gmail.com',
        'company_email' => 'company01@gmail.com'
    ], (new CustomerCreateRequest())->rules());
    $this->assertTrue($validator->fails());
    $this->assertTrue($validator->errors()->has('email'));
    $this->assertTrue($validator->errors()->has('company_email'));
    //check format avatar
    $file = UploadedFile::fake()->image('test.txt', 600, 600)->size(1024);
    $validator = Validator::make([
        'avatar' => $file
    ], (new CustomerCreateRequest())->rules());
    $this->assertTrue($validator->fails());
    $this->assertTrue($validator->errors()->has([
        'avatar'
    ]));
});
