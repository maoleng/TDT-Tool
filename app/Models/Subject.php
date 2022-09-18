<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Base
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'subject_id', 'name',
    ];

    public function groups(): HasMany
    {
        return $this->hasMany(Group::class, 'subject_id', 'id');
    }
}
