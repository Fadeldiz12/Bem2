@extends('layouts.admin')

@section('title', 'Kelola Users')

@section('content')
<div class="container-fluid p-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2 class="fw-bold">Kelola Users</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah User
            </a>
        </div>
    </div>

    @if ($users->count())
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $index => $user)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $user->name }}</strong></td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if ($user->role === 'super_admin')
                                <span class="badge bg-danger">Super Admin</span>
                            @elseif ($user->role === 'admin')
                                <span class="badge bg-warning text-dark">Admin</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($user->role) }}</span>
                            @endif
                        </td>
                        <td>
                            @if ($user->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Non-aktif</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('users.delete', $user->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Hapus" onclick="return confirm('Yakin ingin menghapus user ini?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> Belum ada users. <a href="{{ route('users.create') }}">Tambah user sekarang</a>
    </div>
    @endif
</div>
@endsection
