<?php

namespace App\Objects;

use Illuminate\Support\Collection;

class IssueReport
{
    public const VALID_ISSUE_HEADER = ['Severity', 'Problem', 'Problem Description', 'Solution'];
    public const MAC_ADDRESS_PATTERN = '/[\w]{2}:[\w]{2}:[\w]{2}:[\w]{2}:[\w]{2}:[\w]{2}/';

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
        $this->issues = collect($issues)->map(function ($issueArray) use (&$headerSet) {
            if ($headerSet) {
                $issue = new Issue($issueArray);
                $this->setAffectedDevices($issue->description, $issue->uid);
            } else {
                $issue = new Issue($issueArray, true);
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

    protected function setAffectedDevices(string $deviceString, string $uid): void
    {
        $devices = collect(explode("\n", $deviceString))
            ->map(function ($device, $key) use ($uid) {
                preg_match(self::MAC_ADDRESS_PATTERN, $device, $matches);
                $macAddress = $matches ? $matches[0] : null;
                if ($macAddress) {
                    return [$macAddress => $device];
                }

                if ($device) {
                    return [$uid => $device];
                }

                return null;
            })->filter();

        if ($devices->isNotEmpty()) {
            $this->affectDevices->put($uid, $devices);
        }
    }
}
