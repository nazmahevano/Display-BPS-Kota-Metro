@extends('layouts.admin')

@section('content')

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin: 0;">Manajemen Running Text</h3>
        <button class="btn btn-primary" onclick="showModal('tambahRunningTextModal')">
            <i class="fas fa-plus"></i> Add
        </button>
    </div>
    
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Konten Teks</th>
                    {{-- Hapus kolom Status --}}
                    <th style="width: 80px;">Urutan</th>
                    <th style="width: 150px;">Aksi</th> {{-- Lebarkan sedikit untuk 3 tombol --}}
                </tr>
            </thead>
            <tbody>
                @forelse($runningTexts as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($item->content, 80) }}</td>
                        {{-- Hapus data Status --}}
                        <td>{{ $item->urutan }}</td>
                        <td style="display: flex; gap: 5px; justify-content: center;">
                            {{-- Tombol Toggle Status (Gunakan lebar tetap) --}}
                            @php
                                $isAktif = $item->status == 'Aktif';
                                $newStatus = $isAktif ? 'Tidak Aktif' : 'Aktif';
                            @endphp
                            <form action="{{ route('admin.running_texts.toggle_status', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengubah status menjadi: {{ $newStatus }}?');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn {{ $isAktif ? 'btn-success' : 'btn-secondary' }}" 
                                        style="padding: 5px 10px; font-size: 10px; width: 60px; {{ $isAktif ? '' : 'color: #333; border-color: #6c757d;' }}" {{-- BARU: Menambahkan warna teks gelap dan border untuk kontras --}}
                                        title="Status: {{ $item->status }}">
                                    {{ $isAktif ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </form>
                            
                            {{-- Tombol Edit Konten (Ikon Saja) --}}
                            <button class="btn btn-info" style="padding: 5px 10px; width: 40px;" onclick="showModal('editRunningTextModal{{ $item->id }}')" title="Edit Data">
                                <i class="fas fa-pencil-alt"></i>
                            </button>

                            {{-- Tombol Hapus (Ikon Saja) --}}
                            <button class="btn btn-danger" style="padding: 5px 10px; width: 40px;" onclick="confirmDelete('delete-running-text-form-{{ $item->id }}')" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                            
                            {{-- Form Hapus (Tersembunyi) --}}
                            <form id="delete-running-text-form-{{ $item->id }}" action="{{ route('admin.running_texts.destroy', $item->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    {{-- Modal Edit untuk setiap item --}}
                    @include('admin.running_texts.edit-modal', ['item' => $item, 'statusOptions' => $statusOptions])
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center;">Belum ada data Running Text.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection

{{-- Modal Tambah Running Text --}}
@include('admin.running_texts.create-modal', ['statusOptions' => $statusOptions])