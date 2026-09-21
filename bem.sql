-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping data for table bem_polmed.activities: ~3 rows (approximately)
INSERT INTO `activities` (`id`, `created_by`, `title`, `borrower_name`, `description`, `activity_date`, `start_time`, `end_time`, `location`, `status`, `created_at`, `updated_at`) VALUES
	(1, 1, 'When Yes', 'Kapan ya', 'kapan satu orang jadi 2', '2026-06-24', '12:00:00', '22:01:00', 'Ged U', 'disetujui', '2026-06-22 10:22:12', '2026-06-22 10:23:51'),
	(2, 1, 'dsffgdfgGFDG', 'dfgdfgdfg', 'sdfsdfsdf', '2026-06-24', '12:12:00', '12:50:00', 'dfgdfgdfg', 'menunggu', '2026-06-22 10:23:20', '2026-06-22 10:24:12'),
	(3, 1, 'bebas', 'when', NULL, '2026-06-27', '12:12:00', '12:13:00', 'when', 'ditolak', '2026-06-23 08:50:35', '2026-06-23 08:51:06');

-- Dumping data for table bem_polmed.assets: ~1 rows (approximately)
INSERT INTO `assets` (`id`, `uploaded_by`, `title`, `category`, `file_path`, `file_type`, `created_at`, `updated_at`) VALUES
	(1, 1, NULL, 'logo_kabinet', 'uploads/logos/YcYzXnRk8a9bKKMpsoIh2oSP4mDKlQYFE6yssQ9a.png', 'png', '2026-06-20 22:25:01', '2026-06-20 22:25:01');

-- Dumping data for table bem_polmed.bon: ~0 rows (approximately)
INSERT INTO `bon` (`ID_Bon`, `ID_Sie`, `Nama_Bon`, `Foto_Bon`, `created_at`, `updated_at`) VALUES
	(1, 2, 'Makan Siang', NULL, '2026-07-17 09:24:23', '2026-07-17 09:24:23'),
	(2, 3, 'Spanduk', NULL, '2026-07-17 14:50:40', '2026-07-17 14:50:40');

-- Dumping data for table bem_polmed.cache: ~10 rows (approximately)

-- Dumping data for table bem_polmed.cache_locks: ~0 rows (approximately)

-- Dumping data for table bem_polmed.departments: ~11 rows (approximately)
INSERT INTO `departments` (`id`, `ministry_id`, `name`, `description`, `tugas_pokok`, `sort_order`, `created_at`, `updated_at`) VALUES
	(2, 2, 'Departemen Kesekretariatan', 'Kementerian Kesekretariatan adalah kementerian dalam Badan Eksekutif Mahasiswa (BEM) yang bertanggung jawab mengelola administrasi BEM, proposal, LPJ, surat menyurat, kegiatan notulensi dan lainnya serta bertugas menjalankan administrasi seluruh keperluan anggota. Berfungsi mengkoordinasi UKM & Himpunan Mahasiswa Program Studi.', NULL, 1, '2026-06-20 03:50:43', '2026-06-25 07:42:28'),
	(3, 3, 'Departemen Keuangan', NULL, NULL, 2, '2026-06-20 03:54:24', '2026-06-25 07:42:40'),
	(4, 3, 'Departemen Ekonomi Kreatif', NULL, NULL, 3, '2026-06-20 03:54:37', '2026-06-25 07:42:46'),
	(5, 4, 'Dalam Kampus', NULL, NULL, 4, '2026-06-25 07:42:59', '2026-06-25 07:43:57'),
	(6, 5, 'Ilmu Pengetahuan & Teknologi', NULL, NULL, 5, '2026-06-25 07:43:48', '2026-06-25 07:44:01'),
	(7, 5, 'Seni & Olahraga', NULL, NULL, 6, '2026-06-25 07:44:26', '2026-06-25 07:44:26'),
	(8, 6, 'Kesejahteraan Mahasiswa', NULL, NULL, 7, '2026-06-25 07:44:52', '2026-06-25 07:44:58'),
	(9, 7, 'Analisis Kebijakan Internal Eksternal', NULL, NULL, 8, '2026-06-25 07:45:31', '2026-06-25 07:45:31'),
	(10, 8, 'Luar Kampus', NULL, NULL, 9, '2026-06-25 07:45:48', '2026-06-25 07:45:48'),
	(11, 8, 'Pengabdian Masyarakat', NULL, NULL, 10, '2026-06-25 07:46:16', '2026-06-25 07:46:16'),
	(12, 1, 'Komunikasi dan Informasi', NULL, NULL, 11, '2026-06-25 07:46:42', '2026-06-25 07:46:42');

-- Dumping data for table bem_polmed.item: ~0 rows (approximately)
INSERT INTO `item` (`ID_Item`, `ID_Sie`, `ID_Bon`, `Jenis_Pengeluaran`, `Keterangan`, `Qty`, `Satuan`, `Harga_Unit`, `created_at`, `updated_at`) VALUES
	(2, 2, 1, 'Perlengkapan', 'Cue card', 2, 'Pcs', 10000.00, '2026-07-17 07:39:30', '2026-07-17 09:24:23'),
	(3, 2, 1, 'Perlengkapan', 'Balon', 5, 'kotak', 15000.00, '2026-07-17 07:39:53', '2026-07-17 09:24:23'),
	(4, 3, 2, 'Perlengkapan', 'Desain', 1, 'Pcs', 20000.00, '2026-07-17 07:40:20', '2026-07-17 14:50:40'),
	(5, 4, NULL, 'Perlengkapan', 'Spanduk', 1, 'Pcs', 64000.00, '2026-07-17 07:40:42', '2026-07-17 07:40:42');

-- Dumping data for table bem_polmed.item_lpj: ~0 rows (approximately)
INSERT INTO `item_lpj` (`ID_Item_LPJ`, `ID_Sie`, `ID_Bon`, `Jenis_Pengeluaran`, `Keterangan`, `Qty_Realisasi`, `Satuan_Realisasi`, `Harga_Realisasi`, `created_at`, `updated_at`) VALUES
	(1, 2, 1, 'Perlengkapan', 'Cue card', 2, 'Pcs', 10000.00, '2026-07-17 09:24:23', '2026-07-17 09:24:56'),
	(2, 2, 1, 'Perlengkapan', 'Balon', 5, 'kotak', 15000.00, '2026-07-17 09:24:23', '2026-07-17 09:24:56'),
	(3, 3, 2, 'Perlengkapan', 'Desain', 1, 'Pcs', 20000.00, '2026-07-17 14:50:40', '2026-07-17 14:50:40');

