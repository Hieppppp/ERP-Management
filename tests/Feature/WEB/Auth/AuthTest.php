<?php

use App\Models\User;
use Illuminate\Support\Facades\Password;

it('Auth /login - Success', function () {
    $user = User::where('username', 'admin')->first();
    $data = [
        'username' => 'admin',
        'password' => '@admin1234',
    ];
    $response = $this->postJson('/login', $data);
    $this->assertAuthenticatedAs($user);
    $response->assertRedirect('/');
    $response->assertSessionHas('success', trans('message.loginSuccess'));
});

it('Auth /login - fails', function () {
    $data = [
        'username' => 'admin',
        'password' => '@admi11n1234',
    ];
    $response = $this->postJson('/login', $data);
    $this->assertGuest();
    $response->assertRedirect();
    $response->assertSessionHas('error', trans('message.loginFail'));
});


it('Auth /Forgot Password - Success', function () {

    $response = $this->post('/forgot-password', ['email' => 'admin@gmail.com']);

    $response->assertRedirect('/resend-email');
    $response->assertSessionHas('userEmailResendEmail', 'admin@gmail.com');
});

it('Auth /Forgot Password - Fail', function () {
    Password::shouldReceive('sendResetLink')->andReturn('user_not_found');

    $response = $this->post('/forgot-password', ['email' => 'nonexistent@example.com']);

    $response->assertRedirect();
    $response->assertSessionHasErrors(['email' => 'user_not_found']);
});
