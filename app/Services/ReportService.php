<?php

namespace App\Services;

use App\Models\Report;
use App\Models\ReportIssue;
use App\Models\ReportLine;
use App\Objects\Inventory;
use App\Objects\Issue;
use App\Objects\IssueReport;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ReportService
{
    public const MAC_ADDRESS_PATTERN = '/[\w]{2}:[\w]{2}:[\w]{2}:[\w]{2}:[\w]{2}:[\w]{2}/';

    public function getFileList(): array
    {
        $path = 'public/data/';
        $dir = Storage::disk('local')->files($path);

        return collect($dir)->map(static function ($file) use ($path) {
            return str_replace($path, '', $file);
        })->sort()->toArray();
    }

    public function getReportList(): array
    {
        return Report::query()
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
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
                    return !empty($row[$index]);
                }));
        }

        return $inventory;
    }

    public function getReportByUid(string $uid): ?Collection
    {
        $report = Report::query()
            ->where('uid', '=', $uid)
            ->first();
        if ($report) {
            ReportIssue::query()->where('report_id', '=', $report->id)
                ->each(static function (ReportIssue $issue) {
                    !d($issue);
                });
        }

        die();
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

            // Upload file to public path in storage directory
            $file->move(storage_path('app/public/data'), $uploadFileName);
            $ext = $file->getClientOriginalExtension();
            $this->storeReport($uploadFileName, $ext);
        });
    }

    public function storeReport(string $filename, $extension = 'csv'): void
    {
        $issueReport = $this->getWyebotIssues($filename);
        $issues = $issueReport->getIssues();

        $report = new Report();
        $report->uid = uniqid('', true);
        $report->file_name = str_replace('.' . $extension, '', $filename);
        $report->save();

        $issues->each(static function (Issue $issue) use ($report, $issueReport) {
            if ($issue->uid) {
                $reportIssue = new ReportIssue();
                $reportIssue->report_id = $report->id;
                $reportIssue->severity = $issue->severity;
                $reportIssue->problem = $issue->problem;
                $reportIssue->solution = $issue->solution;
                $reportIssue->uid = $issue->uid;
                $reportIssue->save();

                if ($issueReport->hasAffectedDevices($issue->uid)) {
                    $issueReport->getAffectedDevices($issue->uid)
                        ->each(static function ($line) use ($issue, $report, $reportIssue) {
                            $lineData = is_array($line) ? current($line) : null;
                            if ($lineData) {
                                $reportLine = new ReportLine();
                                $reportLine->report_id = $report->id;
                                $reportLine->report_issue_id = $reportIssue->id;
                                $reportLine->data = $lineData;
                                preg_match_all(self::MAC_ADDRESS_PATTERN, $lineData, $matches);
                                $mac_addresses = $matches ? implode(',', current($matches)) : null;
                                $reportLine->mac_addresses = $mac_addresses;
                                $reportLine->save();
                            }
                        });
                }
            }

        });
    }
}