-- Dumping data for table bem_polmed.kegiatan: ~0 rows (approximately)
INSERT INTO `kegiatan` (`ID_Kegiatan`, `user_id`, `Nama_Kegiatan`, `Tanggal_Pelaksanaan`, `Jenis_RAB`, `created_at`, `updated_at`) VALUES
	(2, NULL, 'PPKMB', '2026-07-31', 'Proposal', NULL, NULL);

-- Dumping data for table bem_polmed.letter_formats: ~8 rows (approximately)
INSERT INTO `letter_formats` (`id`, `uploaded_by`, `title`, `description`, `icon_type`, `file_path`, `file_type`, `download_count`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Surat Undangan', NULL, 'envelope', 'uploads/files/VeKKOpMEu8bu22Of6cavACnqnfgWntQwhmOqgEQs.png', 'png', 4, 1, 1, '2026-06-19 04:35:06', '2026-06-30 21:00:17'),
	(2, 1, 'LPJ', NULL, 'clipboard', 'uploads/files/MrtTY2VkIzGKQGfD7SmjqmpNf6KD4o0nNs3ctucv.pdf', 'pdf', 5, 1, 2, '2026-06-19 04:35:06', '2026-06-29 01:31:21'),
	(3, 1, 'Pengantar Proposal', NULL, 'send', '#', 'docx', 1, 1, 3, '2026-06-19 04:35:06', '2026-06-22 03:24:17'),
	(4, 1, 'Proposal', NULL, 'file-text', '#', 'docx', 2, 1, 4, '2026-06-19 04:35:06', '2026-06-22 09:13:57'),
	(5, 1, 'Perizinan Acara', NULL, 'calendar', '#', 'docx', 0, 1, 5, '2026-06-19 04:35:06', '2026-06-19 04:35:06'),
	(6, 1, 'Permohonan Dana', NULL, 'cash-stack', '#', 'docx', 0, 1, 6, '2026-06-19 04:35:06', '2026-07-01 00:07:42'),
	(7, 1, 'Peminjaman Tempat', NULL, 'building', '#', 'docx', 1, 1, 7, '2026-06-19 04:35:06', '2026-07-01 00:07:55'),
	(8, 1, 'Peminjaman Barang', NULL, 'box-seam', '#', 'docx', 0, 1, 8, '2026-06-19 04:35:06', '2026-07-01 00:08:07');

-- Dumping data for table bem_polmed.management_years: ~2 rows (approximately)
INSERT INTO `management_years` (`id`, `year_label`, `cabinet_name`, `tagline`, `logo_path`, `visi`, `misi`, `filosofi_logo`, `makna_warna`, `presma_name`, `presma_photo`, `wapresma_name`, `wapresma_photo`, `status`, `is_active`, `start_date`, `end_date`, `created_at`, `updated_at`) VALUES
	(1, '2025/2026', 'Kabinet Karsa Abhinaya', 'Bahu Membahu Untuk Bersatu', 'uploads/logos/Mp77mA8S6AIjscY6g4DmrtB5OCYYp6nFIr7wrgCu.webp', 'Menjadikan BEM POLMED sebagai organisasi yang responsif, aktif dan kolaboratif.', '["Menumbuhkan nilai profesionalitas dan kepercayaan di internal BEM POLMED","Menciptakan ruang komunikasi dan partisipasi aktif","Meningkatkan keaktifan dengan responsibilitas","Menciptakan program yang mendukung kebutuhan mahasiswa"]', '[{"nama":"Dua Naga Sebagai Simbol Utama","penjelasan":"Menggambarkan diri masing masing presma dan wapresma yang dimana kedua naga tersebut melingkari\\/merangkul\\/melindungi kabinet yang di naungi. Dua naga juga mengandung makna mendalam tentang dualitas, keseimbangan, kekuasaan serta melambangkan persatuan atau hubungan yang kuat.","gambar":"uploads\\/logos\\/oeKgKef2iO6O4Nc9xv7t8FwmIkJi2TbuBCqc3uhL.webp"},{"nama":"Daun dan Api","penjelasan":"Melambangkan pertumbuhan, kreativitas, dan semangat. pada elemen api mencerminkan inisiatif, transformasi, dan semangat yang tak pernah padam. elemen daun menunjukkan koneksi dengan alam, harmoni, dan keberlanjutan. 8 helai daun melambangkan bem polmed memiliki 8 kementrian didalamnya yang siap untuk menangani tupoksinya.","gambar":"uploads\\/logos\\/1olawm2VmkDA2FRUdNXADkzwmKnYruk4FhaCacGr.webp"},{"nama":"Lingkaran Tengah (Inti)","penjelasan":"Melambangkan kesempurnaan, keutuhan dan fokus. Dalam konteks ini, lingkaran dapat merepresentasikan inti gagasan, visi, atau tujuan utama dari \\"Karsa Abhinaya.\\" Lingkaran ini juga bisa melambangkan pusat energi atau inovasi.","gambar":"uploads\\/logos\\/q6CwRFTsBMWqPQR6XPeaQ1lX0oGG8ahFSR4kz3BX.webp"},{"nama":"Tiga roda bergerigi","penjelasan":"Mencerminkan progres yang berkelanjutan, seperti roda yang terus bergerak maju. filosofi ini menekankan pentingnya setiap individu atau komponen dalam sebuah kelompok atau sistem. Semua elemen harus bekerja sama untuk mencapai tujuan bersama.","gambar":"uploads\\/logos\\/JHccgOLiuP47OXIuGphbAFgmtoXT4yUw2nAtlvsG.webp"},{"nama":"Tiga titik","penjelasan":"Melambangkan Tri Dharma Perguruan Tinggi. setiap titik mewakili pendidikan, penelitian dan pengabdian kepada masyarakat.","gambar":"uploads\\/logos\\/a6b1e7HRkJ7WzFhcjBOGVz7Anmm4m3b7vLM01wbr.webp"}]', '[{"nama":"Vivid Purple","hex":"#8029C6","makna":"Melambangkan kebijakasanaan dan menjadi refleksi dari politeknik negeri medan"},{"nama":"Crimson Red","hex":"#BC1326","makna":"Melambangkan Keberanian,Semangat,dan kerja keras untuk membangun bem polmed yang lebih baik lagi"},{"nama":"Black","hex":"#000000","makna":"Melambangkan Kewibawaan dan ketegasan bem polmed"},{"nama":"Golden Orange","hex":"#F1A14F","makna":"Melambangkan Kenyamanan dan Kreativitas"},{"nama":"Sky Blue","hex":"#8CC9ED","makna":"Melambangkan ketenangan dan bertanggung jawab"}]', 'Ahmad Arya Attalah', 'uploads/logos/9edDeRyQt2MPQiCz2wJ3YwzzQSKeX1R8tpPmNH43.webp', 'Amru Disyacitta Siswanto', 'uploads/logos/Ln7jRsfxFEdOvbhwKpCu6bzF7EBQqm5XXiiaGRvu.webp', 'published', 1, '2025-01-01', '2026-12-31', '2026-06-19 04:35:06', '2026-07-03 02:16:23'),
	(5, '2026/2027', 'asdfa', 'asdfa', 'uploads/logos/H6W4xNIFRHWNNkdeLoXPm7WjElDf0TTrE9ovahsf.webp', 'as', '[]', '[]', '[]', 'asdf', NULL, 'asdf', NULL, 'archived', 0, '2026-06-04', '2026-06-26', '2026-06-25 20:15:37', '2026-07-03 02:16:23');

-- Dumping data for table bem_polmed.media_partners: ~2 rows (approximately)
INSERT INTO `media_partners` (`id`, `type`, `title`, `procedures`, `gform_link`, `created_at`, `updated_at`) VALUES
	(1, 'internal', 'Internal', '["Mengisi gform pengajuan media partner\\r","BEM Polmed tidak menerima materi publikasi yang mengandung unsur negatif\\r","Menunggu acc dari pihak BEM Polmed\\r","Memfollow 3 akun social media BEM Polmed\\r","Mengirimkan flyer dengan mencantumkan logo BEM Polmed beserta caption"]', '#', '2026-06-19 04:35:06', '2026-06-29 01:31:53'),
	(2, 'external', 'External', '["Mengisi gform pengajuan media partner","BEM Polmed tidak menerima materi publikasi yang mengandung unsur negatif","Menunggu acc dari pihak BEM Polmed","Membayar fee sesuai ketetapan BEM Polmed","Mengirimkan flyer dengan mencantumkan logo BEM Polmed beserta caption"]', '#', '2026-06-19 04:35:06', '2026-06-19 04:35:06');

-- Dumping data for table bem_polmed.members: ~59 rows (approximately)
INSERT INTO `members` (`id`, `management_year_id`, `ministry_id`, `department_id`, `full_name`, `nim`, `position`, `role`, `is_head`, `photo_path`, `sort_order`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, NULL, 'Dahliani Ramandha', '2305181000', 'Menteri', 'menteri', 0, 'uploads/photos/vqelcgOGKqTXdZ8cAdhdbZX7jIhCstgSm6M5eGjH.webp', 8, '2026-06-20 02:51:01', '2026-07-03 02:16:13'),
	(2, 1, 1, 12, 'Naila Yasmine Anggraini', '230518100', 'Kepala Departement', 'kepala_departemen', 1, 'uploads/photos/FLCgHNwImtzQAQka0b7ZGQolsphyf2HsKIb4Dgfq.webp', 47, '2026-06-20 03:41:25', '2026-07-03 02:16:13'),
	(3, 1, 1, 12, 'Anastasia Azzahra Pane', '-', 'Staff', 'staff', 0, 'uploads/photos/EUC9d9lLMoxcpAff338HFOUEOy3lmruOb3MwkAlB.webp', 48, '2026-06-20 03:42:30', '2026-07-03 02:16:13'),
	(4, 1, 1, 12, 'Fathiyyah Mufidah', '-', 'Staff', 'staff', 0, 'uploads/photos/kE5uoRv01WDuSqEe0RxahbuGFoarKKKzBY5buyTr.webp', 49, '2026-06-20 03:42:46', '2026-07-03 02:16:13'),
	(5, 1, 1, 12, 'Siti Sisfany Huda Aini', '-', 'Staff', 'staff', 0, 'uploads/photos/OLHlAM6dTSj2tP79uwll0yLj2nyaVAA1Sb5qcKqJ.webp', 49, '2026-06-20 03:43:02', '2026-07-03 02:16:13'),
	(6, 1, 2, NULL, 'Shintya Pratiwi Batubara', '2305181000', 'Menteri', 'menteri', 0, 'uploads/photos/noCNbIJIiH6Qu2yTb0HqePpBdh1tMuQ9xuc6B5lN.webp', 1, '2026-06-20 03:51:30', '2026-07-03 02:16:14'),
	(7, 1, 7, 9, 'M. Hariansyah', '-', 'Kepala Departement', 'kepala_departemen', 1, 'uploads/photos/LLxOrlrswuEKALkByhZRLo4csIciDVxbaera0yhm.webp', 30, '2026-06-20 03:52:06', '2026-07-03 02:16:14'),
	(8, 1, 7, 9, 'Ared Parsaulian Manalu', '1', 'Staff', 'staff', 0, 'uploads/photos/zDsQyQwAY5dMI12NQEhpIZcDj5cuyqWiqsZWe4xv.webp', 36, '2026-06-20 03:52:20', '2026-07-03 02:16:14'),
	(9, 1, 7, 9, 'Arjuna Hamdani Anugrah', '1', 'Staff', 'staff', 0, 'uploads/photos/xp0vFljSifBM5oLdzeKtvuaDwNsHylq6KQpL88l3.webp', 35, '2026-06-20 03:52:34', '2026-07-03 02:16:14'),
	(10, 1, 7, 9, 'Pintor Pasaribu', '1', 'Staff', 'staff', 0, 'uploads/photos/Jg3iXYifgL2YKZDATHrPqvx9bgXU5U1linqfHUHo.webp', 34, '2026-06-20 03:52:46', '2026-07-03 02:16:14'),
	(11, 1, 7, 9, 'Fahdia Natama Akbar', '1', 'Staff', 'staff', 0, 'uploads/photos/9vDpk1b1nos2maMXKYjliR1w4SE8a76uPBnzJrMv.webp', 33, '2026-06-20 03:53:04', '2026-07-03 02:16:14'),
	(12, 1, 3, 3, 'Umi Nurfadilah Siregar', NULL, 'Kepala Departemen', 'kepala_departemen', 1, 'uploads/photos/6a4f12d6d7c3d.webp', 6, '2026-06-20 03:56:10', '2026-07-08 20:17:43'),
	(13, 1, 3, NULL, 'Syafitri Uswatun Hasanah', '2305181000', 'Menteri', 'menteri', 0, 'uploads/photos/6a4f12bebc6e7.webp', 2, '2026-06-20 03:56:48', '2026-07-08 20:17:19'),
	(14, 1, 3, 3, 'Alya Nasyani Purba', '2305181000', 'Staff', 'staff', 0, 'uploads/photos/SuPWpYpH1I4cVHFIOcIzxYMpphkZl6dEIxyuinU4.webp', 8, '2026-06-20 03:57:10', '2026-07-03 02:16:15'),
	(15, 1, 3, 3, 'Arruna Qirani', '2305181000', 'Staff', 'staff', 0, 'uploads/photos/TTH5JJuxtAbqG5fcQniJns0tuKelWIdzUjVZZ87p.webp', 7, '2026-06-20 03:57:24', '2026-07-03 02:16:15'),
	(16, 1, 3, 4, 'Yosa Brigitta Caroline S', '-', 'Kepala Departemen', 'kepala_departemen', 1, 'uploads/photos/r2rCXquZVTQPPUzW6tsldLIxtfIRtFq0x0BdO7zL.webp', 9, '2026-06-20 03:57:46', '2026-07-03 02:16:15'),
	(17, 1, 3, 4, 'Inayah Ranitya', '2305181000', 'Staff', 'staff', 0, 'uploads/photos/WOAmap05Ob18AQZeGRX8HbppRYKYVYIfBJYbOulo.webp', 10, '2026-06-20 03:58:26', '2026-07-03 02:16:15'),
	(18, 1, 3, 4, 'Roberto Siboro', '2305181000', 'Staff', 'staff', 0, 'uploads/photos/N3LmTpRboLdrb9o5QKWUe20Oz9znGEn2GAAJxdFx.webp', 11, '2026-06-20 03:58:39', '2026-07-03 02:16:16'),
	(19, 1, 3, 4, 'Sarah Filadelfia Naibaho', '2305181000', 'Staff', 'staff', 0, 'uploads/photos/QBjddE16945WzWdcNQlCqwGHu8sw7kHXiZW3bOQI.webp', 12, '2026-06-20 03:58:50', '2026-07-03 02:16:16'),
	(20, 1, 3, 4, 'Antonius Defael Ginting', '2305181000', 'Staff', 'staff', 0, 'uploads/photos/bVJ4S2stLu9QoCwq24GVOvNOwDFApdpD9rs0AsNM.webp', 13, '2026-06-20 03:59:01', '2026-07-03 02:16:16'),
	(22, 1, 7, NULL, 'Margaretha Yolanda Florencia Siagian', '1', 'Menteri', 'menteri', 0, 'uploads/photos/EwhstOgt0M0GxdFV4jcxlbbXifJedhLo1nGPUotE.webp', 6, '2026-06-27 02:38:17', '2026-07-03 02:16:16'),
	(23, 1, 2, 2, 'Dita Liana', '-', 'Kepala Departemen', 'kepala_departemen', 1, 'uploads/photos/Vc2VxiovYisLQx1AA6KdJrZnfA2SYGYUKCDyFUCF.webp', 1, '2026-06-28 01:32:51', '2026-07-03 02:16:16'),
	(24, 1, 2, 2, 'Flora Anggraini', '-', 'Staf', 'staff', 0, 'uploads/photos/PqcQIjYK2ltUGrZ3iMVp36KTPMMNcNuNemeKlKCd.webp', 2, '2026-06-28 01:35:04', '2026-07-03 02:16:16'),
	(25, 1, 2, 2, 'Andriyani Putri', '-', 'Staf', 'staff', 0, 'uploads/photos/hwmT8PLMCPTmEmYMbrLodWuBcMvGwPcoRz8u7PmG.webp', 3, '2026-06-28 01:38:03', '2026-07-03 02:16:17'),
	(26, 1, 2, 2, 'Celsy Sijabat', '-', 'Staf', 'staff', 0, 'uploads/photos/s4bydhx4ZuOuSg9sv6YGeJteki3ti7I4suNeDsuN.webp', 4, '2026-06-28 01:39:28', '2026-07-03 02:16:17'),
	(27, 1, 2, 2, 'Tiara Suci Lestari', '-', 'Staf', 'staff', 0, 'uploads/photos/JN4ndg9d4148xYcad7NMiHxK7CRksZAbfzLRMCEA.webp', 5, '2026-06-28 01:40:30', '2026-07-03 02:16:17'),
	(28, 1, 4, NULL, 'Shobihah Wulan Agustina', '-', 'Menteri', 'menteri', 0, 'uploads/photos/tOSoVXxB2VRUxTUl2J4Ex0Knv9SFxQSo3vrCAhlD.webp', 3, '2026-06-30 20:02:01', '2026-07-03 02:16:17'),
	(29, 1, 4, 5, 'Tongku Guru Siregar', '-', 'Kepala Departemen', 'kepala_departemen', 1, 'uploads/photos/zAVp2p4FpGIVBZXpLIXZLNb5KvNwh6oCUbpOI2tz.webp', 12, '2026-06-30 20:02:45', '2026-07-03 02:16:17'),
	(30, 1, 4, 5, 'Bagendro sihombing', '-', 'Staf', 'staff', 0, 'uploads/photos/yMjHAn2a9696oo5dG8yPevqBRc33mmwC2EcGBCQY.webp', 15, '2026-06-30 20:03:24', '2026-07-03 02:16:17'),
	(31, 1, 4, 5, 'Darius Simangunsong', '-', 'Staf', 'staff', 0, 'uploads/photos/bndPqouhxffdYHZPcmM6EVFF7vr8dDJeAb8EaKrP.webp', 15, '2026-06-30 20:03:51', '2026-07-03 02:16:18'),
	(32, 1, 4, 5, 'Elfrida Lenta Manalu', '-', 'Staf', 'staff', 0, 'uploads/photos/J1sKeezJfYfbnDXuFpvLnThytbe1PdDHMFMq81cz.webp', 16, '2026-06-30 20:05:01', '2026-07-03 02:16:18'),
	(33, 1, 4, 5, 'Magdalena Siregar', '-', 'Staf', 'staff', 0, 'uploads/photos/oGTUv8wYmXdN00wnQbxf4ackhAZ4IMgUrESSyj1K.webp', 17, '2026-06-30 20:05:30', '2026-07-03 02:16:18'),
	(34, 1, 4, 5, 'Rara Triya Amanda', '-', 'Staf', 'staff', 0, 'uploads/photos/shnhdUblHwOPh5toHwbnXmmIy1gmDuxy3r6xY0Jw.webp', 18, '2026-06-30 20:06:17', '2026-07-03 02:16:18'),
	(35, 1, 5, NULL, 'RIFKI ATHAR PASARIBU', '-', 'Menteri', 'menteri', 0, 'uploads/photos/ikN0CdOptyNmIvyno2cyMe6BdrXb2IRaTGcZRzWh.webp', 4, '2026-06-30 20:17:41', '2026-07-03 02:16:18'),
	(36, 1, 5, 6, 'Chintya Amanda', '-', 'Kepala Departemen', 'kepala_departemen', 1, 'uploads/photos/jBH2S0huVSpnS2hp0m5RkvU7X5WJSEyv3rwt2yN3.webp', 19, '2026-06-30 20:18:14', '2026-07-03 02:16:18'),
	(37, 1, 5, 6, 'Fadel Suyaga', '-', 'Staf', 'staff', 0, 'uploads/photos/7kJhySgMa55tPBH4sxTFIla13I0yOSRiegAphkgn.webp', 20, '2026-06-30 20:19:06', '2026-07-03 02:16:19'),
	(38, 1, 5, 6, 'risa liana nababan', '-', 'Staf', 'staff', 0, 'uploads/photos/HgnIZQsRoulY9sSAj1gZik7w9VjGoQ39e2laoi2e.webp', 21, '2026-06-30 20:19:34', '2026-07-03 02:16:19'),
	(39, 1, 5, 6, 'nayaka rifqi', '-', 'Staf', 'staff', 0, 'uploads/photos/tq2Jn8cn2rVF50bxxbIvd2eZsaLhUFdzSv837Ygh.webp', 22, '2026-06-30 20:20:04', '2026-07-03 02:16:19'),
	(40, 1, 5, 7, 'Tengku Muhammad Taufiq', '-', 'Kepala Departemen', 'kepala_departemen', 1, 'uploads/photos/a0nbrd5MarDlW6THufUu68WlLI4IwfmZNCLeCxbE.webp', 23, '2026-06-30 20:20:48', '2026-07-03 02:16:19'),
	(41, 1, 5, 7, 'Faiz Ramadhani Munthe', '-', 'Staf', 'staff', 0, 'uploads/photos/1l7Hm5En2PJTyatAdAT9udW9CkCXGN5p1xQGKKrp.webp', 24, '2026-06-30 20:22:03', '2026-07-03 02:16:19'),
	(42, 1, 5, 7, 'Rivi An-na Zahra', '-', 'Staf', 'staff', 0, 'uploads/photos/cBXPpW00yxCPDaQUzfoXaORI95kvrryHlzaK55bb.webp', 25, '2026-06-30 20:22:36', '2026-07-03 02:16:19'),
	(43, 1, 5, 7, 'Soni Anggara', '-', 'Staf', 'staff', 0, 'uploads/photos/rVYVHUyjLgPckpDl45g51yNksPPMPJJ5sSTRC1Yy.webp', 26, '2026-06-30 20:23:05', '2026-07-03 02:16:20'),
	(44, 1, 6, NULL, 'Quratu Aini', '-', 'Menteri', 'menteri', 0, 'uploads/photos/uM7bZ42ymvXaimPC9vUtO7bt1XfBQbbPZ0id7CG5.webp', 5, '2026-06-30 20:23:47', '2026-07-03 02:16:20'),
	(45, 1, 6, 8, 'Tio Enjelina Wardani Simbolon', '-', 'Kepala Departemen', 'kepala_departemen', 1, 'uploads/photos/NoX5wdAOfccQGAJx8dG96nZBGO73H5Cwd1Cz0b1i.webp', 27, '2026-06-30 20:37:45', '2026-07-03 02:16:20'),
	(46, 1, 6, 8, 'Nurul Inayah', '-', 'Staf', 'staff', 0, 'uploads/photos/1mEa7NGZnyXn36O951LKKfyDZ0JvfEOz63kc5FUM.webp', 28, '2026-06-30 20:38:14', '2026-07-03 02:16:20'),
	(47, 1, 6, 8, 'Ruth Heldina Sibarani', '-', 'Staf', 'staff', 0, 'uploads/photos/AMvAdnFo6fFCAIFhRaLYH9ZCLuvq4MBqUKeRSXqy.webp', 29, '2026-06-30 20:39:05', '2026-07-03 02:16:20'),
	(48, 1, 6, 8, 'Mikha Putra Nadin Pasaribu', '-', 'Staf', 'staff', 0, 'uploads/photos/RkSBL20Jh3MixYwJmiTAE6vxRACGh1lQM2XUzAN1.webp', 30, '2026-06-30 20:39:31', '2026-07-03 02:16:20'),
	(50, 1, 6, 8, 'Gabriel Herbert Jonathan Gurning', '-', 'Staf', 'staff', 0, 'uploads/photos/1GDkEjvYqVgFRYANlbngYoVmElDWIC19S13RSUgo.webp', 31, '2026-06-30 20:40:23', '2026-07-03 02:16:21'),
	(51, 1, 8, NULL, 'Walid Fardan Harjoyudanto', '-', 'Menteri', 'menteri', 0, 'uploads/photos/wOHotxdrkZgGjK06XNZlIGDZFsAMC5ENQXCiZR3e.webp', 7, '2026-06-30 20:47:05', '2026-07-03 02:16:21'),
	(52, 1, 8, 10, 'Fencia emmanuela pasaribu', '-', 'Kepala Departemen', 'kepala_departemen', 1, 'uploads/photos/4iVzse5Fpu8nKKrjoOg3d1URBnIy05YhrSkKEK1G.webp', 37, '2026-06-30 20:47:57', '2026-07-03 02:16:21'),
	(53, 1, 8, 10, 'Angel Irsani Sinulingga', '-', 'Staf', 'staff', 0, 'uploads/photos/G56vFMBgJPcBm4ndZ2m59Xwsqe4zXubf8QRuAuhU.webp', 38, '2026-06-30 20:48:22', '2026-07-03 02:16:21'),
	(54, 1, 8, 10, 'Moreno Aulya Septyawan', '-', 'Staf', 'staff', 0, 'uploads/photos/JlbtOQRhq1W0kkvTUUYJtHlnfTppUEFsFGtw3kJ0.webp', 39, '2026-06-30 20:48:59', '2026-07-03 02:16:21'),
	(55, 1, 8, 10, 'Dzaky Alvansyah Daulay', '-', 'Staf', 'staff', 0, 'uploads/photos/uxksXg6QTKF1I4it91SQDorocZ6bFilZPiO0WAlm.webp', 39, '2026-06-30 20:49:23', '2026-07-03 02:16:21'),
	(56, 1, 8, 10, 'Qc putri retno ningtyas', '-', 'Staf', 'staff', 0, 'uploads/photos/dS5dq7PwHDDLeUB9KHnakzqxCDvGj8fQhtIcZcR6.webp', 41, '2026-06-30 20:49:52', '2026-07-03 02:16:22'),
	(57, 1, 8, 11, 'Ardian Gustiaviano Simanjuntak', '-', 'Kepala Departemen', 'kepala_departemen', 1, 'uploads/photos/3qOxxSerJ0MFU3MkMTnvl5x8EPa3kA7ItFIdSoRw.webp', 42, '2026-06-30 20:52:54', '2026-07-03 02:16:22'),
	(58, 1, 8, 11, 'M Akbar Kadafi', '-', 'Staf', 'staff', 0, 'uploads/photos/kZstrTkkGdeuolpua1W0aahQxqMYLZ55hqYj8LGC.webp', 43, '2026-06-30 20:53:19', '2026-07-03 02:16:22'),
	(59, 1, 8, 11, 'Rumintang Siahaan', '-', 'Staf', 'staff', 0, 'uploads/photos/NjGGMvKiktJSFgFisw3dYZzgjUi9YQZCJI7mqzsv.webp', 44, '2026-06-30 20:54:19', '2026-07-03 02:16:22'),
	(60, 1, 8, 11, 'Nadya N Akbar', '-', 'Staf', 'staff', 0, 'uploads/photos/WqHrvC6kngjFBqrez3S3L5ClxqUJKuvOAiWtqMbT.webp', 45, '2026-06-30 20:54:54', '2026-07-03 02:16:22'),
	(61, 1, 8, 11, 'Gabriella arta tampubolon', '-', 'Staf', 'staff', 0, 'uploads/photos/JQVpnEJ0EiuDgCGdkOWJ8gn7uOXvFCyigMZWJSMz.webp', 45, '2026-06-30 20:55:21', '2026-07-03 02:16:22');

-- Dumping data for table bem_polmed.migrations: ~15 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2024_01_01_000001_create_users_table', 1),
	(2, '2024_01_01_000002_create_management_years_table', 1),
	(3, '2024_01_01_000003_create_ministries_table', 1),
	(4, '2024_01_01_000004_create_departments_table', 1),
	(5, '2024_01_01_000005_create_members_table', 1),
	(6, '2024_01_01_000006_create_programs_table', 1),
	(7, '2024_01_01_000007_create_posts_table', 1),
	(8, '2024_01_01_000008_create_activities_table', 1),
	(9, '2024_01_01_000009_create_letter_formats_table', 1),
	(10, '2024_01_01_000010_create_media_partners_table', 1),
	(11, '2024_01_01_000011_create_site_settings_table', 1),
	(12, '2024_01_01_000012_create_assets_table', 1),
	(13, '2026_06_19_113708_create_sessions_table', 2),
	(14, '2024_01_01_000013_add_filosofi_and_warna_to_management_years', 3),
	(15, '2024_01_01_000014_add_image_to_programs', 4),
	(16, '2026_07_03_091329_create_cache_table', 5),
	(17, '2026_07_08_021008_add_session_id_to_users_table', 6),
	(18, '2026_07_09_021637_add_reset_otp_to_users_table', 7),
	(19, '2026_07_10_230454_create_kegiatan_table', 8),
	(20, '2026_07_10_230514_create_sie_table', 8),
	(21, '2026_07_10_230527_create_bon_table', 8),
	(22, '2026_07_10_230538_create_item_table', 8),
	(23, '2026_07_11_113852_create_item_lpjs_table', 8),
	(24, '2026_07_12_073722_add_user_role_to_table', 9);

-- Dumping data for table bem_polmed.ministries: ~8 rows (approximately)
INSERT INTO `ministries` (`id`, `management_year_id`, `name`, `alias`, `logo_path`, `description`, `tugas_pokok`, `whatsapp_number`, `whatsapp_label`, `sort_order`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Media dan Transformasi Digital', 'Kemendigi', 'uploads/logos/GnmH14nzM0m9qDWmc9WmCF4iuFa0IuTENfvQJ9Vg.webp', 'Kementerian Media dan Transformasi Digital adalah kementerian dalam Badan Eksekutif Mahasiswa (BEM) yang berperan dalam membuat dan mengelola konten digital seperti website, media sosial, video dan grafis guna menyampaikan informasi penting kepada mahasiswa dan publik. Juga bertugas untuk memanfaatkan teknologi digital dalam mendukung komunikasi yang efektif meningkatkan keterlibatan mahasiswa, serta memperkenalkan kegiatan-kegiatan BEM dan organisasi lainnya melalui media digital.', 'GO DIGITAL\r\n\r\nPlatform website yang bertujuan untuk memperkenalkan segala hal tentang BEM, memudahkan kema Polmed dalam melihat jadwal peminjaman tempat dan barang untuk kebutuhan kegiatan, serta menyediakan format surat-menyurat.', 'belum ada', NULL, 8, '2026-06-20 02:45:22', '2026-07-03 02:16:22'),
	(2, 1, 'Kesekretariatan', 'Kemensek', 'uploads/logos/MpJCeUnUbH0WNIc1gVCgRuYNLIBzWyeEKpQCB5o2.webp', 'Kementerian Kesekretariatan adalah kementerian dalam Badan Eksekutif Mahasiswa (BEM) yang bertanggung jawab mengelola administrasi BEM, proposal, LPJ, surat menyurat, kegiatan notulensi dan lainnya serta bertugas menjalankan administrasi seluruh keperluan anggota. Berfungsi mengkoordinasi UKM & Himpunan Mahasiswa Program Studi.', NULL, NULL, NULL, 1, '2026-06-20 03:45:12', '2026-07-03 02:16:22'),
	(3, 1, 'Keuangan & Kewirausahaan', 'Kemenkeu', 'uploads/logos/PYEF00QOBg69Pvy12FJ71ZcEWiEdry1CV4KtuTeL.png', 'Kementerian Keuangan dan Kewirausahaan adalah kementerian dalam Badan Eksekutif Mahasiswa (BEM) yang bertanggung jawab atas pengelolaan keuangan dalam organisasi BEM POLMED. Berperan dalam perencanaan anggaran, pencatatan transaksi, pengawasan penggunaan dana, penyusunan laporan keuangan guna memastikan transparansi dan akuntabilitas dalam setiap kegiatan BEM juga berperan dalam mendukung kewirausahaan kreatif, meningkatkan literasi ekonomi kreatif, dan menyelenggarakan event inovatif.', NULL, NULL, NULL, 2, '2026-06-20 03:49:24', '2026-07-05 18:14:50'),
	(4, 1, 'Dalam Negeri', 'Kemendagri', 'uploads/logos/WO9mzd09xN1FZy2KZDdNJEvERlqcBBCM4GT2q8yT.png', 'adalh', 'adalh', NULL, NULL, 3, '2026-06-25 07:30:04', '2026-07-03 02:20:48'),
	(5, 1, 'Pengembangan Sumber Daya Mahasiswa', 'KemenPSDM', 'uploads/logos/vtCXgRlHRRWGKvr7YnRF9IoqV95xVrzqGpx2UqCL.png', 'qw', 'qw', NULL, NULL, 4, '2026-06-25 07:33:00', '2026-07-03 02:21:03'),
	(6, 1, 'Advokasi Kesejahteraan Mahasiswa', 'Adkesma', 'uploads/logos/Hb4dHRqu4RApA6ypZrCZOGB0Yht1uLrABcaQ6FWk.png', 'ad', 'as', NULL, NULL, 5, '2026-06-25 07:34:42', '2026-07-03 02:21:13'),
	(7, 1, 'Kajian Aksi Strategis', 'Kemenkastrat', 'uploads/logos/G9Y14D2sMzqP4yHk9Oi55QGkDHiKKSgjt1ScDP4v.png', 'qw', 'qw', NULL, NULL, 6, '2026-06-25 07:38:56', '2026-07-03 02:21:30'),
	(8, 1, 'Luar Negeri', 'Kemenlurgi', 'uploads/logos/nMDnEsK0gdhvmYNKdbwh6NMJabOrvCbd5h2NvIAI.png', 'weq', 'wq', NULL, NULL, 7, '2026-06-25 07:39:41', '2026-07-03 02:21:39');

-- Dumping data for table bem_polmed.posts: ~1 rows (approximately)
INSERT INTO `posts` (`id`, `author_id`, `title`, `slug`, `content`, `featured_image`, `status`, `category`, `published_at`, `created_at`, `updated_at`) VALUES
	(3, 1, 'Lantang', 'lantang', 'ghfg', NULL, 'published', 'kegiatan', '2026-06-29 01:29:19', '2026-06-29 01:29:02', '2026-06-29 01:29:19');

-- Dumping data for table bem_polmed.programs: ~0 rows (approximately)
INSERT INTO `programs` (`id`, `department_id`, `name`, `description`, `execution_date`, `status`, `sort_order`, `created_at`, `updated_at`) VALUES
	(3, 5, 'Pola', NULL, '2026-07-02', 'akan_dilaksanakan', 1, '2026-06-30 21:03:05', '2026-06-30 21:03:05');

-- Dumping data for table bem_polmed.sessions: ~9 rows (approximately)
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('5R3Z0TWXYrvPMdxFelorq8C8Jja1XEcVIVPD8l12', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJWdlV2T1J6OExFMTk0TGZRVDNqZjNCWEhpVkdTMXFYejdxdzFtSDdRIiwiX2ZsYXNoIjp7Im5ldyI6W10sIm9sZCI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2xwalwvMlwvcmFiIiwicm91dGUiOiJscGouc2hvdyJ9LCJhZG1pbl9sb2dnZWRfaW4iOnRydWUsImFkbWluX2lkIjo0LCJhZG1pbl9uYW1lIjoiRmFkZWwiLCJhZG1pbl9lbWFpbCI6ImRpendhcmFAZ21haWwuY29tIiwiYWRtaW5fcm9sZSI6ImFkbWluIn0=', 1784308712),
	('8CPJ0LqvON1mqjpKX5YOfGoobHT75oalCidCa5M8', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJzSkMwTVN1Z2pKSFYxWklJWmVFejc1eFlzazZVaGhSU210VGxsVlVhIiwiX2ZsYXNoIjp7Im5ldyI6W10sIm9sZCI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2xvZ2luIiwicm91dGUiOiJhZG1pbi5sb2dpbiJ9fQ==', 1784308381),
	('GYwLnbirGHcVgSvF81qyzjmAYjNjjuRkAkqTSK51', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJ5b2pTVFZUbENRRjcxbk94Ynh4RjMyOEpxMGRBMmljcHJNeVZ0YzVMIiwiX2ZsYXNoIjp7Im5ldyI6W10sIm9sZCI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2xvZ2luIiwicm91dGUiOiJhZG1pbi5sb2dpbiJ9fQ==', 1784324153),
	('Ko8qoVZ5zltnFcyueMiSDEtxM94XIEX6O28H9wQR', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJ6Z3lnN2VsRDNjVHpTZHk1Z292eFZhRjh6QkNmaVpqTXlXbEVSdzEyIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9sb2dpbiIsInJvdXRlIjoiYWRtaW4ubG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1784307804),
	('maEOqEaHMbLaaARxFIxyqRif6m0DGrDDb7iYkK6R', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJmVHZxcjFqMlZxRll4V3dmMm5YZkNDTUltT09XRnpzbHk4SjVZcVA0IiwiX2ZsYXNoIjp7Im5ldyI6W10sIm9sZCI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2xwalwvMlwvcmFiIiwicm91dGUiOiJscGouc2hvdyJ9LCJhZG1pbl9sb2dnZWRfaW4iOnRydWUsImFkbWluX2lkIjo0LCJhZG1pbl9uYW1lIjoiRmFkZWwiLCJhZG1pbl9lbWFpbCI6ImRpendhcmFAZ21haWwuY29tIiwiYWRtaW5fcm9sZSI6ImFkbWluIn0=', 1784306448),
	('pPJYXpKqxVgK1ghXebLtDw93T4fgJLNcvd0JA2kJ', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJQajJoTXlJMkR1d0ZidVlkWElYaGlZdnpqdEVWWm51TDNRUW1KTUM3IiwiX2ZsYXNoIjp7Im5ldyI6W10sIm9sZCI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2xvZ2luIiwicm91dGUiOiJhZG1pbi5sb2dpbiJ9fQ==', 1784300744),
	('sMmLkeeJr3tTceJeXquPVSUYhQFAok86YNyDcyjg', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJVMERtQ25jdFFXU0llOEx5SVZUR05FbFhKQ2FvbFJKQVZRb0VvemZBIiwiX2ZsYXNoIjp7Im5ldyI6W10sIm9sZCI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2xwalwvMlwvZXhwb3J0LWV4Y2VsIiwicm91dGUiOiJscGouZXhwb3J0LmV4Y2VsIn0sImFkbWluX2xvZ2dlZF9pbiI6dHJ1ZSwiYWRtaW5faWQiOjQsImFkbWluX25hbWUiOiJGYWRlbCIsImFkbWluX2VtYWlsIjoiZGl6d2FyYUBnbWFpbC5jb20iLCJhZG1pbl9yb2xlIjoiYWRtaW4ifQ==', 1784325059),
	('vAxVVVWRzRK8tzXdtEX3ES6QZpFczC02PoSCMNp0', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJpR1dEN2k2MFRjUVJFYVZjTWFTMWZQVFl2Q3NVRHRLVUR3YUFlNXBBIiwiX2ZsYXNoIjp7Im5ldyI6W10sIm9sZCI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2xwalwvYm9uXC91bmRlZmluZWRcL2RldGFpbCIsInJvdXRlIjoiQm9uLmRldGFpbCJ9LCJhZG1pbl9sb2dnZWRfaW4iOnRydWUsImFkbWluX2lkIjo0LCJhZG1pbl9uYW1lIjoiRmFkZWwiLCJhZG1pbl9lbWFpbCI6ImRpendhcmFAZ21haWwuY29tIiwiYWRtaW5fcm9sZSI6ImFkbWluIn0=', 1784300037),
	('Z3q8SJXO7z2mGjl7uNzHgxhvmEAeb7N9CUvyBBaj', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJrSTZoOUYzd2h5SmRpejJzUWEzUlVVM0ltdTdpVFNIWFJIQXlqRGNVIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9scGpcLzJcL3JhYiIsInJvdXRlIjoibHBqLnNob3cifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJhZG1pbl9sb2dnZWRfaW4iOnRydWUsImFkbWluX2lkIjoxLCJhZG1pbl9uYW1lIjoiU3VwZXIgQWRtaW4iLCJhZG1pbl9lbWFpbCI6ImFkbWluQGJlbXBvbG1lZC5hYy5pZCIsImFkbWluX3JvbGUiOiJzdXBlcl9hZG1pbiJ9', 1784308205);

-- Dumping data for table bem_polmed.sie: ~0 rows (approximately)
INSERT INTO `sie` (`ID_Sie`, `ID_Kegiatan`, `Nama_Sie`, `created_at`, `updated_at`) VALUES
	(2, 2, 'Konsumsi', '2026-07-17 07:01:22', '2026-07-17 07:01:22'),
	(3, 2, 'Pdd', '2026-07-17 07:01:22', '2026-07-17 07:01:22'),
	(4, 2, 'Ptt', '2026-07-17 07:01:22', '2026-07-17 07:01:22');

-- Dumping data for table bem_polmed.site_settings: ~8 rows (approximately)
INSERT INTO `site_settings` (`id`, `key`, `value`, `type`, `label`, `created_at`, `updated_at`) VALUES
	(1, 'sekretariat_address', 'Jalan Almamater No. 1 Kampus USU, Padang Bulan, Medan Baru, Sumatera Utara 20155', 'textarea', 'Alamat Sekretariat', '2026-06-19 04:35:06', '2026-06-19 04:35:06'),
	(2, 'facebook_url', '#', 'url', 'URL Facebook', '2026-06-19 04:35:06', '2026-06-19 04:35:06'),
	(3, 'instagram_url', '#', 'url', 'URL Instagram', '2026-06-19 04:35:06', '2026-06-19 04:35:06'),
	(4, 'tiktok_url', '#', 'url', 'URL TikTok', '2026-06-19 04:35:06', '2026-06-19 04:35:06'),
	(5, 'youtube_url', '#', 'url', 'URL YouTube', '2026-06-19 04:35:06', '2026-06-19 04:35:06'),
	(6, 'twitter_url', '#', 'url', 'URL Twitter/X', '2026-06-19 04:35:06', '2026-06-19 04:35:06'),
	(7, 'whatsapp_url', '#', 'url', 'URL WhatsApp', '2026-06-19 04:35:06', '2026-06-19 04:35:06'),
	(8, 'about_bem', 'Badan Eksekutif Mahasiswa (BEM) adalah lembaga eksekutif dalam struktur pemerintahan mahasiswa.', 'textarea', 'Tentang BEM', '2026-06-19 04:35:06', '2026-06-19 04:35:06');

-- Dumping data for table bem_polmed.users: ~4 rows (approximately)
INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `role`, `is_active`, `last_login`, `session_id`, `remember_token`, `reset_otp`, `reset_otp_expires_at`, `created_at`, `updated_at`) VALUES
	(1, 'Super Admin', 'admin@bempolmed.ac.id', '$2y$12$JMDK.JECt2c/np9cy4XvLO8RFifxQbFe8x7LkIO4e73OSGeqQxBYe', 'super_admin', 1, '2026-07-17 10:04:29', 'Z3q8SJXO7z2mGjl7uNzHgxhvmEAeb7N9CUvyBBaj', NULL, NULL, NULL, '2026-06-19 04:35:06', '2026-07-17 10:04:29'),
	(2, 'fadel', 'fadel@gmail.com', '$2y$12$P2J0N1XMafm86TS6SoZMHO0shnpB3xMNQI5M.5bJlyAB/OyUsXbxC', 'admin', 1, '2026-07-03 18:55:53', NULL, NULL, NULL, NULL, '2026-07-03 18:53:10', '2026-07-08 01:16:19'),
	(3, 'fadel', 'fadel.dizwara@gmail.com', '$2y$12$iwh.FqbrfR2aQunI0ahubuh9jaaRSled4gnA/YCdAaSvTZnYnysKu', 'admin', 1, '2026-07-12 00:52:11', 'pvsbEmZnrs1RSVHD95AcoVA2S58r8zQQWg3EO3aK', NULL, NULL, NULL, '2026-07-08 19:48:04', '2026-07-12 00:52:11'),
	(4, 'Fadel', 'dizwara@gmail.com', '$2y$12$gnA08HByfASSQ.xXoiXMiO1OmZwwducz/f5KCUwaPwLR3Cyrghx9C', 'admin', 1, '2026-07-17 14:36:06', 'sMmLkeeJr3tTceJeXquPVSUYhQFAok86YNyDcyjg', NULL, NULL, NULL, '2026-07-17 00:43:52', '2026-07-17 14:36:06');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
