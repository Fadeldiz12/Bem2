{{-- Keterangan item realisasi; item tambahan (di luar proposal) bisa diedit & dihapus langsung --}}
<div class="flex items-start justify-between gap-2">
    <span>
        {{ $item->Keterangan }}
        @if ($item->isNew)
            <span class="ml-1 px-1.5 py-0.5 bg-amber-100 text-amber-700 text-[10px] rounded-full">Tambahan</span>
        @endif
    </span>

    @if ($item->isNew)
        <div class="flex shrink-0 gap-1">
            <button type="button" title="Edit item tambahan" onclick="bukaModalItemTambahan(this)"
                data-item-id="{{ $item->ID_Item_LPJ }}" data-sie-id="{{ $item->ID_Sie }}"
                data-sie-nama="{{ $namaSie }}" data-bon-id="{{ $item->ID_Bon }}"
                data-jenis="{{ $item->Jenis_Pengeluaran }}" data-keterangan="{{ $item->Keterangan }}"
                data-qty="{{ $item->Qty_Realisasi }}" data-satuan="{{ $item->Satuan_Realisasi }}"
                data-harga="{{ (float) $item->Harga_Realisasi }}"
                class="w-7 h-7 rounded-full bg-blue-500 hover:bg-blue-600 text-white flex items-center justify-center transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                    </path>
                </svg>
            </button>
            <form action="{{ route('ItemLpj.destroy', $item->ID_Item_LPJ) }}" method="POST" class="inline"
                onsubmit="return confirm('Hapus item tambahan ini? Kalau ini item terakhir di kwitansinya, kwitansi itu (beserta fotonya) ikut terhapus.')">
                @csrf
                @method('DELETE')
                <button type="submit" title="Hapus item tambahan"
                    class="w-7 h-7 rounded-full bg-red-700 hover:bg-red-800 text-white flex items-center justify-center transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                        </path>
                    </svg>
                </button>
            </form>
        </div>
    @endif
</div>
