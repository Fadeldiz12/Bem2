<?php
namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'title'             => 'required|string|max:255',
            'content'           => 'required|string',
            'status'            => 'required|in:draft,published,archived',
            'category'          => 'required|in:berita,pengumuman,kegiatan,artikel',
            'featured_image'    => 'nullable|image|max:3072',
            'content_image_1'   => 'nullable|image|max:3072',
            'content_image_2'   => 'nullable|image|max:3072',
            'content_image_3'   => 'nullable|image|max:3072',
        ];
    }
    public function messages(): array
    {
        return [
            'title.required'              => 'Judul wajib diisi.',
            'content.required'            => 'Konten wajib diisi.',
            'featured_image.image'        => 'Gambar utama harus berupa file gambar.',
            'featured_image.max'          => 'Ukuran gambar utama maksimal 3MB.',
            'content_image_1.image'       => 'Gambar konten 1 harus berupa file gambar.',
            'content_image_1.max'         => 'Ukuran gambar konten 1 maksimal 3MB.',
            'content_image_2.image'       => 'Gambar konten 2 harus berupa file gambar.',
            'content_image_2.max'         => 'Ukuran gambar konten 2 maksimal 3MB.',
            'content_image_3.image'       => 'Gambar konten 3 harus berupa file gambar.',
            'content_image_3.max'         => 'Ukuran gambar konten 3 maksimal 3MB.',
        ];
    }
}
