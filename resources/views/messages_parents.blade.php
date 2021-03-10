@extends('layouts.back')
@section('content')
    <!-- Liste des messages de chaque enfant -->
    <article class="box box-lg">
        <header class="box__header">
            <h4 class="box__header--titre">Les messages</h4>
        </header>
        <div id="messages" class="box__contenu">
            <input type="hidden" id="js-id-parent" value="{{ Auth::user()->categorie->id }}">
            <select id="js-messages-enfant" autofocus class="form-select box__contenu--select messages-enfants" aria-label="enfants" required>
                <option value="#" disabled selected>Selectionnez un enfant</option>
                @foreach ($enfants as $enfant)
                <option value="{{ $enfant->id }}">{{ ucFirst($enfant->prenom) }}</option>
                @endforeach
            </select>
    </article>
@endsection
