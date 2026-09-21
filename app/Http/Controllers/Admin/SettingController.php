<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index() { return view('admin.settings.index', ['setting_items' => SiteSetting::all()]); }

    public function update(Request $request) {
        foreach ($request->except('_token') as $key => $value) SiteSetting::saveValue($key, $value);
        return redirect()->route('admin.setting.index')->with('success', 'Pengaturan berhasil disimpan.');
    }
}
