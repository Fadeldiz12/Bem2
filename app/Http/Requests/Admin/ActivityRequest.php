<?php
namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;

class ActivityRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'title'         => 'required|string|max:150',
            'borrower_name' => 'required|string|max:100',
            'location'      => 'required|string|max:150',
            'activity_date' => 'required|date',
            'start_time'    => 'nullable|date_format:H:i',
            'end_time'      => 'nullable|date_format:H:i|after:start_time',
            'status'        => 'required|in:menunggu,disetujui,ditolak',
            'description'   => 'nullable|string',
        ];
    }
    public function messages(): array
    {
        return [
            'title.required'         => 'Nama kegiatan wajib diisi.',
            'borrower_name.required' => 'Nama peminjam wajib diisi.',
            'location.required'      => 'Tempat wajib diisi.',
            'activity_date.required' => 'Tanggal wajib diisi.',
            'end_time.after'         => 'Jam selesai harus setelah jam mulai.',
        ];
    }
}
