<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReportIssue extends Model
{
    use HasFactory;

    public const HEADER_ARRAY = [
        'severity' => 'Severity',
        'problem' => 'Problem',
        'description' => 'Problem Description',
        'solution' => 'Solution'
    ];

    public $fillable = [
        'report_id',
        'severity',
        'problem',
        'description',
        'solution',
        'uid',
    ];

    public static function map(array $values): array
    {
        return array_combine(
            array_keys(self::HEADER_ARRAY),
            $values
        );
    }

    public static function getIssueHeaderValues(): array
    {
        return array_values(self::HEADER_ARRAY);
    }

    public function reportLines(): HasMany
    {
        return $this->hasMany(ReportLine::class, 'report_issue_id');
    }
}
