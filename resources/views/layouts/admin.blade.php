<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    </head>
<body id="page-top">

    <div id="wrapper">

        {{-- Incluez votre barre latérale ici, par exemple un partial --}}
        {{-- @include('admin.partials.sidebar') --}}

        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                {{-- Incluez votre barre supérieure ici, par exemple un partial --}}
                {{-- @include('admin.partials.topbar') --}}

                <div class="container-fluid">
                    @yield('content') {{-- C'est ici que le contenu de vos vues (evenements, organisateurs, etc.) sera inséré --}}
                </div>
                </div>
            {{-- @include('admin.partials.footer') --}}
            </div>
        </div>
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    {{-- @include('admin.partials.logout-modal') --}}

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    @stack('scripts') {{-- Pour les scripts spécifiques aux pages individuelles --}}
</body>
</html>