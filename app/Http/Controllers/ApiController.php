<?php
namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function activities(Request $request)
    {
        $year  = $request->get('year',  date('Y'));
        $month = $request->get('month', date('m'));
        $activities = Activity::whereYear('activity_date', $year)
            ->whereMonth('activity_date', $month)
            ->orderBy('activity_date')
            ->get()
            ->map(fn($a) => array_merge($a->toArray(), [
                'status_label' => Activity::statusLabel($a->status),
                'status_color' => Activity::statusColor($a->status),
            ]));
        return response()->json(['status' => 'ok', 'data' => $activities]);
    }
}
