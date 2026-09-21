@extends('layouts.app')

@section('title', 'Daftar LPJ - BEM System')

@section('content')
    <div class="max-w-6xl mx-auto">

        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-purple-900 mb-2">Daftar LPJ</h1>
            <p class="text-gray-500">Kelola RAB untuk kebutuhan laporan pertanggungjawaban.</p>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Card Header -->
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-white">
                <h2 class="text-lg font-semibold text-gray-800">Data LPJ Kegiatan</h2>
                {{-- <button
                    class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah LPJ
                </button> --}}
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-purple-100 text-purple-900 text-sm border-y border-purple-200">
                            <th class="px-6 py-4 font-semibold w-16">No</th>
                            <th class="px-6 py-4 font-semibold">Nama Kegiatan</th>
                            <th class="px-6 py-4 font-semibold">Tanggal</th>
                            <th class="px-6 py-4 font-semibold text-center">RAB</th>
                            {{-- <th class="px-6 py-4 font-semibold text-center w-48">Aksi</th> --}}
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-700">
                        @forelse ($kegiatan as $item)
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $item->Nama_Kegiatan }}</td>
                                <td class="px-6 py-4">{{ $item->Tanggal_Pelaksanaan }}</td>
                                <td class="px-6 py-4 text-center">
                                    <!-- Link ke halaman detail LPJ -->
                                    <a href="{{ route('lpj.show', $item->ID_Kegiatan) }}"
                                        class="inline-flex items-center gap-1 bg-purple-500 hover:bg-purple-600 text-white px-3 py-1.5 rounded-full text-xs font-medium transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                        Lihat RAB
                                    </a>
                                </td>
                                {{-- <td class="px-6 py-4 flex justify-center gap-2">
                                    <button
                                        class="bg-purple-500 hover:bg-purple-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                            </path>
                                        </svg>
                                        Edit
                                    </button>
                                    <button
                                        class="bg-red-900 hover:bg-red-950 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                        Hapus
                                    </button>
                                </td> --}}
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                    Tidak ada data LPJ yang tersedia.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
