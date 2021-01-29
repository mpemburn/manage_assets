<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportLine extends Model
{
    use HasFactory;

    public $fillable = [
        'report_id',
        'uid',
        'data',
        'mac_addresses',
    ];

    public function reportIssues(): BelongsTo
    {
        return $this->belongsTo('ReportIssue');
    }
}
