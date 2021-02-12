<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use App\Services\InventoryService;
use App\Services\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected ReportService $reportService;
    protected AuthService $authService;
    protected InventoryService $inventoryService;

    public function __construct(
        ReportService $reportService,
        AuthService $authService,
        InventoryService $inventoryService)
    {

        $this->reportService = $reportService;
        $this->authService = $authService;
        $this->inventoryService = $inventoryService;
    }

    public function index()
    {
        return view('reports', [
            'reports' => $this->reportService->getReportList(),
            'token' =>  $this->authService->getAuthToken(),
            'action' =>  '/api/receive_files',
        ]);
    }

    public function show(Request $request)
    {
        $fileUid = $request->get('id');

        $issueCollection = $this->reportService->getReportByUid($fileUid);
        if ($issueCollection->hasValidIssueData()) {
            return view('issue_report', [
                'filename' => $issueCollection->getFilename(),
                'issueCollection' => $issueCollection,
                'issues' => $issueCollection->getIssues(),
                'inventory' => $this->inventoryService->getInventoryCollection()
            ]);
        }

        return view('invalid_data');
    }

    public function upload()
    {
        return view('uploader');
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
