@extends('layouts.admin')

@section('content')

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin: 0;">Manajemen Petugas PST</h3>
        <button class="btn btn-primary" onclick="showModal('tambahAdminModal')">
            <i class="fas fa-plus"></i> Add
        </button>
    </div>
    
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th style="width: 80px;">Foto</th>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>Status Jaga</th>
                    <th style="width: 80px;">Urutan</th>
                    <th style="width: 100px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admins as $admin)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <img src="{{ $admin->photo_url }}" alt="Foto" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                        </td>
                        <td>{{ $admin->name }}</td>
                        <td>{{ $admin->jabatan }}</td>
                        <td>
                            <span style="font-weight: bold; color: {{ $admin->status_jaga == 'Sedang Bertugas' ? '#28a745' : '#dc3545' }}">
                                {{ $admin->status_jaga }}
                            </span>
                        </td>
                        <td>{{ $admin->urutan }}</td>
                        <td>
                            <button class="btn btn-warning" style="padding: 5px 10px;" onclick="showModal('editAdminModal{{ $admin->id }}')">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            <button class="btn btn-danger" style="padding: 5px 10px;" onclick="confirmDelete('delete-admin-form-{{ $admin->id }}')">
                                <i class="fas fa-trash"></i>
                            </button>
                            
                            {{-- Form Hapus (Tersembunyi) --}}
                            <form id="delete-admin-form-{{ $admin->id }}" action="{{ route('admin.admin_pst.destroy', $admin->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    {{-- Modal Edit untuk setiap admin --}}
                    @include('admin.admin_pst.edit-modal', ['admin' => $admin, 'statusOptions' => $statusOptions])
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center;">Belum ada data Admin PST.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection

{{-- Modal Tambah Admin PST --}}
@include('admin.admin_pst.create-modal', ['statusOptions' => $statusOptions])