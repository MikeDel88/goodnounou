@extends('layouts.back')
@section('content')
    @if ($role === 'parents')
        <!-- Afficher les erreurs de validation -->
        @if ($errors->any())
            <div class="mt-3 alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <!-- Création d'un contrat -->
        <article class="box box-lg">
            <header class="box__header">
                <button class="btn btn-dark mx-auto" autofocus type="button" data-bs-toggle="collapse" data-bs-target="#collapseContrat" aria-expanded="false" aria-controls="collapseExample">Créer un contrat</button>
            </header>
            <div id="collapseContrat" class="box__contenu collapse">
                <div class="card card-body">
                    <form action="{{ route('parent.contrat_creation') }}" method="POST">
                        @csrf
                        {{-- Liste des enfants de l'utilisateur --}}
                        <select class="form-select my-2" aria-label="Liste des enfants" name="enfant" required>
                            <option selected disabled>Liste de mes enfants</option>
                            @foreach ($enfants as $enfant)
                            <option value="{{ $enfant->id }}">{{ $enfant->prenom }}</option>
                            @endforeach

                        </select>
                        {{-- Liste des assistantes maternelles en favoris --}}
                        <select class="form-select my-2" aria-label="Liste des asistantes maternelles favorites" name="assistante_maternelle" required>
                            <option selected disabled>Liste de mes assistantes maternelles favorites</option>
                            @foreach ($liste_favoris as $favoris)
                                @if ($favoris->assistanteMaternelle->disponible === 1 && $favoris->assistanteMaternelle->nombre_place > 0)
                                    <option value="{{ $favoris->assistante_maternelle_id }}">{{ $favoris->assistanteMaternelle->categorie->getIdentite() }}</option>
                                @endif
                            @endforeach

                        </select>
                        {{-- Détails du contrat --}}
                        <div class="row my-3">
                            <div class="col-md-6 mb-3">
                                <label class="input-group-text" for="nombre-semaines">Nombre de semaines par an</label>
                                <input id="nombre-semaines" type="number" class="form-control" aria-label="nombre semaines" aria-describedby="nombre semaines" min="0" max="47" name="nombre_semaines" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="input-group-text" for="nombre-heures">Nombre d'heures par semaine</label>
                                <input id="nombre-heures" type="number" class="form-control" aria-label="nombre heures" aria-describedby="nombre heures" min="0" max="48" name="nombre_heures" required>
                            </div>
                        </div>
                        <div class="row my-2">
                            <div class="col-md-4">
                                <label class="input-group-text" for="debut-contrat">Début du contrat</label>
                                <input id="debut-contrat" type="date" class="form-control" aria-label="début contrat" aria-describedby="début contrat" value="{{ date('Y-m-d') }}" name="date_debut" required>
                            </div>
                        </div>
                        <footer class="text-end">
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </footer>
                    </form>
                </div>
            </div>
        </article>
    @endif
    @if ($role === 'assistante-maternelle')
        <!-- Réponse à une demande de contrat -->
        <article class="box box-lg">
            <header class="box__header">
                <button class="btn btn-dark mx-auto" type="button" data-bs-toggle="collapse" data-bs-target="#collapseReponseContrat" aria-expanded="false" aria-controls="collapseExample">Contrat en attente de validation</button>
            </header>
            <div id="collapseReponseContrat" class="box__contenu collapse">
                <div class="card card-body">
                    <ul class="list-group list-group-flush">
                        {{-- Liste des contrats avec le status en attente --}}
                        @foreach ($contrats as $contrat)
                            @if ($contrat->status_id === 1)
                                <li class="d-flex justify-content-between list-group-item">
                                    <a href="contrat/{{ $contrat->id }}">{{ $contrat->parent->categorie->getIdentite() }} pour {{ $contrat->enfant->prenom }}</a>
                                    <div>
                                        <a href="{{ route('assistante-maternelle.contrat_validation', ['id' => $contrat->id]) }}" class="d-inline-block mx-3" title="accepter"><i class="fas fa-check text-success"></i></a>
                                        <a href="{{ route('assistante-maternelle.contrat_refus', ['id' => $contrat->id]) }}" class="d-inline-block mx-3" title="refuser"><i class="fas fa-trash text-danger"></i></a>
                                    </div>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </article>
    @endif
    <!-- Liste des contrats en cours -->
    <article class="box box-lg">
        <header class="box__header">
            <h4 clas="box__header--titre">Liste des contrats en cours</h4>
        </header>
        <div class="box__contenu">
            <ul class="list-group list-group-flush">
                @foreach ($contrats as $contrat)
                    @if ($contrat->status_id === 2 && $role === 'assistante-maternelle')
                        <li class="d-flex justify-content-between list-group-item">
                            <span>Famille {{ $contrat->parent->categorie->getIdentite() }} pour {{ $contrat->enfant->getIdentite() }} du {{ Carbon\Carbon::parse($contrat->date_debut)->translatedFormat('j F Y') }}</span>
                            <a href="{{ route('assistante-maternelle.contrat_show', ['id' => $contrat->id]) }}" class="btn btn-dark box__contenu--lien">Consulter</a>
                        </li>
                    @elseif($role === 'parents' && $contrat->status_id !== 4)
                        <li class="d-flex justify-content-between list-group-item">
                            <span>{{ $contrat->enfant->getIdentite() }} avec {{ $contrat->assistanteMaternelle->categorie->getIdentite() }} du {{ Carbon\Carbon::parse($contrat->date_debut)->translatedFormat('j F Y') }} ({{ $contrat->status->nom }})</span>
                            @if ($contrat->status_id === 3 || $contrat->status_id === 1)
                                <a href="{{ route('parent.contrat_supprimer', ['id' => $contrat->id]) }}" title="supprimer contrat" class="box__contenu--lien"><i class="fas fa-trash fa-lg text-danger"></i></a>
                            @else
                                <a href="{{ route('parent.contrat_edit', ['id' => $contrat->id]) }}" class="btn btn-dark box__contenu--lien">Consulter</a>
                            @endif
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    </article>
    <!-- Liste des contrats clos -->
    <article class="box box-lg">
        <header class="box__header">
            <h4 class="box__header--titre">Liste des contrats clos</h4>
        </header>
        <div class="box__contenu">
            <ul class="list-group list-group-flush">
                @foreach ($contrats as $contrat)
                    @if ($contrat->status_id === 4)
                        <li class="d-flex justify-content-between list-group-item">
                            <span>
                                @if ($role === 'parents')
                                    {{ "{$contrat->assistanteMaternelle->categorie->nom} {$contrat->assistanteMaternelle->categorie->prenom}" }}
                                @elseif($role === 'assistante-maternelle')
                                    {{ "{$contrat->parent->categorie->nom} {$contrat->parent->categorie->prenom}" }}
                                @endif
                                pour {{ "{$contrat->enfant->nom} {$contrat->enfant->prenom}" }} - Du {{ Carbon\Carbon::parse($contrat->date_debut)->translatedFormat('d/m/Y') }} au {{ Carbon\Carbon::parse($contrat->updated_at)->translatedFormat('d/m/Y') }}
                            </span>
                            @if ($role === 'parents')
                                <a href="{{ route('parent.contrat_edit', ['id' => $contrat->id]) }}" class="btn btn-dark box__contenu--lien">Consulter</a>
                            @elseif($role === 'assistante-maternelle')
                                <a href="{{ route('assistante-maternelle.contrat_show', ['id' => $contrat->id]) }}" class="btn btn-dark box__contenu--lien">Consulter</a>
                            @endif
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    </article>
@endsection
