<div class="modal" id="tambahAdminModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.admin_pst.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Admin PST Baru</h5>
                    <button type="button" class="close-btn" onclick="hideModal('tambahAdminModal')" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="jabatan">Jabatan</label>
                        <input type="text" name="jabatan" id="jabatan" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="photo">Foto (Maks 2MB)</label>
                        <input type="file" name="photo" id="photo" class="form-control" accept="image/*">
                    </div>
                    <div class="form-group">
                        <label for="status_jaga">Status Jaga</label>
                        <select name="status_jaga" id="status_jaga" class="form-control" required>
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
                    <button type="button" class="btn btn-light" onclick="hideModal('tambahAdminModal')">Tutup</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>