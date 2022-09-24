<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Notification extends Base
{
    use HasFactory;
    public $timestamps = false;

    public const NOTIFICATION_AT_LEAST_TO_READ = 50;
    public const MAX_NOTIFICATION_PAGE = 70;
    public const LIMIT_REQUEST_NOTIFICATION_IF_404 = 3;
    public const START_NOTIFICATION_ID = '139270';
    public const CRON_NOTIFICATION_TIME = 30; // minutes

    protected $fillable = [
        'notification_id', 'title', 'department_id', 'created_at',
    ];

    public function files(): HasMany
    {
        return $this->hasMany(File::class, 'notification_id', 'id');
    }
}
