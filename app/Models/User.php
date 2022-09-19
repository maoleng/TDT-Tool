<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class User extends Base
{
    use HasFactory;

    public const TIME_VERIFY = 180; //minutes
    public const MAX_SYSTEM_MAIL_PER_DAY = 3;

    protected $fillable = [
        'name', 'email', 'tdt_password', 'avatar', 'role', 'active', 'is_read_notification_today', 'google_id',
        'is_notify_score', 'notify_notification', 'is_auto_read_notification',
    ];

    protected $casts = [
        'active' => 'boolean',
        'is_notify_score' => 'boolean',
        'is_auto_read_notification' => 'boolean',
        'is_read_notification_today' => 'boolean',
    ];

    public function devices(): HasMany
    {
        return $this->hasMany(Device::class, 'user_id', 'id');
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'user_group', 'user_id', 'group_id');
    }

    public function promotions(): HasMany
    {
        return $this->hasMany(Promotion::class, 'user_id', 'id');
    }

    public function getStudentIdAttribute(): string
    {
        return explode('@', $this->email)[0];
    }

    public function getActiveNameAttribute(): string
    {
        return $this->active === false ? 'Khóa' : 'Kích hoạt';
    }

    public function getRoleNameAttribute(): string
    {
        switch($this->role) {
            case 0:
                return 'Quản lý';
            case 1:
                return 'Người dùng thường';
            case 2:
                return 'Người dùng VIP';
        }
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
            if($model->email === '521h0504@student.tdtu.edu.vn') {
                $model->id = 'master-user-id';
                $model->role = 0;
                $model->save();
                Promotion::query()->insert([
                    [
                        'id' => 'id-code-1',
                        'code' => 'ma-code-1',
                        'status' => 1,
                        'user_id' => $model->id,
                    ],
                    [
                        'id' => 'id-code-2',
                        'code' => 'ma-code-2',
                        'status' => 0,
                        'user_id' => $model->id,
                    ],
                    [
                        'id' => 'id-code-3',
                        'code' => 'ma-code-3',
                        'status' => null,
                        'user_id' => $model->id,
                    ],
                ]);
            }
            Promotion::query()->create([
                'code' => Str::random(25),
                'user_id' => $model->id,
            ]);
        });

    }
}
