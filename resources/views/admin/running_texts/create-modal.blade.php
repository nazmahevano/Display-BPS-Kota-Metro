<div class="modal" id="tambahRunningTextModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.running_texts.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Running Text Baru</h5>
                    <button type="button" class="close-btn" onclick="hideModal('tambahRunningTextModal')" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="content">Konten Teks</label>
                        <textarea name="content" id="content" class="form-control" rows="3" required></textarea>
                    </div>
                    {{-- Status dan Urutan Tampil dihilangkan --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" onclick="hideModal('tambahRunningTextModal')">Tutup</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>