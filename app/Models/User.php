<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Base
{
    use HasFactory;

    public const TIME_VERIFY = 180; //minutes
    public const MAX_SYSTEM_MAIL_PER_DAY = 3;

    protected $fillable = [
        'name', 'email', 'avatar', 'role',
        'active', 'count_system_mail_daily', 'google_id',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function devices(): HasMany
    {
        return $this->hasMany(Device::class, 'user_id', 'id');
    }
    protected static function boot(): void
    {
        parent::boot();

        self::created(static function ($model) {
            Setting::query()->create([
                'key' => 'theme',
                'value' => 'light',
                'user_id' => $model->id,
            ]);
        });

    }
}
