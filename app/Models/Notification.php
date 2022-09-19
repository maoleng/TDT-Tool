<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Notification extends Model
{
    use HasFactory;
    public $timestamps = false;

    public const NOTIFICATION_AT_LEAST_TO_READ = 50;
    public const MAX_NOTIFICATION_PAGE = 70;

    protected $fillable = [
        'notification_id', 'title', 'content', 'department', 'is_notify', 'created_at',
    ];

    protected $casts = [
        'is_notify' => 'boolean',
    ];

    public function files(): HasMany
    {
        return $this->hasMany(File::class, 'notification_id', 'id');
    }
}
