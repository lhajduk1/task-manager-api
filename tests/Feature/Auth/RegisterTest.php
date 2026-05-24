<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('user can register', function (): void {
    $response = $this->postJson(route('auth.register'), [
        'name' => 'John Doe',
        'email' => 'johndoe@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertCreated()
        ->assertJsonPath('message', 'User created successfully')
        ->assertJsonPath('user.name', 'John Doe')
        ->assertJsonPath('user.email', 'johndoe@example.com')
        ->assertJsonStructure([
            'message',
            'user' => [
                'id',
                'name',
                'email',
            ]
        ])
        ->assertJsonMissingPath('user.password')
        ->assertJsonMissingPath('user.remember_token')
        ->assertJsonMissingPath('user.email_verified_at')
        ->assertJsonMissingPath('user.created_at')
        ->assertJsonMissingPath('user.updated_at');

    $user = User::where('email', 'johndoe@example.com')->first();

    expect($user)->not->toBeNull()
        ->and($user->name)->toBe('John Doe')
        ->and($user->email)->toBe('johndoe@example.com')
        ->and(Hash::check('password', $user->password))->toBeTrue()
        ->and($user->password)->not->toBe('password');

    $this->assertDatabaseHas('users', ['name' => 'John Doe', 'email' => 'johndoe@example.com']);
});

test('user cannot register with invalid email', function (): void {
    $response = $this->postJson(route('auth.register'), [
        'name' => 'John Doe',
        'email' => 'invalid-email',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response
        ->assertStatus(422)
        ->assertJsonValidationErrors('email');

    $this->assertDatabaseMissing('users', ['email' => 'invalid-email']);
});

test('user cannot register with invalid password', function (): void {
    $response = $this->postJson(route('auth.register'), [
        'name' => 'John Doe',
        'email' => 'johndoe@example.com',
        'password' => '123',
        'password_confirmation' => '123',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors('password');

    $this->assertDatabaseMissing('users', ['email' => 'johndoe@example.com']);
});

test('user cannot register with mismatched passwords', function (): void {
    $response = $this->postJson(route('auth.register'), [
        'name' => 'John Doe',
        'email' => 'johndoe@example.com',
        'password' => 'password',
        'password_confirmation' => 'wrong-password',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors('password');

    $this->assertDatabaseMissing('users', ['email' => 'johndoe@example.com']);
});

test('user cannot register with existing email', function (): void {
    User::factory()->create([
        'email' => 'johndoe@example.com',
    ]);

    $response = $this->postJson(route('auth.register'), [
        'name' => 'John Doe',
        'email' => 'johndoe@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors('email');
});
