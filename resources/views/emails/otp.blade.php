<!-- Path asli: resources/views/emails/otp.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Kode OTP Reset Password</title>
</head>
<body style="margin:0;padding:0;background:#f3f4f6;font-family:Arial,Helvetica,sans-serif;">
  <div style="max-width:480px;margin:30px auto;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,.08);">

    <div style="background:linear-gradient(135deg,#4a1e8a,#7c3aed);padding:24px;text-align:center;color:#fff;">
      <h2 style="margin:0;">🏛️ BEM Polmed</h2>
      <p style="margin:4px 0 0;font-size:13px;opacity:.9;">Reset Password Admin</p>
    </div>

    <div style="padding:28px 24px;color:#111827;">
      <p>Halo <strong>{{ $name }}</strong>,</p>
      <p>Kami menerima permintaan untuk mereset password akun admin Anda. Gunakan kode OTP berikut untuk melanjutkan:</p>

      <div style="text-align:center;margin:28px 0;">
        <span style="display:inline-block;font-size:32px;letter-spacing:8px;font-weight:bold;color:#4a1e8a;background:#f5f3ff;padding:14px 24px;border-radius:10px;">
          {{ $otp }}
        </span>
      </div>

      <p>Kode ini berlaku selama <strong>10 menit</strong> dan hanya bisa digunakan satu kali.</p>
      <p style="color:#6b7280;font-size:13px;">Jika Anda tidak merasa meminta reset password, abaikan email ini. Jangan bagikan kode OTP ini kepada siapa pun, termasuk pihak yang mengaku sebagai admin BEM Polmed.</p>
    </div>

    <div style="padding:16px 24px;background:#f9fafb;text-align:center;color:#9ca3af;font-size:12px;">
      Email ini dikirim otomatis, mohon tidak membalas.
    </div>

  </div>
</body>
</html>
