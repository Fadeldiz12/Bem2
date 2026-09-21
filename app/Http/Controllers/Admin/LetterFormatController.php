<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LetterFormatRequest;
use App\Models\LetterFormat;

class LetterFormatController extends Controller
{
    public function index()
    {
        return view('admin.letter_formats.index', ['formats' => LetterFormat::orderBy('sort_order')->get()]);
    }

    public function create()
    {
        return view('admin.letter_formats.form', ['format' => null]);
    }

    public function store(LetterFormatRequest $request)
    {
        $data = $request->safe()->except(['file_path','file_path_hmps','file_path_ukm']);
        $data['uploaded_by'] = session('admin_id');
        $data['is_active']   = true;
        if ($request->hasFile('file_path_hmps')) {
            $data['file_path_hmps'] = $request->file('file_path_hmps')->store('uploads/files', 'public');
            $data['file_type_hmps'] = $request->file('file_path_hmps')->getClientOriginalExtension();
        }
        if ($request->hasFile('file_path_ukm')) {
            $data['file_path_ukm'] = $request->file('file_path_ukm')->store('uploads/files', 'public');
            $data['file_type_ukm'] = $request->file('file_path_ukm')->getClientOriginalExtension();
        }
        if ($request->hasFile('file_path')) {
            $data['file_path'] = $request->file('file_path')->store('uploads/files', 'public');
            $data['file_type'] = $request->file('file_path')->getClientOriginalExtension();
        }
        LetterFormat::create($data);
        return redirect()->route('admin.letter.index')->with('success', 'Format surat berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        return view('admin.letter_formats.form', ['format' => LetterFormat::findOrFail($id)]);
    }

    public function update(LetterFormatRequest $request, int $id)
    {
        $format = LetterFormat::findOrFail($id);
        $data = $request->safe()->except(['file_path','file_path_hmps','file_path_ukm']);
        if ($request->hasFile('file_path_hmps')) {
            $data['file_path_hmps'] = $request->file('file_path_hmps')->store('uploads/files', 'public');
            $data['file_type_hmps'] = $request->file('file_path_hmps')->getClientOriginalExtension();
        }
        if ($request->hasFile('file_path_ukm')) {
            $data['file_path_ukm'] = $request->file('file_path_ukm')->store('uploads/files', 'public');
            $data['file_type_ukm'] = $request->file('file_path_ukm')->getClientOriginalExtension();
        }
        if ($request->hasFile('file_path')) {
            $data['file_path'] = $request->file('file_path')->store('uploads/files', 'public');
            $data['file_type'] = $request->file('file_path')->getClientOriginalExtension();
        }
        $format->update($data);
        return redirect()->route('admin.letter.index')->with('success', 'Berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        LetterFormat::findOrFail($id)->delete();
        return redirect()->route('admin.letter.index')->with('success', 'Berhasil dihapus.');
    }
}
