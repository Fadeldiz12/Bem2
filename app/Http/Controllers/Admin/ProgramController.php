<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProgramRequest;
use App\Models\{Program, Department, Ministry, ManagementYear};

class ProgramController extends Controller
{
    public function index()
    {
        return view('admin.programs.index', [
            'programs' => Program::with(['department.ministry'])->latest()->get(),
        ]);
    }

    public function create()
    {
        return view('admin.programs.form', [
            'program'     => null,
            'departments' => $this->deptOptions(),
        ]);
    }

    public function store(ProgramRequest $request)
    {
        Program::create($request->validated());
        return redirect()->route('admin.program.index')->with('success', 'Program kerja berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        return view('admin.programs.form', [
            'program'     => Program::findOrFail($id),
            'departments' => $this->deptOptions(),
        ]);
    }

    public function update(ProgramRequest $request, int $id)
    {
        Program::findOrFail($id)->update($request->validated());
        return redirect()->route('admin.program.index')->with('success', 'Berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        Program::findOrFail($id)->delete();
        return redirect()->route('admin.program.index')->with('success', 'Berhasil dihapus.');
    }

    private function deptOptions(): array
    {
        $year = ManagementYear::getActive();
        if (!$year) return [];
        return Department::whereHas('ministry', fn($q) => $q->where('management_year_id', $year->id))
            ->with('ministry')->get()
            ->map(fn($d) => ['id' => $d->id, 'label' => $d->ministry->name . ' — ' . $d->name])
            ->toArray();
    }
}