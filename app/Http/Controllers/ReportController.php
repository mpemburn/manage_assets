<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\FileBag;

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

    public function upload()
    {
        return view('upload');
    }

    public function receive(Request $request): void
    {
        if ($request->hasFile('uploads')) {
            foreach ($request->uploads as $file) {
                $uploadFileName = $file->getClientOriginalName();
                // Upload file to public path in storage directory
                $file->move(storage_path('app/public/data'), $uploadFileName);
            }
        }
//        collect($request->allFiles())->each(static function ($file) {
//        });
    }
}
