<?php

namespace App\Services;

use App\Objects\Inventory;
use App\Objects\IssueReport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ReportService
{
    public function getFileList(): array
    {
        $path = 'public/data/';
        $dir = Storage::disk('local')->files($path);

        return collect($dir)->map(static function ($file) use ($path) {
            return str_replace($path, '', $file);
        })->sort()->toArray();
    }

    public function getInventory(): Inventory
    {
        $inventory = new Inventory();

        try {
            $reader = IOFactory::createReader("Xlsx");
        } catch (Exception $e) {
            return $inventory;
        }

        if ($reader) {
            $spreadsheet = $reader->load(storage_path('/app/private/') . env('BANNER_INVENTORY_XLS'));

            $data = $spreadsheet->getActiveSheet()->toArray();
            $inventory->setHeaders(collect($data)->first());
            $inventory->setDevices(collect($data)
                ->filter(static function ($row) use ($inventory) {
                    $index = $inventory->getHeaderIndex('MAC Address');
                    return ! empty($row[$index]);
                }));
        }

        return $inventory;
    }

    public function getWyebotIssues(string $csvFile): IssueReport
    {
        $issueReport = new IssueReport();

        $reader = new Csv();
        $reader->setInputEncoding('CP1252');
        $reader->setDelimiter(',');
        $reader->setEnclosure('');
        $reader->setSheetIndex(0);

        if ($reader) {
            $spreadsheet = null;
            try {
                $spreadsheet = $reader->load(storage_path('app/public/data/') . $csvFile);
                $data = $spreadsheet->getActiveSheet()->toArray();
                $issueReport->loadIssues($data);
            } catch (Exception $e) {
                return $issueReport;
            }
        }

        return $issueReport;
    }

    public function receiveUploadedReports($uploadArray): void
    {
        collect($uploadArray)->each(function ($file) {
            $uploadFileName = $file->getClientOriginalName();
            $ext = $file->getClientOriginalExtension();
            $timeNow = Carbon::now()->format('Y-m-d-H-i-s');

            $uploadFileName = str_replace('.' . $ext, '-' . $timeNow . '.' . $ext, $uploadFileName);
            // Upload file to public path in storage directory
            $file->move(storage_path('app/public/data'), $uploadFileName);
        });
    }

    public function storeReport(string $filename): void
    {
        $issueReport = $this->getWyebotIssues($filename);
        $issues = $issueReport->getIssues();
    }
}
