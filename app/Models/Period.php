<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Period extends Base
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'period', 'started_ed', 'ended_at',
    ];

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_period', 'period_id', 'group_id');
    }
}
