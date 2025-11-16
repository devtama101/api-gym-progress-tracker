<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MuscleGroup extends Model
{
    protected $table = 'muscle_groups';

    protected $fillable = [
        'name',
        'description',
    ];

    protected $hidden = [
        'updated_at',
    ];

    public function exercises()
    {
        return $this->hasMany(Exercise::class);
    }
}
