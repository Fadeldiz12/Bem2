<?php
namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;

class ManagementYearRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'year_label'      => 'required|string|max:20',
            'cabinet_name'    => 'required|string|max:100',
            'tagline'         => 'nullable|string|max:150',
            'visi'            => 'nullable|string',
            'misi'            => 'nullable|string',
            'presma_name'     => 'nullable|string|max:100',
            'wapresma_name'   => 'nullable|string|max:100',
            'start_date'      => 'nullable|date',
            'end_date'        => 'nullable|date|after_or_equal:start_date',
            'logo_path'       => 'nullable|image|max:2048',
            'presma_photo'    => 'nullable|image|max:2048',
            'wapresma_photo'  => 'nullable|image|max:2048',
            // filosofi dan warna array — dihandle manual di controller
            'filosofi'        => 'nullable|array',
            'filosofi.*.nama' => 'nullable|string|max:150',
            'filosofi.*.penjelasan' => 'nullable|string',
            'warna'           => 'nullable|array',
            'warna.*.nama'    => 'nullable|string|max:100',
            'warna.*.hex'     => 'nullable|string|max:7',
            'warna.*.makna'   => 'nullable|string|max:255',
        ];
    }
    public function messages(): array
    {
        return [
            'year_label.required'     => 'Tahun kepengurusan wajib diisi.',
            'cabinet_name.required'   => 'Nama kabinet wajib diisi.',
            'end_date.after_or_equal' => 'Tanggal selesai harus setelah tanggal mulai.',
            'logo_path.image'         => 'Logo harus berupa file gambar.',
            'logo_path.max'           => 'Ukuran logo maksimal 2MB.',
        ];
    }
}