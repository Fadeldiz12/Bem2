@extends('layouts.app')

@section('title', 'Dashboard - BEM System')

@section('content')
    <div class="max-w-6xl mx-auto">

        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-purple-900 mb-2">Dashboard</h1>
            <p class="text-gray-500">Selamat datang, <strong>{{ session('admin_name') }}</strong>.
                Kelola Format Proposal dan Format LPJ secara digital.</p>
        </div>

        <!-- Stats Cards -->
        <div class="mb-8">
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500 font-semibold mb-1">Total Kegiatan</p>
                    <h3 class="text-3xl font-bold text-gray-900">
                        @if(session('admin_role') === 'super_admin')
                            {{ \App\Models\Kegiatan::count() }}
                        @else
                            {{ \App\Models\Kegiatan::where('user_id', session('admin_id'))->count() }}
                        @endif
                    </h3>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- ✅ Role-based Main Menus -->
        @if (session('admin_role') === 'super_admin')
            <!-- Superadmin Menu -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Proposal -->
                <div class="bg-white rounded-xl p-8 shadow-sm border border-gray-100">
                    <div class="w-14 h-14 bg-purple-600 rounded-xl flex items-center justify-center text-white mb-6">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 mb-3">Format Proposal</h2>
                    <p class="text-gray-500 mb-6">Buat dan kelola proposal anggaran dengan mudah.</p>
                    <a href="{{ route('proposal.index') }}"
                        class="inline-block bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-6 rounded-lg transition">
                        Buka Proposal &rarr;
                    </a>
                </div>

                <!-- LPJ -->
                <div class="bg-white rounded-xl p-8 shadow-sm border border-gray-100">
                    <div class="w-14 h-14 bg-purple-600 rounded-xl flex items-center justify-center text-white mb-6">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                            </path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 mb-3">Format LPJ</h2>
                    <p class="text-gray-500 mb-6">Dokumentasikan pengeluaran secara terstruktur.</p>
                    <a href="{{ route('lpj.index') }}"
                        class="inline-block bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-6 rounded-lg transition">
                        Buka LPJ &rarr;
                    </a>
                </div>
            </div>
        @elseif(session('admin_role') === 'admin')
            <!-- Admin Menu -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Proposal -->
                <div class="bg-white rounded-xl p-8 shadow-sm border border-gray-100">
                    <div class="w-14 h-14 bg-purple-600 rounded-xl flex items-center justify-center text-white mb-6">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 mb-3">Format Proposal</h2>
                    <p class="text-gray-500 mb-6">Buat dan kelola proposal anggaran dengan mudah.</p>
                    <a href="{{ route('proposal.index') }}"
                        class="inline-block bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-6 rounded-lg transition">
                        Buka Proposal &rarr;
                    </a>
                </div>

                <!-- LPJ -->
                <div class="bg-white rounded-xl p-8 shadow-sm border border-gray-100">
                    <div class="w-14 h-14 bg-purple-600 rounded-xl flex items-center justify-center text-white mb-6">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                            </path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 mb-3">Format LPJ</h2>
                    <p class="text-gray-500 mb-6">Dokumentasikan pengeluaran secara terstruktur.</p>
                    <a href="{{ route('lpj.index') }}"
                        class="inline-block bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-6 rounded-lg transition">
                        Buka LPJ &rarr;
                    </a>
                </div>
            </div>
        @else
            <!-- User Menu -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- My Proposals -->
                <div class="bg-white rounded-xl p-8 shadow-sm border border-gray-100">
                    <div class="w-14 h-14 bg-purple-600 rounded-xl flex items-center justify-center text-white mb-6">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 mb-3">Proposal Saya</h2>
                    <p class="text-gray-500 mb-6">Lihat proposal yang tersedia.</p>
                    <a href="{{ route('user.proposals') }}"
                        class="inline-block bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-6 rounded-lg transition">
                        Lihat Proposal &rarr;
                    </a>
                </div>

                <!-- My LPJ -->
                <div class="bg-white rounded-xl p-8 shadow-sm border border-gray-100">
                    <div class="w-14 h-14 bg-purple-600 rounded-xl flex items-center justify-center text-white mb-6">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                            </path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 mb-3">LPJ Saya</h2>
                    <p class="text-gray-500 mb-6">Lihat laporan LPJ yang tersedia.</p>
                    <a href="{{ route('user.lpj') }}"
                        class="inline-block bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-6 rounded-lg transition">
                        Lihat LPJ &rarr;
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection
