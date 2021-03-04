@extends('layouts.back')
@section('content')
    <article class="box box-lg">
        <header>
            <h4>Informations générales</h4>
        </header>
        <div class="contenu">
            <ul>
                <li>Nombre de notes : @if ($nombreNote > 0) {{ $nombreNote }} @else aucune @endif</li>
                <li>Nombre d'avis : @if ($nombreAvis > 0) {{ $nombreAvis }} @else aucun @endif</li>
                <li>Ma note moyenne :
                    @if($moyenne !== null)
                        @for ($i = 1; $i <= $noteMax; $i++)
                            <i class="fs-6 note text-warning @if($i <= $moyenne) fas @else far @endif fa-star"></i>
                        @endfor
                    @endif
                </li>
            </ul>
        </div>
    </article>
    <article id="avis" class="box box-lg">
        <header>
            <h4>Tous les avis</h4>
        </header>
        <div class="contenu">
            <div class="d-flex justify-content-center m-3 p-3">
                <div class="spinner-border" role="status"></div>
            </div>
            <form class="d-flex flex-wrap align-items-center my-3 border-bottom pb-3">
                <label class="mx-2" for="filtre">Filtre : </label>
                <select name="filtre" id="filtre" class=" w-75 form-select form-select-sm" aria-label="Filtre de selection" value="{{old('filtre')}}">
                    <option value="aucun" selected disabled>Selectionnez</option>
                    <option value="note_desc">Note : + vers -</option>
                    <option value="note_asc">Note :  - vers + </option>
                    <option value="avis_desc">Avis : Récents vers anciens</option>
                    <option value="avis_asc">Avis : Anciens vers récents</option>
                </select>
            </form>
            <div id='liste_avis' data-nounou-id="{{Auth::user()->categorie_id}}" class="visually-hidden">
                <div id="messages_avis">
                </div>
                <div id="pagination_avis" class="text-center">
                </div>
            </div>
        </div>
    </article>
@endsection