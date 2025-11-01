@extends('layouts.admin')

@section('content')

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin: 0;">Manajemen Infografis</h3>
        <button class="btn btn-primary" onclick="showModal('tambahInfografisModal')">
            <i class="fas fa-plus"></i> Add
        </button>
    </div>
    
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Judul</th>
                    <th>Tipe</th>
                    <th>Konten</th>
                    {{-- Hapus kolom Status --}}
                    <th style="width: 80px;">Urutan</th>
                    <th style="width: 150px;">Aksi</th> {{-- Lebarkan sedikit untuk 3 tombol --}}
                </tr>
            </thead>
            <tbody>
                @forelse($infographics as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->title }}</td>
                        <td>{{ $item->type }}</td>
                        <td>
                            @if($item->isPhoto())
                                <a href="{{ asset('storage/' . $item->content_url) }}" target="_blank">Lihat Foto</a>
                            @else
                                <a href="{{ $item->content_url }}" target="_blank">Lihat Video Embed</a>
                            @endif
                        </td>
                        {{-- Hapus data Status --}}
                        <td>{{ $item->urutan }}</td>
                        <td style="display: flex; gap: 5px; justify-content: center;">
                            {{-- Tombol Toggle Status (Gunakan lebar tetap) --}}
                            @php
                                $isAktif = $item->status == 'Aktif';
                                $newStatus = $isAktif ? 'Tidak Aktif' : 'Aktif';
                            @endphp
                            <form action="{{ route('admin.infographics.toggle_status', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengubah status menjadi: {{ $newStatus }}?');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn {{ $isAktif ? 'btn-success' : 'btn-secondary' }}" 
                                        style="padding: 5px 10px; font-size: 10px; width: 60px; {{ $isAktif ? '' : 'color: #333; border-color: #6c757d;' }}" {{-- BARU: Menambahkan warna teks gelap dan border untuk kontras --}}
                                        title="Status: {{ $item->status }}">
                                    {{ $isAktif ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </form>

                            {{-- Tombol Edit Judul/Konten (Ikon Saja) --}}
                            <button class="btn btn-info" style="padding: 5px 10px; width: 40px;" onclick="showModal('editInfografisModal{{ $item->id }}')" title="Edit Data">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            
                            {{-- Tombol Hapus (Ikon Saja) --}}
                            <button class="btn btn-danger" style="padding: 5px 10px; width: 40px;" onclick="confirmDelete('delete-infographic-form-{{ $item->id }}')" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                            
                            {{-- Form Hapus (Tersembunyi) --}}
                            <form id="delete-infographic-form-{{ $item->id }}" action="{{ route('admin.infographics.destroy', $item->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    {{-- Modal Edit untuk setiap item --}}
                    @include('admin.infographics.edit-modal', ['item' => $item])
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center;">Belum ada data infografis.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection

{{-- Modal Tambah Infografis --}}
@include('admin.infographics.create-modal', ['typeOptions' => $typeOptions, 'statusOptions' => $statusOptions])