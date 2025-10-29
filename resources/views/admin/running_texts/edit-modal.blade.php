<div class="modal" id="editRunningTextModal{{ $item->id }}">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.running_texts.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Running Text #{{ $item->id }}</h5>
                    <button type="button" class="close-btn" onclick="hideModal('editRunningTextModal{{ $item->id }}')" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit-content-{{ $item->id }}">Konten Teks</label>
                        <textarea name="content" id="edit-content-{{ $item->id }}" class="form-control" rows="3" required>{{ $item->content }}</textarea>
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
                    <button type="button" class="btn btn-light" onclick="hideModal('editRunningTextModal{{ $item->id }}')">Tutup</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>