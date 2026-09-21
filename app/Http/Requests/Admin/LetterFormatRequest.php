<?php
namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;

class LetterFormatRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        $isUpdate = $this->route('id') !== null;
        return [
            'title'           => 'required|string|max:150',
            'icon_type'       => 'nullable|string|max:50',
            'description'     => 'nullable|string',
            'sort_order'      => 'nullable|integer|min:0',
            'file_path'       => $isUpdate ? 'nullable|file|max:10240' : 'required_without_all:file_path_hmps,file_path_ukm|file|max:10240',
            'file_path_hmps'  => 'nullable|file|max:10240',
            'file_path_ukm'   => 'nullable|file|max:10240',
        ];
    }
    public function messages(): array
    {
        return [
            'title.required'             => 'Judul wajib diisi.',
            'file_path.required_without_all' => 'Unggah minimal satu file format surat.',
            'file_path.max'              => 'Ukuran file maksimal 10MB.',
            'file_path_hmps.max'         => 'Ukuran file HMPS maksimal 10MB.',
            'file_path_ukm.max'          => 'Ukuran file UKM maksimal 10MB.',
        ];
    }
}
