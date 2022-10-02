<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class User extends Base
{
    use HasFactory;

    public const MASTER_ID = 'master-user-id';
    public const TIME_VERIFY = 180; //minutes
    public const MAX_SYSTEM_MAIL_PER_DAY = 3;

    protected $fillable = [
        'name', 'email', 'tdt_password', 'avatar', 'role', 'active', 'is_read_notification_today', 'google_id',
    ];

    protected $casts = [
        'active' => 'boolean',
        'is_notify_score' => 'boolean',
        'is_read_notification_today' => 'boolean',
    ];

    public function devices(): HasMany
    {
        return $this->hasMany(Device::class, 'user_id', 'id');
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class, 'user_id', 'id')
            ->where('active', true)->limit(1);
    }

    public function promotions(): HasMany
    {
        return $this->hasMany(Promotion::class, 'user_id', 'id');
    }

    public function subscribedDepartments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'user_department', 'user_id', 'department_id');
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
            case 1:
                return 'Người dùng thường';
            case 2:
                return 'Người dùng VIP';
            case 3:
                return 'Quản lý';
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
            Promotion::query()->create([
                'code' => Str::random(25),
                'user_id' => $model->id,
            ]);
        });

    }
}
