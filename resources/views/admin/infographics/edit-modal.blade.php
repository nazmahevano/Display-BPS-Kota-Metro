<div class="modal" id="editInfografisModal{{ $item->id }}">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.infographics.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Infografis: {{ $item->title }}</h5>
                    <button type="button" class="close-btn" onclick="hideModal('editInfografisModal{{ $item->id }}')" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit-title-{{ $item->id }}">Judul</label>
                        <input type="text" name="title" id="edit-title-{{ $item->id }}" class="form-control" value="{{ $item->title }}" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-type-{{ $item->id }}">Tipe Konten</label>
                        <select name="type" id="edit-type-{{ $item->id }}" class="form-control" onchange="toggleContentInput('edit-{{ $item->id }}')" required>
                            @foreach ($typeOptions as $type)
                                <option value="{{ $type }}" @if($item->type == $type) selected @endif>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    {{-- Konten Foto --}}
                    <div class="form-group content-input-edit edit-photo-upload-{{ $item->id }}" @if(!$item->isPhoto()) style="display: none;" @endif>
                        <label for="edit-photo-{{ $item->id }}">Ganti Foto (Maks 2MB)</label>
                        @if($item->isPhoto() && $item->content_url)
                            <small style="color: #6c757d; font-size: 0.8em; margin-bottom: 5px;">File saat ini: <a href="{{ asset('storage/' . $item->content_url) }}" target="_blank">Lihat</a></small>
                        @endif
                        <input type="file" name="photo" id="edit-photo-{{ $item->id }}" class="form-control" accept="image/*">
                    </div>

                    {{-- Konten Video --}}
                    <div class="form-group content-input-edit edit-video-url-{{ $item->id }}" @if($item->isPhoto()) style="display: none;" @endif>
                        <label for="edit-video_url-{{ $item->id }}">Video (URL Embed)</label>
                        <input type="url" name="video_url" id="edit-video_url-{{ $item->id }}" class="form-control" value="{{ $item->content_url }}" placeholder="Contoh: https://www.youtube.com/watch?v=...">
                    </div>
                    
                    <div class="form-group">
                        <label for="edit-status-{{ $item->id }}">Status</label>
                        <select name="status" id="edit-status-{{ $item->id }}" class="form-control" required>
                            @foreach ($statusOptions as $status)
                                <option value="{{ $status }}" @if($item->status == $status) selected @endif>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit-urutan-{{ $item->id }}">Urutan Tampil (Angka)</label>
                        <input type="number" name="urutan" id="edit-urutan-{{ $item->id }}" class="form-control" value="{{ $item->urutan }}" min="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" onclick="hideModal('editInfografisModal{{ $item->id }}')">Tutup</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>