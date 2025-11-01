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
                        <label for="photo">Foto (Maks 2MB, *wajib)</label>
                        <input type="file" name="photo" id="photo" class="form-control" accept="image/*" required>
                    </div>
                    {{-- Status Jaga dan Urutan Tampil dihilangkan --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" onclick="hideModal('tambahAdminModal')">Tutup</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>