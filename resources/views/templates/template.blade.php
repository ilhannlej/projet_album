<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Gestionnaire d\'album photo')</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body>
    <header>
        <h1>Gestionnaire d'album photo</h1>
        <nav>
                <a href="{{url('/') }}"><span>Accueil</span></a>
                <a href="{{url('/albums') }}"><span>Albums</span></a>
                <a href="{{url('/') }}"><span>Photos</span></a>
                <a href="{{url('/') }}"><span>Connexion</span></a>

        </nav>
    </header>

    <main>
        <!-- Zone principale où les vues injecteront leur contenu -->
        @yield('contenu')
    </main>


    @stack('scripts')
    @yield('scripts')
</body>
</html>
