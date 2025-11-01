@extends('layouts.admin')

@section('content')

<div class="card">
    <h3 style="margin-top: 0; margin-bottom: 20px;">Data Tamu</h3>
    
    {{-- Form Filter dan Pencarian --}}
    <div class="form-row">
        <button class="btn btn-success" onclick="showModal('tambahDataModal')">
            <i class="fas fa-plus"></i> Add
        </button>

        {{-- Filter Keperluan --}}
        <select id="purpose-filter" class="form-control" style="width: 200px;">
            <option value="Semua Keperluan">Semua Keperluan</option>
            @foreach ($purposes as $purpose)
                <option value="{{ $purpose }}" @if(request('purpose') == $purpose) selected @endif>{{ $purpose }}</option>
            @endforeach
        </select>
        
        {{-- Filter Tanggal Mulai --}}
        <input type="date" id="start-date-filter" class="form-control" placeholder="Tanggal Awal" value="{{ request('start_date') }}">

        <span class="date-separator">To</span>

        {{-- Filter Tanggal Akhir --}}
        <input type="date" id="end-date-filter" class="form-control" placeholder="Tanggal Akhir" value="{{ request('end_date') }}">

        <button class="btn btn-primary" onclick="applyFilter()">
            <i class="fas fa-filter"></i> Filter
        </button>
        
        <button class="btn btn-light" onclick="resetFilter()">
            Clear
        </button>



        {{-- Ekspor dan Hapus (Simulasi tombol) --}}
        <button class="btn btn-export" style="margin-left: 10px;" onclick="redirectToExport()">
            <i class="fas fa-file-export"></i> Export
        </button>

        {{-- Pencarian --}}
        <div class="input-group search-group">
            <input type="text" id="search-filter" class="form-control" placeholder="Search..." value="{{ request('search') }}">
            <button class="btn btn-primary" onclick="applyFilter()">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>

    {{-- Tabel Data Tamu --}}
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama</th>
                    <th>Asal Instansi</th>
                    <th>Telepon</th>
                    <th>Keperluan</th>
                    <th>Tujuan</th>
                    <th>Tanggal</th>
                    <th style="width: 100px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($guests as $guest)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $guest->name }}</td>
                        <td>{{ $guest->institution }}</td>
                        <td>{{ $guest->phone }}</td>
                        <td>{{ $guest->purpose }}</td>
                        <td>{{ $guest->objective }}</td>
                        <td>{{ \Carbon\Carbon::parse($guest->created_at)->format('d/m/Y') }}</td>
                        <td>
                            <button class="btn btn-warning" style="padding: 5px 10px;" onclick="showModal('editDataModal{{ $guest->id }}')">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            <button class="btn btn-danger" style="padding: 5px 10px;" onclick="confirmDelete('delete-form-{{ $guest->id }}')">
                                <i class="fas fa-trash"></i>
                            </button>
                            
                            {{-- Form Hapus (Tersembunyi) --}}
                            <form id="delete-form-{{ $guest->id }}" action="{{ route('admin.guests.destroy', $guest->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    {{-- Modal Edit untuk setiap tamu --}}
                    @include('admin.guests.edit-modal', ['guest' => $guest, 'purposes' => $purposes])
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center;">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

{{-- Modal Tambah Data --}}
@include('admin.guests.create-modal', ['purposes' => $purposes])

@endsection

@section('scripts')
<script>
function redirectToExport() {
    const purpose = document.getElementById('purpose-filter')?.value;
    const startDate = document.getElementById('start-date-filter')?.value;
    const endDate = document.getElementById('end-date-filter')?.value;
    const search = document.getElementById('search-filter')?.value;

    const url = new URL("{{ route('admin.guests.export') }}");
     
    // Tambahkan parameter hanya jika ada isinya
    if (purpose && purpose !== 'Semua Keperluan') url.searchParams.set('purpose', purpose);
    if (startDate) url.searchParams.set('start_date', startDate);
    if (endDate) url.searchParams.set('end_date', endDate);
    if (search) url.searchParams.set('search', search);

    window.location.href = url.toString();
} // <-- Tanda kurung kurawal penutup fungsi HARUS ADA di sini

</script> @endsection