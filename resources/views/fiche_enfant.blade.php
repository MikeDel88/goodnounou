@extends('layouts.back')
@section('content')
    <article class="box box-lg">
        <header class="box__header">
            <h4 class="box__header--titre">{{ $enfant->getIdentite() }}</h4>
            <div>
                <a href="#supprimer_enfant" class="col-md-2 text-danger" data-bs-toggle="modal"><i class="fas fa-trash"></i></a>
            </div>
        </header>
        <div class="box__contenu row">
            <div class="col-md-6 my-3">
                <form action="/fiche/enfant/{{ $enfant->id }}/update" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row mb-3">
                        <label for="nom" class="form-label">Nom</label>
                        <input autofocus type="text" class="form-control" id="nom" value="{{ $enfant->nom ?? old('nom') }}" name="nom" required>
                    </div>
                    <div class="row mb-3">
                        <label for="prenom" class="form-label">Prénom</label>
                        <input type="text" class="form-control" id="prenom" value="{{ $enfant->prenom ?? old('prenom') }}" name="prenom" required>
                    </div>
                    <div class="row mb-3">
                        <label for="date_naissance" class="form-label">Date de naissance</label>
                        <input type="date" class="form-control" id="date_naissance" value="{{ $enfant->date_naissance ?? old('date_naissance') }}" min="1950-01-01" max="{{ date('Y-m-d') }}" name="date_naissance" required>
                    </div>
                    <div class="row d-flex d-flex justify-content-end">
                        <button class="col-md-4" type="submit">Modifier</button>
                    </div>
                </form>
                @if ($errors->any())
                    <div class="mt-3 alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            <ul class="col-md-4 my-3">
                <li>Crée le : {{ Carbon\Carbon::parse($enfant->created_at)->translatedFormat('j F Y') }}</li>
                <li>Dernière modification le : {{ Carbon\Carbon::parse($enfant->updated_at)->translatedFormat('j F Y à H:i') }}</li>
            </ul>
        </div>
        <footer class="box__footer">
            <a href="{{ route('parent.enfants') }}" class="bg-dark text-light px-3 box__footer--lien">Retour</a>
        </footer>
    </article>

    <div class="modal fade show" id="supprimer_enfant" tabindex="-1" aria-labelledby="modalSuppressionEnfant" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSuppressionEnfant">Suppression de l'enfant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer l'enfant ?</p>
                    <p>Cette action est irreversible...</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non Merci</button>
                    <form id="delete-form" action="/fiche/enfant/{{ $enfant->id }}/supprimer" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger" type="submit">Confirmer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
