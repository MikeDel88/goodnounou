<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!--  Favicon  -->
    <link rel="apple-touch-icon" sizes="180x180"
        href="{{ URL::asset('assets/images/favicon_io/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32"
        href="{{ URL::asset('assets/images/favicon_io/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16"
        href="{{ URL::asset('assets/images/favicon_io//favicon-16x16.png') }}">
    <link rel="manifest" href="{{ URL::asset('assets/images/favicon_io/site.webmanifest') }}">

    <!-- Encodage de la page -->
    <meta charset="utf-8">

    <!-- Surface de la fenêtre du navigateur -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fontawesome -->
    <script src="https://kit.fontawesome.com/19f71f9368.js" crossorigin="anonymous"></script>

    <!-- Titre du site + espace personnel -->
    <title>{{ env('APP_NAME') }} : Espace Personnel</title>


    <!-- Bootstrap v5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

    <!-- Styles -->
    @if ($role === 'parents')
    <link rel="stylesheet" href="{{ URL::asset('assets/css/back-office/layout-parents.css') }}">
    @else
    <link rel="stylesheet" href="{{ URL::asset('assets/css/back-office/layout-nounou.css') }}">
    @endif

    <link rel="stylesheet" href="{{ URL::asset('assets/css/back-office/mobile.css') }}">

    @isset($geolocalisation)
        <!-- Plugin Leaflet et MarkerCluster pour la géolocalisation -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css" integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ==" crossorigin="" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css">
        <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css">
    @endisset
    @isset($planning)
        <!-- Plugin FullCalendar pour l'agenda -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.5.1/main.min.css">
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.5.1/main.min.js" defer></script>
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.5.1/locales/fr.min.js" defer></script>
    @endisset
</head>

