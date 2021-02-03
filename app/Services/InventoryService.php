<?php

namespace App\Services;

use App\Filters\ExcelColumnFilter;
use App\Models\Antivirus;
use App\Models\DeviceModel;
use App\Models\DeviceType;
use App\Models\Inventory;
use App\Models\Manufacturer;
use App\Models\OperatingSystem;
use App\Models\Processor;
use App\Objects\InventoryCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
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
    ];
    public const MODEL_MAP = [
        DeviceType::class => 'device_type',
        DeviceModel::class => 'device_model',
        Manufacturer::class => 'manufacturer',
        Antivirus::class => 'antivirus',
        OperatingSystem::class => 'operating_system',
        Processor::class => 'processor',
    ];
    public const DEFAULT_ZERO_COLUMNS = [
        'has_user_profiles',
        'manage_assets',
        'requires_password',
        'has_complex_password',
        'monitor_count',
        'is_os_current',
        'is_hd_encrypted',
    ];

    public function receiveUploadedInventory(array $uploadArray): void
    {
        collect($uploadArray)->each(function ($file) {
            $uploadFileName = $file->getClientOriginalName();

            // Upload file to public path in storage directory
            $filePath = storage_path('app/private');
            $file->move($filePath, $uploadFileName);
            // Extract file to database
            $this->extractDataFromInventoryFile($filePath . '/' . $uploadFileName, true);
        });
    }

    public function getInventoryCollection(): InventoryCollection
    {
        $inventoryCollection = new InventoryCollection();

        // Get just those devices with a defined MAC address
        $devices = Inventory::query()
            ->whereNotNull('mac_address')
            ->get();

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
            // Get the letter of the last column we want
            $lastColumn = chr(count(self::INVENTORY_COLUMNS) + 64);
            // Set the filter to get the correct range of columns
            $reader->setReadFilter(new ExcelColumnFilter('A', $lastColumn));
            $spreadsheet = $reader->load($filename);

            $data = collect($spreadsheet->getActiveSheet()->toArray());
            $inventoryCollection->setHeaders($data->first());
            // Drop the header row and grab the remaining rows
            $data->shift();
            $inventoryCollection->setAssets($data->filter());
        }

        return $inventoryCollection;
    }

    public function extractDataFromInventoryFile(string $filePath, bool $truncateTable = false): void
    {
        $filePath = $filePath ?: storage_path('/app/private/') . env('BANNER_INVENTORY_XLS');

        if ($truncateTable) {
            Inventory::query()->truncate();
        }

        $inventoryCollection = $this->getInventoryCollectionFromExcel($filePath);
        // Create the rows for the inventory table as well as sub-tables
        $inventoryCollection->assets->each(function ($item) {
            $itemCollection = collect($item);
            $row = collect(self::INVENTORY_COLUMNS)
                ->combine($itemCollection);

            $row = $row->map(static function ($value, $key) {
                if (!is_int($value) && in_array($key, self::DEFAULT_ZERO_COLUMNS, true)) {
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
            // Create unique rows in all sub-tables
            $this->extractToModels($row);

            $inventory = new Inventory($row->toArray());
            // Break the location column into parts
            $inventory = $this->extractLocation($inventory, $row);
            $inventory->save();
        });
    }

    protected function extractLocation(Inventory $inventory, Collection $row): Inventory
    {
        $pattern = '/([\w]{1})( \- )(.*)( \- )(.*)/';
        // Break contents of cell similar to "B - First Floor - Classroom (101)" into constituent parts.
        $inventory->building = preg_replace($pattern, '$1', $row['location']);
        $inventory->floor = preg_replace($pattern, '$3', $row['location']);
        $inventory->room = preg_replace($pattern, '$5', $row['location']);

        return $inventory;
    }

    protected function extractToModels(Collection $row): void
    {
        collect(self::MODEL_MAP)->each(static function ($columname, $modelClass) use ($row) {
            $model = new $modelClass();
            $value = $row->get($columname);
            if ($value) {
                $model->firstOrCreate(['name' => $value]);
            }
        });
    }
}
