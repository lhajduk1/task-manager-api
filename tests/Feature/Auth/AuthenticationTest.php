<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('user can login', function (): void {
    $user = User::factory()->create([
        'password' => Hash::make('password'),
    ]);

    $response = $this->postJson(route('auth.login'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertOk()
        ->assertJsonStructure(['message', 'token']);

    $this->assertDatabaseHas('personal_access_tokens', [
        'tokenable_id' => $user->id
    ]);
});

test('user cannot login with not existing email', function (): void {
    $response = $this->postJson(route('auth.login'), [
        'email' => 'notexisting@email.com',
        'password' => 'password',
    ]);

    $response->assertStatus(401)
        ->assertExactJson(['message' => 'Invalid credentials']);
});

test('user cannot login with invalid email', function (): void {
    $response = $this->postJson(route('auth.login'), [
        'email' => 'invalid-email',
        'password' => 'password',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors('email');
});

test('user cannot login with not filled email', function (): void {
    $response = $this->postJson(route('auth.login'), [
        'email' => '',
        'password' => 'password',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors('email');
});

test('user cannot login with wrong password', function (): void {
    $user = User::factory()->create([
        'password' => Hash::make('password'),
    ]);

    $response = $this->postJson(route('auth.login'), [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response->assertStatus(401)
        ->assertExactJson(['message' => 'Invalid credentials']);
});

test('user cannot login with not filled password', function (): void {
    $user = User::factory()->create([
        'password' => Hash::make('password'),
    ]);

    $response = $this->postJson(route('auth.login'), [
        'email' => $user->email,
        'password' => '',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors('password');
});


test('user can logout using token', function (): void {
    $user = User::factory()->create();

    $token = $user->createToken('auth_token');
    $token2 = $user->createToken('auth_token');

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token->plainTextToken,
    ])->postJson(route('auth.logout'));

    $response->assertOk()
        ->assertExactJson(['message' => 'Logout successful']);

    $this->assertDatabaseMissing('personal_access_tokens', ['tokenable_id' => $user->id, 'id' => $token->accessToken->id]);
    $this->assertDatabaseHas('personal_access_tokens', ['tokenable_id' => $user->id, 'id' => $token2->accessToken->id]);
});

test('user cannot logout without token', function (): void {
    $response = $this->postJson(route('auth.logout'));

    $response->assertStatus(401)
        ->assertExactJson(['message' => 'Unauthenticated.']);
});

test('users are rate limited', function (): void {
    $user = User::factory()->create([
        'password' => 'password'
    ]);

    for ($i = 0; $i < 6; $i++) {
        $response = $this->postJson(route('auth.login'), [
            'email' => $user->email,
            'password' => 'password'
        ]);
    }

    $response->assertTooManyRequests();
});
