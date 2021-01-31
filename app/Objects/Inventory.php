<?php

namespace App\Objects;

use Illuminate\Support\Collection;

class Inventory
{
    public Collection $headers;
    public Collection $devices;

    public function setHeaders(array $headers): void
    {
        $this->headers = collect($headers);
    }

    public function setDevices(Collection $devices): void
    {
        $this->devices = $devices;
    }

    public function getHeadersArray(): array
    {
        return $this->headers->toArray();
    }

    public function getHeaderIndex(string $name): int
    {
        return $this->headers->search($name);
    }

    public function findDevice(string $key, string $columnName = 'MAC Address'): Collection
    {
        $index = $this->getHeaderIndex($columnName);

        return $this->devices->filter(static function ($device) use ($key, $index) {
            return $device[$index] === $key;
        });
    }

    public function hasMacAddress(array $macAddresses): bool
    {
        return collect($macAddresses)->contains(function ($mac) {
            return $this->findDevice($mac)->isNotEmpty();
        });
    }

    public function getDeviceString(string $key): string
    {
        $device = $this->findDevice($key);

        if ($device->isNotEmpty()) {
            $deviceArray = current($device->toArray());

            return 'DEVICE: ' . $deviceArray[0] . ' — Location: Building ' . $deviceArray[2] . ' (Type: ' . $deviceArray[3] . ' ' . $deviceArray[4] . ')';
        }
        return '';
    }
}
