<?php

namespace App\Models;

use Illuminate\Support\Collection;

class IssueReport
{
    public const VALID_ISSUE_HEADER = ['Severity', 'Problem', 'Problem Description', 'Solution'];

    public Collection $issues;
    public Collection $affectDevices;


    public function __construct()
    {
        $this->issues = collect();
        $this->affectDevices = collect();
    }

    public function loadIssues(array $issues): void
    {
        $headerSet = false;
        $this->issues = collect($issues)->map(function ($issue) use (&$headerSet) {
            // Set a unique id string to get the devices when the report is generated
            $uid = uniqid('', true);
            $this->setAffectedDevices($issue[2], $uid);
            if ($headerSet) {
                $issue[2] = $uid;
            }

            $headerSet = true;

            return $issue;
        });
    }

    public function hasValidIssueData(): bool
    {
        if ($this->issues->isEmpty()) {
            return false;
        }

        $valid = collect(self::VALID_ISSUE_HEADER);
        $header = collect($this->issues->first());

        return $valid->diff($header)->isEmpty();
    }

    public function getIssues(): Collection
    {
        return $this->issues;
    }

    public function getAllAffectedDevices(): Collection
    {
        return $this->affectDevices;
    }

    public function getAffectedDevices(string $index): Collection
    {
        return $this->affectDevices->has($index)
            ? $this->affectDevices->pull($index)
            : collect();
    }

    public function hasAffectedDevices(string $index): bool
    {
        return $this->affectDevices->contains(function ($value, $key) use ($index) {
            return $key === $index;
        });
    }

    protected function setAffectedDevices(string $deviceString, string $index): void
    {
        $devices = collect(explode("\n", $deviceString))
            ->map(function ($device, $key) {
                preg_match('/[\w]{2}:[\w]{2}:[\w]{2}:[\w]{2}:[\w]{2}:[\w]{2}/', $device, $matches);
                $macAddress = $matches ? $matches[0] : null;
                if ($macAddress) {
                    return [$macAddress => $device];
                }
                return null;
            })->filter();

        if ($devices->isNotEmpty()) {
            $this->affectDevices->put($index, $devices);
        }
    }
}
