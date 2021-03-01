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
                <button class="btn btn-dark mx-auto" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Créer un contrat</button>
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
                        <select class="form-select my-2" aria-label="Liste des asistantes maternelles favorites" name="assistante_maternelle" required>
                            <option selected disabled>Liste de mes assistantes maternelles favorites</option>

                            @foreach ($liste_favoris as $favoris)
                                @if ($favoris->assistanteMaternelle->disponible === 1 && $favoris->assistanteMaternelle->nombre_place > 0)
                                    <option value="{{ $favoris->assistante_maternelle_id }}">{{ "{$favoris->assistanteMaternelle->categorie->nom} {$favoris->assistanteMaternelle->categorie->prenom}" }}</option>
                                @endif
                            @endforeach

                        </select>
                        <div class="row my-3">
                            <div class="col-md-6 mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-default">Nombre de semaines par an</span>
                                <input type="number" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" min="0" max="47" name="nombre_semaines" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-default">Nombre d'heures par semaine</span>
                                <input type="number" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" min="0" max="48" name="nombre_heures" required>
                            </div>
                        </div>
                        <div class="row my-2">
                            <div class="col-md-4">
                                <span class="input-group-text" id="inputGroup-sizing-default">Début du contrat</span>
                                <input type="date" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" value="{{ date('Y-m-d') }}" name="date_debut" required>
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
    @if ($role === 'assistante-maternelle')
        <article class="box box-lg">
            <header>
                <button class="btn btn-dark mx-auto" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Contrat en attente de validation</button>
            </header>
            <div id="collapseExample" class="contenu collapse">
                <div class="card card-body">
                    <ul class="list-group list-group-flush">
                        @foreach ($contrats as $contrat)
                            @if ($contrat->status_id === 1)
                                <li class="d-flex justify-content-between list-group-item">
                                    <a href="contrat/{{ $contrat->id }}">{{ "{$contrat->parent->categorie->nom} {$contrat->parent->categorie->prenom}" }} pour {{ $contrat->enfant->prenom }}</a>
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
    <article class="box box-lg">
        <header>
            <h4>Liste des contrats en cours</h4>
        </header>
        <div class="contenu">
            <ul class="list-group list-group-flush">
                @foreach ($contrats as $contrat)
                    @if ($contrat->status_id === 2 && $role === 'assistante-maternelle')
                        <li class="d-flex justify-content-between list-group-item">
                            <span>Famille {{ "{$contrat->parent->categorie->nom} {$contrat->parent->categorie->prenom}" }} pour {{ "{$contrat->enfant->nom} {$contrat->enfant->prenom}" }} du {{ Carbon\Carbon::parse($contrat->date_debut)->translatedFormat('j F Y') }}</span>
                            <a href="{{ route('assistante-maternelle.contrat_show', ['id' => $contrat->id]) }}" class="btn btn-dark">Consulter</a>
                        </li>
                    @elseif($role === 'parents' && $contrat->status_id !== 4)
                        <li class="d-flex justify-content-between list-group-item">
                            <span>{{ "{$contrat->enfant->nom} {$contrat->enfant->prenom}" }} avec {{ "{$contrat->assistanteMaternelle->categorie->nom} {$contrat->assistanteMaternelle->categorie->prenom}" }} du {{ Carbon\Carbon::parse($contrat->date_debut)->translatedFormat('j F Y') }} ({{ $contrat->status->nom }})</span>
                            @if ($contrat->status_id === 3 || $contrat->status_id === 1)
                                <a href="{{ route('parent.contrat_supprimer', ['id' => $contrat->id]) }}" title="supprimer"><i class="fas fa-trash text-danger"></i></a>
                            @else
                                <a href="{{ route('parent.contrat_edit', ['id' => $contrat->id]) }}" class="btn btn-dark">Consulter</a>
                            @endif
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    </article>
    <article class="box box-lg">
        <header>
            <h4>Liste des contrats clos</h4>
        </header>
        <div class="contenu">
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
                                <a href="{{ route('parent.contrat_edit', ['id' => $contrat->id]) }}" class="btn btn-dark">Consulter</a>
                            @elseif($role === 'assistante-maternelle')
                                <a href="{{ route('assistante-maternelle.contrat_show', ['id' => $contrat->id]) }}" class="btn btn-dark">Consulter</a>
                            @endif
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    </article>
@endsection
