<?php

namespace App\Objects;

use App\Models\Inventory;
use Illuminate\Support\Collection;

class InventoryCollection
{
    public Collection $headers;
    public Collection $assets;
    public Collection $devices;

    public function __construct()
    {
        $this->headers = collect();
        $this->assets = collect();
        $this->devices = collect();
    }

    public function setHeaders(array $headers): void
    {
        $this->headers = collect($headers);
    }

    public function setAssets(Collection $assets): void
    {
        // Remove empty rows before setting
        $this->assets = $assets->filter(function ($value) {
            return collect($value)
                ->filter()
                ->isNotEmpty();
        });
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

    public function findDevice(string $key, string $columnName = 'mac_address'): Collection
    {
        return $this->devices->filter(static function (Inventory $device) use ($key, $columnName) {
            return $device->mac_address === $key;
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
        $inventoryItem = $this->findDevice($key);

        if ($inventoryItem->isNotEmpty()) {
            $device = $inventoryItem->first();
            $location = $device->building . ' - ' . $device->floor . ' - ';
            $location .= ($device->room) ?: '';
            
            return 'DEVICE: '
                . $device->device_type
                . ' — Location: Building ' . $location
                . ' (Type: ' . $device->manufacturer . ' ' . $device->device_model . ')';
        }

        return '';
    }
}
