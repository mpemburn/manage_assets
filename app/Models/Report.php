<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class Report extends Model
{
    use HasFactory;

    public $fillable = [
        'uid',
        'file_name'
    ];

    public function getCreatedAtAttribute($value): string
    {
        try {
            return Carbon::parse($value)
                ->timezone(env('TIMEZONE'))
                ->format('n-j-Y g:i A');
        } catch (Exception $e) {
            Log::debug('getCreatedAtAttribute error: ' . $e->getMessage());
        }

        return $value;
    }
}
