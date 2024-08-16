<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Spatie\Activitylog\Models\Activity;

class LogController extends Controller
{
    public function index(): View
    {
        $logs = Activity::query()->latest()->get();

        return view('logs.activity-log', compact('logs'));
    }
}
