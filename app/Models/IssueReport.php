<?php

namespace App\Models;

use Illuminate\Support\Collection;

class IssueReport
{
    public Collection $issues;
    public Collection $affectDevices;

    public function __construct()
    {
        $this->affectDevices = collect();
    }

    public function loadIssues(array $issues): void
    {
        $index = 0;
        $this->issues = collect($issues)->map(function ($issue) use (&$index) {
            $this->setAffectedDevices($issue[2], $index);
            $issue[2] = $index;
            $index++;

            return $issue;
        });
    }

    public function getIssues(): Collection
    {
        return $this->issues;
    }

    public function getAllAffectedDevices(): Collection
    {
        return $this->affectDevices;
    }

    public function getAffectedDevices(int $index): Collection
    {
        return $this->affectDevices->pull($index);
    }

    public function hasAffectedDevices(int $index): bool
    {
        return $this->affectDevices->contains(function ($value, $key) use ($index) {
            return $key === $index;
        });
    }

    protected function setAffectedDevices(string $deviceString, int $index): void
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
