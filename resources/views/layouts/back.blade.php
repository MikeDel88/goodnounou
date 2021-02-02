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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

    @if ($role === 'parents')
        <link rel="stylesheet" href="{{ URL::asset('assets/css/back_office/layout_parents.css') }}">
    @else
        <link rel="stylesheet" href="{{ URL::asset('assets/css/back_office/layout_nounou.css') }}">
    @endif

    <link rel="stylesheet" href="{{ URL::asset('assets/css/back_office/mobile.css') }}">

</head>

<body>
    <!-- Bandeau du côté version parents -->
    <aside>
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
                        <li><a href="#"><i class="fas fa-map-marker-alt"></i><span>Rechercher</span></a></li>
                        <li><a href="#"><i class="far fa-folder-open"></i><span>Mes contrats</span></a></li>
                        <li><a href="#"><i class="fas fa-users"></i><span>Famille</span></a></li>
                        <li><a href="#"><i class="fas fa-book"></i><span>Carnet de bord</span></a></li>
                    @else
                        <li><a href="#"><i class="fas fa-inbox"></i><span>Ma fiche</span></a></li>
                        <li><a href="#"><i class="far fa-folder-open"></i><span>Mes contrats</span></a></li>
                        <li><a href="#"><i class="fas fa-star-half-alt"></i><span>Recommandations</span></a></li>
                        <li><a href="#"><i class="fas fa-book"></i><span>Carnet de bord</span></a></li>
                    @endif
                </ul>
            </nav>
        </div>
    </aside>
    <main>
        <!-- Bannière du haut layout version parents -->
        <header>
            <div class="menu-mobile">
                <a href="#" aria-label="menu"><i class="fas fa-bars"></i></a>
            </div>
            @if ($role === 'parents')
                <h2>Espace Parents</h2>
            @else
                <h2>Espace Assistante Maternelle</h2>
            @endif
            <nav>
                <a href="#" aria-label="mon agenda" title="mon agenda"><i class="far fa-calendar-alt"></i></a>
                <a href="/users/{{ Auth::user()->id }}/edit" aria-label="mon compte"
                    title="mon profil utilisateur"><i class="fas fa-user-circle"></i></a>
                <a href="{{ route('logout') }}" title="me déconnecter"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i>
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </nav>
            <div class="menu-mobile">
                <a href="#" aria-label="menu"><i class="fas fa-ellipsis-v"></i></a>
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
    <script src="{{ URL::asset('assets/js/back_office/box.js') }}"></script>
    <script src="{{ URL::asset('assets/js/back_office/form.js') }}"></script>
</body>

</html>
