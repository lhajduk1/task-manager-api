<?php

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user can see only his tasks', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    Task::factory()->for($user)->count(3)->create();
    Task::factory()->for($otherUser)->count(2)->create();

    $response = $this->actingAs($user)
        ->getJson(route('tasks.index'));

    $response->assertOk()
        ->assertJsonCount(3, 'data');
});

test('guest cannot see tasks', function () {
    $response = $this->getJson(route('tasks.index'));

    $response->assertStatus(401)
        ->assertExactJson([
            'message' => 'Unauthenticated.'
        ]);
});

test('guest cannot create tasks', function () {
    $response = $this->postJson(route('tasks.store'), [
        'title' => 'Example',
        'description' => 'Description'
    ]);

    $response->assertStatus(401)
        ->assertExactJson([
            'message' => 'Unauthenticated.'
        ]);
});
