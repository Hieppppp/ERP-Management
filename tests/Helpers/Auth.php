<?php

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\ClientRepository;
use Tests\TestCase;

function login(): TestCase
{
    $user = User::first();
    if (!$user) {
        $user = User::factory()->create();
    }
    return test()->actingAs($user);
}

function createPersonalAccessClient()
{
    $clientRepository = new ClientRepository();
    $client = $clientRepository->createPersonalAccessClient(
        null, 'Test Personal Access Client', 'http://localhost'
    );

    return $client;
}
