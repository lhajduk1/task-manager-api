<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)
            ->has(
                Project::factory()
                    ->count(1)
                    ->has(
                        Task::factory()->count(3)
                            ->has(
                                Comment::factory()
                                    ->state(function (array $attributes, Task $task) {
                                        return [
                                            'user_id' => $task->project->user_id,
                                        ];
                                    })
                                    ->count(10)
                            )
                    )
            )
            ->create();
    }
}
