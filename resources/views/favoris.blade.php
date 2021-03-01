@extends('layouts.back')
@section('content')
    <article class="box box-lg">
        <header>
            <h4>Liste des favoris</h4>
        </header>
        <div class="contenu">
            <ul class="list-group">
                @foreach (Auth::user()->categorie->favoris as $favoris)
                    <li class="list-group-item">
                        <a href="/fiche/assistante-maternelle/{{ $favoris->assistante_maternelle_id }}" target="_blank">{{ "{$favoris->assistanteMaternelle->categorie->nom} {$favoris->assistanteMaternelle->categorie->prenom} à {$favoris->assistanteMaternelle->ville_pro}" }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </article>


@endsection
