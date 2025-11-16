<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    protected $table = 'exercises';

    protected $fillable = [
        'name',
        'description',
        'muscle_group_id',
    ];

    protected $hidden = [
        'updated_at',
    ];

    public function muscleGroup()
    {
        return $this->belongsTo(MuscleGroup::class);
    }
}
