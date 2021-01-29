<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function reportLines(): HasMany
    {
        return $this->hasMany(ReportLine::class, 'issue_id');
    }
}
