<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Date extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'date_time', 'week',
    ];
}
