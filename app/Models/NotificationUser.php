<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NotificationUser extends Base
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'notification_id', 'user_id', 'status',
    ];

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'notification_id', 'id');
    }
}
