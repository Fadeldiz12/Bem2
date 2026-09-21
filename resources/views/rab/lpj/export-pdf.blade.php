<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Export PDF LPJ</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 15px;
            text-align: center;
            background-color: #4285F4;
            color: #FFFFFF;
            padding: 10px;
        }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #4285F4; padding: 4px; vertical-align: middle; }
        th { text-align: center; }

        /* Palet warna diambil dari screenshot referensi */
        .header-row { background-color: #4285F4; color: #FFFFFF; font-weight: bold; }
        .group-cell { background-color: #CFE2F3; }
        .subtotal-row { background-color: #CFE2F3; font-weight: bold; }

        .ttd-container { width: 100%; margin-top: 30px; }
        .ttd-box { width: 33%; float: left; text-align: center; }
        .font-bold { font-weight: bold; }
    </style>
</head>
<body>

    <div class="title">
        LAPORAN PERTANGGUNGJAWABAN (LPJ)<br>
        KEGIATAN {{ strtoupper($kegiatan->Nama_Kegiatan) }}
    </div>

    <table>
        <thead>
            <tr>
                <th class="header-row">No</th>
                <th class="header-row">Jenis Pengeluaran</th>
                <th class="header-row">Keterangan</th>
                <th class="header-row">Qty</th>
                <th class="header-row">Satuan</th>
                <th class="header-row">Harga/Unit (@)</th>
                <th class="header-row">Total</th>
                <th class="header-row">Total Kwitansi</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; $grandTotal = 0; @endphp
            @foreach($sies as $sie)
                @php
                    $itemsByBon = $sie->item_lpj->groupBy('ID_Bon');
                    $itemsWithoutBon = $sie->items->whereNull('ID_Bon');

                    // +1 untuk baris Sub Total, biar rowspan No & Jenis Pengeluaran ikut nutupin baris itu
                    $totalBaris = $sie->item_lpj->count() + $itemsWithoutBon->count() + 1;
                    $first = true;
                    $subtotalSie = 0;
                @endphp

                {{-- BAGIAN A: SUDAH ADA BON, pakai data realisasi --}}
                @foreach($itemsByBon as $bonId => $items)
                    @php
                        $firstInBon = true;
                        $totalKwitansi = $items->sum('Total_Realisasi');
                    @endphp

                    @foreach($items as $item)
                        @php $subtotalSie += $item->Total_Realisasi; @endphp
                        <tr>
                            @if($first)
                                <td rowspan="{{ $totalBaris }}" class="text-center group-cell">{{ $no++ }}</td>
                                <td rowspan="{{ $totalBaris }}" class="group-cell">{{ $sie->Nama_Sie }}</td>
                            @endif

                            <td>{{ $item->Keterangan }}</td>
                            <td class="text-center">{{ $item->Qty_Realisasi }}</td>
                            <td class="text-center">{{ $item->Satuan_Realisasi }}</td>
                            <td class="text-right">Rp.{{ number_format($item->Harga_Realisasi,0,',','.') }}</td>
                            <td class="text-right">Rp.{{ number_format($item->Total_Realisasi,0,',','.') }}</td>

                            @if($firstInBon)
                                <td rowspan="{{ count($items) }}" class="text-right font-bold">
                                    Rp.{{ number_format($totalKwitansi,0,',','.') }}
                                </td>
                            @endif
                        </tr>
                        @php $first = false; $firstInBon = false; @endphp
                    @endforeach
                @endforeach

                {{-- BAGIAN B: BELUM ADA BON, fallback ke data RAB (Item) asli --}}
                @foreach($itemsWithoutBon as $item)
                    @php $subtotalSie += $item->Total; @endphp
                    <tr>
                        @if($first)
                            <td rowspan="{{ $totalBaris }}" class="text-center group-cell">{{ $no++ }}</td>
                            <td rowspan="{{ $totalBaris }}" class="group-cell">{{ $sie->Nama_Sie }}</td>
                        @endif

                        <td>{{ $item->Keterangan }}</td>
                        <td class="text-center">{{ $item->Qty }}</td>
                        <td class="text-center">{{ $item->Satuan }}</td>
                        <td class="text-right">Rp.{{ number_format($item->Harga_Unit,0,',','.') }}</td>
                        <td class="text-right">Rp.{{ number_format($item->Total,0,',','.') }}</td>
                        <td class="text-right">Rp.{{ number_format($item->Total,0,',','.') }}</td>
                    </tr>
                    @php $first = false; @endphp
                @endforeach

                {{-- SUB TOTAL PER SIE --}}
                <tr>
                    <th colspan="5" class="text-right subtotal-row">Sub Total</th>
                    <th class="text-right subtotal-row">{{ number_format($subtotalSie,0,',','.') }}</th>
                </tr>
                @php $grandTotal += $subtotalSie; @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="7" class="text-right subtotal-row">Total Realisasi Dana Kegiatan</th>
                <th class="text-right subtotal-row">{{ number_format($grandTotal,0,',','.') }}</th>
            </tr>
        </tfoot>
    </table>

    <div class="ttd-container">
        <div class="ttd-box">
            Mengetahui,<br>Ketua Panitia<br><br><br><br>
            <strong>( .................................... )</strong>
        </div>
        <div class="ttd-box" style="float: right;">
            <br>Bendahara<br><br><br><br>
            <strong>( .................................... )</strong>
        </div>
        <div style="clear: both;"></div>
    </div>

</body>
</html>