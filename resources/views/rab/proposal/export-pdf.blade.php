<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Export PDF RAB</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 11px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
            background-color: #4285F4;
            color: #FFFFFF;
            padding: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #4285F4;
            padding: 6px;
            vertical-align: middle;
            word-wrap: break-word;
        }

        th {
            text-align: center;
        }

        tr {
            page-break-inside: avoid;
        }

        .header-row {
            background-color: #4285F4;
            color: #FFFFFF;
            font-weight: bold;
        }

        .group-cell {
            background-color: #CFE2F3;
        }

        .subtotal-row {
            background-color: #CFE2F3;
            font-weight: bold;
            text-align: center;
        }

        .ttd-container {
            width: 100%;
            margin-top: 40px;
        }

        .ttd-box {
            width: 33%;
            float: left;
            text-align: center;
        }

        .font-bold {
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="title">
        RENCANA ANGGARAN BIAYA (RAB)<br>
        KEGIATAN {{ strtoupper($kegiatan->Nama_Kegiatan) }}
    </div>

    <table>
        <thead>
            <tr>
                <th class="header-row" style="width: 6%;">No</th>
                <th class="header-row" style="width: 16%;">Jenis Pengeluaran</th>
                <th class="header-row" style="width: 21%;">Keterangan</th>
                <th class="header-row" style="width: 7%;">Qty</th>
                <th class="header-row" style="width: 10%;">Satuan</th>
                <th class="header-row" style="width: 20%;">Harga/Unit<br>(@)</th>
                <th class="header-row" style="width: 20%;">Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $grandTotal = 0;
            @endphp

            @foreach ($sies as $sie)
                @php
                    $hasItems = $sie->items->count() > 0;
                    $subtotal = 0;
                @endphp

                @forelse($sie->items as $itemIndex => $item)
                    @php
                        $lineTotal = $item->Qty * $item->Harga_Unit;
                        $subtotal += $lineTotal;
                        $grandTotal += $lineTotal;
                    @endphp
                    <tr>
                        <td class="text-center group-cell">
                            @if($itemIndex === 0) {{ $no++ }} @else &nbsp; @endif
                        </td>
                        <td class="group-cell">
                            @if($itemIndex === 0) {{ $sie->Nama_Sie }} @else &nbsp; @endif
                        </td>
                        <td>{{ $item->Keterangan }}</td>
                        <td class="text-center">{{ $item->Qty }}</td>
                        <td class="text-center">{{ $item->Satuan }}</td>
                        <td class="text-right">Rp{{ number_format($item->Harga_Unit, 2, ',', '.') }}</td>
                        <td class="text-right">Rp{{ number_format($lineTotal, 2, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center group-cell">{{ $no++ }}</td>
                        <td class="group-cell">{{ $sie->Nama_Sie }}</td>
                        <td colspan="5" class="text-center font-bold">Belum ada item anggaran di sie ini</td>
                    </tr>
                @endforelse

                @if ($hasItems)
                    <tr class="subtotal-row">
                        <td colspan="6" class="text-right font-bold">Sub Total</td>
                        <td class="text-right">Rp{{ number_format($subtotal, 2, ',', '.') }}</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
        <tfoot>
            <tr class="subtotal-row">
                <th colspan="6">Total Keseluruhan</th>
                <th class="text-right">Rp{{ number_format($grandTotal, 2, ',', '.') }}</th>
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