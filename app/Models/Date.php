<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Date extends Base
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'date_time', 'week',
    ];
}
