<?php

namespace App\Http\Controllers;

use App\Services\ReportService;

class ReportController extends Controller
{
    public function show()
    {
        $report = new ReportService();

        $issueReport = $report->getWyebotIssues('Issue Detail - The Banner School - AK05000068 - 19700101-0000.csv');
        $inventory = $report->getInventory();
//        foreach ($issueReport->getAffectedDevices(2)->toArray() as $key => $device) {
//            !d($inventory->getDeviceString(key($device)));
//        }

        return view('issue_report', [
            'issueReport' => $issueReport,
            'issues' => $issueReport->getIssues()->toArray(),
            'inventory' => $report->getInventory()
        ]);

    }

}
