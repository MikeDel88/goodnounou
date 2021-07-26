@extends('layouts.back')
@section('content')
    <!-- Détails du contrat -->
    <article class="box box-lg">
        <header class="box__header">
            <h4 class="box__header--titre">Fiche du contrat n°{{ $contrat->id }} - Début le {{ Carbon\Carbon::parse($contrat->date_debut)->translatedFormat('j F Y') }}</h4>
            <div>
                <a href="{{ route('contrats') }}" class="px-3"><i class="fas fa-arrow-left"></i></a>
            </div>
        </header>
        <article class="box__contenu d-flex justify-content-start flex-wrap">
            <ul class="p-2">
                <li><span class="fw-bold">Identité assistante maternelle :</span> {{ $contrat->assistanteMaternelle->categorie->getIdentite() }}</li>
                <li><span class="fw-bold">Enfant : </span>{{ $contrat->enfant->prenom }}</li>
                <li><span class="fw-bold">Nombre d'heures prévu par semaine: </span>{{ $contrat->nombre_heures }}h</li>
                <li><span class="fw-bold">Nombre de semaines prévu (hors 5 semaines de congés de l'assistante maternelle) sur l'année : </span>{{ $contrat->nombre_semaines }}</li>
                <li><span class="fw-bold">Nombre d'heures lissés par mois : </span>{{ $nombre_heures_lisse }}h</li>
                <li><span class="fw-bold">Fériés : </span>@if ($contrat->assistanteMaternelle->criteres->ferie === 0) non travaillé @else possible @endif</li>
                <li><span class="fw-bold">Week-end : </span>@if ($contrat->assistanteMaternelle->criteres->week_end === 0) non travaillé @else possible @endif</li>
            </ul>
            <ul class="p-2">
                <li><span class="fw-bold">Taux horaires net : </span>{{ $contrat->taux_horaire }}€</li>
                <li><span class="fw-bold">Taux d'entretien par jour : </span>{{ $contrat->taux_entretien }}€</li>
                <li><span class="fw-bold">Frais repas par jour (si pris en charge): </span>{{ "$contrat->frais_repas € " ?? 'non pris en charge' }}</li>
                <li><span class="fw-bold">Salaire mensuel moyen à verser (hors heures supplémentaires) : </span>{{ $salaire_mensuel }}€</li>
            </ul>
        </article>

        <div class="p-3 text-end">
            @if (intval($contrat->status_id) !== 4)
                <a href="cloture" class="btn btn-danger box__contenu--lien">Mettre fin au contrat</a>
            @else
                <span class="text-danger">Contrat clos le {{ $contrat->updated_at->translatedFormat('j F Y') }}</span>
            @endif
        </div>
    </article>
    <!-- Ajouter des horaires au contrat -->
    @if (intval($contrat->status_id) === 2)
        <article class="box box-lg">
            <header class="box__header">
                <h4 class="box__header--titre">Ajouter un horaire de garde</h4>
                <div class="nombre_heures mx-1"></div>
            </header>
            <article class="box__contenu">
                <form action="/horaires/ajouter" method="POST" class="container">
                    @csrf
                    <input type="hidden" id="contrat" name="contrat_id" value="{{ $contrat->id }}" required>
                    <input type="hidden" name="debut_contrat" value="{{ $contrat->date_debut }}" required>
                    <input type="hidden" id="heures_garde" name="nombre_heures" required>
                    <div class="input-group mb-3">
                        <label class="input-group-text" id="garde">Garde du</label>
                        <input type="date" name="jour_garde" class="form-control" aria-label="date garde" aria-describedby="garde" min="{{ $contrat->date_debut }}" required>
                    </div>
                    <div class="row mb-3">
                        <div class="input-group">
                            <label class="input-group-text">Déposer</label>
                            <input type="time" aria-label="déposer à" name="heure_debut" class="form-control heure_debut" step="300" required>
                            <input type="text" aria-label="déposer par" name="depose_par" class="form-control" placeholder="nom du déposant">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="input-group">
                            <label class="input-group-text">Récupérer</label>
                            <input type="time" aria-label="récupérer à" name="heure_fin" class="form-control heure_fin" step="300" required>
                            <input type="text" aria-label="récupérer par" name="recupere_par" class="form-control" placeholder="nom du récupérant">
                        </div>
                    </div>
                    <div class="form-floating">
                        <textarea class="form-control" placeholder="Laissez un commentaire ici" id="commentaires" name="description"></textarea>
                        <label for="commentaires">Commentaires</label>
                    </div>
                    <div class="row d-flex d-flex justify-content-end my-3">
                        <button class="col-md-2" type="submit">Saisir</button>
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
        </article>
        <!-- Voir un mois dans le détail -->
        <article class="box box-lg">
            <header class="box__header">
                <h4 class="box__header--titre">Voir un mois dans le détail</h4>
            </header>
            <div class="box__contenu selection_mois">
                <form action="#" class="row">
                    <div class="col-4">
                        <select id="mois" class="form-select" aria-label="Mois" required>
                            <option value="#" selected>Selectionnez un mois</option>
                            @foreach ($mois as $numero => $libelle)
                            <option value="{{ $numero }}">{{ $libelle }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-4">
                        <select id="annee" class="form-select" aria-label="Année" required>
                            <option value="{{ date('Y') - 1 }}">{{ date('Y') - 1 }}</option>
                            <option value="{{ date('Y') }}" selected>{{ date('Y') }}</option>
                            <option value="{{ date('Y') + 1 }}">{{ date('Y') + 1 }}</option>
                        </select>
                    </div>
                </form>
            </div>
        </article>
    @endif
@endsection
