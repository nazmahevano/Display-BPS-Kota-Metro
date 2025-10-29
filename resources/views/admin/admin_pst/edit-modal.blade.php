<div class="modal" id="editAdminModal{{ $admin->id }}">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.admin_pst.update', $admin->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Admin PST: {{ $admin->name }}</h5>
                    <button type="button" class="close-btn" onclick="hideModal('editAdminModal{{ $admin->id }}')" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit-name-{{ $admin->id }}">Nama</label>
                        <input type="text" name="name" id="edit-name-{{ $admin->id }}" class="form-control" value="{{ $admin->name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-jabatan-{{ $admin->id }}">Jabatan</label>
                        <input type="text" name="jabatan" id="edit-jabatan-{{ $admin->id }}" class="form-control" value="{{ $admin->jabatan }}">
                    </div>
                    
                    {{-- Tampilan Foto Saat Ini --}}
                    <div class="form-group">
                        <label>Foto Saat Ini</label>
                        <img src="{{ $admin->photo_url }}" alt="Foto Admin" style="width: 100px; height: 100px; object-fit: cover; margin-bottom: 10px; border-radius: 5px;">
                    </div>

                    <div class="form-group">
                        <label for="edit-photo-{{ $admin->id }}">Ganti Foto (Maks 2MB)</label>
                        <input type="file" name="photo" id="edit-photo-{{ $admin->id }}" class="form-control" accept="image/*">
                    </div>
                    <div class="form-group">
                        <label for="edit-status_jaga-{{ $admin->id }}">Status Jaga</label>
                        <select name="status_jaga" id="edit-status_jaga-{{ $admin->id }}" class="form-control" required>
                            @foreach ($statusOptions as $status)
                                <option value="{{ $status }}" @if($admin->status_jaga == $status) selected @endif>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit-urutan-{{ $admin->id }}">Urutan Tampil (Angka)</label>
                        <input type="number" name="urutan" id="edit-urutan-{{ $admin->id }}" class="form-control" value="{{ $admin->urutan }}" min="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" onclick="hideModal('editAdminModal{{ $admin->id }}')">Tutup</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>