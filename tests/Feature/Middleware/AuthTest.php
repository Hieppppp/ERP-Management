<?php

use App\Models\User;

it('Middleware auth - Success', function () {
    $user = User::where('username', 'admin')->first();
    $this->actingAs($user);

    $response = $this->get('/');
    $response->assertStatus(200);
});

it('Middleware auth - fails', function () {
    $response = $this->get('/');
    $response->assertRedirect('/login');
});
