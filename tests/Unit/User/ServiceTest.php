<?php

use App\Enums\UserRole;
use App\Common\Entity\DatatableParams;
use App\Enums\ActionLogEnum;
use App\Http\Requests\User\UserDatatableRequest;
use App\Models\User;
use App\Services\User\UserServiceInterface;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;


beforeEach(function () {
    $this->userService = app(UserServiceInterface::class);
});

//paginate
it('User/Paginate - Check the function runs successfully and check order by', function () {
    $datatableRequest = new UserDatatableRequest();
    $dataValidate = DatatableParams::createDatatableParams(
        [
            'id',
            'username',
            'role',
            'email',
            'created_at'
        ],
        [
            'orders' => [
                'id' => 'desc'
            ],
            'length' => 5
        ]
    );
    $params = $datatableRequest->getDatatableParamsWithParams($dataValidate);
    $data = $this->userService->paginate($params);
    $expect1 = $data instanceof LengthAwarePaginator || $data instanceof Paginator;
    $data = $data->jsonSerialize();
    $expect2 = count($data['data']) <= 5;
    $expect3 = count(array_filter($data['data'], function ($user) {
        return $user['role'] == UserRole::SUPPER_ADMIN;
    })) <= 0;

    // check order
    $userId = $userIdSort = array_column($data['data'], 'id');
    rsort($userIdSort);
    $expect4 = $userId === $userIdSort;
    expect($expect1)->toBeTrue();
    expect($expect2)->toBeTrue();
    expect($expect3)->toBeTrue();
    expect($expect4)->toBeTrue();
});

it('User/Paginate - Test the filter feature', function () {
    $datatableRequest = new UserDatatableRequest();
    $dataValidate = DatatableParams::createDatatableParams(
        [
            'id',
            'username',
            'role',
            'email',
            'created_at'
        ],
        [
            'globalSearch' => 'admin1',
        ]
    );
    $params = $datatableRequest->getDatatableParamsWithParams($dataValidate);
    $data = $this->userService->paginate($params);
    $expect1 = $data instanceof LengthAwarePaginator || $data instanceof Paginator;
    $users = $data->jsonSerialize();
    $expect2 = false;
    foreach ($users['data'] as $user) {
        if (
            preg_match('/admin1/', strval($user['username'])) ||
            preg_match('/admin1/', strval($user['id'])) ||
            preg_match('/admin1/', strval($user['email'])) ||
            preg_match('/admin1/', strval($user['created_at']))
        ) {
            $expect2 = true;
            break;
        }
    }
    expect($expect1)->toBeTrue();
    expect($expect2)->toBeTrue();
});

it('User/Paginate - Test the search feature', function () {
    $datatableRequest = new UserDatatableRequest();
    $dataValidate = DatatableParams::createDatatableParams(
        [
            'id',
            'username',
            'role',
            'email',
            'created_at'
        ],
        [
            'searchFields' => [
                'email' => 'admin1'
            ],
            'orders' => [
                'id' => 'asc'
            ],
        ]
    );
    $params = $datatableRequest->getDatatableParamsWithParams($dataValidate);
    $data = $this->userService->paginate($params);
    $expect1 = $data instanceof LengthAwarePaginator || $data instanceof Paginator;
    $data = $data->jsonSerialize();
    $expect2 = ($data['data'][0]['email'] == 'admin1@gmail.com');
    expect($expect1)->toBeTrue();
    expect($expect2)->toBeTrue();
});

//create
it('User/Create - Check create feature', function () {
    $dataCreate = [
        'username' => 'testCreate',
        'first_name' => "test",
        'last_name' => "Create",
        'role' => UserRole::USER,
        'email' => "testcreate@gmail.com",
        'password' => "Xemmex@123"
    ];
    $user = $this->userService->create($dataCreate);
    $expect1 = $user instanceof User;
    $expect2 = ($user->username == 'testCreate');
    $userDatabase = User::where('username', 'testCreate')->first();
    $expect3 = ($userDatabase != null);
    expect($expect1)->toBeTrue();
    expect($expect2)->toBeTrue();
    expect($expect3)->toBeTrue();
    //check log
    $this->assertEquals(ActionLogEnum::CREATED, $userDatabase->activities->first()->event);
});

