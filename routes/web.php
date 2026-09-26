<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\SeoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;

// ===== Modul RAB (Kegiatan/Proposal/LPJ) — digabung dari project kedua =====
use App\Http\Controllers\Rab\RabIndexController;
use App\Http\Controllers\Rab\KegiatanController;
use App\Http\Controllers\Rab\SieController;
use App\Http\Controllers\Rab\BonController;
use App\Http\Controllers\Rab\ItemController;
use App\Models\User;

// ===================== PUBLIC =====================
Route::middleware(['throttle:public'])->group(function () {
    Route::get('/', [PublicController::class, 'index'])->name('home');
    Route::get('/profil', [PublicController::class, 'profil'])->name('profil');
    Route::get('/kementerian/{id}', [PublicController::class, 'kementerianDetail'])->name('kementerian.detail');
    Route::get('/pengurus', [PublicController::class, 'pengurus'])->name('pengurus');
    Route::get('/struktur-organisasi', [PublicController::class, 'strukturOrganisasi'])->name('struktur');
    Route::get('/arsip-kabinet', [PublicController::class, 'arsipKabinet'])->name('arsip');
    Route::get('/arsip-kabinet/{id}', [PublicController::class, 'arsipKabinetDetail'])->name('arsip.detail');
    Route::get('/layanan/jadwal-peminjaman', [PublicController::class, 'jadwal'])->name('jadwal');
    Route::get('/layanan/format-surat', [PublicController::class, 'formatSurat'])->name('format-surat');
    Route::get('/layanan/format-surat/download/{id}/{type?}', [PublicController::class, 'downloadSurat'])->name('download-surat');
    Route::get('/hubungi/kontak', [PublicController::class, 'kontak'])->name('kontak');
    Route::get('/hubungi/media-partner', [PublicController::class, 'mediaPartner'])->name('media-partner');
    Route::get('/berita', [PublicController::class, 'berita'])->name('berita');
    Route::get('/berita/{slug}', [PublicController::class, 'beritaDetail'])->name('berita.detail');

    // SEO: peta situs & robots.txt (public/robots.txt statis sudah dihapus agar route ini yang dipakai)
    Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
    Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');
});

// ===================== API =====================
Route::middleware(['noindex', 'throttle:api-public'])->group(function () {
    Route::get('/api/activities', [ApiController::class, 'activities'])->name('api.activities');
    Route::get('/api/departments/{ministryId}', [Admin\MemberController::class, 'getDepartments'])->name('api.departments');
    Route::get('/api/ministries/{yearId}', [Admin\MemberController::class, 'getMinistriesByYear'])->name('api.ministries');
});


// ===================== ADMIN AUTH =====================
// Semua route auth pakai throttle ketat (10 req/menit)
// 'noindex' = halaman privat tidak boleh muncul di hasil pencarian Google
Route::middleware(['noindex', 'throttle:auth'])->group(function () {
    Route::get('/login',     [Admin\AuthController::class, 'login'])->name('admin.login');
    Route::post('/login',    [Admin\AuthController::class, 'doLogin'])->name('admin.login.post');
    Route::get('/logout',    [Admin\AuthController::class, 'logout'])->name('admin.logout');
    Route::get('/register',  [Admin\AuthController::class, 'showRegister'])->name('admin.register');
    Route::post('/register', [Admin\AuthController::class, 'doRegister'])->name('admin.register.post');
});

// /admin dan /admin/login redirect ke /login
Route::prefix('admin')->middleware('noindex')->group(function () {
    Route::get('/', fn() => redirect()->route('admin.login'));
    Route::get('/login', fn() => redirect()->route('admin.login'));
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('admin.forgot-password');
    Route::post('/forgot-password', [AuthController::class, 'sendResetOtp'])->name('admin.forgot-password.post');
    Route::get('/reset-password', [AuthController::class, 'showResetPassword'])->name('admin.reset-password');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('admin.reset-password.post');
    Route::post('/reset-password/resend', [AuthController::class, 'resendOtp'])->name('admin.reset-password.resend');
});

