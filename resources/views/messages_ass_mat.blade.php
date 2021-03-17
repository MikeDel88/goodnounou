@extends('layouts.back')
@section('content')
    <!-- Création d'un message pour un enfant -->
    <article class="box box-lg">
        <header class="box__header">
            <h4 class="box__header--titre">Créer un message</h4>
        </header>
        <div class="box__contenu">
            <form method="POST" action="message/ajouter">
                @csrf
                <div class="row d-flex flex-wrap">
                    <div class="col-md-6 my-2">
                        <select name="enfant" id="enfant" class="form-select" aria-label="enfants" required>
                            <option value="#" disabled selected>Selectionnez un enfant</option>
                            @foreach ($contrats as $contrat)
                            <option value="{{ $contrat->enfant_id }}">{{ $contrat->enfant->getIdentite() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 my-2">
                        <input type="date" class="form-control" id="jour_garde" value="{{ old('jour_garde') ?? date('Y-m-d') }}" max="{{ date('Y-m-d') }}" name="jour_garde" required>
                    </div>
                </div>
                <div class="row p-2 form-floating">
                    <textarea class="form-control" placeholder="Message concernant l'enfant" id="floatingTextarea" name="message" style="height: 200px"></textarea>
                    <label for="floatingTextarea">Message concernant l'enfant pour les parents</label>
                </div>
                <div class="row mx-2">
                    <button type="submit" class="col-md-2">Enregistrer</button>
                </div>
                @if ($errors->any())
                    <div class="mt-3 alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </form>
    </article>
    <!-- Modification d'un mesage existant -->
    <article class="box box-lg">
        <header class="box__header">
            <h4 class="box__header--titre">Voir les messages</h4>
        </header>
        <div id="messages" class="box__contenu">
            <input type="hidden" id="assistante_maternelle_id" value="{{ Auth::user()->categorie->id }}">
            <select id="messages_enfant" class="form-select" aria-label="enfants" required>
                <option value="#" disabled selected>Selectionnez un enfant</option>
                @foreach ($contrats as $contrat)
                <option value="{{ $contrat->enfant_id }}">{{ $contrat->enfant->getIdentite() }}</option>
                @endforeach
            </select>
    </article>
    <form action="/message/modifier" method="POST" class="modal fade" id="modificationMessage" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        @csrf
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalNomEnfant"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row m-3 p-2 form-floating">
                    <input type="hidden" name="enfant" id="idEnfant">
                    <input type="hidden" name="id_message" id="idMessage">
                    <textarea class="form-control" placeholder="Message concernant l'enfant" id="contenu" name="contenu" style="height: 200px"></textarea>
                    <label for="contenu">Message concernant l'enfant pour les parents</label>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </div>
        </div>
    </form>
@endsection
