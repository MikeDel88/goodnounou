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

    <!--  Metadescription pour le référencement  -->
    <meta name="description" content="{{ $metadescription ?? '' }}">

    <!-- Fontawesome -->
    <script src="https://kit.fontawesome.com/19f71f9368.js" crossorigin="anonymous"></script>

    <!--  Titre du site  -->
    <title>GoodNounou : {{ $title ?? '' }}</title>

    <!-- Styles -->
    @isset($bootstrap)
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    @endisset
    @isset($css)
        <!-- Fichiers CSS -->
        @foreach ($css as $file)
            <link rel="stylesheet" href="{{ URL::asset("assets/css/front-office/$file.css") }}">
        @endforeach
    @endisset
</head>

<body>
    <div id="app">
        <header id="header" role="banner">
            {{-- Menu pour le mobile --}}
            <nav class="menu-burger">
                <div id="burger"><i class="fas fa-bars"></i></div>
                <div id="nav-mobile" class="inactive">
                    <ul>
                        @guest
                            <li><a href="/" class="accueil"><i class="fas fa-home"></i>Accueil</a></li>
                            @if (Route::has('register'))
                                <li><a href="{{ route('register') }}" class="inscription"><i class="fas fa-user-plus"></i>Inscription</a></li>
                            @endif
                            @if (Route::has('login'))
                                <li><a href="{{ route('login') }}" class="connexion"><i class="fas fa-user"></i>Connexion</a></li>
                            @endif
                        @else
                            <li>
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> revenir à l'accueil</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        @endguest
                    </ul>
                </div>
            </nav>
            <h1 class="titre">GoodNounou</h1>
            {{-- Menu pour l'ecran de bureau --}}
            <nav id="navigation" role="navigation" class="menu-desktop">
                <ul>
                    @guest
                        <li><a href="/" class="accueil">Accueil</a></li>
                        @if (Route::has('register'))
                        <li><a href="{{ route('register') }}" class="inscription">Inscription</a></li>
                        @endif
                        @if (Route::has('login'))
                        <li><a href="{{ route('login') }}" class="connexion">Connexion</a></li>
                        @endif
                    @else
                        <li>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">revenir à l'accueil</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    @endguest
                </ul>
            </nav>
        </header>
        <main id="main" class="py-4" role="main">
            {{-- Affiche une erreur sur la page demandé est interdite d'accès --}}
            @if (session('error403'))
                <div class="response">{{ session('error403') }}</div>
            @endif
            @if (session('message'))
                <div class="response">{{ session('message') }}</div>
            @endif
            @yield('content')
        </main>
        <footer id="footer" role="contentinfo">
            <div class="menu">
                <nav role="navigation" class="liens">
                    <ul>
                        <li><a href="#">Contacter l'administrateur</a></li>
                        <li><a href="#">Conditions générales</a></li>
                        <li><a href="#">Mentions légales</a></li>
                        <li><a href="#">Remerciements</a></li>
                    </ul>
                </nav>
                <nav role="navigation" class="login">
                    <ul>
                        @guest
                            @if (Route::has('register'))
                                <li><a href="{{ route('register') }}" class="inscription">Inscription</a></li>
                            @endif
                            @if (Route::has('login'))
                                <li><a href="{{ route('login') }}" class="connexion">Connexion</a></li>
                            @endif
                        @else
                            <li>
                                <a href="{{ route('logout') }}"onclick="event.preventDefault();document.getElementById('logout-form').submit();">revenir à l'accueil</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        @endguest
                    </ul>
                </nav>
            </div>
            <small>Copyright {{ date('Y') }} - Michael Delamarre</small>
        </footer>
        <div id="to-top" class="inactive"><i class="fas fa-arrow-up fa-2x"></i></div>
    </div>
    <!-- Scripts -->
    @isset($bootstrap)
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
    @endisset
    @isset($js)
        <!-- Fichiers JS -->
        @foreach ($js as $file)
            <script src="{{ URL::asset("assets/js/front_office/$file.js") }}"></script>
        @endforeach
    @endisset
</body>

</html>
