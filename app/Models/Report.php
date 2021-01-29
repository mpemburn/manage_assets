<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Report extends Model
{
    use HasFactory;

    public function getCreatedAtAttribute($value): string
    {
        return Carbon::parse($value)->format('n-j-Y g:i');
    }
}
