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
                    <th style="width: 100px;">Status</th>
                    <th style="width: 80px;">Urutan</th>
                    <th style="width: 100px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($runningTexts as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($item->content, 80) }}</td>
                        <td>
                            <span style="font-weight: bold; color: {{ $item->status == 'Aktif' ? '#28a745' : '#dc3545' }}">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td>{{ $item->urutan }}</td>
                        <td>
                            <button class="btn btn-warning" style="padding: 5px 10px;" onclick="showModal('editRunningTextModal{{ $item->id }}')">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            <button class="btn btn-danger" style="padding: 5px 10px;" onclick="confirmDelete('delete-running-text-form-{{ $item->id }}')">
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
                        <td colspan="5" style="text-align: center;">Belum ada data Running Text.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection

{{-- Modal Tambah Running Text --}}
@include('admin.running_texts.create-modal', ['statusOptions' => $statusOptions])