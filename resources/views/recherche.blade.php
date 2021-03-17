@extends('layouts.back')
@section('content')
    <!-- Recherche avec adresse et rayon de recherche-->
    <article class="box box-lg">
        <header class="box__header">
            <h4 class="box__header--titre">Rechercher une assistante maternelle</h4>
        </header>
        <div class="box__contenu">
            <form class="row" action="#">
                <!-- Barre de recherche -->
                <div class="mb-3 col-md-6">
                    <label for="search" class="form-label">Adresse complète</label>
                    <input type="text" autofocus class="form-control" id="search" role="search" value="{{ old('adresse') ?? Auth::user()->adresseComplete() }} " name="search" required>
                </div>
                <!-- Rayon de recherche -->
                <div class="col-md-4">
                    <label for="rangeDistance" class="form-label">Rayon de recherche : <span id="distance" class="js-distance-label">20 km</span></label>
                    <input type="range" class="form-range js-distance" id="rangeDistance" min="0" max="100" step="5" value="20" required>
                </div>
                <div class="mb-3 col-md-2 d-flex align-items-end">
                    <button class="py-1 js-search-submit" type="submit">Rechercher</button>
                </div>
            </form>
        </div>
    </article>
    <section class="d-flex flex-wrap">
        <!-- Filtre de recherche par critères -->
        <article class="box box-sm align-self-start">
            <header class="box__header">
                <h4 class="box__header--titre">Les critères</h4>
            </header>
            <div class="box__contenu p-3">
                <ul class="mt-1">
                    @foreach ($criteres as $critere)
                        <li class="form-check">
                            <input class="form-check-input js-criteres" type="checkbox" name="{{ $critere }}" id="{{ $critere }}">
                            @if($critere === 'pas_animaux')
                            <label class="form-check-label" for="{{ $critere }}">Pas d'animaux</label>
                            @elseif($critere === 'pas_deplacements')
                            <label class="form-check-label" for="{{ $critere }}">Pas de déplacement</label>
                            @else
                            <label class="form-check-label" for="{{ $critere }}">{{ ucFirst(str_replace('_', ' ', $critere)) }}</label>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </article>
        <!-- Map OpenStreetMap -->
        <nav id="detailsMap" class="map align-self-start"></nav>
    </section>

@endsection
