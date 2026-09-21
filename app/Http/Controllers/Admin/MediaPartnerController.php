<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaPartner;
use Illuminate\Http\Request;

class MediaPartnerController extends Controller
{
    public function index() { return view('admin.media_partners.index', ['partners' => MediaPartner::all()]); }

    public function update(Request $request, int $id) {
        MediaPartner::findOrFail($id)->update([
            'title'      => $request->title,
            'procedures' => json_encode(array_values(array_filter(explode("\n", $request->procedures ?? '')))),
            'gform_link' => $request->gform_link,
        ]);
        return redirect()->route('admin.media.index')->with('success', 'Berhasil diperbarui.');
    }
}
