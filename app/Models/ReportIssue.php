<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportIssue extends Model
{
    use HasFactory;

    public $fillable = [
        'report_id',
        'severity',
        'problem',
        'solution',
        'uid',
    ];
}
