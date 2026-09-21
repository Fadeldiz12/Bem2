@extends('layouts.app')

@section('title', 'Detail RAB Proposal - BEM System')

@section('content')
    <div class="max-w-6xl mx-auto relative">

        <!-- Top Actions & Title -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-purple-900 mb-1">RAB: {{ $kegiatan->Nama_Kegiatan }}</h1>
                <p class="text-gray-500 text-sm">Rencana Anggaran Biaya — Format Proposal</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('proposal.export.pdf', $kegiatan->ID_Kegiatan) }}"
                    class="bg-purple-800 hover:bg-purple-900 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                        </path>
                    </svg>
                    Cetak PDF
                </a>
                <button onclick="toggleModalSie()"
                    class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Sie
                </button>
                <!-- Tombol ini akan membuka Modal -->
                {{-- <button onclick="toggleModal()"
                    class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Item
                </button> --}}
                <a href="{{ route('proposal.export.excel', $kegiatan->ID_Kegiatan) }}"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Export Excel
                </a>
            </div>
        </div>

        <!-- Table Content -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-purple-800 text-white text-sm">
                            <th class="px-6 py-4 font-semibold w-16">No</th>
                            <th class="px-6 py-4 font-semibold">Keterangan</th>
                            <th class="px-6 py-4 font-semibold">Volume</th>
                            <th class="px-6 py-4 font-semibold">Satuan</th>
                            <th class="px-6 py-4 font-semibold">Harga/Unit (@)</th>
                            <th class="px-6 py-4 font-semibold">Total</th>
                            <th class="px-6 py-4 font-semibold text-center w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-700">
                        {{-- Kolom Sie --}}
                        @forelse ($sie as $index => $s)
                            <tr class="bg-purple-200 border-b border-purple-300">
                                <td colspan="8" class="px-6 py-3">
                                    <div class="flex items-center justify-between w-full">
                                        <span class="text-purple-900 font-bold text-sm uppercase tracking-wider">
                                            {{ $s->Nama_Sie }}
                                        </span>

                                        <div class="flex items-center gap-2">
                                            <button onclick="toggleModal(this)" data-sie-id="{{ $s->ID_Sie }}"
                                                class="px-3 py-1.5 rounded-lg bg-purple-600 hover:bg-purple-700 text-white flex items-center gap-1 text-xs font-medium transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 4v16m8-8H4"></path>
                                                </svg>
                                                Tambah Item
                                            </button>
                                            <button title="Edit Nama Sie" onclick="toggleModalEditSie(this)"
                                                data-sie-id="{{ $s->ID_Sie }}" data-sie-name="{{ $s->Nama_Sie }}"
                                                class="px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white flex items-center gap-1 text-xs font-medium transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                                    </path>
                                                </svg>
                                                Edit Sie
                                            </button>
                                            <form action="{{ route('Sie.destroy', $s->ID_Sie) }}" method="POST"
                                                class="inline"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus Sie ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button title="Hapus Sie" type="submit"
                                                    class="px-3 py-1.5 rounded-lg bg-red-700 hover:bg-red-800 text-white flex items-center gap-1 text-xs font-medium transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                    Hapus Sie
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @forelse ($s->items as $itemIndex => $item)
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">{{ $itemIndex + 1 }}</td>
                                    <td class="px-6 py-4">{{ $item->Keterangan }}</td>
                                    <td class="px-6 py-4">{{ $item->Qty }}</td>
                                    <td class="px-6 py-4">{{ $item->Satuan }}</td>
                                    <td class="px-6 py-4">Rp {{ number_format($item->Harga_Unit, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 font-semibold">Rp {{ number_format($item->Total, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 flex justify-center gap-2">
                                        <button title="Edit Item" onclick="editItem(this)"
                                            data-item-id="{{ $item->ID_Item }}"
                                            data-item-jenis="{{ $item->Jenis_Pengeluaran }}"
                                            data-item-keterangan="{{ $item->Keterangan }}"
                                            data-item-qty="{{ $item->Qty }}" data-item-satuan="{{ $item->Satuan }}"
                                            data-item-harga="{{ $item->Harga_Unit }}"
                                            class="w-8 h-8 rounded-full bg-blue-500 hover:bg-blue-600 text-white flex items-center justify-center transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                                </path>
                                            </svg>
                                        </button>
                                        <form action="{{ route('Item.destroy', $item->ID_Item) }}" method="POST"
                                            class="inline"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus item ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button title="Hapus Item" type="submit"
                                                class="w-8 h-8 rounded-full bg-red-700 hover:bg-red-800 text-white flex items-center justify-center transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                        Belum ada item untuk Sie ini.
                                    </td>
                                </tr>
                            @endforelse
                            <tr class="bg-violet-300 border-b border-purple-300">
                                <td colspan="8" class="px-6 py-3">
                                    <div class="flex items-center justify-end w-full">
                                        <span class="text-purple-900 font-bold text-sm uppercase tracking-wider">
                                            Total Sie {{ $s->Nama_Sie }}
                                        </span>
                                        <span class="ml-3 text-purple-900 font-bold text-sm uppercase tracking-wider mr-6">
                                            Rp {{ number_format($s->items->sum('Total'), 0, ',', '.') }}
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                    Belum ada Sie yang tersedia.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                    <tfoot>
                        <tr class="bg-purple-700 text-white text-sm font-bold uppercase tracking-wider">
                            <td colspan="6" class="px-6 py-4 text-right">TOTAL KESELURUHAN</td>
                            <td colspan="2" class="px-6 py-4">{{ number_format($kegiatan->items->sum('Total'), 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Back Button -->
        <a href="{{ route('proposal.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-full text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
            Kembali ke Daftar Proposal
        </a>

        {{-- Modal Tambah Sie --}}
        <div id="sieModal"
            class="hidden fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 flex items-center justify-center">
            <div
                class="bg-white rounded-2xl w-full max-w-lg shadow-xl overflow-hidden animate-fade-in-up max-h-[90vh] flex flex-col">
                <div class="p-6 overflow-y-auto">
                    <h2 class="text-xl font-bold text-purple-900 mb-1">Tambah Sie</h2>
                    <p class="text-sm text-purple-600 mb-6">Kegiatan: <span class="font-bold">SIGMA BEM</span></p>

                    <form action="{{ route('Sie.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="kegiatan_id" value="{{ $kegiatan->ID_Kegiatan }}">

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-semibold mb-2">Jumlah Sie</label>
                            <input type="number" id="jumlah_sie" min="1" max="20"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600"
                                placeholder="Contoh: 3">
                        </div>

                        <div id="container_nama_sie" class="flex flex-col gap-4 mb-4">
                        </div>

                        <div class="flex gap-4 mt-6">
                            <button type="submit"
                                class="flex-1 bg-purple-700 hover:bg-purple-800 text-white font-bold py-2.5 px-4 rounded-lg transition flex justify-center items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                                    </path>
                                </svg>
                                Simpan
                            </button>
                            <button type="button" onclick="toggleModalSie()"
                                class="flex-1 bg-red-800 hover:bg-red-900 text-white font-bold py-2.5 px-4 rounded-lg transition flex justify-center items-center gap-2">
                                <svg class="w-4 h-4 " fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Individual Sie Edit Modal --}}
        <div id="editSieModal"
            class="hidden fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 flex items-center justify-center">
            <div class="bg-white rounded-2xl w-full max-w-lg shadow-xl overflow-hidden animate-fade-in-up">
                <div class="p-6">
                    <h2 class="text-xl font-bold text-purple-900 mb-1">Edit Nama Sie</h2>
                    <p class="text-sm text-purple-600 mb-6">Kegiatan: <span
                            class="font-bold">{{ $kegiatan->Nama_Kegiatan }}</span></p>

                    <form id="editSieForm" action="#" method="POST">
                        @csrf
                        @method('PUT') <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-semibold mb-2">Nama Sie</label>
                            <input type="text" id="editSieName" name="Nama_Sie" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600"
                                placeholder="Contoh: Sie Acara">
                        </div>

                        <div class="flex gap-4 mt-6">
                            <button type="submit"
                                class="flex-1 bg-purple-700 hover:bg-purple-800 text-white font-bold py-2.5 px-4 rounded-lg transition flex justify-center items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                                    </path>
                                </svg>
                                Simpan
                            </button>
                            <button type="button" onclick="closeEditSieModal()"
                                class="flex-1 bg-red-800 hover:bg-red-900 text-white font-bold py-2.5 px-4 rounded-lg transition flex justify-center items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Overlay Background & Modal (Hidden by default) -->
    <div id="itemModal"
        class="hidden fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 flex items-center justify-center">
        <!-- Modal Content -->
        <div class="bg-white rounded-2xl w-full max-w-lg shadow-xl overflow-hidden animate-fade-in-up">
            <div class="p-6">
                <h2 class="text-xl font-bold text-purple-900 mb-1">Tambah Item Proposal</h2>
                <p class="text-sm text-purple-600 mb-6">Kegiatan: <span
                        class="font-bold">{{ $kegiatan->Nama_Kegiatan }}</span></p>

                <form action="{{ route('Item.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_sie" id="id_sie" value="">
                    <input type="hidden" name="Jenis_Pengeluaran" value="Proposal">

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Keterangan</label>
                        <input type="text" name="Keterangan"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600"
                            placeholder="Contoh: Nasi Panitia">
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">Volume/Jumlah</label>
                            <input type="number" name="Qty"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600"
                                placeholder="Contoh: 20">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">Satuan</label>
                            <input type="text" name="Satuan"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600"
                                placeholder="Contoh: Kotak">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Harga/Unit (@)</label>
                        <input type="number" name="Harga_Unit"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600"
                            placeholder="Contoh: 15000">
                    </div>

                    <div class="flex gap-4 mt-6">
                        <button type="submit"
                            class="flex-1 bg-purple-700 hover:bg-purple-800 text-white font-bold py-2.5 px-4 rounded-lg transition flex justify-center items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                                </path>
                            </svg>
                            Simpan
                        </button>
                        <button type="button" onclick="toggleModal()"
                            class="flex-1 bg-red-800 hover:bg-red-900 text-white font-bold py-2.5 px-4 rounded-lg transition flex justify-center items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Edit Item --}}
    <div id="editItemModal"
        class="hidden fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 flex items-center justify-center">
        <div class="bg-white rounded-2xl w-full max-w-lg shadow-xl overflow-hidden animate-fade-in-up">
            <div class="p-6">
                <h2 class="text-xl font-bold text-purple-900 mb-1">Edit Item Proposal</h2>
                <p class="text-sm text-purple-600 mb-6">Kegiatan: <span
                        class="font-bold">{{ $kegiatan->Nama_Kegiatan }}</span></p>

                <form id="editItemForm" action="#" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id_item" id="editItemId" value="">
                    <input type="hidden" name="Jenis_Pengeluaran" value="Proposal">

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Keterangan</label>
                        <input type="text" name="Keterangan"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600"
                            placeholder="Contoh: Nasi Panitia">
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">Volume/Jumlah</label>
                            <input type="number" name="Qty"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600"
                                placeholder="Contoh: 20">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">Satuan</label>
                            <input type="text" name="Satuan"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600"
                                placeholder="Contoh: Kotak">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Harga/Unit (@)</label>
                        <input type="number" name="Harga_Unit"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600"
                            placeholder="Contoh: 15000">
                    </div>

                    <div class="flex gap-4 mt-6">
                        <button type="submit"
                            class="flex-1 bg-purple-700 hover:bg-purple-800 text-white font-bold py-2.5 px-4 rounded-lg transition flex justify-center items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                                </path>
                            </svg>
                            Simpan
                        </button>
                        <button type="button" onclick="toggleModal()"
                            class="flex-1 bg-red-800 hover:bg-red-900 text-white font-bold py-2.5 px-4 rounded-lg transition flex justify-center items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Script Simple untuk memunculkan Modal -->
    <script>
        function toggleModal(buttonElement = null) {
            const modal = document.getElementById('itemModal');
            modal.classList.toggle('hidden');

            if (buttonElement) {
                const sieId = buttonElement.getAttribute('data-sie-id');
                modal.querySelector('#id_sie').value = sieId;
            }
        }

        function editItem(buttonElement) {
            const modal = document.getElementById('editItemModal');
            modal.classList.remove('hidden');

            // Ambil data dari atribut data-* dari tombol yang diklik
            const itemId = buttonElement.getAttribute('data-item-id');
            const jenisPengeluaran = buttonElement.getAttribute('data-item-jenis');
            const keterangan = buttonElement.getAttribute('data-item-keterangan');
            const qty = buttonElement.getAttribute('data-item-qty');
            const satuan = buttonElement.getAttribute('data-item-satuan');
            const hargaUnit = buttonElement.getAttribute('data-item-harga');

            // Masukkan data ke dalam form
            document.getElementById('editItemId').value = itemId;
            document.querySelector('#editItemForm input[name="Jenis_Pengeluaran"]').value = jenisPengeluaran;
            document.querySelector('#editItemForm input[name="Keterangan"]').value = keterangan;
            document.querySelector('#editItemForm input[name="Qty"]').value = qty;
            document.querySelector('#editItemForm input[name="Satuan"]').value = satuan;
            document.querySelector('#editItemForm input[name="Harga_Unit"]').value = hargaUnit;

            // Arahkan action form ke rute update. 
            // CATATAN: Pastikan URL ini sesuai dengan route web.php kamu! 
            // Contoh jika routenya menggunakan resource: /item/{id}
            const form = document.getElementById('editItemForm');
            form.action = `/proposal/edit-item/${itemId}`;
        }

        function toggleModalSie() {
            const modal = document.getElementById('sieModal');
            modal.classList.toggle('hidden');

            if (modal.classList.contains('hidden')) {
                document.getElementById('jumlah_sie').value = '1';
                document.getElementById('container_nama_sie').innerHTML = '';
            }
        }

        document.getElementById('jumlah_sie').addEventListener('input', function() {
            const container = document.getElementById('container_nama_sie');
            let count = parseInt(this.value);

            // Bersihkan isi container sebelumnya
            container.innerHTML = '';

            // Validasi: Jika input kosong, bukan angka, atau kurang dari 1, hentikan proses
            if (isNaN(count) || count < 1) return;

            // Validasi: Batasi batas maksimum agar tidak crash/hang (misal max 20)
            if (count > 20) count = 20;

            // Buat elemen input sebanyak jumlah yang diminta
            for (let i = 1; i <= count; i++) {
                const inputHtml = `
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Nama Sie ${i}</label>
                    <input type="text" name="nama_Sie[]" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600"
                        placeholder="Contoh: Sie Acara / Sie Konsumsi">
                </div>
            `;
                // Masukkan HTML ke dalam container
                container.insertAdjacentHTML('beforeend', inputHtml);
            }
        });

        // Buka Modal Edit dan isi datanya
        function toggleModalEditSie(buttonElement) {
            const modal = document.getElementById('editSieModal');

            // Ambil data dari atribut data-sie-id dan data-sie-name dari tombol yang diklik
            const sieId = buttonElement.getAttribute('data-sie-id');
            const sieName = buttonElement.getAttribute('data-sie-name');

            // Masukkan nama Sie ke dalam input
            document.getElementById('editSieName').value = sieName;

            // Arahkan action form ke rute update. 
            // CATATAN: Pastikan URL ini sesuai dengan route web.php kamu! 
            // Contoh jika routenya menggunakan resource: /sie/{id}
            const form = document.getElementById('editSieForm');
            form.action = `/proposal/edit-sie/${sieId}`;

            // Tampilkan modal
            modal.classList.remove('hidden');
        }

        // Tutup Modal Edit
        function closeEditSieModal() {
            const modal = document.getElementById('editSieModal');
            modal.classList.add('hidden');
        }
    </script>
@endsection
