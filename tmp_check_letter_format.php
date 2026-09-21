<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$fmt = App\Models\LetterFormat::first();
if (!$fmt) {
    echo "NO_FMT\n";
    exit(0);
}
$disk = Illuminate\Support\Facades\Storage::disk('public');
$paths = [
    'file' => $fmt->file_path,
    'hmps' => $fmt->file_path_hmps,
    'ukm' => $fmt->file_path_ukm,
];
foreach ($paths as $key => $path) {
    echo "$key=$path\n";
    if ($path) {
        echo $disk->exists($path) ? "exists\n" : "missing\n";
        echo $disk->path($path) . "\n";
    }
}
