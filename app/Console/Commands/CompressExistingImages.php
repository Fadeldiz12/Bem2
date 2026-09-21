<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Helpers\ImageHelper;
use App\Models\{Member, Ministry, ManagementYear, Post};

class CompressExistingImages extends Command
{
    protected $signature   = 'images:compress {--dry-run : Tampilkan saja tanpa proses}';
    protected $description = 'Compress semua gambar lama di storage ke WebP dan update path di database';

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->warn('MODE DRY-RUN — tidak ada perubahan yang disimpan.');
        }

        $this->info('Mulai compress gambar...');
        $this->newLine();

        $totalProcessed = 0;
        $totalSkipped   = 0;
        $totalFailed    = 0;

        // ── 1. Foto Pengurus (members.photo_path) ──────────────────────
        $this->info('📸 Foto Pengurus (members)...');
        $members = Member::whereNotNull('photo_path')->get();
        $bar = $this->output->createProgressBar($members->count());
        $bar->start();

        foreach ($members as $member) {
            $result = $this->processImage($member->photo_path, 600, 82, $dryRun);
            if ($result === 'skipped') { $totalSkipped++; }
            elseif ($result === null)  { $totalFailed++;  }
            else {
                if (!$dryRun && $result !== $member->photo_path) {
                    $member->update(['photo_path' => $result]);
                }
                $totalProcessed++;
            }
            $bar->advance();
        }
        $bar->finish();
        $this->newLine();

        // ── 2. Foto Kabinet — hanya presma & wapresma, SKIP logo_path ───
        $this->info('📅 Foto Presma & Wapresma (management_years)...');
        $years = ManagementYear::whereNotNull('presma_photo')
            ->orWhereNotNull('wapresma_photo')
            ->get();
        $bar = $this->output->createProgressBar($years->count());
        $bar->start();

        foreach ($years as $year) {
            // SKIP logo_path kabinet — jangan dicompress supaya tidak pecah
            foreach (['presma_photo', 'wapresma_photo'] as $field) {
                if (empty($year->$field)) continue;
                $result = $this->processImage($year->$field, 600, 85, $dryRun);
                if ($result === 'skipped') { $totalSkipped++; }
                elseif ($result === null)  { $totalFailed++;  }
                else {
                    if (!$dryRun && $result !== $year->$field) {
                        $year->update([$field => $result]);
                    }
                    $totalProcessed++;
                }
            }
            $bar->advance();
        }
        $bar->finish();
        $this->newLine();
        $years = ManagementYear::whereNotNull('logo_path')
            ->orWhereNotNull('presma_photo')
            ->orWhereNotNull('wapresma_photo')
            ->get();
        $bar = $this->output->createProgressBar($years->count());
        $bar->start();

        foreach ($years as $year) {
            foreach (['logo_path', 'presma_photo', 'wapresma_photo'] as $field) {
                if (empty($year->$field)) continue;
                $result = $this->processImage($year->$field, 600, 85, $dryRun);
                if ($result === 'skipped') { $totalSkipped++; }
                elseif ($result === null)  { $totalFailed++;  }
                else {
                    if (!$dryRun && $result !== $year->$field) {
                        $year->update([$field => $result]);
                    }
                    $totalProcessed++;
                }
            }
            $bar->advance();
        }
        $bar->finish();
        $this->newLine();

        // ── 3. Featured Image Berita (posts.featured_image) ─────────────
        $this->info('📰 Featured Image Berita (posts)...');
        $posts = Post::whereNotNull('featured_image')->get();
        $bar = $this->output->createProgressBar($posts->count());
        $bar->start();

        foreach ($posts as $post) {
            $result = $this->processImage($post->featured_image, 1200, 80, $dryRun);
            if ($result === 'skipped') { $totalSkipped++; }
            elseif ($result === null)  { $totalFailed++;  }
            else {
                if (!$dryRun && $result !== $post->featured_image) {
                    $post->update(['featured_image' => $result]);
                }
                $totalProcessed++;
            }
            $bar->advance();
        }
        $bar->finish();
        $this->newLine(2);

        // ── Ringkasan ────────────────────────────────────────────────────
        $this->table(
            ['Status', 'Jumlah'],
            [
                ['✅ Berhasil dicompress', $totalProcessed],
                ['⏭️  Dilewati (sudah WebP kecil)', $totalSkipped],
                ['❌ Gagal', $totalFailed],
            ]
        );

        if ($dryRun) {
            $this->newLine();
            $this->warn('Ini adalah dry-run. Jalankan tanpa --dry-run untuk menyimpan perubahan.');
        }

        return self::SUCCESS;
    }

    private function processImage(?string $path, int $maxWidth, int $quality, bool $dryRun): ?string
    {
        if (!$path) return null;

        // Skip jika file tidak ada
        if (!Storage::disk('public')->exists($path)) return null;

        $ext      = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $fullPath = Storage::disk('public')->path($path);
        $filesize = filesize($fullPath);

        // Skip jika sudah WebP dan ukuran sudah kecil (< 150KB)
        if ($ext === 'webp' && $filesize < 150 * 1024) {
            return 'skipped';
        }

        if ($dryRun) {
            $kb = round($filesize / 1024);
            $this->newLine();
            $this->line("  → {$path} ({$kb}KB) akan dicompress ke WebP");
            return $path; // Return path yang sama di dry-run
        }

        return ImageHelper::compressExisting($path, $maxWidth, $quality);
    }
}