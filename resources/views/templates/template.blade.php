<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Gestionnaire d\'album photo')</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <!-- Place pour les liens CSS / fonts (à ajouter plus tard) -->
    @stack('styles')
</head>
<body>
    <header>
        <h1>Gestionnaire d'album photo</h1>
        <p class="lead">Projet de gestion d'albums — travail à réaliser à deux</p>
        <nav>
            <!-- Liens non ajoutés pour l'instant -->
            <ul>
                <li><span>Accueil</span></li>
                <li><span>Albums</span></li>
                <li><span>Photos</span></li>
                <li><span>Connexion</span></li>
            </ul>
        </nav>
    </header>

    <main>
        <!-- Zone principale où les vues injecteront leur contenu -->
        @yield('contenu')
    </main>

    <aside>
        <!-- Rappel rapide des consignes / fonctionnalités (peut être affiché sur la page d'accueil) -->


    <!-- Place pour les scripts JS (à ajouter plus tard) -->
    @stack('scripts')
    @yield('scripts')
</body>
</html>
