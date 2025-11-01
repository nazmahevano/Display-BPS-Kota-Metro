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
                        <select name="type" id="type" class="form-control" required onchange="toggleInfographicFields(this.value, 'infographics')">
                            @foreach ($typeOptions as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    {{-- File Upload (for Foto) --}}
                    <div class="form-group" id="infographics-photo-group">
                        <label for="photo">Foto (Maks 2MB, *wajib untuk tipe Foto)</label>
                        <input type="file" name="photo" id="photo" class="form-control" accept="image/*">
                    </div>
                    
                    {{-- URL Input (for Video) --}}
                    <div class="form-group" id="infographics-video-group" style="display:none;">
                        <label for="video_url">URL Video Embed (YouTube/lainnya, *wajib untuk tipe Video)</label>
                        <input type="url" name="video_url" id="video_url" class="form-control" placeholder="Contoh: https://www.youtube.com/watch?v=dQw4w9WgXcQ">
                    </div>

                    {{-- Status dan Urutan Dihapus --}}

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" onclick="hideModal('tambahInfografisModal')">Tutup</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Fungsi JavaScript untuk mengontrol tampilan field
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi tampilan modal saat pertama kali dimuat
        var initialType = document.getElementById('type');
        if (initialType) {
            toggleInfographicFields(initialType.value, 'infographics');
        }
    });

    function toggleInfographicFields(typeValue, prefix) {
        var photoGroup = document.getElementById(prefix + '-photo-group');
        var videoGroup = document.getElementById(prefix + '-video-group');
        var photoInput = photoGroup ? photoGroup.querySelector('input[type="file"]') : null;
        var videoInput = videoGroup ? videoGroup.querySelector('input[type="url"]') : null;

        if (photoGroup && videoGroup) {
            if (typeValue === 'Foto (Upload)') {
                photoGroup.style.display = 'block';
                videoGroup.style.display = 'none';
                if (photoInput) photoInput.setAttribute('required', 'required');
                if (videoInput) videoInput.removeAttribute('required');
            } else if (typeValue === 'Video (URL Embed)') {
                photoGroup.style.display = 'none';
                videoGroup.style.display = 'block';
                if (photoInput) photoInput.removeAttribute('required');
                if (videoInput) videoInput.setAttribute('required', 'required');
            } else {
                photoGroup.style.display = 'none';
                videoGroup.style.display = 'none';
                if (photoInput) photoInput.removeAttribute('required');
                if (videoInput) videoInput.removeAttribute('required');
            }
        }
    }
</script>