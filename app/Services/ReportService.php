<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\IssueReport;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ReportService
{
    public function getInventory(): Inventory
    {
        $inventory = new Inventory();

        try {
            $reader = IOFactory::createReader("Xlsx");
        } catch (Exception $e) {
            return $inventory;
        }

        if ($reader) {
            $spreadsheet = $reader->load(storage_path() . '/data/' . env('BANNER_INVENTORY_XLS'));

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
            $spreadsheet = $reader->load(storage_path('data') . '/' . $csvFile);
            $data = $spreadsheet->getActiveSheet()->toArray();
            $issueReport->loadIssues($data);
        }


        return $issueReport;
    }
}
