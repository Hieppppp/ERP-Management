<?php

use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordViewRequest;
use Illuminate\Support\Facades\Validator;

//Create
it('Request - Auth/LoginRequest - Check passes validation when data is valid', function () {
    $validator = Validator::make([
        'username' => 'John',
        'password' => 'Xemmex1',
    ], (new LoginRequest())->rules());
    expect($validator->fails())->toBeFalse();
});

it('Request - Auth/LoginRequest - Check fail validation when data is not valid', function () {
    $validator = Validator::make([], (new LoginRequest())->rules());
    expect($validator->fails())->toBeTrue();
});

it('Request - Auth/ForgotPasswordRequest - Check passes validation when data is valid', function () {
    $validator = Validator::make([
        'email' => 'admin@gmail.com'
    ], (new ForgotPasswordRequest())->rules());
    expect($validator->fails())->toBeFalse();
});

it('Request - Auth/ForgotPasswordRequest - Check fail validation when data is not valid', function () {
    $validator = Validator::make([
        'email' => 'admidssdsdngmail.com'
    ], (new ForgotPasswordRequest())->rules());
    expect($validator->fails())->toBeTrue();
});

it('Request - Auth/ResetPasswordRequest - Check passes validation when data is valid', function () {
    $validator = Validator::make([
        'token' => Str::random(),
        'email' => 'admin@gmail.com',
        'password' => 'Xemmex@1234',
        'password_confirmation' => 'Xemmex@1234',
    ], (new ResetPasswordRequest())->rules());
    expect($validator->fails())->toBeFalse();
});

it('Request - Auth/ResetPasswordRequest - Check fails validation when data is not valid', function () {
    $validator = Validator::make([
        'email' => 'admin@gmail.com',
        'password' => 'Xemmex@1234',
        'password_confirmation' => 'Xem1mex@1234',
    ], (new ResetPasswordRequest())->rules());
    expect($validator->fails())->toBeTrue();
});

it('Request - Auth/ResetPasswordViewRequest - Check passes validation when data is valid', function () {
    $validator = Validator::make([
        'token' => Str::random(),
        'email' => 'admin@gmail.com',
    ], (new ResetPasswordViewRequest())->rules());
    expect($validator->fails())->toBeFalse();
});

it('Request - Auth/ResetPasswordViewRequest - Check fails validation when data is not valid', function () {
    $validator = Validator::make([
        'token' => Str::random(),
        'email' => 'admingmail.com',
    ], (new ResetPasswordViewRequest())->rules());
    expect($validator->fails())->toBeTrue();
});
