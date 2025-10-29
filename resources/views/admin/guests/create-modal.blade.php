<div class="modal" id="tambahDataModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.guests.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Input Data Tamu Baru</h5>
                    <button type="button" class="close-btn" onclick="hideModal('tambahDataModal')" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="institution">Asal Instansi</label>
                        <input type="text" name="institution" id="institution" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="phone">No. Telepon</label>
                        <input type="text" name="phone" id="phone" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="purpose">Keperluan</label>
                        <select name="purpose" id="purpose" class="form-control" required>
                            <option value="">Pilih Keperluan</option>
                            @foreach ($purposes as $purpose)
                                <option value="{{ $purpose }}">{{ $purpose }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="objective">Tujuan</label>
                        <textarea name="objective" id="objective" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" onclick="hideModal('tambahDataModal')">Tutup</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>