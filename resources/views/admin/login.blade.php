<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Login Admin - BEM Polmed</title>
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
  .card-header {
    background: linear-gradient(135deg, #4a1e8a, #7c3aed);
    border-radius: 16px 16px 0 0;
  }
  .btn-primary {
    background: linear-gradient(135deg, #4a1e8a, #7c3aed);
    border: none;
  }
  .btn-primary:hover {
    background: linear-gradient(135deg, #3b1870, #6d28d9);
    border: none;
  }
  .btn-outline-register {
    border: 2px solid #7c3aed;
    color: #7c3aed;
    background: transparent;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.2s ease;
  }
  .btn-outline-register:hover {
    background: #7c3aed;
    color: #fff;
  }
  .divider {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #9ca3af;
    font-size: 13px;
    margin: 16px 0;
  }
  .divider::before, .divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #e5e7eb;
  }
  .form-control:focus {
    border-color: #7c3aed;
    box-shadow: 0 0 0 0.2rem rgba(124,58,237,.25);
  }
</style>
</head>
<body>
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-4">
      <div class="card login-card">

        <div class="card-header text-white text-center py-4">
          <h4 class="mb-0 fw-bold">🏛️ BEM Polmed</h4>
          <small>Admin Dashboard</small>
        </div>

        <div class="card-body p-4">

          <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary rounded-pill mb-3">
            ← Kembali ke Beranda
          </a>

          @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              {{ session('error') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          @endif
          @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              {{ session('success') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          @endif

          <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf
            <div class="mb-3">
              <label class="form-label fw-semibold">Email</label>
              <input type="email" name="email" class="form-control" required
                     value="{{ old('email') }}"
                     autocomplete="email">
            </div>
            <div class="mb-2">
              <label class="form-label fw-semibold">Password</label>
              <input type="password" name="password" class="form-control" required
                     autocomplete="current-password">
            </div>
            <div class="text-end mb-3">
              <a href="{{ route('admin.forgot-password') }}" class="small" style="color:#7c3aed;text-decoration:none;">
                Lupa Password?
              </a>
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
              Masuk
            </button>
          </form>

          <div class="divider">atau</div>

          <a href="{{ route('admin.register') }}" class="btn btn-outline-register w-100 py-2">
            Daftar Akun Baru
          </a>

        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>