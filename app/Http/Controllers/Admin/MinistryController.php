<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MinistryRequest;
use App\Helpers\ImageHelper;
use App\Models\{Ministry, ManagementYear};

class MinistryController extends Controller
{
    public function index()
    {
        $yearId = request('year_id', optional(ManagementYear::getActive())->id);
        return view('admin.ministries.index', [
            'ministries'    => Ministry::where('management_year_id', $yearId)->orderBy('sort_order')->get(),
            'years'         => ManagementYear::latest()->get(),
            'selected_year' => $yearId,
        ]);
    }

    public function create()
    {
        return view('admin.ministries.form', ['ministry' => null, 'years' => ManagementYear::latest()->get()]);
    }

    public function store(MinistryRequest $request)
    {
        $data = $request->safe()->except(['logo_path']);
        if ($request->hasFile('logo_path')) {
            // Logo kementerian disimpan langsung tanpa compress — menjaga kualitas
            $data['logo_path'] = $request->file('logo_path')->store('uploads/logos', 'public');
        }
        Ministry::create($data);
        return redirect()->route('admin.kementerian.index')->with('success', 'Kementerian berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        return view('admin.ministries.form', [
            'ministry' => Ministry::findOrFail($id),
            'years'    => ManagementYear::latest()->get(),
        ]);
    }

    public function update(MinistryRequest $request, int $id)
    {
        $ministry = Ministry::findOrFail($id);
        $data     = $request->safe()->except(['logo_path']);
        if ($request->hasFile('logo_path')) {
            $data['logo_path'] = $request->file('logo_path')->store('uploads/logos', 'public');
        }
        $ministry->update($data);
        return redirect()->route('admin.kementerian.index')->with('success', 'Berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        Ministry::findOrFail($id)->delete();
        return redirect()->route('admin.kementerian.index')->with('success', 'Berhasil dihapus.');
    }
}