<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'status', 'task_date'];

    protected function casts(): array
    {
        return ['task_date' => 'date'];
    }

    public function scopeForDate(Builder $query, CarbonInterface|string $date): Builder
    {
        $dateString = $date instanceof CarbonInterface ? $date->toDateString() : $date;

        return $query->whereDate('task_date', $dateString);
    }
}
