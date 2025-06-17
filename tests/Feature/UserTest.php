<?php

use App\Models\User;

// Merchants List
test('Authenticated user can access Merchants list page', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get('/merchant');
    $response->assertStatus(200);
});

test('Guests cannot access Merchants list page', function () {
    $response = $this->get('/merchant');
    $response->assertRedirect('/login');
});

// Notes List
test('Authenticated user can access Notes list page', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get('/notes');
    $response->assertStatus(200);
});

test('Guests cannot access Notes list page', function () {
    $response = $this->get('/notes');
    $response->assertRedirect('/login');
});
