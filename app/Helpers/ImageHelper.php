<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    /**
     * Compress dan simpan gambar ke storage/public menggunakan GD (built-in PHP).
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $directory  Contoh: 'uploads/photos'
     * @param int $maxWidth      Lebar maksimum (px), tinggi menyesuaikan aspek rasio
     * @param int $quality       Kualitas JPEG/WebP (0-100)
     * @return string            Path relatif yang disimpan di database
     */
    public static function compressAndStore($file, string $directory, int $maxWidth = 800, int $quality = 82): string
    {
        $ext      = strtolower($file->getClientOriginalExtension());
        $mime     = $file->getMimeType();
        $filename = uniqid() . '.webp';
        $savePath = $directory . '/' . $filename;

        // Buat image resource dari GD sesuai tipe file
        $source = match(true) {
            in_array($ext, ['jpg','jpeg']) || $mime === 'image/jpeg' => imagecreatefromjpeg($file->getRealPath()),
            $ext === 'png'  || $mime === 'image/png'  => imagecreatefrompng($file->getRealPath()),
            $ext === 'webp' || $mime === 'image/webp' => imagecreatefromwebp($file->getRealPath()),
            $ext === 'gif'  || $mime === 'image/gif'  => imagecreatefromgif($file->getRealPath()),
            default => imagecreatefromjpeg($file->getRealPath()),
        };

        if (!$source) {
            // Fallback: simpan langsung tanpa compress jika GD gagal
            return $file->store($directory, 'public');
        }

        // Hitung dimensi baru
        $origW = imagesx($source);
        $origH = imagesy($source);

        if ($origW > $maxWidth) {
            $newW = $maxWidth;
            $newH = (int) round($origH * ($maxWidth / $origW));
        } else {
            $newW = $origW;
            $newH = $origH;
        }

        // Buat canvas baru dengan transparansi
        $canvas = imagecreatetruecolor($newW, $newH);
        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);
        $transparent = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
        imagefilledrectangle($canvas, 0, 0, $newW, $newH, $transparent);

        // Resize
        imagecopyresampled($canvas, $source, 0, 0, 0, 0, $newW, $newH, $origW, $origH);

        // Simpan ke buffer lalu upload ke storage
        ob_start();
        imagewebp($canvas, null, $quality);
        $imageData = ob_get_clean();

        imagedestroy($source);
        imagedestroy($canvas);

        Storage::disk('public')->put($savePath, $imageData);

        return $savePath;
    }

    /**
     * Compress gambar yang sudah ada di storage (untuk batch/artisan command).
     *
     * @param string $storagePath  Path relatif di storage/app/public
     * @param int $maxWidth
     * @param int $quality
     * @return string|null  Path baru (WebP), atau null jika gagal
     */
    public static function compressExisting(string $storagePath, int $maxWidth = 800, int $quality = 82): ?string
    {
        $fullPath = Storage::disk('public')->path($storagePath);
        if (!file_exists($fullPath)) return null;

        $ext  = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
        $mime = mime_content_type($fullPath);

        $source = match(true) {
            in_array($ext, ['jpg','jpeg']) || $mime === 'image/jpeg' => imagecreatefromjpeg($fullPath),
            $ext === 'png'  || $mime === 'image/png'  => imagecreatefrompng($fullPath),
            $ext === 'webp' || $mime === 'image/webp' => imagecreatefromwebp($fullPath),
            $ext === 'gif'  || $mime === 'image/gif'  => imagecreatefromgif($fullPath),
            default => null,
        };

        if (!$source) return null;

        $origW = imagesx($source);
        $origH = imagesy($source);

        if ($origW <= $maxWidth) {
            // Sudah kecil, tetap compress ke WebP saja
            $newW = $origW;
            $newH = $origH;
        } else {
            $newW = $maxWidth;
            $newH = (int) round($origH * ($maxWidth / $origW));
        }

        $canvas = imagecreatetruecolor($newW, $newH);
        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);
        $transparent = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
        imagefilledrectangle($canvas, 0, 0, $newW, $newH, $transparent);
        imagecopyresampled($canvas, $source, 0, 0, 0, 0, $newW, $newH, $origW, $origH);

        // Simpan sebagai WebP di path yang sama (ganti ekstensi)
        $newPath    = preg_replace('/\.[^.]+$/', '.webp', $storagePath);
        $newFullPath = Storage::disk('public')->path($newPath);

        // Pastikan direktori ada
        if (!is_dir(dirname($newFullPath))) {
            mkdir(dirname($newFullPath), 0755, true);
        }

        imagewebp($canvas, $newFullPath, $quality);
        imagedestroy($source);
        imagedestroy($canvas);

        // Hapus file lama jika ekstensinya berbeda
        if ($newPath !== $storagePath && file_exists($fullPath)) {
            unlink($fullPath);
        }

        return $newPath;
    }
}