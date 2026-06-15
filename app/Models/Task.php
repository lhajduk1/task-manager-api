<?php

namespace App\Models;

use App\Enums\TaskStatusEnum;
use App\Traits\FilterScopes;
use Database\Factories\TaskFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[UseFactory(TaskFactory::class)]
class Task extends Model
{
    use HasFactory, SoftDeletes, FilterScopes;

    protected $fillable = [
        'title',
        'description',
        'status',
        'due_date'
    ];

    protected $attributes = [
        'status' => TaskStatusEnum::TODO,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'due_date' => 'datetime',
            'status' => TaskStatusEnum::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
