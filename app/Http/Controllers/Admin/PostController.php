<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PostRequest;
use App\Helpers\ImageHelper;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        return view('admin.posts.index', ['posts' => Post::with('author')->latest()->get()]);
    }

    public function create()
    {
        return view('admin.posts.form', ['post' => null]);
    }

    public function store(PostRequest $request)
    {
        $data              = $request->safe()->except(['featured_image','content_image_1','content_image_2','content_image_3']);
        $data['author_id'] = session('admin_id');
        $data['slug']      = Post::generateSlug($data['title']);
        if ($data['status'] === 'published') $data['published_at'] = now();
        if ($request->hasFile('featured_image')) {
            // Featured image berita: max 1200px, quality 80
            $data['featured_image'] = ImageHelper::compressAndStore(
                $request->file('featured_image'), 'uploads/photos', 1200, 80
            );
        }
        for ($i = 1; $i <= 3; $i++) {
            $field = 'content_image_'.$i;
            if ($request->hasFile($field)) {
                $data[$field] = ImageHelper::compressAndStore(
                    $request->file($field), 'uploads/photos', 1200, 80
                );
            }
        }
        Post::create($data);
        return redirect()->route('admin.berita.index')->with('success', 'Post berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        return view('admin.posts.form', ['post' => Post::findOrFail($id)]);
    }

    public function update(PostRequest $request, int $id)
    {
        $post = Post::findOrFail($id);
        $data = $request->safe()->except(['featured_image','content_image_1','content_image_2','content_image_3']);
        if ($data['status'] === 'published' && !$post->published_at) $data['published_at'] = now();
        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = ImageHelper::compressAndStore(
                $request->file('featured_image'), 'uploads/photos', 1200, 80
            );
        }
        for ($i = 1; $i <= 3; $i++) {
            $field = 'content_image_'.$i;
            if ($request->hasFile($field)) {
                $data[$field] = ImageHelper::compressAndStore(
                    $request->file($field), 'uploads/photos', 1200, 80
                );
            }
        }
        $post->update($data);
        return redirect()->route('admin.berita.index')->with('success', 'Berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        Post::findOrFail($id)->delete();
        return redirect()->route('admin.berita.index')->with('success', 'Berhasil dihapus.');
    }
}