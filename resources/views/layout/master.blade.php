<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.css" rel="stylesheet" />
</head>
<body class="h-full">
    <div class="min-h-full">

    <!-- Komponen Navbar -->
    <x-navbar></x-navbar>

    <main>
        <div class="content">
            @yield('content')
        </div>
    </main>

    <!-- Komponen Footer -->
    <x-footer></x-footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/preline/dist/preline.js"></script>

    @include('sweetalert::alert')
    @stack('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.querySelector('[data-mobile-menu-toggle]');
    if (!toggleBtn) return;

    const targetId = toggleBtn.getAttribute('data-mobile-menu-toggle');
    const menu     = document.getElementById(targetId);
    if (!menu) return;

    const iconHamburger = toggleBtn.querySelector('.icon-hamburger');
    const iconClose     = toggleBtn.querySelector('.icon-close');

    function setMenuState(isOpen) {
        menu.dataset.open = isOpen ? 'true' : 'false';

        if (isOpen) {
            // buka menu
            menu.classList.remove('hidden');
            menu.style.maxHeight = menu.scrollHeight + 'px';
            menu.classList.remove('opacity-0');
            menu.classList.add('opacity-100');

            if (iconHamburger && iconClose) {
                iconHamburger.classList.add('hidden');
                iconClose.classList.remove('hidden');
            }

            toggleBtn.setAttribute('aria-expanded', 'true');
        } else {
            // tutup menu
            menu.style.maxHeight = '0px';
            menu.classList.remove('opacity-100');
            menu.classList.add('opacity-0');

            // setelah animasi selesai, baru di-hidden
            setTimeout(function () {
                if (menu.dataset.open === 'false') {
                    menu.classList.add('hidden');
                }
            }, 250);

            if (iconHamburger && iconClose) {
                iconHamburger.classList.remove('hidden');
                iconClose.classList.add('hidden');
            }

            toggleBtn.setAttribute('aria-expanded', 'false');
        }
    }

    // set state awal: tertutup
    setMenuState(false);

    // klik tombol → toggle
    toggleBtn.addEventListener('click', function () {
        const currentlyOpen = menu.dataset.open === 'true';
        setMenuState(!currentlyOpen);
    });

    // klik link dalam menu → tutup
    menu.querySelectorAll('a').forEach(function (link) {
        link.addEventListener('click', function () {
            setMenuState(false);
        });
    });
});
</script>
</body>

</html>
