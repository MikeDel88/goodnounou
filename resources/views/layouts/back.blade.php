<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <link rel="apple-touch-icon" sizes="180x180"
        href="{{ URL::asset('assets/images/favicon_io/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32"
        href="{{ URL::asset('assets/images/favicon_io/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16"
        href="{{ URL::asset('assets/images/favicon_io//favicon-16x16.png') }}">
    <link rel="manifest" href="{{ URL::asset('assets/images/favicon_io/site.webmanifest') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fontawesome -->
    <script src="https://kit.fontawesome.com/19f71f9368.js" crossorigin="anonymous"></script>

    <title>GoodNounou | Espace Personnel</title>

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

    @if ($role === 'parents')
        <link rel="stylesheet" href="{{ URL::asset('assets/css/back_office/layout_parents.css') }}">
    @else
        <link rel="stylesheet" href="{{ URL::asset('assets/css/back_office/layout_nounou.css') }}">
    @endif

    <link rel="stylesheet" href="{{ URL::asset('assets/css/back_office/mobile.css') }}">

    @isset($geolocalisation)
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css" integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ==" crossorigin="" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css">
        <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css">
    @endisset
    @isset($planning)
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.5.1/main.min.css">
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.5.1/main.min.js" defer></script>
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.5.1/locales/fr.min.js" defer></script>
    @endisset
</head>

<body>
    {{-- DIV qui permet d'afficher un fond style modal pour l'apparation des menus en mode mobile --}}
    <div class="fond"></div>

    <!-- Bandeau du côté version parents -->
    <aside style="z-index:100">

        <div class="menu-one">
            <h1 aria-label="menu">GoodNounou</h1>
        </div>
        <div class="identite">
            <figure>
                {{-- Mettre en place une condition pour afficher la photo de profil si elle existe --}}
                @if (Auth::user()->photo !== null)
                    <img src="{{ Auth::user()->photo }}" alt=" photo identite">
                @else
                    <img src="{{ URL::asset('assets/images/photo_vide.jpg') }}" alt="Photo d'identité vide">
                @endif
            </figure>
            <h3>{{ Auth::user()->nom ?? '' }} {{ Auth::user()->prenom ?? '' }}</h3>
        </div>
        <div class="menu-two">
            <nav>
                <ul>
                    <li><a href="{{ route('profile') }}"><i class="fas fa-home"></i><span>Accueil</span></a></li>
                    @if ($role === 'parents')
                        <li><a href="{{ route('parent.recherche') }}"><i class="fas fa-map-marker-alt"></i><span>Rechercher</span></a></li>
                        <li><a href="{{ route('contrats') }}"><i class="far fa-folder-open"></i><span>Mes contrats</span></a></li>
                        <li><a href="{{ route('parent.enfants') }}"><i class="fas fa-users"></i><span>Famille</span></a></li>
                        <li><a href="{{ route('parent.favoris') }}"><i class="fas fa-star"></i><span>Mes favoris</span></a></li>
                        <li><a href="{{ route('parent.carnet_consultation') }}"><i class="fas fa-book"></i><span>Carnet de bord</span></a></li>
                    @else
                        <li><a href="{{ route('assistante-maternelle.fiche', ['id' => Auth::user()->categorie_id]) }}"><i class="fas fa-inbox"></i><span>Ma fiche</span></a></li>
                        <li><a href="{{ route('contrats') }}"><i class="far fa-folder-open"></i><span>Mes contrats</span></a></li>
                        <li><a href="{{ route('assistante-maternelle.recommandations')}}"><i class="fas fa-star-half-alt"></i><span>Recommandations</span></a></li>
                        <li><a href="{{ route('assistante-maternelle.carnet') }}"><i class="fas fa-book"></i><span>Carnet de bord</span></a></li>
                    @endif
                </ul>
            </nav>
        </div>
    </aside>
    <main>
        <!-- Bannière du haut layout version parents -->
        <header>
            {{-- Accès vers le menu général --}}
            <div class="menu-mobile">
                <a href="#" class="menu_principal_mobile" aria-label="menu"><i class="fas fa-bars"></i></a>
            </div>
            @if ($role === 'parents')
                <h2>Espace Parents</h2>
            @else
                <h2>Espace Assistante Maternelle</h2>
            @endif
            <nav>
                <a href="/planning/{{ Auth::user()->id }}" aria-label="mon agenda" title="mon agenda"><i class="far fa-calendar-alt"></i></a>
                <a href="/users/{{ Auth::user()->id }}/edit" aria-label="mon compte" title="mon profil utilisateur"><i class="fas fa-user-circle"></i></a>
                <a href="{{ route('logout') }}" title="me déconnecter" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i></a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </nav>
            {{-- Accès vers le menu secondaire --}}
            <div class="menu-mobile">
                <a href="#" class="menu_secondaire_mobile" aria-label="menu"><i class="fas fa-ellipsis-v"></i></a>
            </div>
        </header>
        <!-- Parties section informations personnelles parents -->
        <section>
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
            @yield('content')
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous">
    </script>
    @isset($geolocalisation)
        <script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js" integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw==" crossorigin=""></script>
        <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
    @endisset
    <script src="{{ URL::asset('assets/js/back_office/box.js') }}"></script>
    <script src="{{ URL::asset('assets/js/back_office/form.js') }}"></script>
    <script src="{{ URL::asset('assets/js/back_office/mobile.js') }}"></script>
    @isset($js)
        @foreach ($js as $file)
            <script src="{{ URL::asset("assets/js/back_office/$file.js") }}"></script>
        @endforeach
    @endisset

</body>

</html>
