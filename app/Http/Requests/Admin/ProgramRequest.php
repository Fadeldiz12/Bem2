<?php
namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;

class ProgramRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'department_id'  => 'required|exists:departments,id',
            'name'           => 'required|string|max:150',
            'description'    => 'nullable|string',
            'execution_date' => 'nullable|date',
            'status'         => 'required|in:akan_dilaksanakan,sedang_berjalan,selesai',
            'sort_order'     => 'nullable|integer|min:0',
        ];
    }
}