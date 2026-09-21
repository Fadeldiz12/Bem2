<?php

namespace App\Http\Controllers\Rab;

use App\Models\ManagementYear;
use App\Models\SiteSetting;
use App\Models\Ministry;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RabIndexController extends Controller
{
    private function baseData(): array
    {
        $year       = ManagementYear::getActive();
        $ministries = $year ? Ministry::where('management_year_id', $year->id)->orderBy('sort_order')->get() : collect();
        $settings   = SiteSetting::allAsArray();
        
        return compact('year', 'ministries', 'settings');
    }

    public function index()
    {
        $data = $this->baseData();
        return view('rab.index', $data);
    }
}