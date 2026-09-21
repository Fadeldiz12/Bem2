<!-- Path asli: resources/views/admin/reset-password.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Reset Password - BEM Polmed</title>
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
  .otp-input {
    letter-spacing: 10px;
    font-size: 22px;
    text-align: center;
    font-weight: 600;
  }
  .form-control:focus {
    border-color: #7c3aed;
    box-shadow: 0 0 0 0.2rem rgba(124,58,237,.25);
  }
  .btn-link-resend {
    font-size: 13px;
    color: #7c3aed;
    text-decoration: none;
  }
  .btn-link-resend:hover {
    text-decoration: underline;
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
          <small>Verifikasi OTP & Password Baru</small>
        </div>

        <div class="card-body p-4">

          <p class="text-muted small mb-3">
            Kode OTP telah dikirim ke <strong>{{ $email }}</strong>. Masukkan kode tersebut beserta password baru Anda.
          </p>

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
          @if($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0 ps-3">
                @foreach($errors->all() as $err)
                  <li>{{ $err }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form method="POST" action="{{ route('admin.reset-password.post') }}">
            @csrf
            <div class="mb-3">
              <label class="form-label fw-semibold">Kode OTP</label>
              <input type="text" name="otp" class="form-control otp-input" required
                     maxlength="6" inputmode="numeric" pattern="[0-9]{6}"
                     placeholder="------" autocomplete="one-time-code">
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Password Baru</label>
              <input type="password" name="password" class="form-control" required
                     autocomplete="new-password">
            </div>
            <div class="mb-4">
              <label class="form-label fw-semibold">Konfirmasi Password Baru</label>
              <input type="password" name="password_confirmation" class="form-control" required
                     autocomplete="new-password">
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
              Reset Password
            </button>
          </form>

          <form method="POST" action="{{ route('admin.reset-password.resend') }}" class="text-center mt-3">
            @csrf
            <button type="submit" class="btn btn-link btn-link-resend p-0">
              Tidak menerima kode? Kirim ulang OTP
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
