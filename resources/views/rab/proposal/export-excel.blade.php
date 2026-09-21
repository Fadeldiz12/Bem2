@php
    // Palet warna diambil dari screenshot referensi (header biru + kolom grup biru muda)
    $borderColor = '#4285F4';
    $lightBlue = '#CFE2F3';

    $thStyle = "background-color:{$borderColor};color:#FFFFFF;font-weight:bold;text-align:center;vertical-align:middle;border:1px solid {$borderColor};padding:8px;";
    $groupStyle = "background-color:{$lightBlue};text-align:center;vertical-align:middle;border:1px solid {$borderColor};padding:8px;";
    $groupLeftStyle = "background-color:{$lightBlue};vertical-align:middle;border:1px solid {$borderColor};padding:8px;";
    $cellStyle = "border:1px solid {$borderColor};padding:6px 8px;";
    $cellCenterStyle = "border:1px solid {$borderColor};padding:6px 8px;text-align:center;";
    $cellRightStyle = "border:1px solid {$borderColor};padding:6px 8px;text-align:right;";
    $subTotalStyle = "background-color:{$lightBlue};font-weight:bold;text-align:right;vertical-align:middle;border:1px solid {$borderColor};padding:8px;";
    $grandTotalStyle = "background-color:{$borderColor};color:#FFFFFF;font-weight:bold;text-align:right;vertical-align:middle;border:1px solid {$borderColor};padding:8px;";
@endphp
<table>
    <thead>
        <tr>
            <th colspan="7"
                style="text-align:center;font-size:16px;background-color:{{ $borderColor }};color:#FFFFFF;border:1px solid {{ $borderColor }};padding:10px;">
                <strong>RENCANA ANGGARAN BIAYA (RAB)</strong><br>
                <strong>{{ strtoupper($kegiatan->Nama_Kegiatan) }}</strong>
            </th>
        </tr>
        <tr></tr>
        <tr>
            <th style="{{ $thStyle }}">No</th>
            <th style="{{ $thStyle }}">Sie</th>
            <th style="{{ $thStyle }}">Keterangan</th>
            <th style="{{ $thStyle }}">Qty</th>
            <th style="{{ $thStyle }}">Satuan</th>
            <th style="{{ $thStyle }}">Harga Unit</th>
            <th style="{{ $thStyle }}">Total</th>
        </tr>
    </thead>
    <tbody>
        @php $no = 1; $grandTotal = 0; @endphp
        @foreach($sies as $sie)
            @php
                $hasItems = $sie->items->count() > 0;
                $rowspan = $hasItems ? $sie->items->count() + 1 : 1;
                $first = true;
                $sieTotal = 0;
            @endphp

            @forelse($sie->items as $item)
                <tr>
                    @if($first)
                        <td rowspan="{{ $rowspan }}" style="{{ $groupStyle }}">{{ $no++ }}</td>
                        <td rowspan="{{ $rowspan }}" style="{{ $groupLeftStyle }}">{{ $sie->Nama_Sie }}</td>
                    @endif

                    <td style="{{ $cellStyle }}">{{ $item->Keterangan }}</td>
                    <td style="{{ $cellCenterStyle }}">{{ $item->Qty }}</td>
                    <td style="{{ $cellStyle }}">{{ $item->Satuan }}</td>
                    <td style="{{ $cellRightStyle }}">Rp.{{ $item->Harga_Unit }}</td>
                    <td style="{{ $cellRightStyle }}">Rp.{{ $item->Qty * $item->Harga_Unit }}</td>
                </tr>
                @php
                    $itemTotal = $item->Qty * $item->Harga_Unit;
                    $sieTotal += $itemTotal;
                    $grandTotal += $itemTotal;
                    $first = false;
                @endphp
            @empty
                <tr>
                    <td style="{{ $groupStyle }}">{{ $no++ }}</td>
                    <td style="{{ $groupLeftStyle }}">{{ $sie->Nama_Sie }}</td>
                    <td colspan="5" style="{{ $cellCenterStyle }}">Belum ada item anggaran</td>
                </tr>
            @endforelse

            @if($hasItems)
                <tr>
                    <td colspan="4" style="{{ $subTotalStyle }}">SUB TOTAL</td>
                    <td style="{{ $subTotalStyle }}">Rp.{{ number_format($sieTotal, 0, ',', '.') }}</td>
                </tr>
            @endif
        @endforeach

        <tr>
            <td colspan="6" style="{{ $grandTotalStyle }}">TOTAL KESELURUHAN</td>
            <td style="{{ $grandTotalStyle }}">Rp.{{ number_format($grandTotal, 0, ',', '.') }}</td>
        </tr>
    </tbody>
</table>