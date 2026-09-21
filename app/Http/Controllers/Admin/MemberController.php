<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MemberRequest;
use App\Helpers\ImageHelper;
use App\Models\{Member, Ministry, Department, ManagementYear};
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $yearId = $request->get('year_id', optional(ManagementYear::getActive())->id);

        $members = Member::with(['ministry', 'department'])
            ->leftJoin('ministries',  'members.ministry_id',   '=', 'ministries.id')
            ->leftJoin('departments', 'members.department_id', '=', 'departments.id')
            ->where('members.management_year_id', $yearId)
            ->orderBy('ministries.sort_order')
            ->orderByRaw("CASE WHEN members.role = 'menteri' THEN 0 ELSE 1 END")
            ->orderBy('departments.sort_order')
            ->orderByRaw("CASE WHEN members.is_head = 1 THEN 0 ELSE 1 END")
            ->orderBy('members.sort_order')
            ->select('members.*')
            ->get();

        return view('admin.members.index', [
            'members'       => $members,
            'years'         => ManagementYear::latest()->get(),
            'selected_year' => $yearId,
        ]);
    }

    public function create()
    {
        $year = ManagementYear::getActive();
        return view('admin.members.form', [
            'member'      => null,
            'years'       => ManagementYear::latest()->get(),
            'ministries'  => $year ? Ministry::where('management_year_id', $year->id)->orderBy('sort_order')->get() : collect(),
            'departments' => collect(),
        ]);
    }

    public function store(MemberRequest $request)
    {
        $data = $request->safe()->except(['photo_path']);
        $data['is_head'] = $data['role'] === 'kepala_departemen';
        if ($data['role'] === 'kepala_departemen') {
            $data['role'] = 'koordinator';
        }
        if ($request->hasFile('photo_path')) {
            // Compress foto anggota ke WebP max 600px lebar, quality 82
            $data['photo_path'] = ImageHelper::compressAndStore(
                $request->file('photo_path'), 'uploads/photos', 600, 82
            );
        }
        Member::create($data);
        return redirect()->route('admin.pengurus.index')->with('success', 'Pengurus berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $member = Member::findOrFail($id);
        return view('admin.members.form', [
            'member'      => $member,
            'years'       => ManagementYear::latest()->get(),
            'ministries'  => Ministry::where('management_year_id', $member->management_year_id)->orderBy('sort_order')->get(),
            'departments' => Department::where('ministry_id', $member->ministry_id)->orderBy('sort_order')->get(),
        ]);
    }

    public function update(MemberRequest $request, int $id)
    {
        $member = Member::findOrFail($id);
        $data   = $request->safe()->except(['photo_path']);
        $data['is_head'] = $data['role'] === 'kepala_departemen';
        if ($data['role'] === 'kepala_departemen') {
            $data['role'] = 'koordinator';
        }
        if ($request->hasFile('photo_path')) {
            $data['photo_path'] = ImageHelper::compressAndStore(
                $request->file('photo_path'), 'uploads/photos', 600, 82
            );
        }
        $member->update($data);
        return redirect()->route('admin.pengurus.index')->with('success', 'Berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        Member::findOrFail($id)->delete();
        return redirect()->route('admin.pengurus.index')->with('success', 'Berhasil dihapus.');
    }

    public function getDepartments(int $ministryId)
    {
        return response()->json(
            Department::where('ministry_id', $ministryId)->orderBy('sort_order')->get(['id','name'])
        );
    }

    public function getMinistriesByYear(int $yearId)
    {
        return response()->json(
            Ministry::where('management_year_id', $yearId)->orderBy('sort_order')->get(['id','name'])
        );
    }
}