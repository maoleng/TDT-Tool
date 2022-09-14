<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Period extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'period', 'started_ed', 'ended_at',
    ];

    public function groups(): HasMany
    {
        return $this->hasMany(Group::class, 'period_id', 'id');
    }
}
