<?php

namespace App\Services;

use App\Models\Antivirus;
use App\Models\DeviceModel;
use App\Models\DeviceType;
use App\Models\Inventory;
use App\Models\Manufacturer;
use App\Models\OperatingSystem;
use App\Models\Processor;
use App\Objects\InventoryCollection;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception;

class InventoryService
{
    public const INVENTORY_COLUMNS = [
        'device_type',
        'primary_users',
        'location',
        'manufacturer',
        'device_model',
        'mac_address',
        'serial_number',
        'computer_name',
        'drive_info',
        'ram',
        'processor',
        'monitor_count',
        'operating_system',
        'has_user_profiles',
        'requires_password',
        'has_complex_password',
        'screen_lock_time',
        'is_os_current',
        'antivirus_status',
        'antivirus',
        'is_hd_encrypted',
        'date_purchased',
        'comment',
        'X', // Empty columns that importer brings in
        'Y',
        'Z',
        'AA',
    ];

    public function receiveUploadedInventory(array $uploadArray): void
    {
        collect($uploadArray)->each(function ($file) {
            $uploadFileName = $file->getClientOriginalName();

            // Upload file to public path in storage directory
            $filePath = storage_path('app/private');
            $file->move($filePath, $uploadFileName);
            // Extract file to database
            $this->extractDataFromInventoryFile($filePath . '/' . $uploadFileName);
        });
    }

    public function getInventoryCollection(): InventoryCollection
    {
        $inventoryCollection = new InventoryCollection();

        $devices = Inventory::query()->whereNotNull('mac_address')->get(['*']);

        $inventoryCollection->setDevices($devices);

        return $inventoryCollection;
    }

    public function getInventoryCollectionFromExcel(string $filename): InventoryCollection
    {
        $inventoryCollection = new InventoryCollection();

        try {
            $reader = IOFactory::createReader("Xlsx");
        } catch (Exception $e) {
            return $inventoryCollection;
        }

        if ($reader) {
            $spreadsheet = $reader->load($filename);

            $data = collect($spreadsheet->getActiveSheet()->toArray());
            $inventoryCollection->setHeaders($data->first());
            $data->shift();
            $inventoryCollection->setAssets($data->filter());
            $inventoryCollection->setDevices($data
                ->filter(static function ($row) use ($inventoryCollection) {
                    $index = $inventoryCollection->getHeaderIndex('MAC Address');
                    return !empty($row[$index]);
                }));
        }

        return $inventoryCollection;
    }

    public function extractDataFromInventoryFile(string $filePath): void
    {
        $filePath = $filePath ?: storage_path('/app/private/') . env('BANNER_INVENTORY_XLS');

        $inventoryCollection = $this->getInventoryCollectionFromExcel($filePath);
        $inventoryCollection->assets->each(function ($item) {
            $itemCollection = collect($item);
            $row = collect(self::INVENTORY_COLUMNS)
                ->combine($itemCollection)
                ->forget(['X', 'Y', 'Z', 'AA']); // Remove empty columns
            $row = $row->map(static function ($value, $key) {
                if (! is_int($value) && in_array($key, [
                        'has_user_profiles',
                        'manage_assets',
                        'requires_password',
                        'has_complex_password',
                        'monitor_count',
                        'is_os_current',
                        'is_hd_encrypted',
                    ])) {
                        return '0';
                    }

                if ($value === 'N/A') {
                    return null;
                }
                if ($value === '?') {
                    return 'Unknown';
                }
                return $value;
            });
            $this->extractToModel(DeviceType::class, $row, 'device_type');
            $this->extractToModel(DeviceModel::class, $row, 'device_model');
            $this->extractToModel(Manufacturer::class, $row, 'manufacturer');
            $this->extractToModel(Antivirus::class, $row, 'antivirus');
            $this->extractToModel(OperatingSystem::class, $row, 'operating_system');
            $this->extractToModel(Processor::class, $row, 'processor');
            $inventory = new Inventory($row->toArray());
            $inventory = $this->extractLocation($inventory, $row);
            $inventory->save();
        });
    }

    protected function getUniqueValues(InventoryCollection $inventoryCollection, int $columnIndex, array $filterOut = []): Collection
    {
        return $inventoryCollection->assets
            ->pluck($columnIndex)
            ->diff($filterOut)
            ->filter()
            ->sort()
            ->unique();
    }

    protected function extractLocation(Inventory $inventory, Collection $row): Inventory
    {
        $pattern = '/([\w])( \- )(.*)( \- )(.*)/';

        $inventory->building = preg_replace($pattern, '$1', $row['location']);
        $inventory->floor = preg_replace($pattern, '$3', $row['location']);
        $inventory->room = preg_replace($pattern, '$5', $row['location']);

        return $inventory;
    }

    protected function extractToModel(string $modelClass, Collection $row, string $columname): void
    {
        $model = new $modelClass();
        $value = $row->get($columname);
        if ($value) {
            $model->firstOrCreate(['name' => $value]);
        }
    }
}
