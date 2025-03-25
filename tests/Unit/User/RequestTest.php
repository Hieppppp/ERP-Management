<?php

use App\Enums\UserRole;
use App\Helpers\CustomRoute;
use App\Http\Requests\User\ProfileRequest;
use App\Http\Requests\User\UpdateAvatarRequest;
use App\Http\Requests\User\UpdatePasswordRequest;
use App\Http\Requests\User\UserCreateRequest;
use App\Http\Requests\User\UserDatatableRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

//Create
it('Request - User/UserCreateRequest - Check passes validation when data is valid', function () {
    $validator = Validator::make([
        'username' => 'John',
        'email' => 'john@example.com',
        'first_name' => 'John',
        'last_name' => 'John',
        'role' => UserRole::ADMIN,
        'password' => 'Xemmex@123',
    ], (new UserCreateRequest())->rules());
    expect($validator->fails())->toBeFalse();
});

it('Request - User/UserCreateRequest - Check fail validation when data is not valid', function () {
    $request = new UserCreateRequest();
    $validator = Validator::make([], ($request)->rules());
    $expect1 = $validator->fails();
    $expect2 = $validator->errors()->has([
        'username',
        'email',
        'first_name',
        'last_name',
        'password'
    ]);
    $validator2 = Validator::make([
        'username' => 'John',
        'email' => 'johnexample.com',
        'first_name' => 'John',
        'last_name' => 'John',
        'role' => 'root_adin',
        'password' => 'Xemmex',
    ], ($request)->rules());
    $expect3 = $validator2->fails();
    $expect4 = $validator2->errors()->has([
        'email',
        'role',
        'password'
    ]);
    $validator3 = Validator::make([
        'username' => 'admin',
        'email' => 'admin@gmail.com',
        'first_name' => 'John',
        'last_name' => 'John',
        'role' => UserRole::ADMIN,
        'password' => 'Xemmex@123',
    ], ($request)->rules());
    $expect5 = $validator3->fails();
    $expect6 = $validator3->errors()->has([
        'username',
        'email'
    ]);
    expect($expect1)->toBeTrue();
    expect($expect2)->toBeTrue();
    expect($expect3)->toBeTrue();
    expect($expect4)->toBeTrue();
    expect($expect5)->toBeTrue();
    expect($expect6)->toBeTrue();
});

//update
it('Request - User/UserUpdateRequest - Check passes validation when data is valid', function () {
    $id = 1;
    $request = new UserUpdateRequest();
    $request->setRouteResolver(function () use ($id) {
        return new CustomRoute(['id' => $id]);
    });
    $validator = Validator::make([
        'username' => 'admin',
        'email' => 'john@example.com',
        'first_name' => 'John',
        'last_name' => 'John',
        'role' => UserRole::ADMIN,
        'password' => 'Xemmex@123',
    ], ($request)->rules());
    expect($validator->fails())->toBeFalse();
});

it('Request - User/UserUpdateRequest - Check fail validation when data is not valid', function () {
    $id = 1;
    $request = new UserUpdateRequest();
    $request->setRouteResolver(function () use ($id) {
        return new CustomRoute(['id' => $id]);
    });
    $validator = Validator::make([], ($request)->rules());
    $expect1 = $validator->fails();
    $expect2 = $validator->errors()->has([
        'username',
        'email',
        'first_name',
        'last_name',
    ]);
    $validator2 = Validator::make([
        'username' => 'John',
        'email' => 'johnexample.com',
        'first_name' => 'John',
        'last_name' => 'John',
        'role' => 'root_adin',
    ], ($request)->rules());
    $expect3 = $validator2->fails();
    $expect4 = $validator2->errors()->has([
        'email',
        'role',
    ]);
    $validator3 = Validator::make([
        'username' => 'admin1',
        'email' => 'admin1@gmail.com',
        'first_name' => 'John',
        'last_name' => 'John',
        'role' => UserRole::ADMIN,
    ], ($request)->rules());
    $expect5 = $validator3->fails();
    $expect6 = $validator3->errors()->has([
        'username',
        'email'
    ]);
    expect($expect1)->toBeTrue();
    expect($expect2)->toBeTrue();
    expect($expect3)->toBeTrue();
    expect($expect4)->toBeTrue();
    expect($expect5)->toBeTrue();
    expect($expect6)->toBeTrue();
});

// Get List With Datatable

