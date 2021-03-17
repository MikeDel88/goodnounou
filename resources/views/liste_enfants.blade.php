@extends('layouts.back')
@section('content')
    <!-- Ajouter des enfants -->
    <article class="box box-lg">
        <header class="box__header">
            <h4 class="box__header--titre">Ajouter un enfant</h4>
        </header>
        <div class="box__contenu">
            <form action="/liste/enfants" method="POST">
                @csrf
                <div class="row d-flex flex-wrap">
                    <div class="mb-3 col-md-3">
                        <label for="nom" class="form-label">Nom</label>
                        <input autofocus type="text" class="form-control" id="nom" value="{{ old('nom') }}" name="nom" required>
                    </div>
                    <div class="mb-3 col-md-3">
                        <label for="prenom" class="form-label">Prénom</label>
                        <input type="text" class="form-control" id="prenom" value="{{ old('prenom') }}" name="prenom" required>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label for="date_naissance" class="form-label">Date de naissance</label>
                        <input type="date" class="form-control" id="date_naissance" value="{{ old('date_naissance') }}" min="1950-01-01" max="{{ date('Y-m-d') }}" name="date_naissance" required>
                    </div>
                    <div class="mb-3 col-md-2 d-flex align-items-end">
                        <button type="submit" class="px-3"><i class="fas fa-user-plus fa-1x p-2"></i></button>
                    </div>
                </div>
            </form>
            <!-- Affiche les erreurs -->
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
    </article>
    <!-- Liste des enfants de l'utilisateur -->
    <article class="box box-md">
        <header class="box__header">
            <h4 class="box__header--titre">Liste de mes enfants</h4>
        </header>
        <div class="box__contenu">
            <ul class="box__contenu--fiches-enfants">
                @foreach ($enfants as $enfant)
                    <li class="item d-flex justify-content-between">
                        <a class="lien" href="/fiche/enfant/{{ $enfant->id }}/edit">
                            <span>{{ $enfant->getIdentite() }} ({{ $enfant->getAge() }})</span>
                            <span><i class="fas fa-edit"></i></span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </article>
@endsection
