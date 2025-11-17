<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkoutSession extends Model
{
    protected $table = 'workout_sessions';

    protected $fillable = [
        'user_id',
        'program_id',
        'date',
        'duration',
        'notes',
        'exercises',
    ];

    protected $hidden = [
        'updated_at',
    ];
}