it('Request - User/UserDatatableRequest - Check passes validation when data is valid', function () {
    $request = new UserDatatableRequest();
    $dataValidate = [
        "draw" => "3",
        "start" => 0,
        "length" => 10,
        "search" => [
            "value" => null,
            "regex" => "false"
        ],
        "columns" => [
            [
                "data" => "id",
                "name" => "id",
                "searchable" => "true",
                "orderable" => "true",
                "search" => [
                    "value" => null,
                    "regex" => "false"
                ]
            ],
            [
                "data" => "username",
                "name" => "username",
                "searchable" => "true",
                "orderable" => "true",
                "search" => [
                    "value" => null,
                    "regex" => "false"
                ]
            ],
            [
                "data" => "function",
                "name" => "role",
                "searchable" => "true",
                "orderable" => "false",
                "search" => [
                    "value" => null,
                    "regex" => "false"
                ]
            ],
            [
                "data" => "email",
                "name" => "email",
                "searchable" => "true",
                "orderable" => "true",
                "search" => [
                    "value" => null,
                    "regex" => "false"
                ]
            ],
            [
                "data" => "function",
                "name" => "created_at",
                "searchable" => "true",
                "orderable" => "true",
                "search" => [
                    "value" => null,
                    "regex" => "false"
                ]
            ],
            [
                "data" => "function",
                "name" => null,
                "searchable" => "true",
                "orderable" => "false",
                "search" => [
                    "value" => null,
                    "regex" => "false"
                ]
            ]
        ],
        "order" => [
            [
                "column" => "0",
                "dir" => "desc"
            ]
        ]
    ];
    $validator = Validator::make($dataValidate, ($request)->rules());
    expect($validator->fails())->toBeFalse();
});

it('Request - User/UserDatatableRequest - Check fail validation when data is not valid', function () {
    $request = new UserDatatableRequest();
    $validator = Validator::make([], ($request)->rules());
    $expect = $validator->errors()->has([
        'draw',
        'start',
        'length',
        'search',
        'columns',
        'order'
    ]);
    expect($validator->fails())->toBeTrue();
    expect($expect)->toBeTrue();
});

it('Request - User/ProfileRequest - Check fail validation when data is not valid', function () {
    $request = new ProfileRequest();
    Auth::shouldReceive('user')->andReturn(User::find(2));
    $validator = Validator::make([], ($request)->rules());
    expect($validator->fails())->toBeTrue();
});

it('Request - User/ProfileRequest - Check pass validation when data is valid', function () {
    $request = new ProfileRequest();
    Auth::shouldReceive('user')->andReturn(User::find(2));
    $validator = Validator::make([
        'first_name' => 'test',
        'last_name' => 'profile',
        'email' => 'testprofile@gmail.com'
    ], ($request)->rules());
    expect($validator->fails())->toBeFalse();
});

it('Request - User/UpdateAvatarRequest - Check pass validation when data is valid', function () {
    $file = UploadedFile::fake()->image('test.jpg', 600, 600)->size(1024);

    $request = new UpdateAvatarRequest();

    $data = ['avatar' => $file];

    $validator = Validator::make($data, $request->rules());

    expect($validator->passes())->toBeTrue();
});

it('Request - User/UpdateAvatarRequest - Check fail validation when file type not valid', function () {
    $file = UploadedFile::fake()->image('test.txt', 600, 600)->size(1024);

    $request = new UpdateAvatarRequest();

    $data = ['avatar' => $file];

    $validator = Validator::make($data, $request->rules());

    expect($validator->passes())->toBeFalse();
});

it('Request - User/UpdateAvatarRequest - Check fail validation when file size not valid', function () {
    $file = UploadedFile::fake()->image('test.txt', 600, 600)->size(3000);

    $request = new UpdateAvatarRequest();

    $data = ['avatar' => $file];

    $validator = Validator::make($data, $request->rules());

    expect($validator->passes())->toBeFalse();
});

it('Request - User/UpdatePasswordRequest - Check pass validation when data valid', function () {

    $request = new UpdatePasswordRequest();

    $validator = Validator::make([
        'password' => 'Xemmex@123',
        'new_password' => 'Xemmex@12333'
    ], ($request)->rules());

    expect($validator->passes())->toBeTrue();
});

it('Request - User/UpdatePasswordRequest - Check fail validation when data is not valid', function () {

    $request = new UpdatePasswordRequest();

    $validator = Validator::make([], ($request)->rules());

    expect($validator->passes())->toBeFalse();
});
