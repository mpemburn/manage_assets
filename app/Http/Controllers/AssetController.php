<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function show()
    {
        $planFile = storage_path('app/public/plans/') . 'plan.svg';
        if (file_exists($planFile)) {
            $floorplan = 'data:image/svg+xml;base64,' . base64_encode(file_get_contents($planFile));
            return view('canvas', [
                'plan' => $floorplan
            ]);

        }
    }
}
