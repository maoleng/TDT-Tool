<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Models\Activity as OriginalActivity;

class Activity extends OriginalActivity
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'group', 'key', 'value',
    ];

    public function getCreatedAtAttribute($date): string
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d-m-Y H:i:s');
    }

}
