<?php

use App\Enums\Permission;
use App\Helpers\PermissionRole;
use App\Models\User;

//Create
it('Permission - PermissionRole - Check permission with role as user', function () {
    $checkPermissionIllegal = PermissionRole::checkPermission([Permission::CATEGORY], User::find(4));
    expect($checkPermissionIllegal)->toBeFalse();
});

it('Permission - PermissionRole - Check permission with role as admin', function () {
    $checkPermission = PermissionRole::checkPermission([Permission::CUSTOMER], User::find(2));
    expect($checkPermission)->toBeTrue();
});

it('Permission - PermissionRole - Check permission with role as supper admin', function () {
    $checkPermission = PermissionRole::checkPermission([Permission::PRODUCT], User::find(1));
    expect($checkPermission)->toBeTrue();
});
