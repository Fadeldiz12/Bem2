<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ActivityRequest;
use App\Models\Activity;

class ActivityController extends Controller
{
    public function index()
    {
        return view('admin.activities.index', ['activities' => Activity::latest('activity_date')->get()]);
    }

    public function create()
    {
        return view('admin.activities.form', ['activity' => null]);
    }

    public function store(ActivityRequest $request)
    {
        $conflict = Activity::hasConflict(
            $request->location,
            $request->activity_date,
            $request->start_time,
            $request->end_time
        );
        if ($conflict) {
            return back()->withInput()->with('error',
                "Bentrok jadwal! \"{$request->location}\" pada {$request->activity_date} sudah dipakai untuk kegiatan \"{$conflict->title}\" ({$conflict->start_time} - {$conflict->end_time})."
            );
        }

        Activity::create(array_merge(
            $request->validated(),
            ['created_by' => session('admin_id')]
        ));
        return redirect()->route('admin.kegiatan.index')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        return view('admin.activities.form', ['activity' => Activity::findOrFail($id)]);
    }

    public function update(ActivityRequest $request, int $id)
    {
        $conflict = Activity::hasConflict(
            $request->location,
            $request->activity_date,
            $request->start_time,
            $request->end_time,
            $id
        );
        if ($conflict) {
            return back()->withInput()->with('error',
                "Bentrok jadwal! \"{$request->location}\" pada {$request->activity_date} sudah dipakai untuk kegiatan \"{$conflict->title}\" ({$conflict->start_time} - {$conflict->end_time})."
            );
        }

        Activity::findOrFail($id)->update($request->validated());
        return redirect()->route('admin.kegiatan.index')->with('success', 'Berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        Activity::findOrFail($id)->delete();
        return redirect()->route('admin.kegiatan.index')->with('success', 'Berhasil dihapus.');
    }

    public function approve(int $id)
    {
        Activity::findOrFail($id)->update(['status' => 'disetujui']);
        return redirect()->route('admin.kegiatan.index')->with('success', 'Jadwal disetujui.');
    }

    public function reject(int $id)
    {
        Activity::findOrFail($id)->update(['status' => 'ditolak']);
        return redirect()->route('admin.kegiatan.index')->with('success', 'Jadwal ditolak.');
    }
}
