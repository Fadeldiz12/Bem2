@extends('layouts.app')

@section('title', 'Daftar Proposal - BEM System')

@section('content')
    <div class="max-w-6xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-purple-900 mb-2">Daftar Proposal</h1>
            <p class="text-gray-500">Kelola RAB untuk kebutuhan proposal kegiatan.</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-white">
                <h2 class="text-lg font-semibold text-gray-800">Data Proposal Kegiatan</h2>
                <button
                    class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-2"
                    onclick="addProposal()">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Proposal
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-purple-100 text-purple-900 text-sm border-y border-purple-200">
                            <th class="px-6 py-4 font-semibold w-16">No</th>
                            <th class="px-6 py-4 font-semibold">Nama Kegiatan</th>
                            <th class="px-6 py-4 font-semibold">Tanggal</th>
                            <th class="px-6 py-4 font-semibold text-center">RAB</th>
                            <th class="px-6 py-4 font-semibold text-center w-48">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-700">
                        @forelse ($kegiatan as $item)
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $item->Nama_Kegiatan }}</td>
                                <td class="px-6 py-4">{{ $item->Tanggal_Pelaksanaan }}</td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('proposal.show', $item->ID_Kegiatan) }}"
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
                                <td class="px-6 py-4 flex justify-center gap-2">
                                    <button
                                        class="bg-purple-500 hover:bg-purple-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition flex items-center gap-1"
                                        onclick="editProposal(this)" data-id="{{ $item->ID_Kegiatan }}"
                                        data-nama="{{ $item->Nama_Kegiatan }}"
                                        data-tanggal="{{ $item->Tanggal_Pelaksanaan }}" data-jenis="{{ $item->Jenis_RAB }}">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                            </path>
                                        </svg>
                                        Edit
                                    </button>
                                    <form action="{{ route('proposal.destroy', $item->ID_Kegiatan) }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus proposal ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-800 hover:bg-red-900 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition flex items-center gap-1">   
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada data proposal.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    {{-- Modal for Adding Proposal --}}
    <div class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden" id="addProposalModal">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-lg shadow-lg w-96">
                <h2 class="text-xl font-semibold mb-6 bg-purple-500 text-white p-4 rounded-t-lg">Tambah Proposal</h2>

                @if ($errors->any())
                    <div class="px-6 text-red-500 text-sm mb-4">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>- {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('proposal.store') }}" method="POST" class="px-6 pb-6">
                    @csrf
                    <div class="mb-4">
                        <label for="Nama_Kegiatan" class="block text-gray-700 font-medium mb-2">Nama Kegiatan</label>
                        <input type="text" name="Nama_Kegiatan" id="Nama_Kegiatan"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-500"
                            required>
                    </div>

                    <div class="mb-4">
                        <label for="Tanggal_Pelaksanaan" class="block text-gray-700 font-medium mb-2">Tanggal
                            Pelaksanaan</label>
                        <input type="date" name="Tanggal_Pelaksanaan" id="Tanggal_Pelaksanaan"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-500"
                            required>
                    </div>

                    <input type="hidden" name="Jenis_RAB" id="Jenis_RAB" value="Proposal">

                    <div class="flex justify-end gap-2">
                        <button type="button"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition"
                            onclick="addProposal()">Batal</button>
                        <button type="submit"
                            class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal for Editing Proposal --}}
    <div class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden" id="editProposalModal">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-lg shadow-lg w-96">
                <h2 class="text-xl font-semibold mb-6 bg-purple-500 text-white p-4 rounded-t-lg">Edit Proposal</h2>
                <form id="editProposalForm" method="POST" class="px-6 pb-6">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label for="edit_Nama_Kegiatan" class="block text-gray-700 font-medium mb-2">Nama Kegiatan</label>
                        <input type="text" name="Nama_Kegiatan" id="edit_Nama_Kegiatan"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-500"
                            required>
                    </div>

                    <div class="mb-4">
                        <label for="edit_Tanggal_Pelaksanaan" class="block text-gray-700 font-medium mb-2">Tanggal
                            Pelaksanaan</label>
                        <input type="date" name="Tanggal_Pelaksanaan" id="edit_Tanggal_Pelaksanaan"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-500"
                            required>
                    </div>

                    <input type="hidden" name="Jenis_RAB" id="edit_Jenis_RAB" value="Proposal">

                    <div class="flex justify-end gap-2">
                        <button type="button"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition"
                            onclick="closeEditModal()">Batal</button>
                        <button type="submit"
                            class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        function addProposal() {
            const modal = document.getElementById('addProposalModal');
            modal.classList.toggle('hidden');
        }

        function closeEditModal() {
            document.getElementById('editProposalModal').classList.add('hidden');
        }

        function editProposal(buttonElement) {
            const modal = document.getElementById('editProposalModal');
            const form = document.getElementById('editProposalForm');

            const namaInput = document.getElementById('edit_Nama_Kegiatan');
            const tanggalInput = document.getElementById('edit_Tanggal_Pelaksanaan');
            const jenisInput = document.getElementById('edit_Jenis_RAB');

            const id = buttonElement.getAttribute('data-id');
            const nama = buttonElement.getAttribute('data-nama');
            const tanggal = buttonElement.getAttribute('data-tanggal');
            const jenis = buttonElement.getAttribute('data-jenis');

            // Biasa route update Laravel mengarah ke parameter /proposal/{id}
            form.action = `/proposal/${id}/edit`;

            namaInput.value = nama;
            tanggalInput.value = tanggal;
            jenisInput.value = jenis;

            modal.classList.remove('hidden');
        }
    </script>
@endsection
