<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Queue BPS Kota Metro')</title>
    
    <link rel="stylesheet" href="{{ asset('css/queue.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    @yield('styles')
</head>
<body>
    <div class="dynamic-background"></div>
    <header class="header">
        <div class="logo-title">
            <img src="{{ asset('images/logo_bps.png') }}" alt="Logo BPS" class="logo">
            <div class="title-group">
                <span class="main-title">BADAN PUSAT STATISTIK</span>
                <span class="sub-title">KOTA METRO</span>
            </div>
        </div>
        <div class="datetime">
            <span id="date"></span>
            <span id="time"></span>
        </div>
    </header>

    <div class="page-content-wrapper">
        @yield('content') 
    </div>

    <footer class="footer">
        <div class="running-text-container">
            @yield('running_text_content')
        </div>
    </footer>


    <script>
        function updateDateTime() {
            const now = new Date();
            const dateString = now.toLocaleDateString('id-ID', { weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' });
            const timeString = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });

            const dateEl = document.getElementById('date');
            const timeEl = document.getElementById('time');

            if (dateEl) {
                let formattedDate = dateString.replace(/,/g, '');
                formattedDate = formattedDate.replace(/([A-Za-z]+)\s/, '$1, ');
                
                dateEl.textContent = formattedDate;
            }
            if (timeEl) {
                timeEl.textContent = timeString;
            }
        }
        setInterval(updateDateTime, 1000);
        updateDateTime();
    </script>
    
    @yield('scripts')
</body>
</html>