//show
it('User/Show - Check user not found', function () {
    expect(fn() => $this->userService->findById(10000))->toThrow(ModelNotFoundException::class);
});

it('User/Show - Check success', function () {
    $userDatabase = User::find(2);
    $userService = $this->userService->findById(2);
    expect($userDatabase == $userService)->toBeTrue();
});

//update
it('User/Update - Check update feature', function () {
    $userDatabase = User::find(2);
    $dataUpdate = [
        'first_name' => "test",
        'last_name' => "update",
        'email' => 'testupdate@gmail.com',
        'role' => UserRole::USER,
        'permission_ids' => [1, 2]
    ];
    $user = $this->userService->update($dataUpdate, $userDatabase['id']);
    $expect1 = $user instanceof User;
    $expect2 = ($user->first_name == 'test');
    $expect3 = ($user->last_name == 'update');
    $expect4 = ($user->email == 'testupdate@gmail.com');
    $expect5 = ($user->role == UserRole::USER);
    expect($expect1)->toBeTrue();
    expect($expect2)->toBeTrue();
    expect($expect3)->toBeTrue();
    expect($expect4)->toBeTrue();
    expect($expect5)->toBeTrue();
    // Check log
    $log = $user->activities()->orderBy('id', 'desc')->first();
    $this->assertEquals(ActionLogEnum::UPDATED, $log->event);
    $dataChanged = $log->properties['attributes'] ?? [];
    $dataBeforeChanged = $log->properties['old'] ?? [];
    unset($dataUpdate['permission_ids']);
    foreach ($dataUpdate as $key => $expectedValue) {
        if (array_key_exists($key, $dataChanged)) {
            $this->assertEquals($expectedValue, $dataChanged[$key]);
        }
    }
    foreach ($dataUpdate as $key => $expectedValue) {
        if (array_key_exists($key, $dataBeforeChanged)) {
            $this->assertEquals($userDatabase[$key], $dataBeforeChanged[$key]);
        }
    }
    $this->assertEquals([1, 2], $dataChanged['permission_ids']);
});

it('User/Update - Check user not found', function () {
    $dataUpdate = [
        'first_name' => "test",
        'last_name' => "update",
        'email' => 'testupdate@gmail.com',
        'role' => UserRole::USER,
    ];
    expect(fn() => $this->userService->update($dataUpdate, 10000))->toThrow(ModelNotFoundException::class);
});

//delete
it('User/Delete - Check user not found', function () {
    expect(fn() => $this->userService->delete(10000))->toThrow(ModelNotFoundException::class);
});

it('User/Delete - Check Success', function () {
    $user = User::where('role', UserRole::SUPPER_ADMIN)->first();
    $this->actingAs($user);

    expect($this->userService->delete(2))->toBeTrue();
    $user = User::find(2);
    expect($user == null)->toBeTrue();
    //check log and check soft delete
    $user = User::withTrashed()->find(2);
    $this->assertNotNull($user);
    $this->assertEquals(ActionLogEnum::DELETED,  $user->activities()->orderBy('id', 'desc')->first()->event);
});

it('User/UpdateAvatar - Check Success', function () {
    Auth::shouldReceive('user')->andReturn(User::find(2));
    $file = UploadedFile::fake()->image('test.jpg', 600, 600)->size(1024);
    $user = $this->userService->updateAvatar(['avatar' => $file]);
    Storage::disk('public')->assertExists('avatar/' . $user->avatar);
    //check log
    $user = User::find(2);
    $this->assertEquals(ActionLogEnum::UPDATED,  $user->activities()->orderBy('id', 'desc')->first()->event);
});
