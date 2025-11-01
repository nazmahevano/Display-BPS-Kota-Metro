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
                        <select name="type" id="edit-type-{{ $item->id }}" class="form-control" required onchange="toggleInfographicFields(this.value, 'edit-infographics-{{ $item->id }}')">
                            @foreach ($typeOptions as $type)
                                <option value="{{ $type }}" @if($item->type == $type) selected @endif>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    {{-- Tampilan Konten Saat Ini (Foto/Video URL) --}}
                    <div class="form-group">
                        <label>Konten Saat Ini</label>
                        @if($item->content_url)
                            @if($item->isPhoto())
                                <img src="{{ asset('storage/' . $item->content_url) }}" alt="Foto Infografis" style="max-width: 100%; max-height: 200px; object-fit: contain; margin-bottom: 10px; border-radius: 5px;">
                            @else
                                <a href="{{ $item->content_url }}" target="_blank">Lihat Video Embed</a>
                            @endif
                        @else
                            <span>Belum ada konten.</span>
                        @endif
                    </div>

                    {{-- File Upload (for Foto) --}}
                    <div class="form-group" id="edit-infographics-{{ $item->id }}-photo-group" style="{{ $item->isPhoto() ? 'display:block;' : 'display:none;' }}">
                        <label for="edit-photo-{{ $item->id }}">Ganti Foto (Maks 2MB, biarkan kosong jika tidak diganti)</label>
                        <input type="file" name="photo" id="edit-photo-{{ $item->id }}" class="form-control" accept="image/*">
                    </div>
                    
                    {{-- URL Input (for Video) --}}
                    <div class="form-group" id="edit-infographics-{{ $item->id }}-video-group" style="{{ !$item->isPhoto() ? 'display:block;' : 'display:none;' }}">
                        <label for="edit-video_url-{{ $item->id }}">URL Video Embed</label>
                        <input type="url" name="video_url" id="edit-video_url-{{ $item->id }}" class="form-control" value="{{ $item->type === 'Video (URL Embed)' ? $item->content_url : '' }}">
                    </div>

                    {{-- Status dan Urutan Dihilangkan --}}

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" onclick="hideModal('editInfografisModal{{ $item->id }}')">Tutup</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Pastikan fungsi toggleInfographicFields didefinisikan di suatu tempat (atau di index.blade.php)
    // Jika belum ada, tambahkan fungsi ini:
    if (typeof toggleInfographicFields !== 'function') {
        function toggleInfographicFields(typeValue, prefix) {
            var photoGroup = document.getElementById(prefix + '-photo-group');
            var videoGroup = document.getElementById(prefix + '-video-group');
            
            if (photoGroup && videoGroup) {
                if (typeValue === 'Foto (Upload)') {
                    photoGroup.style.display = 'block';
                    videoGroup.style.display = 'none';
                } else if (typeValue === 'Video (URL Embed)') {
                    photoGroup.style.display = 'none';
                    videoGroup.style.display = 'block';
                } else {
                    photoGroup.style.display = 'none';
                    videoGroup.style.display = 'none';
                }
            }
        }
    }
</script>