@extends('layouts.back')
@section('content')
    <article class="box box-lg">
        <header>
            <h4>Les messages</h4>
        </header>
        <div id="messages" class="contenu">
            <input type="hidden" id="idParent" value="{{ Auth::user()->categorie->id }}">
            <select id="messages_enfant" class="form-select" aria-label="enfants" required>
                <option value="#" disabled selected>Selectionnez un enfant</option>
                @foreach ($enfants as $enfant)
                <option value="{{ $enfant->id }}">{{ ucFirst($enfant->prenom) }}</option>
                @endforeach
            </select>
    </article>
@endsection
