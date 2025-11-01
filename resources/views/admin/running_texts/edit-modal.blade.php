<div class="modal" id="editRunningTextModal{{ $item->id }}">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.running_texts.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Running Text: {{ \Illuminate\Support\Str::limit($item->content, 20) }}</h5>
                    <button type="button" class="close-btn" onclick="hideModal('editRunningTextModal{{ $item->id }}')" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit-content-{{ $item->id }}">Konten Teks</label>
                        <textarea name="content" id="edit-content-{{ $item->id }}" class="form-control" rows="3" required>{{ $item->content }}</textarea>
                    </div>
                    {{-- Status dan Urutan Tampil dihilangkan --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" onclick="hideModal('editRunningTextModal{{ $item->id }}')">Tutup</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>