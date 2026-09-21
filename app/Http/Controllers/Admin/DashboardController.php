<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{ManagementYear, Ministry, Department, Member, Program, Activity};

class DashboardController extends Controller
{
    public function index() {
        $year = ManagementYear::getActive();
        return view('admin.dashboard', [
            'active_year'       => $year,
            'total_cabinets'    => ManagementYear::count(),
            'total_ministries'  => $year ? Ministry::where('management_year_id',$year->id)->count() : 0,
            'total_departments' => $year ? Department::whereHas('ministry', fn($q) => $q->where('management_year_id',$year->id))->count() : 0,
            'total_members'     => $year ? Member::where('management_year_id',$year->id)->count() : 0,
            'total_programs'    => $year ? Program::whereHas('department.ministry', fn($q) => $q->where('management_year_id',$year->id))->count() : 0,
            'total_bookings'    => Activity::count(),
            'upcoming'          => Activity::where('status','disetujui')->where('activity_date','>=',now())->orderBy('activity_date')->take(5)->get(),
            'pending_bookings'  => Activity::where('status','menunggu')->orderBy('activity_date')->take(5)->get(),
        ]);
    }
}
