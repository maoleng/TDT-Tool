<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Session extends Base
{
    use HasFactory;

    protected $fillable = [
        'active', 'raw_html', 'user_id'
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function groups(): HasMany
    {
        return $this->hasMany(Group::class, 'session_id', 'id');
    }

}
