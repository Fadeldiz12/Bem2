<!-- Path asli: resources/views/admin/forgot-password.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Lupa Password - BEM Polmed</title>
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
          <small>Lupa Password</small>
        </div>

        <div class="card-body p-4">

          <a href="{{ route('admin.login') }}" class="btn btn-sm btn-outline-secondary rounded-pill mb-3">
            ← Kembali ke Login
          </a>

          <p class="text-muted small mb-3">Masukkan email akun admin Anda. Kami akan mengirimkan kode OTP ke Gmail Anda untuk mereset password.</p>

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
          @error('email')
            <div class="alert alert-danger">{{ $message }}</div>
          @enderror

          <form method="POST" action="{{ route('admin.forgot-password.post') }}">
            @csrf
            <div class="mb-4">
              <label class="form-label fw-semibold">Email</label>
              <input type="email" name="email" class="form-control" required
                     placeholder="admin@bempolmed.ac.id"
                     value="{{ old('email') }}"
                     autocomplete="email">
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
              Kirim Kode OTP
            </button>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