<body>
    {{-- Affiche un fond style modal pour l'apparition des menus en mode mobile --}}
    <div id="js-modal" class="background-modal"></div>

    <!-- Barre de navigation -->
    <aside id="js-barre-navigation" class="barre-navigation" role="complementary">
        <!--  Titre du site -->
        <h1 class="barre-navigation__titre" aria-label="titre-site">{{ env('APP_NAME') }}</h1>
        <!--  Contenu d'identité -->
        <article class="barre-navigation__identite">
            <!-- Nom -->
            <h3 class="barre-navigation__identite--nom">{{ Auth::user()->nom ?? '' }} {{ Auth::user()->prenom ?? '' }}</h3>
            <!-- Photo -->
            <figure class="barre-navigation__identite--photo">
                @php
                    $userId = Auth::user()->id;
                @endphp
                {{-- Condition sur une photo de profil existe pour l'utilisateur --}}
                @if (Auth::user()->getFirstMediaUrl("avatar-$userId", 'thumb'))
                    <img src="{{ Auth::user()->getFirstMediaUrl("avatar-$userId", 'thumb') }}" alt="Photo de profil de l'utilisateur">
                @else
                    <img src="{{ URL::asset('assets/images/photo_vide.jpg') }}" alt="Aucune photo de profil de l'utilisateur">
                @endif
            </figure>
        </article>
        <!-- Menu de navigation principal -->
        <nav class="barre-navigation__menu" role="navigation">
            <ul>
                <li><a class="barre-navigation__menu--lien @if(Request::path() === 'profile') is-current @endif" href="{{ route('profile') }}"><i class="barre-navigation__menu--icone fas fa-home"></i><span class="barre-navigation__menu--texte">Accueil</span></a></li>
                {{-- Condition si l'utilisateur est dans la catégorie parent ou assMat --}}
                @if ($role === 'parents')
                    <li><a class="barre-navigation__menu--lien @if(Request::path() === 'recherche') is-current @endif" href="{{ route('parent.recherche') }}"><i class="barre-navigation__menu--icone fas fa-map-marker-alt"></i><span class="barre-navigation__menu--texte">Rechercher</span></a></li>
                    <li><a class="barre-navigation__menu--lien @if(Request::path() === 'contrats') is-current @endif" href="{{ route('contrats') }}"><i class="barre-navigation__menu--icone far fa-folder-open"></i><span class="barre-navigation__menu--texte">Mes contrats</span></a></li>
                    <li><a class="barre-navigation__menu--lien @if(Request::path() === 'liste/enfants') is-current @endif" href="{{ route('parent.enfants') }}"><i class="barre-navigation__menu--icone fas fa-users"></i><span class="barre-navigation__menu--texte">Famille</span></a></li>
                    <li><a class="barre-navigation__menu--lien @if(Request::path() === 'favoris') is-current @endif" href="{{ route('parent.favoris') }}"><i class="barre-navigation__menu--icone fas fa-star"></i><span class="barre-navigation__menu--texte">Mes favoris</span></a></li>
                    <li><a class="barre-navigation__menu--lien @if(Request::path() === 'carnet-de-bord/consulter') is-current @endif" href="{{ route('parent.carnet_consultation') }}"><i class="barre-navigation__menu--icone fas fa-book"></i><span class="barre-navigation__menu--texte">Carnet de bord</span></a></li>
                @else
                    <li><a class="barre-navigation__menu--lien @if(\Route::current()->getName() === 'assistante-maternelle.fiche') is-current @endif" href="{{ route('assistante-maternelle.fiche', ['id' => Auth::user()->categorie_id]) }}"><i class="barre-navigation__menu--icone fas fa-inbox"></i><span class="barre-navigation__menu--texte">Ma fiche</span></a></li>
                    <li><a class="barre-navigation__menu--lien @if(Request::path() === 'contrats') is-current @endif" href="{{ route('contrats') }}"><i class="barre-navigation__menu--icone far fa-folder-open"></i><span class="barre-navigation__menu--texte">Mes contrats</span></a></li>
                    <li><a class="barre-navigation__menu--lien @if(Request::path() === 'recommandations') is-current @endif" href="{{ route('assistante-maternelle.recommandations')}}"><i class="barre-navigation__menu--icone fas fa-star-half-alt"></i><span class="barre-navigation__menu--texte">Recommandations</span></a></li>
                    <li><a class="barre-navigation__menu--lien @if(Request::path() === 'carnet-de-bord') is-current @endif" href="{{ route('assistante-maternelle.carnet') }}"><i class="barre-navigation__menu--icone fas fa-book"></i><span class="barre-navigation__menu--texte">Carnet de bord</span></a></li>
                @endif
            </ul>
        </nav>
    </aside>
    <main id="main" class="contenu-principal" role="main">
        <!-- Bandeau du haut -->
        <header id="header" class="contenu-principal__header" role="banner">
            <!-- Lien burger vers menu princiapl en Mobile -->
            <nav class="menu-mobile">
                <a href="#" class="menu-mobile__principal" aria-label="menu"><i class="fas fa-bars"></i></a>
            </nav>
            <!-- Titre de l'espace personnel -->
            {{-- Condition si l'utilisateur est dans la catégorie parent ou assMat --}}
            @if ($role === 'parents')
            <h2 class="contenu-principal__header--titre">Espace Parents</h2>
            @else
            <h2 class="contenu-principal__header--titre">Espace Assistante Maternelle</h2>
            @endif
            <!-- Menu de navigation secondaire -->
            <nav id="js-menu-secondaire" class="contenu-principal__header--navigation" role="navigation">
                <a class="lien" href="/planning/{{ Auth::user()->id }}" aria-label="mon agenda" title="mon agenda"><i class="far fa-calendar-alt"></i></a>
                <a class="lien" href="/users/{{ Auth::user()->id }}/edit" aria-label="mon compte" title="mon profil utilisateur"><i class="fas fa-user-circle"></i></a>
                <a class="lien" href="{{ route('logout') }}" title="me déconnecter" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i></a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </nav>
            <!-- Lien vers menu secondaire en Mobile-->
            <nav class="menu-mobile">
                <a href="#" class="menu-mobile__secondaire" aria-label="menu"><i class="fas fa-ellipsis-v"></i></a>
            </nav>
        </header>
        <!-- Section qui permet l'affichage des différents contenu du site -->
        <section class="contenu-principal__page">
            <!-- Bloc pour afficher les alertes (réussite ou echec) -->
            @if (session('message'))
                <div class="container my-2 alert alert-warning alert-dismissible fade show" role="alert">
                    {{ session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('success'))
                <div class="container my-2 alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <!-- Affichage du contenu des pages -->
            @yield('content')
        </section>
    </main>

    <!-- Bootstrap v5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous">
    </script>
    @isset($geolocalisation)
        <!-- Plugin Leaflet et MarkerCluster pour la géolocalisation-->
        <script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js" integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw==" crossorigin=""></script>
        <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
    @endisset
    <script src="{{ URL::asset('assets/js/back-office/box.js') }}"></script>
    <script src="{{ URL::asset('assets/js/back-office/form.js') }}"></script>
    <script src="{{ URL::asset('assets/js/back-office/mobile.js') }}"></script>
    @isset($js)
        @foreach ($js as $file)
            <script src="{{ URL::asset("assets/js/back_-office/$file.js") }}"></script>
        @endforeach
    @endisset

</body>

</html>
