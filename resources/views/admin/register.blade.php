<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Register Admin - BEM Polmed</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
  body {
    background: linear-gradient(135deg, #4a1e8a, #7c3aed);
    min-height: 100vh;
    display: flex;
    align-items: center;
  }
  .login-card {
    border-radius: 16px;
    box-shadow: 0 20px 60px rgba(0,0,0,.3);
    border: none;
  }
  .btn-primary {
    background: linear-gradient(135deg, #4a1e8a, #7c3aed);
    border: none;
  }
  .btn-primary:hover {
    background: linear-gradient(135deg, #3b1870, #6d28d9);
    border: none;
  }
  .card-header {
    background: linear-gradient(135deg, #4a1e8a, #7c3aed);
    border-radius: 16px 16px 0 0;
  }
  .form-control:focus {
    border-color: #7c3aed;
    box-shadow: 0 0 0 0.2rem rgba(124, 58, 237, 0.25);
  }
  .password-wrapper {
    position: relative;
  }
  .toggle-password {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    color: #6b7280;
    padding: 0;
    font-size: 14px;
  }
  .strength-bar {
    height: 4px;
    border-radius: 2px;
    transition: all 0.3s ease;
    margin-top: 6px;
  }
  .link-login {
    color: #7c3aed;
    text-decoration: none;
    font-weight: 600;
  }
  .link-login:hover {
    color: #4a1e8a;
  }
</style>
</head>
<body>
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card login-card">

        {{-- Header --}}
        <div class="card-header text-white text-center py-4">
          <h4 class="mb-0 fw-bold">🏛️ BEM Polmed</h4>
          <small>Daftar Akun Baru</small>
        </div>

        <div class="card-body p-4">

          <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary rounded-pill mb-3">
            ← Kembali ke Beranda
          </a>

          {{-- Alert Error --}}
          @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              {{ session('error') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          @endif

          {{-- Alert Success --}}
          @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              {{ session('success') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          @endif

          {{-- Validation Errors --}}
          @if($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form method="POST" action="{{ route('admin.register.post') }}" id="registerForm">
            @csrf

            {{-- Name --}}
            <div class="mb-3">
              <label class="form-label fw-semibold">Nama Lengkap</label>
              <input
                type="text"
                name="name"
                class="form-control @error('name') is-invalid @enderror"
                required
                placeholder="Nama lengkap Anda"
                value="{{ old('name') }}"
                maxlength="100"
                autocomplete="name"
              >
              @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- Email --}}
            <div class="mb-3">
              <label class="form-label fw-semibold">Email</label>
              <input
                type="email"
                name="email"
                class="form-control @error('email') is-invalid @enderror"
                required
                value="{{ old('email') }}"
                maxlength="255"
                autocomplete="email"
              >
              @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- Password --}}
            <div class="mb-3">
              <label class="form-label fw-semibold">Password</label>
              <div class="password-wrapper">
                <input
                  type="password"
                  name="password"
                  id="password"
                  class="form-control pe-5 @error('password') is-invalid @enderror"
                  required
                  placeholder="Minimal 8 karakter"
                  maxlength="255"
                  autocomplete="new-password"
                  oninput="checkStrength(this.value)"
                >
                <button type="button" class="toggle-password" onclick="togglePassword('password', 'eyeIcon1')">
                  <span id="eyeIcon1">👁️</span>
                </button>
                @error('password')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              {{-- Password Strength Bar --}}
              <div class="strength-bar bg-secondary" id="strengthBar"></div>
              <small id="strengthText" class="text-muted"></small>
            </div>

            {{-- Confirm Password --}}
            <div class="mb-4">
              <label class="form-label fw-semibold">Konfirmasi Password</label>
              <div class="password-wrapper">
                <input
                  type="password"
                  name="password_confirmation"
                  id="password_confirmation"
                  class="form-control pe-5"
                  required
                  placeholder="Ulangi password"
                  maxlength="255"
                  autocomplete="new-password"
                >
                <button type="button" class="toggle-password" onclick="togglePassword('password_confirmation', 'eyeIcon2')">
                  <span id="eyeIcon2">👁️</span>
                </button>
              </div>
            </div>

            {{-- Info Role --}}
            <div class="alert alert-info py-2 mb-3" style="font-size:13px;">
              ℹ️ Akun baru akan terdaftar dengan role <strong>user</strong>. Hubungi super admin untuk mengubah hak akses.
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
              Daftar Sekarang
            </button>

            <div class="text-center mt-3">
              <small class="text-muted">Sudah punya akun? <a href="{{ route('admin.login') }}" class="link-login">Masuk di sini</a></small>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Toggle show/hide password
  function togglePassword(fieldId, iconId) {
    const field = document.getElementById(fieldId);
    const icon  = document.getElementById(iconId);
    if (field.type === 'password') {
      field.type = 'text';
      icon.textContent = '🙈';
    } else {
      field.type = 'password';
      icon.textContent = '👁️';
    }
  }

  // Password strength checker
  function checkStrength(password) {
    const bar  = document.getElementById('strengthBar');
    const text = document.getElementById('strengthText');

    let score = 0;
    if (password.length >= 8)               score++;
    if (password.length >= 12)              score++;
    if (/[A-Z]/.test(password))            score++;
    if (/[0-9]/.test(password))            score++;
    if (/[^A-Za-z0-9]/.test(password))     score++;

    const levels = [
      { label: '',           color: '#e5e7eb', width: '0%'   },
      { label: 'Sangat Lemah', color: '#ef4444', width: '20%'  },
      { label: 'Lemah',        color: '#f97316', width: '40%'  },
      { label: 'Cukup',        color: '#eab308', width: '60%'  },
      { label: 'Kuat',         color: '#22c55e', width: '80%'  },
      { label: 'Sangat Kuat',  color: '#16a34a', width: '100%' },
    ];

    const level = levels[score] || levels[0];
    bar.style.backgroundColor = level.color;
    bar.style.width            = level.width;
    text.textContent           = level.label;
    text.style.color           = level.color;
  }
</script>
</body>
</html>