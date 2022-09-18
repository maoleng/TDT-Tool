<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Promotion extends Base
{
    use HasFactory;

    protected $fillable = [
        'code', 'status', 'user_id',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getStatusNameAttribute()
    {
        if ($this->status === true) {
            return 'Đã dùng';
        }
        if ($this->status === false) {
            return 'Đã khóa';
        }

        return 'Chưa dùng';
    }
}
