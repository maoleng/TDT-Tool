<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Base
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'group_id', 'semester_id', 'subject_id', 'session_id'
    ];

    protected $casts = [
        'weeks' => 'array',
    ];

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    public function periods(): BelongsToMany
    {
        return $this->belongsToMany(Period::class, 'group_period', 'group_id', 'period_id');
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class, 'session_id', 'id');
    }

    public function semesters(): BelongsTo
    {
        return $this->belongsTo(Semester::class, 'semester_id', 'id');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'group_id', 'id');
    }

}
