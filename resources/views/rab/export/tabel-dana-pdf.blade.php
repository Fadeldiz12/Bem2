@php
    use App\Helpers\LaporanDana;

    // Kolom "gabungan" (No/Jenis per Sie, Total Kwitansi per bon) sengaja TIDAK memakai rowspan:
    // dompdf membuang sel rowspan lanjutan saat tabel pindah halaman sehingga kolom bergeser.
    // Sebagai gantinya tiap baris punya selnya sendiri dan garis di dalam grup dihilangkan.
    $barisLabel = fn (int $i, int $n) => $i === intdiv($n, 2);
    $labelGenap = fn (int $i, int $n) => $barisLabel($i, $n) && $n % 2 === 0;
    $kelasGrup = fn (int $i, int $n) => trim(
        ($i > 0 ? 'lanjut-atas ' : '') .
        ($i < $n - 1 ? 'lanjut-bawah ' : '') .
        ($labelGenap($i, $n) ? 'label-genap' : '')
    );

    // Data & tata letak dari App\Helpers\LaporanDana::pengeluaran() (LPJ) / anggaran() (Proposal)
    $lebarKolom = $laporan['lebarKolom'];
    $jumlahKolom = count($lebarKolom);
    $kolomKwitansi = $laporan['kolomKwitansi'];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $kegiatan->Nama_Kegiatan }}</title>
    <style>
        @page { margin: 2.5cm 2.5cm 2.5cm 3cm; }
        body { margin: 0; font-family: "Times New Roman", Times, serif; font-size: 9pt; color: #000; }

        .judul { margin: 0 0 8pt 0; font-size: 12pt; font-weight: bold; }
        .subjudul { margin: 0 0 9pt 21pt; font-size: 12pt; font-weight: bold; }

        table.pengeluaran {
            width: 390pt; margin-left: {{ $laporan['indentTabel'] }}pt;
            border-collapse: collapse; table-layout: fixed;
        }
        .pengeluaran td {
            border: 0.75pt solid #000; padding: 1.5pt 2pt; height: 16pt;
            text-align: center; vertical-align: middle; line-height: 1.1;
        }
        .pengeluaran tr { page-break-inside: avoid; }
        /* thead diulang dompdf di tiap halaman: dipakai sebagai garis atas tabel di halaman lanjutan */
        .pengeluaran thead td { height: 0; padding: 0; border: none; border-bottom: 0.75pt solid #000; }

        .kepala td { background-color: #6A1B9A; color: #FFFFFF; font-weight: bold; }
        .grup, .subtotal td { background-color: #E3D4F7; }
        .subtotal td { font-weight: bold; }
        .total td { background-color: #6A1B9A; color: #FFFFFF; font-weight: bold; }

        .pengeluaran td.lanjut-atas { border-top: none; }
        .pengeluaran td.lanjut-bawah { border-bottom: none; }
        /* Grup berjumlah genap: label ada di baris tengah-bawah lalu dinaikkan setengah baris
           supaya jatuh di tengah grup (dinaikkan, bukan diturunkan, agar tidak tertutup
           background baris berikutnya yang digambar belakangan). */
        .pengeluaran td.label-genap { vertical-align: top; }
        .label-genap span { position: relative; top: -7pt; }
        /* Baris berlabel genap tidak boleh jadi baris pertama di halaman baru (label naik ke atas tabel) */
        .pengeluaran tr.tahan { page-break-before: avoid; }
        .subtotal td.grup { font-weight: normal; }

        .terbilang {
            margin: 14pt 0 0 {{ $laporan['indentTerbilang'] }}pt;
            font-size: 12pt; line-height: 1.5; text-align: justify;
        }
    </style>
</head>
<body>
    @foreach ($laporan['judul'] as $i => $judul)
        <p class="{{ $i === 0 ? 'judul' : 'subjudul' }}">{{ $judul }}</p>
    @endforeach

    <table class="pengeluaran">
        <thead>
            <tr>
                @foreach ($lebarKolom as $lebar)
                    <td style="width: {{ $lebar }}%"></td>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr class="baris kepala">
                <td>No</td>
                <td>Jenis Pengeluaran</td>
                <td>Keterangan</td>
                <td>Qty</td>
                <td>Satuan</td>
                <td>Harga/Unit (@)</td>
                <td>Total</td>
                @if ($kolomKwitansi)
                    <td>Total Kwitansi</td>
                @endif
            </tr>

            @foreach ($laporan['sies'] as $sie)
                @php
                    $barisSie = $sie['jumlah_item'] + 1; // + baris SUBTOTAL
                    $r = 0;
                @endphp

                @foreach ($sie['kwitansi'] as $kwitansi)
                    @php $barisKwitansi = count($kwitansi['items']); @endphp

                    @foreach ($kwitansi['items'] as $k => $item)
                        <tr @class(['baris', 'tahan' => $labelGenap($r, $barisSie) || ($kolomKwitansi && $labelGenap($k, $barisKwitansi))])>
                            <td class="grup {{ $kelasGrup($r, $barisSie) }}">
                                <span>{{ $barisLabel($r, $barisSie) ? $sie['no'] : '' }}</span>
                            </td>
                            <td class="grup {{ $kelasGrup($r, $barisSie) }}">
                                <span>{{ $barisLabel($r, $barisSie) ? $sie['nama'] : '' }}</span>
                            </td>
                            <td>{{ $item['keterangan'] }}</td>
                            <td>{{ $item['qty'] }}</td>
                            <td>{{ $item['satuan'] }}</td>
                            <td>{{ LaporanDana::rupiah($item['harga']) }}</td>
                            <td>{{ LaporanDana::rupiah($item['total']) }}</td>
                            @if ($kolomKwitansi)
                                <td class="{{ $kelasGrup($k, $barisKwitansi) }}">
                                    <span>{{ $barisLabel($k, $barisKwitansi) ? LaporanDana::rupiah($kwitansi['total']) : '' }}</span>
                                </td>
                            @endif
                        </tr>
                        @php $r++; @endphp
                    @endforeach
                @endforeach

                <tr @class(['baris', 'subtotal', 'tahan' => $labelGenap($r, $barisSie)])>
                    <td class="grup {{ $kelasGrup($r, $barisSie) }}">
                        <span>{{ $barisLabel($r, $barisSie) ? $sie['no'] : '' }}</span>
                    </td>
                    <td class="grup {{ $kelasGrup($r, $barisSie) }}">
                        <span>{{ $barisLabel($r, $barisSie) ? $sie['nama'] : '' }}</span>
                    </td>
                    <td colspan="{{ $jumlahKolom - 3 }}">SUBTOTAL</td>
                    <td>{{ LaporanDana::rupiah($sie['subtotal']) }}</td>
                </tr>
            @endforeach

            <tr class="baris total">
                <td colspan="{{ $jumlahKolom - 1 }}">{{ $laporan['labelTotal'] }}</td>
                <td>{{ LaporanDana::rupiah($laporan['grandTotal']) }}</td>
            </tr>
        </tbody>
    </table>

    <p class="terbilang"><strong>Terbilang:</strong> <strong><em>{{ $laporan['terbilang'] }}.</em></strong></p>
</body>
</html>
