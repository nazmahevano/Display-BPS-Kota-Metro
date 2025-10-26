@extends('layouts.app')

@section('title', 'Display Queue - BPS Kota Metro')

@section('content')
    
    <div class="main-content"> 

        <div class="column profile-menu-container">
            <div class="profile-card">
                <div class="avatar-placeholder">
                    @if(isset($admin->photo_url))
                        <img src="{{ $admin->photo_url }}" alt="Foto Admin" class="admin-photo">
                    @else
                        <i class="fas fa-user"></i>
                    @endif
                </div>
                <div class="info-group">
                    <p class="name-label">NAMA ADMIN : <span class="name-value">{{ $admin->name ?? 'Admin Tidak Ditemukan' }}</span></p>
                    <p class="jabatan-label">JABATAN : <span class="jabatan-value">{{ $admin->jabatan ?? 'Tidak Ada' }}</span></p>
                </div>
            </div>

            <div class="menu-grid">
                
                <button class="menu-item primary-btn">
                    <span class="menu-title">PELAYANAN</span>
                    <span class="menu-desc">pelayanan adala bla bla bla</span>
                </button>
                
                <button class="menu-item primary-btn">
                    <span class="menu-title">KUNJUNGAN</span>
                    <span class="menu-desc">kunjungan adala bla bla bla</span>
                </button>
                
                <button class="menu-item secondary-btn">
                    <span class="menu-title">KONSULTASI</span>
                    <span class="menu-desc">konsultasi adala bla bla bla</span>
                </button>
                
                <button class="menu-item secondary-btn">
                    <span class="menu-title">PUSTAKA</span>
                    <span class="menu-desc">pustaka adala bla bla bla</span>
                </button>
                
            </div>
        </div>

        <div class="column queue-container">
            <div class="queue-card">
                <span class="card-label">NOMOR ANTRIAN</span>
                <div class="queue-number" id="queue-display">{{ $queue_number }}</div> 
                <div class="controls">
                    <button class="control-btn back-btn" onclick="changeQueue('back')">BACK</button>
                    <button class="control-btn next-btn" onclick="changeQueue('next')">NEXT</button>
                </div>
                <a href="{{ url('/dashboard') }}" class="dashboard-btn">DASHBOARD</a> 
            </div> 
        </div> <div class="column illustration-container">
            <div class="illustration-placeholder">
                <img src="{{ $infographic_url }}" alt="Infografik Slideshow" class="infographic-img">
            </div>
        </div>

    </div> @endsection

@section('running_text_content')
    <p>{{ $running_text }}</p>
@endsection

@section('scripts')
<script>
    function changeQueue(action) {
        const displayElement = document.getElementById('queue-display');
        let currentNumber = parseInt(displayElement.textContent.trim());

        // Mencegah mundur di bawah 1 sebelum mengirim ke server
        if (action === 'back' && currentNumber <= 1) {
            alert('Nomor antrian tidak bisa kurang dari 1!');
            return;
        }

        // Kirim permintaan POST ke server
        fetch("{{ route('queue.update') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}' 
            },
            body: JSON.stringify({
                action: action,
                current_number: currentNumber 
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Langsung perbarui tampilan
                displayElement.textContent = data.new_number;
            } else {
                alert('Gagal memperbarui nomor antrian di server.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat berkomunikasi dengan server.');
        });
    }
</script>
@endsection