<?php

namespace App\Objects;

use App\Models\ReportIssue;
use App\Models\ReportLine;
use Illuminate\Support\Collection;

class IssueCollection
{
    public const MAC_ADDRESS_PATTERN = '/[\w]{2}:[\w]{2}:[\w]{2}:[\w]{2}:[\w]{2}:[\w]{2}/';

    public Collection $issues;
    public Collection $affectDevices;
    public Collection $reportLines;
    protected string $filename;

    public function __construct()
    {
        $this->issues = collect();
        $this->affectDevices = collect();
        $this->reportLines = collect();
    }

    public function loadIssuesFromCsvData(array $issues): void
    {
        $headerSet = false;
        $this->issues = collect($issues)->map(function ($issueArray) use (&$headerSet) {

            if ($headerSet) {
                $reportIssue = new ReportIssue(ReportIssue::map($issueArray));
                $reportIssue->uid = uniqid('', true);

                $this->setAffectedDevices($reportIssue);
            } else {
                $reportIssue = new ReportIssue(ReportIssue::HEADER_ARRAY);
            }

            $headerSet = true;

            return $reportIssue;
        });
    }

    public function addIssue(ReportIssue $reportIssue): void
    {
        $this->issues->push($reportIssue);
    }

    public function hasValidIssueData(): bool
    {
        if ($this->issues->isEmpty()) {
            return false;
        }

        $valid = collect(ReportIssue::getIssueHeaderValues());
        $header = collect($this->issues->first());

        return $valid->diff($header)->isEmpty();
    }

    public function setFilename(string $fielname): void
    {
        $this->filename = $fielname;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function getIssues(): Collection
    {
        return $this->issues;
    }

    public function getAffectedDevices(string $uid): Collection
    {
        return $this->affectDevices->has($uid)
            ? $this->affectDevices->pull($uid)
            : collect();
    }

    public function hasAffectedDevices(string $uid): bool
    {
        return $this->affectDevices->contains(function ($value, $key) use ($uid) {
            return $key === $uid;
        });
    }

    public function setAffectedDevices(ReportIssue $reportIssue): void
    {
        $devices = collect(explode("\n", $reportIssue->description))
            ->map(function ($device, $key) use ($reportIssue) {
                if ($device) {
                    return [$reportIssue->uid => $device];
                }

                return null;
            })->filter();

        if ($devices->isNotEmpty()) {
            $this->affectDevices->put($reportIssue->uid, $devices);
        }
    }
}
