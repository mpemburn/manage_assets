<?php

namespace App\Http\Controllers;

use App\Models\ReportLine;
use App\Services\AuthService;
use App\Services\InventoryService;
use App\Services\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $report = new ReportService();
        $auth = new AuthService();

        return view('reports', [
            'reports' => $report->getReportList(),
            'token' =>  $auth->getAuthToken()
        ]);
    }

    public function show(Request $request)
    {
        $fileUid = $request->get('id');

        $report = new ReportService();
        $inventory = new InventoryService();

        $issueCollection = $report->getReportByUid($fileUid);
        if ($issueCollection->hasValidIssueData()) {
            return view('issue_report', [
                'filename' => $issueCollection->getFilename(),
                'issueCollection' => $issueCollection,
                'issues' => $issueCollection->getIssues(),
                'inventory' => $inventory->getInventoryCollectionFromExcel()
            ]);
        }

        return view('invalid_data');
    }

    public function showFile(Request $request)
    {
        $filename = $request->get('file');

        $report = new ReportService();

        $issueReport = $report->getWyebotIssues($filename);

        if ($issueReport->hasValidIssueData()) {
            return view('issue_report', [
                'filename' => $filename,
                'issueReport' => $issueReport,
                'issues' => $issueReport->getIssues()->toArray(),
                'inventory' => $report->getInventory()
            ]);
        }


        return view('invalid_data');
    }

    public function upload()
    {
        return view('upload');
    }

    public function receive(Request $request): void
    {
        $report = new ReportService();
        $report->receiveUploadedReports($request->uploads);
    }

    public function storeReport(Request $request): void
    {
        $filename = $request->get('file');
        $report = new ReportService();
        $report->storeReport($filename);
    }
}
