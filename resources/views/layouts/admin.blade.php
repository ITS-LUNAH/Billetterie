<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Admin - EVUP')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    {{-- Material Icons --}}
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    {{-- Font Awesome (v5.15.3 pour la cohérence) --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    {{-- Bootstrap CSS (v4.5.2 pour la cohérence) --}}
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">


    {{-- Votre main custom stylesheet (doit contenir TOUT votre CSS custom, y compris celui des cartes et tables) --}}
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}"> {{-- <-- ASSUREZ-VOUS QUE LE CHEMIN EST CORRECT (css ou assets/css) --}}

    {{-- Pour tout CSS spécifique à une page qui étend ce layout --}}
    @stack('styles')

</head>
<body>
    <div class="container"> {{-- Votre conteneur principal qui englobe la sidebar et le main content --}}
        @include('admin.partials.sidebar')
        <main>
            @yield('content') {{-- Ici sera injecté le contenu de index.blade.php, edit.blade.php, etc. --}}
        </main>
        <div class="right">
            <div class="top">
                <button id="menu-btn">
                    <span class="material-icons-sharp">menu</span>
                </button>
                <div class="theme-toggler">
                    <span class="material-icons-sharp active">light_mode</span>
                    <span class="material-icons-sharp">dark_mode</span>
                </div>
                <div class="profile">
                    <div class="info">
                        <p>Hey, <b>Admin</b></p>
                        <small class="text-muted">Admin</small>
                    </div>
                    <div class="profile-photo">
                        <img src="{{ asset('assets/images/profile-1.jpeg') }}" alt="Profil">
                    </div>
                </div>
            </div>
            {{-- Ici viendraient vos sections 'Recent Updates', 'Analytics', etc. --}}
            @include('admin.partials.right-panel')
        </div>
    </div>

    {{-- Scripts globaux à la fin du body --}}
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> {{-- La version 3.5.1 pour la cohérence --}}
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    {{-- Votre script principal index.js --}}
    <script src="{{ asset('js/index.js') }}"></script> {{-- Assurez-vous que ce fichier existe et contient le JS du menu --}}

    {{-- Script pour la gestion du theme et la sidebar --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Theme toggler
            const themeToggler = document.querySelector('.theme-toggler');
            if (themeToggler) {
                themeToggler.addEventListener('click', () => {
                    document.body.classList.toggle('dark-theme-variables');
                    themeToggler.querySelector('span:nth-child(1)').classList.toggle('active');
                    themeToggler.querySelector('span:nth-child(2)').classList.toggle('active');
                });
            }

            // Menu button
            const sideMenu = document.querySelector('aside');
            const menuBtn = document.querySelector('#menu-btn');
            const closeBtn = document.querySelector('#close-btn'); // Assurez-vous que le bouton de fermeture existe dans votre sidebar.blade.php

            if (menuBtn) {
                menuBtn.addEventListener('click', () => {
                    sideMenu.style.display = 'block';
                });
            }

            if (closeBtn) {
                closeBtn.addEventListener('click', () => {
                    sideMenu.style.display = 'none';
                });
            }

            // Gestion de l'état actif de la sidebar
            const path = window.location.pathname;
            document.querySelectorAll('.sidebar a').forEach(link => {
                link.classList.remove('active');
                // Vérifier si le href du lien correspond au chemin actuel
                if (link.href === window.location.href || link.getAttribute('href') === path) {
                    link.classList.add('active');
                }
            });
        });
    </script>

    @stack('scripts') {{-- Pour les scripts spécifiques à chaque vue de section comme index.blade.php des événements ou participants --}}
</body>
</html>