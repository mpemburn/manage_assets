<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportLine extends Model
{
    use HasFactory;

    public $fillable = [
        'report_id',
        'uid',
        'data',
        'mac_addresses',
    ];
}
