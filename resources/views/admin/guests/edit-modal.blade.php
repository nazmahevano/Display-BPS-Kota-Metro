<div class="modal" id="editDataModal{{ $guest->id }}">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.guests.update', $guest->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Tamu: {{ $guest->name }}</h5>
                    <button type="button" class="close-btn" onclick="hideModal('editDataModal{{ $guest->id }}')" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit-name-{{ $guest->id }}">Nama</label>
                        <input type="text" name="name" id="edit-name-{{ $guest->id }}" class="form-control" value="{{ $guest->name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-institution-{{ $guest->id }}">Asal Instansi</label>
                        <input type="text" name="institution" id="edit-institution-{{ $guest->id }}" class="form-control" value="{{ $guest->institution }}">
                    </div>
                    <div class="form-group">
                        <label for="edit-phone-{{ $guest->id }}">No. Telepon</label>
                        <input type="text" name="phone" id="edit-phone-{{ $guest->id }}" class="form-control" value="{{ $guest->phone }}">
                    </div>
                    <div class="form-group">
                        <label for="edit-purpose-{{ $guest->id }}">Keperluan</label>
                        <select name="purpose" id="edit-purpose-{{ $guest->id }}" class="form-control" required>
                            @foreach ($purposes as $purpose)
                                <option value="{{ $purpose }}" @if($guest->purpose == $purpose) selected @endif>{{ $purpose }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit-objective-{{ $guest->id }}">Tujuan</label>
                        <textarea name="objective" id="edit-objective-{{ $guest->id }}" class="form-control" rows="3" required>{{ $guest->objective }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" onclick="hideModal('editDataModal{{ $guest->id }}')">Tutup</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>