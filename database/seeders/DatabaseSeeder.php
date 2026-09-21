<?php
namespace Database\Seeders;

use App\Models\{User, ManagementYear, SiteSetting, MediaPartner, LetterFormat};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'          => 'Super Admin',
            'email'         => env('SUPERADMIN_EMAIL', 'admin@bempolmed.ac.id'),
            'password_hash' => Hash::make(env('SUPERADMIN_PASSWORD', 'admin123')),
            'role'          => 'super_admin',
            'is_active'     => true,
        ]);

        ManagementYear::create([
            'year_label'   => '2025/2026',
            'cabinet_name' => 'Kabinet Karsa Abhinaya',
            'tagline'      => 'Bahu Membahu Untuk Bersatu',
            'visi'         => 'Menjadikan BEM POLMED sebagai organisasi yang responsif, aktif dan kolaboratif.',
            'misi'         => json_encode([
                'Menumbuhkan nilai profesionalitas dan kepercayaan di internal BEM POLMED',
                'Menciptakan ruang komunikasi dan partisipasi aktif',
                'Meningkatkan keaktifan dengan responsibilitas',
                'Menciptakan program yang mendukung kebutuhan mahasiswa',
            ]),
            'status'     => 'published',
            'is_active'  => true,
            'start_date' => '2025-01-01',
            'end_date'   => '2026-12-31',
        ]);

        $settings = [
            ['key' => 'sekretariat_address', 'value' => 'Jalan Almamater No. 1 Kampus USU, Padang Bulan, Medan Baru, Sumatera Utara 20155', 'type' => 'textarea', 'label' => 'Alamat Sekretariat'],
            ['key' => 'facebook_url',  'value' => '#', 'type' => 'url', 'label' => 'URL Facebook'],
            ['key' => 'instagram_url', 'value' => '#', 'type' => 'url', 'label' => 'URL Instagram'],
            ['key' => 'tiktok_url',    'value' => '#', 'type' => 'url', 'label' => 'URL TikTok'],
            ['key' => 'youtube_url',   'value' => '#', 'type' => 'url', 'label' => 'URL YouTube'],
            ['key' => 'twitter_url',   'value' => '#', 'type' => 'url', 'label' => 'URL Twitter/X'],
            ['key' => 'whatsapp_url',  'value' => '#', 'type' => 'url', 'label' => 'URL WhatsApp'],
            ['key' => 'about_bem',     'value' => 'Badan Eksekutif Mahasiswa (BEM) adalah lembaga eksekutif dalam struktur pemerintahan mahasiswa.', 'type' => 'textarea', 'label' => 'Tentang BEM'],
        ];
        foreach ($settings as $s) SiteSetting::create($s);

        MediaPartner::insert([
            ['type' => 'internal', 'title' => 'Internal', 'procedures' => json_encode(['Mengisi gform pengajuan media partner','BEM Polmed tidak menerima materi publikasi yang mengandung unsur negatif','Menunggu acc dari pihak BEM Polmed','Memfollow 3 akun social media BEM Polmed','Mengirimkan flyer dengan mencantumkan logo BEM Polmed beserta caption']), 'gform_link' => '#', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'external', 'title' => 'External', 'procedures' => json_encode(['Mengisi gform pengajuan media partner','BEM Polmed tidak menerima materi publikasi yang mengandung unsur negatif','Menunggu acc dari pihak BEM Polmed','Membayar fee sesuai ketetapan BEM Polmed','Mengirimkan flyer dengan mencantumkan logo BEM Polmed beserta caption']), 'gform_link' => '#', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $formats = [
            ['title'=>'Surat Undangan','icon_type'=>'envelope'],['title'=>'LPJ','icon_type'=>'clipboard'],
            ['title'=>'Pengantar Proposal','icon_type'=>'send'],['title'=>'Proposal','icon_type'=>'file-text'],
            ['title'=>'Perizinan Acara','icon_type'=>'calendar'],['title'=>'Permohonan Dana','icon_type'=>'dollar-sign'],
            ['title'=>'Peminjaman Tempat','icon_type'=>'home'],['title'=>'Peminjaman Barang','icon_type'=>'package'],
        ];
        foreach ($formats as $i => $f) {
            LetterFormat::create(array_merge($f, ['uploaded_by' => 1, 'file_path' => '#', 'file_type' => 'docx', 'sort_order' => $i + 1]));
        }

        // ===== Modul LPJ & Proposal (digabung dari project kedua) =====
        $this->call([
            UserSeeder::class,
            KegiatanSeeder::class,
        ]);
    }
}
