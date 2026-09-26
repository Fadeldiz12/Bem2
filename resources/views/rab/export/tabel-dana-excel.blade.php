@php
    use App\Helpers\LaporanDana;

    // Format mengikuti tabel "B. Pengeluaran" di dokumen Laporan Dana (ungu tua / ungu muda).
    $ungu = '#6A1B9A';
    $unguMuda = '#E3D4F7';

    $sel = 'font-size:10;text-align:center;vertical-align:middle;word-wrap:break-word;border:1px solid #000000;';
    $kepala = $sel . "background-color:{$ungu};color:#FFFFFF;font-weight:bold;";
    $grup = $sel . "background-color:{$unguMuda};";
    $subtotal = $grup . 'font-weight:bold;';
    $total = $kepala;

    // Sel gabungan (Terbilang) tidak ikut auto-fit tinggi baris di Excel, jadi tingginya dihitung kasar.
    $tinggiTerbilang = 16 * max(1, (int) ceil(mb_strlen($laporan['terbilang']) / 70));

    // Data & tata letak dari App\Helpers\LaporanDana::pengeluaran() (LPJ) / anggaran() (Proposal)
    $jumlahKolom = count($laporan['lebarKolom']);
    $kolomKwitansi = $laporan['kolomKwitansi'];
@endphp
<table>
    @foreach ($laporan['judul'] as $i => $judul)
        <tr>
            <td colspan="{{ $jumlahKolom }}" style="font-size:12;font-weight:bold;{{ $i > 0 ? 'text-indent:2;' : '' }}">{{ $judul }}</td>
        </tr>
    @endforeach
    @if ($laporan['judul'])
        <tr></tr>
    @endif
    <tr>
        <td style="{{ $kepala }}">No</td>
        <td style="{{ $kepala }}">Jenis Pengeluaran</td>
        <td style="{{ $kepala }}">Keterangan</td>
        <td style="{{ $kepala }}">Qty</td>
        <td style="{{ $kepala }}">Satuan</td>
        <td style="{{ $kepala }}">Harga/Unit (@)</td>
        <td style="{{ $kepala }}">Total</td>
        @if ($kolomKwitansi)
            <td style="{{ $kepala }}">Total Kwitansi</td>
        @endif
    </tr>

    @foreach ($laporan['sies'] as $sie)
        @php
            $barisSie = $sie['jumlah_item'] + 1; // + baris SUBTOTAL
            $selSieSudah = false;
        @endphp

        @foreach ($sie['kwitansi'] as $kwitansi)
            @php $barisKwitansi = count($kwitansi['items']); @endphp

            @foreach ($kwitansi['items'] as $k => $item)
                <tr>
                    @unless ($selSieSudah)
                        <td style="{{ $grup }}" @if ($barisSie > 1) rowspan="{{ $barisSie }}" @endif>{{ $sie['no'] }}</td>
                        <td style="{{ $grup }}" @if ($barisSie > 1) rowspan="{{ $barisSie }}" @endif>{{ $sie['nama'] }}</td>
                        @php $selSieSudah = true; @endphp
                    @endunless

                    <td style="{{ $sel }}">{{ $item['keterangan'] }}</td>
                    <td style="{{ $sel }}">{{ $item['qty'] }}</td>
                    <td style="{{ $sel }}">{{ $item['satuan'] }}</td>
                    <td style="{{ $sel }}">{{ LaporanDana::rupiah($item['harga']) }}</td>
                    <td style="{{ $sel }}">{{ LaporanDana::rupiah($item['total']) }}</td>

                    @if ($kolomKwitansi && $k === 0)
                        <td style="{{ $sel }}" @if ($barisKwitansi > 1) rowspan="{{ $barisKwitansi }}" @endif>{{ LaporanDana::rupiah($kwitansi['total']) }}</td>
                    @endif
                </tr>
            @endforeach
        @endforeach

        <tr>
            @unless ($selSieSudah)
                <td style="{{ $grup }}">{{ $sie['no'] }}</td>
                <td style="{{ $grup }}">{{ $sie['nama'] }}</td>
            @endunless
            <td colspan="{{ $jumlahKolom - 3 }}" style="{{ $subtotal }}">SUBTOTAL</td>
            <td style="{{ $subtotal }}">{{ LaporanDana::rupiah($sie['subtotal']) }}</td>
        </tr>
    @endforeach

    <tr>
        <td colspan="{{ $jumlahKolom - 1 }}" style="{{ $total }}">{{ $laporan['labelTotal'] }}</td>
        <td style="{{ $total }}">{{ LaporanDana::rupiah($laporan['grandTotal']) }}</td>
    </tr>
    <tr></tr>
    <tr>
        <td colspan="2" style="font-size:12;font-weight:bold;vertical-align:top;height:{{ $tinggiTerbilang }}pt;">Terbilang:</td>
        <td colspan="{{ $jumlahKolom - 2 }}" style="font-size:12;font-weight:bold;font-style:italic;vertical-align:top;word-wrap:break-word;">{{ $laporan['terbilang'] }}.</td>
    </tr>
</table>
