@extends('layouts.back')
@section('content')
    @if ($role === 'parents')
        @if ($errors->any())
            <div class="mt-3 alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <article class="box box-lg">
            <header>
                <button class="btn btn-dark mx-auto" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                    Créer un contrat
                </button>
            </header>
            <div id="collapseExample" class="contenu collapse">

                <div class="card card-body">
                    <form action="{{ route('parent.contrat_creation') }}" method="POST">
                        @csrf
                        <select class="form-select my-2" aria-label="Liste des enfants" name="enfant" required>
                            <option selected disabled>Liste de mes enfants</option>
                            @foreach ($enfants as $enfant)
                                <option value="{{ $enfant->id }}">{{ $enfant->prenom }}</option>
                            @endforeach
                        </select>
                        <select class="form-select my-2" aria-label="Liste des asistantes maternelles favorites"
                            name="assistante_maternelle" required>
                            <option selected disabled>Liste de mes assistantes maternelles favoris</option>
                            @foreach ($liste_favoris as $favoris)
                                <option value="{{ $favoris->assistante_maternelle_id }}">
                                    {{ "{$favoris->assistanteMaternelle->categorie->nom} {$favoris->assistanteMaternelle->categorie->prenom}" }}
                                </option>
                            @endforeach
                        </select>
                        <div class="row my-3">
                            <div class="col-md-6 mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-default">Nombre de semaines par
                                    an</span>
                                <input type="number" class="form-control" aria-label="Sizing example input"
                                    aria-describedby="inputGroup-sizing-default" min="0" max="47" name="nombre_semaines"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-default">Nombre d'heures par
                                    semaine</span>
                                <input type="number" class="form-control" aria-label="Sizing example input"
                                    aria-describedby="inputGroup-sizing-default" min="0" max="48" name="nombre_heures"
                                    required>
                            </div>
                        </div>
                        <div class="row my-2">
                            <div class="col-md-4">
                                <span class="input-group-text" id="inputGroup-sizing-default">Début du contrat</span>
                                <input type="date" class="form-control" aria-label="Sizing example input"
                                    aria-describedby="inputGroup-sizing-default" value="{{ date('Y-m-d') }}"
                                    name="date_debut" required>
                            </div>
                        </div>
                        <footer>
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </footer>
                    </form>
                </div>
            </div>
        </article>
    @endif
    <article class="box box-lg">
        <header>
            <h4>
                Liste des contrats en cours
            </h4>
        </header>
        <div class="contenu">
            <ul class="list-group list-group-flush">
                @foreach ($contrats as $contrat)
                    <li class="d-flex justify-content-between list-group-item">
                        <span>{{ "{$contrat->enfant->nom} {$contrat->enfant->prenom}" }} du
                            {{ Carbon\Carbon::parse($contrat->date_debut)->translatedFormat('j F Y') }}
                            ({{ $contrat->status }})</span><a href="#" class="btn btn-dark">Consulter</a>
                    </li>
                @endforeach
            </ul>

        </div>
    </article>
@endsection
