<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class User extends Authenticatable
{
    protected $fillable = [
        'name', 'email', 'password_hash',
        'role', 'is_active', 'last_login', 'session_id',
        'reset_otp','reset_otp_expires_at',
    ];

    protected $hidden = ['password_hash', 'remember_token'];

    protected $casts = [
        'is_active'  => 'boolean',
        'last_login' => 'datetime',
    ];

    public $timestamps = true;

    // Override supaya Hash::check() baca kolom password_hash, bukan password
    public function getAuthPassword(): string
    {
        return $this->password_hash ?? '';
    }

    // Dipakai oleh AdminAuth middleware
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles, true);
    }

    public function isActive(): bool
    {
        return (bool) $this->is_active;
    }

    // Dipakai untuk indikator "online" di modul LPJ/Proposal
    // (sebelumnya method ini baca kolom Last_login di tabel Pengguna,
    // sekarang disatukan pakai last_login di tabel users).
    public function isOnline(): bool
    {
        if (!$this->last_login) {
            return false;
        }
        return Carbon::parse($this->last_login)->diffInMinutes(now()) <= 5;
    }

    // Menu sidebar modul LPJ/Proposal, disesuaikan dengan role gabungan
    // (super_admin, admin, user) dan nama-nama route yang dipertahankan
    // dari modul aslinya.
    public function getMenu(): array
    {
        return match ($this->role) {
            'super_admin' => [
                ['name' => 'Dashboard', 'route' => 'dashboard', 'icon' => '🏠'],
                ['name' => 'Proposal', 'route' => 'proposal.index', 'icon' => '📄'],
                ['name' => 'LPJ', 'route' => 'lpj.index', 'icon' => '📋'],
                ['name' => 'Users', 'route' => 'users.index', 'icon' => '👥'],
                ['name' => 'Settings', 'route' => 'settings.index', 'icon' => '⚙️'],
                ['name' => 'Logs', 'route' => 'logs.index', 'icon' => '📊'],
            ],
            'admin' => [
                ['name' => 'Dashboard', 'route' => 'dashboard', 'icon' => '🏠'],
                ['name' => 'Proposal', 'route' => 'proposal.index', 'icon' => '📄'],
                ['name' => 'LPJ', 'route' => 'lpj.index', 'icon' => '📋'],
            ],
            'user' => [
                ['name' => 'Dashboard', 'route' => 'dashboard', 'icon' => '🏠'],
                ['name' => 'Proposal Saya', 'route' => 'user.proposals', 'icon' => '📄'],
                ['name' => 'LPJ Saya', 'route' => 'user.lpj', 'icon' => '📋'],
            ],
            default => [
                ['name' => 'Dashboard', 'route' => 'dashboard', 'icon' => '🏠'],
            ],
        };
    }

    // Relasi ke modul LPJ/Proposal: kegiatan yang dibuat user ini.
    public function kegiatan(): HasMany
    {
        return $this->hasMany(Kegiatan::class, 'user_id');
    }
}
