<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Notification extends Base
{
    use HasFactory;
    public $timestamps = false;

    public const NOTIFICATION_AT_LEAST_TO_READ = 50;
    public const MAX_NOTIFICATION_PAGE = 70;
    public const LIMIT_REQUEST_NOTIFICATION_IF_404 = 3;
    public const START_NOTIFICATION_ID = '141054';
    public const CRON_NOTIFICATION_TIME = 30; // minutes

    protected $fillable = [
        'notification_id', 'title', 'content', 'department_id', 'created_at',
    ];

    public function files(): HasMany
    {
        return $this->hasMany(File::class, 'notification_id', 'id');
    }

    public function receivers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'notification_users', 'notification_id', 'user_id')
            ->withPivot('status');
    }

    public function getCountReceiversAttribute()
    {
        return $this->where('id', $this->id)->withCount('receivers')->first()->receivers_count;
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function getShortTitleAttribute(): string
    {
        return strlen($this->title) > 50 ? mb_substr($this->title, 0, 40)."..." : $this->title;
    }

    public function getLinkContentAttribute(): string
    {
        return 'Xem ở '.substr(TDT::NEW_DETAIL_URL.'/'.$this->notification_id, 8);
    }

    public function getLinkAttribute(): string
    {
        return TDT::NEW_DETAIL_URL.'/'.$this->notification_id;
    }

    public function getCreatedDiffAttribute(): string
    {
        Carbon::setLocale('vi');

        return Carbon::make($this->created_at)->diffForHumans();
    }

    public function getCreatedAtAttribute($date): string
    {
        return Carbon::make($date)->format('d-m-Y H:i:s');
    }
}
