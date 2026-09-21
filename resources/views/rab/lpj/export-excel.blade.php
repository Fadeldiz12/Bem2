@php
    // Palet warna diambil dari screenshot referensi (header biru + baris grup/subtotal biru muda)
    $borderColor = '#4285F4';
    $lightBlue = '#CFE2F3';

    $thStyle = "background-color:{$borderColor};color:#FFFFFF;font-weight:bold;text-align:center;vertical-align:middle;border:1px solid {$borderColor};padding:8px;";
    $groupStyle = "background-color:{$lightBlue};text-align:center;vertical-align:middle;border:1px solid {$borderColor};padding:8px;";
    $cellStyle = "border:1px solid {$borderColor};padding:6px 8px;";
    $cellCenterStyle = "border:1px solid {$borderColor};padding:6px 8px;text-align:center;";
    $cellRightStyle = "border:1px solid {$borderColor};padding:6px 8px;text-align:right;";
    $subtotalStyle = "background-color:{$lightBlue};font-weight:bold;border:1px solid {$borderColor};padding:6px 8px;";
    $subtotalRightStyle = "background-color:{$lightBlue};font-weight:bold;border:1px solid {$borderColor};padding:6px 8px;text-align:right;";
@endphp
<table>
    <thead>
        <tr>
            <th colspan="8"
                style="text-align:center;font-size:16px;background-color:{{ $borderColor }};color:#FFFFFF;border:1px solid {{ $borderColor }};padding:10px;">
                <strong>LAPORAN PERTANGGUNGJAWABAN (LPJ)</strong><br>
                <strong>{{ strtoupper($kegiatan->Nama_Kegiatan) }}</strong>
            </th>
        </tr>
        <tr></tr>
        <tr>
            <th style="{{ $thStyle }}">No</th>
            <th style="{{ $thStyle }}">Jenis Pengeluaran</th>
            <th style="{{ $thStyle }}">Keterangan</th>
            <th style="{{ $thStyle }}">Qty</th>
            <th style="{{ $thStyle }}">Satuan</th>
            <th style="{{ $thStyle }}">Harga/Unit (@)</th>
            <th style="{{ $thStyle }}">Total</th>
            <th style="{{ $thStyle }}">Total Kwitansi</th>
        </tr>
    </thead>
    <tbody>
        @php $no = 1; $grandTotal = 0; @endphp
        @foreach ($sies as $sie)
            @php
                $itemsByBon = $sie->item_lpj->groupBy('ID_Bon');
                $itemsWithoutBon = $sie->items->whereNull('ID_Bon');

                // +1 untuk baris Sub Total, biar rowspan No & Jenis Pengeluaran ikut nutupin baris itu
                $totalBaris = $sie->item_lpj->count() + $itemsWithoutBon->count() + 1;
                $first = true;
                $subtotalSie = 0;
            @endphp

            {{-- BAGIAN A: SUDAH ADA BON, pakai data realisasi --}}
            @foreach ($itemsByBon as $bonId => $items)
                @php
                    $firstInBon = true;
                    $totalKwitansi = $items->sum('Total_Realisasi');
                @endphp

                @foreach ($items as $item)
                    @php $subtotalSie += $item->Total_Realisasi; @endphp
                    <tr>
                        @if ($first)
                            <td rowspan="{{ $totalBaris }}" style="{{ $groupStyle }}">{{ $no++ }}</td>
                            <td rowspan="{{ $totalBaris }}" style="{{ $groupStyle }}">{{ $sie->Nama_Sie }}</td>
                        @endif

                        <td style="{{ $cellStyle }}">{{ $item->Keterangan }}</td>
                        <td style="{{ $cellCenterStyle }}">{{ $item->Qty_Realisasi }}</td>
                        <td style="{{ $cellCenterStyle }}">{{ $item->Satuan_Realisasi }}</td>
                        <td style="{{ $cellRightStyle }}">Rp.{{ $item->Harga_Realisasi }}</td>
                        <td style="{{ $cellRightStyle }}">Rp.{{ $item->Total_Realisasi }}</td>

                        @if ($firstInBon)
                            <td rowspan="{{ count($items) }}" style="{{ $cellRightStyle }}">Rp.{{ $totalKwitansi }}</td>
                        @endif
                    </tr>
                    @php $first = false; $firstInBon = false; @endphp
                @endforeach
            @endforeach

            {{-- BAGIAN B: BELUM ADA BON, fallback ke data RAB (Item) asli --}}
            @foreach ($itemsWithoutBon as $item)
                @php $subtotalSie += $item->Total; @endphp
                <tr>
                    @if ($first)
                        <td rowspan="{{ $totalBaris }}" style="{{ $groupStyle }}">{{ $no++ }}</td>
                        <td rowspan="{{ $totalBaris }}" style="{{ $groupStyle }}">{{ $sie->Nama_Sie }}</td>
                    @endif

                    <td style="{{ $cellStyle }}">{{ $item->Keterangan }}</td>
                    <td style="{{ $cellCenterStyle }}">{{ $item->Qty }}</td>
                    <td style="{{ $cellCenterStyle }}">{{ $item->Satuan }}</td>
                    <td style="{{ $cellRightStyle }}">Rp.{{ $item->Harga_Unit }}</td>
                    <td style="{{ $cellRightStyle }}">Rp.{{ $item->Total }}</td>
                    <td style="{{ $cellRightStyle }}">Rp.{{ $item->Total }}</td>
                </tr>
                @php $first = false; @endphp
            @endforeach

            {{-- SUB TOTAL PER SIE --}}
            <tr>
                <th colspan="5" style="{{ $subtotalRightStyle }}">Sub Total</th>
                <th style="{{ $subtotalRightStyle }}">Rp.{{ $subtotalSie }}</th>
            </tr>
            @php $grandTotal += $subtotalSie; @endphp
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="7" style="{{ $subtotalRightStyle }}">Total Realisasi Dana Kegiatan</th>
            <th style="{{ $subtotalRightStyle }}">Rp.{{ $grandTotal }}</th>
        </tr>
    </tfoot>
</table>