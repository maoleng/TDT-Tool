<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Semester extends Base
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'semester', 'start_year', 'end_year',
    ];

    protected $casts = [
        'semester' => 'integer',
        'start_year' => 'integer',
        'end_year' => 'integer',
    ];

    public function groups(): HasMany
    {
        return $this->hasMany(Group::class, 'semester_id', 'id');
    }

    public function dates(): HasMany
    {
        return $this->hasMany(Date::class, 'semester_id', 'id');
    }

}
