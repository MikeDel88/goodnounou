@extends('layouts.back')
@section('content')
    <article class="box box-lg">
        <header>
            <h4>Rechercher une assistante maternelle</h4>
        </header>
        <div class="contenu">
            <form class="row" action="#">
                <div class="mb-3 col-md-6">
                    <label for="adresse" class="form-label">Adresse complète</label>
                    <input type="text" class="form-control" id="search" value="{{ old('adresse') }}" name="search"
                        required>
                </div>
                <div class="col-md-4">
                    <label for="customRange1" class="form-label">Rayon de recherche : <span id="distance"></span></label>
                    <input type="range" class="form-range distance" id="rangeDistance" min="0" max="100" step="5">
                </div>
                <div class="mb-3 col-md-2 d-flex align-items-end">
                    <button class="py-1" type="submit">Rechercher</button>
                </div>
            </form>
        </div>
    </article>
    <div class="d-flex flex-wrap">
        <article class="box box-sm" style="max-height: none">
            <header>
                <h4>Les critères</h4>
            </header>
            <div class="contenu p-3">
                <ul class="mt-1">
                    @foreach ($criteres as $critere)
                        <li>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="{{ $critere }}"
                                    id="{{ $critere }}">
                                <label class="form-check-label" for="{{ $critere }}">
                                    {{ $critere }}
                                </label>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </article>
        <div id="detailsMap" style="width:700px; height:500px; margin: 15px auto; z-index:1"></div>
    </div>

@endsection
