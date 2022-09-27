<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Date extends Base
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'date', 'week', 'semester_id',
    ];

    protected $casts = [
        'date'  => 'date:Y-m-d',
    ];

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class, 'semester_id', 'id');
    }
}
