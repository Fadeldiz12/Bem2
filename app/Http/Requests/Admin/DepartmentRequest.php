<?php
namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;

class DepartmentRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'ministry_id'  => 'required|exists:ministries,id',
            'name'         => 'required|string|max:100',
            'description'  => 'nullable|string',
            'tugas_pokok'  => 'nullable|string',
            'sort_order'   => 'nullable|integer|min:0',
        ];
    }
    public function messages(): array
    {
        return [
            'ministry_id.required' => 'Kementerian wajib dipilih.',
            'ministry_id.exists'   => 'Kementerian tidak valid.',
            'name.required'        => 'Nama departemen wajib diisi.',
        ];
    }
}
