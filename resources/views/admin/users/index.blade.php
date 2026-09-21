@extends('layouts.admin')
@section('title','Manajemen User')
@section('content')

<div class="user-mgmt">
  <div class="card border-0 shadow-sm">

    <div class="card-header user-mgmt__header d-flex align-items-center justify-content-between flex-wrap gap-2">
      <div class="d-flex align-items-center gap-2">
        <span class="user-mgmt__icon"><i class="bi bi-people"></i></span>
        <span class="fw-semibold">Manajemen User</span>
      </div>
      <span class="badge user-mgmt__count">{{ $users->count() }} user</span>
    </div>

    <div class="card-body">

      {{-- Filter & Search --}}
      <form method="GET" class="row g-2 align-items-end mb-4">
        <div class="col-md-5">
          <label for="search" class="form-label small text-muted mb-1">Cari user</label>
          <div class="input-group input-group-sm">
            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
            <input type="text" id="search" name="search" class="form-control border-start-0"
                   placeholder="Cari nama atau email..."
                   value="{{ $search }}">
          </div>
        </div>
        <div class="col-md-3">
          <label for="role" class="form-label small text-muted mb-1">Role</label>
          <select id="role" name="role" class="form-select form-select-sm">
            <option value="">Semua Role</option>
            <option value="super_admin" {{ $role === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
            <option value="user"        {{ $role === 'user'        ? 'selected' : '' }}>Admin (User)</option>
          </select>
        </div>
        <div class="col-auto d-flex gap-2">
          <button type="submit" class="btn btn-primary btn-sm">
            <i class="bi bi-search me-1"></i>Cari
          </button>
          <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
          </a>
        </div>
      </form>

      {{-- Tabel --}}
      @if($users->isEmpty())
        <div class="text-center text-muted py-5">
          <i class="bi bi-search display-6 d-block mb-2 opacity-50"></i>
          <p class="mb-1">Tidak ada user ditemukan.</p>
          <small>Coba ubah kata kunci pencarian atau filter role.</small>
        </div>
      @else
      <div class="table-responsive user-mgmt__table-wrap">
        <table class="table table-hover align-middle mb-0 user-mgmt__table">
          <thead>
            <tr>
              <th style="width:40px">#</th>
              <th>Nama</th>
              <th>Email</th>
              <th style="width:150px">Role</th>
              <th style="width:100px">Status</th>
              <th style="width:150px">Login Terakhir</th>
              <th style="width:110px" class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($users as $i => $u)
            @php $isSelf = $u->id === session('admin_id'); @endphp
            <tr class="{{ $isSelf ? 'user-mgmt__row--self' : '' }}">
              <td class="text-muted small" data-label="#">{{ $i + 1 }}</td>

              <td data-label="Nama">
                <div class="d-flex align-items-center gap-2">
                  <span class="user-mgmt__avatar {{ $u->role === 'super_admin' ? 'user-mgmt__avatar--super' : '' }}">
                    {{ strtoupper(substr($u->name, 0, 1)) }}
                  </span>
                  <div>
                    <span class="fw-medium">{{ $u->name }}</span>
                    @if($isSelf)
                      <span class="badge user-mgmt__badge-you ms-1">Anda</span>
                    @endif
                  </div>
                </div>
              </td>

              <td data-label="Email"><small class="text-muted">{{ $u->email }}</small></td>

              {{-- Role — bisa diubah langsung --}}
              <td data-label="Role">
                @if($isSelf)
                  <span class="badge user-mgmt__badge-role--super">
                    <i class="bi bi-shield-lock me-1"></i>{{ $u->role === 'super_admin' ? 'Super Admin' : 'Admin' }}
                  </span>
                @else
                  <form method="POST" action="{{ route('admin.users.role', $u->id) }}">
                    @csrf
                    <label class="visually-hidden" for="role-{{ $u->id }}">Ubah role {{ $u->name }}</label>
                    <select id="role-{{ $u->id }}" name="role"
                            class="form-select form-select-sm user-mgmt__role-select {{ $u->role === 'super_admin' ? 'is-super' : '' }}"
                            onchange="this.form.submit()">
                      <option value="user"         {{ $u->role === 'user'         ? 'selected' : '' }}>User</option>
                      <option value="admin"        {{ $u->role === 'admin'        ? 'selected' : '' }}>Admin</option>
                      <option value="super_admin" {{ $u->role === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                    </select>
                  </form>
                @endif
              </td>

              {{-- Status aktif --}}
              <td data-label="Status">
                @if($u->is_active)
                  <span class="badge user-mgmt__badge-status--active"><i class="bi bi-dot"></i>Aktif</span>
                @else
                  <span class="badge user-mgmt__badge-status--inactive"><i class="bi bi-dot"></i>Nonaktif</span>
                @endif
              </td>

              {{-- Login terakhir --}}
              <td data-label="Login Terakhir">
                <small class="text-muted" title="{{ $u->last_login ? $u->last_login->format('d M Y, H:i') : '' }}">
                  {{ $u->last_login ? $u->last_login->diffForHumans() : 'Belum pernah' }}
                </small>
              </td>

              {{-- Aksi --}}
              <td data-label="Aksi" class="text-end">
                @if(!$isSelf)
                <div class="d-flex gap-1 justify-content-end">
                  {{-- Toggle aktif/nonaktif --}}
                  <form method="POST" action="{{ route('admin.users.toggle', $u->id) }}">
                    @csrf
                    <button class="btn btn-sm user-mgmt__btn-icon {{ $u->is_active ? 'text-warning' : 'text-success' }}"
                            title="{{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                            onclick="return confirm('{{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }} akun {{ $u->name }}?')">
                      <i class="bi bi-{{ $u->is_active ? 'pause-circle' : 'play-circle' }}"></i>
                    </button>
                  </form>

                  {{-- Hapus --}}
                  <form method="POST" action="{{ route('admin.users.destroy', $u->id) }}">
                    @csrf
                    <button class="btn btn-sm user-mgmt__btn-icon text-danger" title="Hapus"
                            onclick="return confirm('Hapus akun {{ $u->name }}? Tindakan ini tidak bisa dibatalkan.')">
                      <i class="bi bi-trash"></i>
                    </button>
                  </form>
                </div>
                @else
                  <span class="text-muted small">—</span>
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      @endif

    </div>
  </div>
</div>

<style>
  .user-mgmt {
    --um-accent: #4a1e8a;
    --um-accent-soft: rgba(74, 30, 138, .08);
    --um-accent-soft-2: rgba(74, 30, 138, .16);
    --um-success: #16a34a;
    --um-success-soft: rgba(22, 163, 74, .1);
    --um-danger: #dc2626;
    --um-danger-soft: rgba(220, 38, 38, .1);
    --um-border: #edeef1;
  }

  .user-mgmt .card { border-radius: 14px; overflow: hidden; }

  .user-mgmt__header {
    background: linear-gradient(135deg, var(--um-accent) 0%, #7247b3 100%);
    color: #fff;
    padding: .9rem 1.25rem;
    border: none;
  }
  .user-mgmt__icon {
    display: inline-flex; align-items: center; justify-content: center;
    width: 30px; height: 30px; border-radius: 8px;
    background: rgba(255,255,255,.18);
  }
  .user-mgmt__count {
    background: rgba(255,255,255,.2);
    color: #fff;
    font-weight: 500;
    padding: .4em .8em;
    border-radius: 999px;
  }

  .user-mgmt__table thead th {
    font-size: .72rem;
    text-transform: uppercase;
    letter-spacing: .04em;
    color: #6b7280;
    background: #fafafa;
    border-bottom: 2px solid var(--um-border);
    padding: .75rem;
  }
  .user-mgmt__table td { padding: .7rem .75rem; border-bottom: 1px solid var(--um-border); vertical-align: middle; }
  .user-mgmt__table tbody tr:last-child td { border-bottom: none; }
  .user-mgmt__table tbody tr:hover { background: #faf9fc; }

  .user-mgmt__row--self { background: var(--um-accent-soft) !important; }
  .user-mgmt__row--self:hover { background: var(--um-accent-soft-2) !important; }

  .user-mgmt__avatar {
    width: 34px; height: 34px; flex: 0 0 34px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-weight: 600; font-size: .8rem;
    background: #e5e7eb; color: #4b5563;
  }
  .user-mgmt__avatar--super { background: var(--um-accent-soft-2); color: var(--um-accent); }

  .user-mgmt__badge-you {
    background: #fff; color: var(--um-accent);
    border: 1px solid var(--um-accent-soft-2);
    font-weight: 500; font-size: .65rem;
  }

  .user-mgmt__badge-role--super {
    background: var(--um-accent-soft-2); color: var(--um-accent);
    font-weight: 500; padding: .45em .7em; border-radius: 6px;
  }

  .user-mgmt__role-select {
    font-size: .75rem; min-width: 120px; border-radius: 6px;
  }
  .user-mgmt__role-select.is-super {
    border-color: var(--um-accent-soft-2);
    color: var(--um-accent);
    font-weight: 500;
  }

  .user-mgmt__badge-status--active,
  .user-mgmt__badge-status--inactive {
    font-weight: 500; font-size: .72rem;
    padding: .35em .65em .35em .45em; border-radius: 999px;
    display: inline-flex; align-items: center;
  }
  .user-mgmt__badge-status--active { background: var(--um-success-soft); color: var(--um-success); }
  .user-mgmt__badge-status--active i { font-size: 1.3rem; }
  .user-mgmt__badge-status--inactive { background: var(--um-danger-soft); color: var(--um-danger); }
  .user-mgmt__badge-status--inactive i { font-size: 1.3rem; }

  .user-mgmt__btn-icon {
    width: 32px; height: 32px; padding: 0;
    display: inline-flex; align-items: center; justify-content: center;
    border: 1px solid var(--um-border); border-radius: 8px; background: #fff;
    transition: background .15s ease, transform .1s ease;
  }
  .user-mgmt__btn-icon:hover { background: #f3f4f6; transform: translateY(-1px); }
  .user-mgmt__btn-icon:focus-visible,
  .user-mgmt__role-select:focus-visible {
    outline: 2px solid var(--um-accent); outline-offset: 2px;
  }

  @media (prefers-reduced-motion: reduce) {
    .user-mgmt__btn-icon { transition: none; }
  }

  /* Stack rows into cards on small screens */
  @media (max-width: 767.98px) {
    .user-mgmt__table thead { display: none; }
    .user-mgmt__table, .user-mgmt__table tbody, .user-mgmt__table tr, .user-mgmt__table td {
      display: block; width: 100%;
    }
    .user-mgmt__table tr {
      border: 1px solid var(--um-border); border-radius: 10px;
      margin-bottom: .75rem; padding: .5rem .75rem;
    }
    .user-mgmt__table td {
      border-bottom: none; padding: .35rem 0;
      display: flex; justify-content: space-between; align-items: center; gap: .5rem;
    }
    .user-mgmt__table td[data-label]::before {
      content: attr(data-label);
      font-size: .68rem; text-transform: uppercase; letter-spacing: .03em;
      color: #9ca3af; font-weight: 600;
    }
    .user-mgmt__table td[data-label="Nama"],
    .user-mgmt__table td[data-label="Email"] {
      display: block;
    }
    .user-mgmt__table td[data-label="Nama"]::before,
    .user-mgmt__table td[data-label="Email"]::before {
      display: block; margin-bottom: .2rem;
    }
  }
</style>

@endsection