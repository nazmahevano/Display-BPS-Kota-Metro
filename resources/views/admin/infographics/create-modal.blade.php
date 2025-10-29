<div class="modal" id="tambahInfografisModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.infographics.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Infografis Baru</h5>
                    <button type="button" class="close-btn" onclick="hideModal('tambahInfografisModal')" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="title">Judul</label>
                        <input type="text" name="title" id="title" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="type">Tipe Konten</label>
                        <select name="type" id="type-create" class="form-control" onchange="toggleContentInput('create')" required>
                            @foreach ($typeOptions as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    {{-- Konten Foto (Tampilkan default) --}}
                    <div class="form-group content-input" id="photo-upload-create">
                        <label for="photo">Foto (Upload)</label>
                        <input type="file" name="photo" id="photo" class="form-control" accept="image/*">
                        <small style="color: #6c757d; font-size: 0.8em; margin-top: 5px;">Maks 2MB</small>
                    </div>

                    {{-- Konten Video (Sembunyikan default) --}}
                    <div class="form-group content-input" id="video-url-create" style="display: none;">
                        <label for="video_url">Video (URL Embed)</label>
                        <input type="url" name="video_url" id="video_url" class="form-control" placeholder="Contoh: https://www.youtube.com/watch?v=...">
                    </div>
                    
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control" required>
                            @foreach ($statusOptions as $status)
                                <option value="{{ $status }}">{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="urutan">Urutan Tampil (Angka)</label>
                        <input type="number" name="urutan" id="urutan" class="form-control" value="0" min="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" onclick="hideModal('tambahInfografisModal')">Tutup</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>