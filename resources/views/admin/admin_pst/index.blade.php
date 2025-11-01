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
                    {{-- Hapus kolom Status Jaga --}}
                    <th style="width: 180px;">Aksi</th> {{-- Lebarkan sedikit untuk 3 tombol --}}
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
                        {{-- Hapus data Status Jaga --}}
                        <td style="display: flex; gap: 5px;">
                            {{-- Tombol Toggle Status (Menggantikan tombol edit lama) --}}
                            @php
                                $isBertugas = $admin->status_jaga == 'Sedang Bertugas';
                                $newStatus = $isBertugas ? 'Tidak Bertugas' : 'Sedang Bertugas';
                            @endphp
                            {{-- Perhatian: Anda perlu menambahkan route 'admin.admin_pst.toggle_status' di routes/web.php --}}
                            <form action="{{ route('admin.admin_pst.toggle_status', $admin->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengubah status menjadi: {{ $newStatus }}?');" style="flex-grow: 1;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn {{ $isBertugas ? 'btn-success' : 'btn-secondary' }}" 
                                        style="padding: 5px 10px; font-size: 10px; width: 60px; {{ $isBertugas ? '' : 'color: #333; border-color: #6c757d;' }}" {{-- BARU: Menambahkan warna teks gelap dan border untuk kontras --}}
                                        title="Status Jaga: {{ $admin->status_jaga }}">
                                    {{ $isBertugas ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </form>
                            
                            {{-- Tombol Edit Nama/Foto (Modal) --}}
                            <button class="btn btn-info" style="padding: 5px 10px; min-width: 40px;" onclick="showModal('editAdminModal{{ $admin->id }}')" title="Edit Data">
                                <i class="fas fa-pencil-alt"></i>
                            </button>

                            {{-- Tombol Hapus --}}
                            <button class="btn btn-danger" style="padding: 5px 10px; min-width: 40px;" onclick="confirmDelete('delete-admin-form-{{ $admin->id }}')" title="Hapus">
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
                        <td colspan="6" style="text-align: center;">Belum ada data Admin PST.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection

{{-- Modal Tambah Admin PST --}}
@include('admin.admin_pst.create-modal', ['statusOptions' => $statusOptions])