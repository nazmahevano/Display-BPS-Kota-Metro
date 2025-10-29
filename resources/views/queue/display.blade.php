@extends('layouts.app')

@section('title', 'Display Queue - BPS Kota Metro')

@section('content')
    
    <div class="main-content"> 
        {{-- Kolom 1: Profile & Menu --}}
        <div class="column profile-menu-container">
            
            {{-- KOTAK ADMIN --}}
            <div class="profile-card">
                <div class="avatar-placeholder">
                    {{-- Kunci Foto Admin: CSS akan memastikan ini 250x250px --}}
                    <img src="{{ $admin->photo_url ?? asset('images/admin_photo.jpg') }}" alt="Foto Admin" class="admin-photo">
                </div>
                <div class="info-group">
                    <p><span class="name-label">Petugas PST :</span> <span class="name-value">{{ $admin->name ?? 'Admin Tidak Bertugas' }}</span></p>
                    <p><span class="jabatan-label">Jabatan :</span> <span class="jabatan-value">{{ $admin->jabatan ?? 'Silakan Hubungi Petugas' }}</span></p>
                </div>
            </div>
            
            {{-- KOTAK LAYANAN (4 Kotak Info BARU) --}}
            <div class="menu-grid">
                <div class="menu-item primary-btn">
                    <span class="menu-title">PERPUSTAKAAN</span>
                    <span class="menu-desc">Publikasi statistik terbitan BPS dari berbagai kategori.</span>
                </div>
                <div class="menu-item primary-btn">
                    <span class="menu-title">PRODUK STATISTIK</span>
                    <span class="menu-desc">Layanan data mikro, publikasi elektronik, dan peta digital wilkerstat.</span>
                </div>
                <div class="menu-item secondary-btn">
                    <span class="menu-title">KONSULTASI</span>
                    <span class="menu-desc">Konsultasi terkait data, metadata, klasifikasi, dan produk statistik BPS lainnya.</span>
                </div>
                <div class="menu-item secondary-btn">
                    <span class="menu-title">REKOMENDASI</span>
                    <span class="menu-desc">Layanan bagi instansi pemerintah yang akan melakukan survei serta mengajukan rekomendasi kegiatan statistik.</span>
                </div>
            </div>
        </div>

        {{-- Kolom 2: Queue Number --}}
        <div class="column queue-container">
            <div class="queue-card">
                <span class="card-label">NOMOR ANTRIAN SAAT INI</span>
                <div class="queue-number" id="queue-display">{{ $queue_number }}</div>
                
                {{-- PERUBAHAN: Tombol NEXT/BACK di bawah angka --}}
                <div class="controls-antrian">
                    <button class="control-btn back-btn" onclick="changeQueue('back')">BACK</button>
                    <button class="control-btn next-btn" onclick="changeQueue('next')">NEXT</button>
                </div>
                <a href="{{ url('/dashboard') }}" class="dashboard-btn">DASHBOARD</a> 
            </div>
        </div>

        {{-- Kolom 3: Illustration (Slideshow) --}}
        <div class="column illustration-container">
            {{-- Hapus .illustration-placeholder dan ganti dengan struktur Aspect Ratio --}}
            <div class="infographic-aspect-ratio-container"> 
                <div class="infographic-padding-box">
                    <div id="slideshow-container" class="slideshow-container-style"> 
                        {{-- Konten akan diisi oleh JavaScript --}}
                    </div>
                </div>
            </div>
        </div>
    </div> 
@endsection

@section('running_text_content')
    <p>{{ $running_text }}</p>
@endsection

@section('scripts')
<script>
    // Data Infografis dari Controller (Diambil dari $infographic_list)
    const infographicList = @json($infographic_list);
    let currentSlide = 0;
    const slideshowIntervalTime = 7000; // 7 detik per slide

    function renderSlide(item) {
        const container = document.getElementById('slideshow-container');
        container.innerHTML = ''; // Kosongkan konten sebelumnya

        if (item.type.includes('Foto')) {
            // Tampilkan Gambar
            container.innerHTML = `
                <img src="${item.content}" alt="${item.title}" class="infographic-img">
            `;
        } else if (item.type.includes('Video')) {
            // Tampilkan Iframe Video YouTube
            const embedUrl = item.content.startsWith('https://www.youtube.com/embed/') 
                           ? item.content 
                           : item.content.replace('watch?v=', 'embed/');
                           
            container.innerHTML = `
                <iframe 
                    class="infographic-iframe"
                    src="${embedUrl}?autoplay=1&mute=1&loop=1&playlist=${item.content.split('/').pop().replace('?autoplay=1&mute=1&loop=1&playlist=', '')}" 
                    frameborder="0" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                    allowfullscreen
                ></iframe>
            `;
        }
    }
    
    function nextSlide() {
        if (infographicList.length === 0) return;

        currentSlide = (currentSlide + 1) % infographicList.length;
        renderSlide(infographicList[currentSlide]);
    }
    
    // Inisialisasi Slideshow
    document.addEventListener('DOMContentLoaded', function() {
        if (infographicList.length > 0) {
            renderSlide(infographicList[currentSlide]);
            setInterval(nextSlide, slideshowIntervalTime);
        }
    });

    // --- Fungsi Queue yang Sudah Ada ---
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