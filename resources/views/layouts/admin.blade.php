<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard BPS Kota Metro')</title>
    
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    @yield('styles')
</head>
<body>

    {{-- Tambahkan class 'collapsed' secara default jika disimpan dalam local storage --}}
    <div class="wrapper" id="wrapper">
        {{-- SIDEBAR --}}
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <span class="sidebar-title">BPS Kota Metro</span>
                <button id="sidebar-toggle-btn" class="toggle-btn" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
</div>

            <a href="{{ route('admin.guests.index') }}" class="menu-item @if(Route::is('admin.guests.index')) active @endif">
                <i class="fas fa-book"></i> <span class="menu-text">Buku Tamu</span>
            </a>
            
            @php
                $displayActive = Route::is('admin.admin_pst.index') || Route::is('admin.infographics.index') || Route::is('admin.running_texts.index');
            @endphp
            
            <div class="menu-group-title"><span class="menu-text">PENGATURAN DISPLAY</span>
                <ul class="submenu-list">
                    <li>
                        <a href="{{ route('admin.admin_pst.index') }}" class="menu-item submenu-item @if(Route::is('admin.admin_pst.index')) active @endif">
                            <i class="fas fa-circle" style="font-size: 0.5em; margin-right: 5px;"></i> <span class="menu-text">petugas PST</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.infographics.index') }}" class="menu-item submenu-item @if(Route::is('admin.infographics.index')) active @endif">
                            <i class="fas fa-circle" style="font-size: 0.5em; margin-right: 5px;"></i> <span class="menu-text">Infografis</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.running_texts.index') }}" class="menu-item submenu-item @if(Route::is('admin.running_texts.index')) active @endif">
                            <i class="fas fa-circle" style="font-size: 0.5em; margin-right: 5px;"></i> <span class="menu-text">Running Text</span>
                        </a>
                    </li>
                </ul>
            </div>
            
            <div class="menu-group-title"><span class="menu-text">LAINNYA</span></div>
            <a href="{{ route('display.queue') }}" class="menu-item">
                <i class="fas fa-tv"></i> <span class="menu-text">Ke Halaman Display</span>
            </a>

        </div>

        {{-- MAIN CONTENT --}}
        <div class="content" id="content">
            <div class="content-header">
                <h1>@yield('page_title', 'Dashboard')</h1>
                <div class="breadcrumb">
                    <a href="{{ route('admin.dashboard') }}">Home</a> / @yield('breadcrumb')
                </div>
            </div>
            
            @if(session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                    <button type="button" class="alert-close" onclick="this.parentElement.style.display='none';" aria-label="Close">&times;</button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script>
        // --- LOGIKA SIDEBAR TOGGLE ---
        const SIDEBAR_ID = 'sidebar';
        const WRAPPER_ID = 'wrapper';
        const SIDEBAR_STATE_KEY = 'sidebarCollapsed';

        function toggleSidebar() {
            const wrapper = document.getElementById(WRAPPER_ID);
            const isCollapsed = wrapper.classList.toggle('collapsed');
            
            // Simpan status di Local Storage untuk mengingat pilihan pengguna
            localStorage.setItem(SIDEBAR_STATE_KEY, isCollapsed ? 'true' : 'false');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const wrapper = document.getElementById(WRAPPER_ID);
            const isCollapsed = localStorage.getItem(SIDEBAR_STATE_KEY) === 'true';

            // Muat status dari Local Storage
            if (isCollapsed) {
                wrapper.classList.add('collapsed');
            }
            
            // --- Logika Modal, Filter, dll. (Kode Anda Sebelumnya) ---
            const backdrop = document.getElementById('modal-backdrop');
            if (backdrop) {
                backdrop.addEventListener('click', function() {
                    document.querySelectorAll('.modal.show').forEach(m => hideModal(m.id));
                });
            }
            
            document.querySelectorAll('.modal .close-btn').forEach(btn => {
                btn.addEventListener('click', () => hideModal(btn.closest('.modal').id));
            });
            document.querySelectorAll('.modal .btn-light').forEach(btn => {
                if (btn.textContent.trim() === 'Tutup') {
                    btn.addEventListener('click', () => hideModal(btn.closest('.modal').id));
                }
            });
        });

        // Fungsi Modal, Filter, dll.
        function showModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.add('show');
                modal.style.display = 'block';
                document.getElementById('modal-backdrop').style.display = 'block';
            }
        }
        function hideModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.remove('show');
                modal.style.display = 'none';
                document.getElementById('modal-backdrop').style.display = 'none';
            }
        }
        function confirmDelete(formId) {
            if (confirm('Yakin ingin menghapus data ini?')) {
                document.getElementById(formId).submit();
            }
        }
        function applyFilter() {
            const purpose = document.getElementById('purpose-filter')?.value;
            const startDate = document.getElementById('start-date-filter')?.value;
            const endDate = document.getElementById('end-date-filter')?.value;
            const search = document.getElementById('search-filter')?.value;

            const url = new URL(window.location.href.split('?')[0]);
            
            if (purpose) url.searchParams.set('purpose', purpose);
            if (startDate) url.searchParams.set('start_date', startDate);
            if (endDate) url.searchParams.set('end_date', endDate);
            if (search) url.searchParams.set('search', search);

            window.location.href = url.toString();
        }
        function resetFilter() {
            window.location.href = window.location.href.split('?')[0];
        }
        function toggleContentInput(suffix) {
            const typeSelect = document.getElementById(`type-${suffix}`);
            if (!typeSelect) return;
            const type = typeSelect.value;
            const photoDiv = document.getElementById(`photo-upload-${suffix}`);
            const videoDiv = document.getElementById(`video-url-${suffix}`);
            
            if (type === 'Foto (Upload)') {
                if (photoDiv) photoDiv.style.display = 'block';
                if (videoDiv) videoDiv.style.display = 'none';
            } else if (type === 'Video (URL Embed)') {
                if (photoDiv) photoDiv.style.display = 'none';
                if (videoDiv) videoDiv.style.display = 'block';
            }
        }

    </script>
    <div class="modal-backdrop" id="modal-backdrop"></div>
    @yield('scripts')
</body>
</html>