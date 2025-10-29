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
                    <th style="width: 80px;">Status</th>
                    <th style="width: 80px;">Urutan</th>
                    <th style="width: 100px;">Aksi</th>
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
                        <td>
                            <span style="font-weight: bold; color: {{ $item->status == 'Aktif' ? '#28a745' : '#dc3545' }}">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td>{{ $item->urutan }}</td>
                        <td>
                            <button class="btn btn-warning" style="padding: 5px 10px;" onclick="showModal('editInfografisModal{{ $item->id }}')">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            <button class="btn btn-danger" style="padding: 5px 10px;" onclick="confirmDelete('delete-infographic-form-{{ $item->id }}')">
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
                        <td colspan="7" style="text-align: center;">Belum ada data infografis.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection

{{-- Modal Tambah Infografis --}}
@include('admin.infographics.create-modal', ['typeOptions' => $typeOptions, 'statusOptions' => $statusOptions])