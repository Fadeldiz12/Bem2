<?php
namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;

class MinistryRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'management_year_id' => 'required|exists:management_years,id',
            'name'               => 'required|string|max:100',
            'alias'              => 'nullable|string|max:50',
            'description'        => 'nullable|string',
            'tugas_pokok'        => 'nullable|string',
            'whatsapp_number'    => 'nullable|string|max:20',
            'whatsapp_label'     => 'nullable|string|max:100',
            'sort_order'         => 'nullable|integer|min:0',
            'logo_path'          => 'nullable|image|max:2048',
        ];
    }
    public function messages(): array
    {
        return [
            'management_year_id.required' => 'Tahun kepengurusan wajib dipilih.',
            'management_year_id.exists'   => 'Tahun kepengurusan tidak valid.',
            'name.required'               => 'Nama kementerian wajib diisi.',
            'logo_path.image'             => 'Logo harus berupa file gambar.',
            'logo_path.max'               => 'Ukuran logo maksimal 2MB.',
        ];
    }
}
