<?php

it('POST /api/v1/auth/login - Success', function () {
    $data = [
        'username' => 'admin',
        'password' => '@admin1234',
    ];
    $this->postJson('/api/v1/auth/login', $data)->assertStatus(200);
});

it('POST /api/v1/auth/login - Check 401', function () {
    $data = [
        'username' => 'admin',
        'password' => '@admin12341',
    ];
    $this->postJson('/api/v1/auth/login', $data)->assertStatus(401);
});

it('POST /api/v1/auth/login - Check 422', function () {
    $data = [
        'username' => 'admin'
    ];
    $this->postJson('/api/v1/auth/login', $data)->assertStatus(422);
});
