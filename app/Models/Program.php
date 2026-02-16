<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $fillable = ['name', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function activeStudents()
    {
        return $this->students()->whereHas('year', fn($q) => $q->where('is_active', true));
    }
}
