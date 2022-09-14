<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Group extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'group_id', 'semester', 'room', 'period_id', 'day_in_week', 'weeks', 'count_student', 'subject_id',
    ];

    protected $casts = [
        'weeks' => 'array',
    ];

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(Period::class, 'period_id', 'id');
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_group', 'group_id', 'user_id');
    }

}