// ===================== ADMIN PANEL =====================
Route::prefix('admin')->name('admin.')->middleware(['noindex', 'admin.session-expiry', 'admin.auth'])->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('kabinet')->name('kabinet.')->group(function () {
        Route::get('/', [Admin\ManagementYearController::class, 'index'])->name('index');
        Route::get('/tambah', [Admin\ManagementYearController::class, 'create'])->name('create');
        Route::post('/tambah', [Admin\ManagementYearController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [Admin\ManagementYearController::class, 'edit'])->name('edit');
        Route::post('/edit/{id}', [Admin\ManagementYearController::class, 'update'])->name('update');
        Route::post('/hapus/{id}', [Admin\ManagementYearController::class, 'destroy'])->name('destroy');
        Route::post('/publish/{id}', [Admin\ManagementYearController::class, 'publish'])->name('publish');
        Route::get('/preview/{id}', [Admin\ManagementYearController::class, 'preview'])->name('preview');
    });

    Route::prefix('kementerian')->name('kementerian.')->group(function () {
        Route::get('/', [Admin\MinistryController::class, 'index'])->name('index');
        Route::get('/tambah', [Admin\MinistryController::class, 'create'])->name('create');
        Route::post('/tambah', [Admin\MinistryController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [Admin\MinistryController::class, 'edit'])->name('edit');
        Route::post('/edit/{id}', [Admin\MinistryController::class, 'update'])->name('update');
        Route::post('/hapus/{id}', [Admin\MinistryController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('departemen')->name('departemen.')->group(function () {
        Route::get('/', [Admin\DepartmentController::class, 'index'])->name('index');
        Route::get('/tambah', [Admin\DepartmentController::class, 'create'])->name('create');
        Route::post('/tambah', [Admin\DepartmentController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [Admin\DepartmentController::class, 'edit'])->name('edit');
        Route::post('/edit/{id}', [Admin\DepartmentController::class, 'update'])->name('update');
        Route::post('/hapus/{id}', [Admin\DepartmentController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('pengurus')->name('pengurus.')->group(function () {
        Route::get('/', [Admin\MemberController::class, 'index'])->name('index');
        Route::get('/tambah', [Admin\MemberController::class, 'create'])->name('create');
        Route::post('/tambah', [Admin\MemberController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [Admin\MemberController::class, 'edit'])->name('edit');
        Route::post('/edit/{id}', [Admin\MemberController::class, 'update'])->name('update');
        Route::post('/hapus/{id}', [Admin\MemberController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('program-kerja')->name('program.')->group(function () {
        Route::get('/', [Admin\ProgramController::class, 'index'])->name('index');
        Route::get('/tambah', [Admin\ProgramController::class, 'create'])->name('create');
        Route::post('/tambah', [Admin\ProgramController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [Admin\ProgramController::class, 'edit'])->name('edit');
        Route::post('/edit/{id}', [Admin\ProgramController::class, 'update'])->name('update');
        Route::post('/hapus/{id}', [Admin\ProgramController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('berita')->name('berita.')->group(function () {
        Route::get('/', [Admin\PostController::class, 'index'])->name('index');
        Route::get('/tambah', [Admin\PostController::class, 'create'])->name('create');
        Route::post('/tambah', [Admin\PostController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [Admin\PostController::class, 'edit'])->name('edit');
        Route::post('/edit/{id}', [Admin\PostController::class, 'update'])->name('update');
        Route::post('/hapus/{id}', [Admin\PostController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('kegiatan')->name('kegiatan.')->group(function () {
        Route::get('/', [Admin\ActivityController::class, 'index'])->name('index');
        Route::get('/tambah', [Admin\ActivityController::class, 'create'])->name('create');
        Route::post('/tambah', [Admin\ActivityController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [Admin\ActivityController::class, 'edit'])->name('edit');
        Route::post('/edit/{id}', [Admin\ActivityController::class, 'update'])->name('update');
        Route::post('/hapus/{id}', [Admin\ActivityController::class, 'destroy'])->name('destroy');
        Route::post('/setujui/{id}', [Admin\ActivityController::class, 'approve'])->name('approve');
        Route::post('/tolak/{id}', [Admin\ActivityController::class, 'reject'])->name('reject');
    });

    Route::prefix('format-surat')->name('letter.')->group(function () {
        Route::get('/', [Admin\LetterFormatController::class, 'index'])->name('index');
        Route::get('/tambah', [Admin\LetterFormatController::class, 'create'])->name('create');
        Route::post('/tambah', [Admin\LetterFormatController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [Admin\LetterFormatController::class, 'edit'])->name('edit');
        Route::post('/edit/{id}', [Admin\LetterFormatController::class, 'update'])->name('update');
        Route::post('/hapus/{id}', [Admin\LetterFormatController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('media-partner')->name('media.')->group(function () {
        Route::get('/', [Admin\MediaPartnerController::class, 'index'])->name('index');
        Route::post('/update/{id}', [Admin\MediaPartnerController::class, 'update'])->name('update');
    });

    Route::get('/pengaturan', [Admin\SettingController::class, 'index'])->name('setting.index');
    Route::post('/pengaturan', [Admin\SettingController::class, 'update'])->name('setting.update');

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [Admin\UserController::class, 'index'])->name('index');
        Route::post('/toggle/{id}', [Admin\UserController::class, 'toggleActive'])->name('toggle');
        Route::post('/role/{id}', [Admin\UserController::class, 'updateRole'])->name('role');
        Route::post('/force-logout/{id}', [Admin\UserController::class, 'forceLogout'])->name('force-logout');
        Route::post('/hapus/{id}', [Admin\UserController::class, 'destroy'])->name('destroy');
    });
});

// =====================================================================
// MODUL RAB — Kegiatan, Proposal & LPJ (digabung dari project kedua)
// -----------------------------------------------------------------------
// Login memakai satu pintu yang sama dengan admin panel di atas
// (/login, Admin\AuthController, session admin_id/admin_role/dst).
//
// Controller  : App\Http\Controllers\Rab\*
// Views       : resources/views/rab/*
// Middleware  : 'role' (App\Http\Middleware\CheckRole), alias sudah
//               terdaftar di bootstrap/app.php
//   - 'role'                                -> wajib login, role apa saja
//   - 'role:super_admin,admin' / 'role:super_admin' / 'role:user' -> role tertentu
// =====================================================================

Route::get('/about', function () {
    return view('rab.about');
})->name('about');

// ----- Public landing page SIGMA BEM -----
Route::get('/rab', [RabIndexController::class, 'index'])->name('rab.index');

// ----- Wajib login, role apa saja -----
Route::middleware(['noindex', 'role'])->group(function () {
    Route::get('/dashboard', function () {
        return view('rab.dashboard');
    })->name('dashboard');

    Route::get('/profile', function () {
        return view('rab.profile');
    })->name('profile');
});

// ----- Superadmin & Admin: kelola Proposal + LPJ -----
Route::middleware(['noindex', 'role:super_admin,admin'])->group(function () {
    Route::get('/proposal', [KegiatanController::class, 'index'])->name('proposal.index');
    Route::get('/proposal/{id}/rab', [KegiatanController::class, 'show'])->name('proposal.show');
    Route::post('/proposal', [KegiatanController::class, 'store'])->name('proposal.store');
    Route::put('/proposal/{kegiatan}/edit', [KegiatanController::class, 'update'])->name('proposal.update');

    Route::post('/proposal/tambah-sie', [SieController::class, 'store'])->name('Sie.store');
    Route::put('/proposal/edit-sie/{sie}', [SieController::class, 'update'])->name('Sie.update');
    Route::delete('/proposal/hapus-sie/{sie}', [SieController::class, 'destroy'])->name('Sie.destroy');

    Route::post('/proposal/tambah-item', [ItemController::class, 'store'])->name('Item.store');
    Route::put('/proposal/edit-item/{item}', [ItemController::class, 'update'])->name('Item.update');
    Route::delete('/proposal/hapus-item/{item}', [ItemController::class, 'destroy'])->name('Item.destroy');
    Route::delete('/proposal/hapus-item-terpilih', [ItemController::class, 'destroySelected'])->name('Item.destroySelected');
    Route::delete('/proposal/{kegiatan}/hapus-semua-item', [ItemController::class, 'destroyAllForKegiatan'])->name('Item.destroyAllForKegiatan');

    // Diletakkan setelah route literal di atas ('/proposal/hapus-item-terpilih', dkk.)
    // karena '{kegiatan}' adalah wildcard satu segmen — kalau didaftarkan lebih dulu,
    // ia akan "menelan" request ke route literal tersebut duluan.
    Route::delete('/proposal/{kegiatan}', [KegiatanController::class, 'destroy'])->name('proposal.destroy');

    Route::get('/proposal/{id}/export-pdf', [KegiatanController::class, 'exportPdfRab'])->name('proposal.export.pdf');
    Route::get('/proposal/{id}/export-excel', [KegiatanController::class, 'exportExcelRab'])->name('proposal.export.excel');

    Route::get('/lpj', [KegiatanController::class, 'index'])->name('lpj.index');
    Route::get('/lpj/{id}/rab', [KegiatanController::class, 'show'])->name('lpj.show');

    Route::post('/lpj/tambah-bon', [BonController::class, 'store'])->name('Bon.store');
    Route::get('/lpj/bon/{bon}/detail', [BonController::class, 'getDetail'])->name('Bon.detail');
    Route::put('/lpj/edit-bon/{bon}', [BonController::class, 'update'])->name('Bon.update');
    Route::delete('/lpj/hapus-bon/{bon}', [BonController::class, 'destroy'])->name('Bon.destroy');

    Route::get('/lpj/{id}/export-pdf', [KegiatanController::class, 'exportPdf'])->name('lpj.export.pdf');
    Route::get('/lpj/{id}/export-excel', [KegiatanController::class, 'exportExcel'])->name('lpj.export.excel');

    Route::get('/lpj/create', function () {
        return view('rab.lpj.create');
    })->name('lpj.create');
});

// ----- Superadmin saja: kelola users, settings, log -----
Route::middleware(['noindex', 'role:super_admin'])->group(function () {
    Route::get('/users', function () {
        $users = User::all();
        return view('rab.users.index', compact('users'));
    })->name('users.index');

    Route::get('/users/create', function () {
        return view('rab.users.create');
    })->name('users.create');

    Route::get('/users/{id}/edit', function ($id) {
        $user = User::findOrFail($id);
        return view('rab.users.edit', compact('user'));
    })->name('users.edit');

    Route::post('/users', function () {
        $validated = request()->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:super_admin,admin,user',
            'is_active' => 'nullable|boolean',
        ]);
        
        $validated['password_hash'] = bcrypt($validated['password']);
        unset($validated['password']);
        $validated['is_active'] = request()->has('is_active') ? 1 : 0;
        
        User::create($validated);
        
        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan');
    })->name('users.store');

    Route::put('/users/{id}', function ($id) {
        $user = User::findOrFail($id);
        
        $validated = request()->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:super_admin,admin,user',
            'is_active' => 'nullable|boolean',
        ]);
        
        if ($validated['password']) {
            $validated['password_hash'] = bcrypt($validated['password']);
        }
        unset($validated['password']);
        
        $validated['is_active'] = request()->has('is_active') ? 1 : 0;
        
        $user->update($validated);
        
        return redirect()->route('users.index')->with('success', 'User berhasil diupdate');
    })->name('users.update');

    Route::delete('/users/{id}', function ($id) {
        $user = User::findOrFail($id);
        $user->delete();
        
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus');
    })->name('users.delete');

    Route::get('/settings', function () {
        return view('rab.settings.index');
    })->name('settings.index');

    Route::get('/activity-logs', function () {
        return view('rab.logs.index');
    })->name('logs.index');
});

// ----- Role user biasa saja: proposal & LPJ milik sendiri -----
Route::middleware(['noindex', 'role:user'])->group(function () {
    Route::get('/my-proposals', function () {
        return view('rab.proposal.index');
    })->name('user.proposals');

    Route::get('/my-lpj', function () {
        return view('rab.user.lpj');
    })->name('user.lpj');
});