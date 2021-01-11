<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function index()
    {
        $report = new ReportService();

        return view('report_files', [
            'files' => $report->getFileList()
        ]);
    }

    public function show(Request $request)
    {
        $filename = $request->get('file');

        $report = new ReportService();

        $issueReport = $report->getWyebotIssues($filename);

        return view('issue_report', [
            'filename' => $filename,
            'issueReport' => $issueReport,
            'issues' => $issueReport->getIssues()->toArray(),
            'inventory' => $report->getInventory()
        ]);

    }

}
