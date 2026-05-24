<?php

use App\Events\Auth\UserRegistered;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

uses(RefreshDatabase::class);

test('users can be registered', function (): void {
    Event::fake();

    $this->postJson(route('auth.register'), [
        'name' => 'John Doe',
        'email' => 'johndoe@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $user = User::where('email', 'johndoe@example.com')->first();

    Event::assertDispatched(function (UserRegistered $event) use ($user): bool {
        return $event->user->id === $user->id;
    });
});
