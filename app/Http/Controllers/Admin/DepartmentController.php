<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DepartmentRequest;
use App\Models\{Department, Ministry, ManagementYear};

class DepartmentController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $yearId = $request->get('year_id', optional(ManagementYear::getActive())->id);

        $departments = Department::with('ministry')
            ->whereHas('ministry', fn($q) => $q->where('management_year_id', $yearId))
            ->orderBy(
                Ministry::select('sort_order')
                    ->whereColumn('ministries.id', 'departments.ministry_id')
                    ->limit(1)
            )
            ->orderBy('sort_order')
            ->get();

        return view('admin.departments.index', [
            'departments'   => $departments,
            'years'         => ManagementYear::latest()->get(),
            'selected_year' => $yearId,
        ]);
    }

    public function create()
    {
        $year = ManagementYear::getActive();
        return view('admin.departments.form', [
            'department'   => null,
            'years'        => ManagementYear::latest()->get(),
            'activeYearId' => $year?->id,
            'ministries'   => $year ? Ministry::where('management_year_id', $year->id)->orderBy('sort_order')->get() : collect(),
        ]);
    }

    public function store(DepartmentRequest $request)
    {
        Department::create($request->validated());
        return redirect()->route('admin.departemen.index')->with('success', 'Departemen berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $dept = Department::with('ministry')->findOrFail($id);
        return view('admin.departments.form', [
            'department'   => $dept,
            'years'        => ManagementYear::latest()->get(),
            'activeYearId' => $dept->ministry?->management_year_id,
            'ministries'   => Ministry::where('management_year_id', $dept->ministry?->management_year_id)->orderBy('sort_order')->get(),
        ]);
    }

    public function update(DepartmentRequest $request, int $id)
    {
        Department::findOrFail($id)->update($request->validated());
        return redirect()->route('admin.departemen.index')->with('success', 'Berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        Department::findOrFail($id)->delete();
        return redirect()->route('admin.departemen.index')->with('success', 'Berhasil dihapus.');
    }
}