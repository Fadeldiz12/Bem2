<?php
namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;

class MemberRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'management_year_id' => 'required|exists:management_years,id',
            'ministry_id'        => 'required|exists:ministries,id',
            'department_id'      => 'nullable|exists:departments,id',
            'full_name'          => 'required|string|max:100',
            'nim'                => 'nullable|string|max:20',
            'position'           => 'required|string|max:100',
            'role'               => 'required|in:menteri,kepala_departemen,staff',
            'sort_order'         => 'nullable|integer|min:0',
            'photo_path'         => 'nullable|image|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'management_year_id.required' => 'Tahun kepengurusan wajib dipilih.',
            'ministry_id.required'        => 'Kementerian wajib dipilih.',
            'full_name.required'          => 'Nama lengkap wajib diisi.',
            'position.required'           => 'Jabatan tampilan wajib diisi.',
            'role.required'               => 'Role wajib dipilih.',
            'role.in'                     => 'Role tidak valid.',
            'photo_path.image'            => 'Foto harus berupa file gambar.',
            'photo_path.max'              => 'Ukuran foto maksimal 2MB.',
        ];
    }
}