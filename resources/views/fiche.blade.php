@extends('layouts.back')
@section('content')
    <article class="box box-lg">
        <header class="box__header">
            <h4 class="box__header--titre">Ma fiche de renseignement</h4>
            <div class="form-check form-switch">
                <input class="form-check-input visibilite" data-client-id="{{ Auth::user()->categorie->id }}" type="checkbox" id="flexSwitchCheckDefault" name="visible" @if (Auth::user()->categorie->visible === 1) checked="checked" @endif>
                <label class="form-check-label" for="flexSwitchCheckDefault">Visible</label>
            </div>
        </header>
        <div class="box__contenu">
            <ul>
                <li>Identité : {{ Auth::user()->getIdentite() }} ({{ Auth::user()->getAge() }})</li>
                <li>Téléphone : {{ Auth::user()->telephone ?? 'non renseigné' }}</li>
                <li>Email : {{ Auth::user()->email_contact ?? 'non renseigné ' }}</li>
                <li>Adresse personnelle : <span id="adresse-perso">{{Auth::user()->adresse}}</span> <span id='code-postal-perso'>{{Auth::user()->code_postal}}</span> <span id="ville-perso">{{Auth::user()->ville}}</span></li>
                <li>Membre depuis le : {{ Auth::user()->created_at->translatedFormat('j F Y') }}</li>
                <li class="text-end">
                    <a href=" /users/{{ Auth::user()->id }}/edit">Modifier mes informations</a>
                </li>
            </ul>
        </div>
    </article>
    <section>
        <article class="box box-lg">
            <header class="box__header">
                <h4 class="box__header--titre">Mes informations professionnelles</h4>
                <div class="form-check form-switch">
                    <input class="form-check-input disponible" type="checkbox" data-client-id="{{ Auth::user()->categorie->id }}" id="flexSwitchCheckChecked" @if (Auth::user()->categorie->disponible === 1) checked="checked" @endif>
                    <label class="form-check-label" for="flexSwitchCheckChecked">Disponible</label>
                </div>
            </header>
            <div class="box__contenu p-4">
                <form action="/fiche/{{ Auth::user()->categorie->id }}" method="POST">
                    @csrf
                    <div class="row my-2">
                        <div class="mb-3 col-md-4">
                            <label for="date_debut" class="form-label">Début dans le métier</label>
                            <input type="date" class="form-control" id="date_debut" value="{{ Auth::user()->categorie->date_debut ?? old('date_debut') }}" min="1950-01-01" max="{{ date('Y-m-d') }}" name="date_debut" required>
                        </div>
                        <div class="mb-3 col-md-8">
                            <label for="formation" class="form-label">Ma formation</label>
                            <input type="text" class="form-control" id="formation" value="{{ Auth::user()->categorie->formation ?? old('formation') }}" name="formation" required>
                        </div>
                    </div>
                    @if(Auth::user()->adresse !== null)
                        <div class="row">
                            <div class="mb-3 col-md-4">
                                <label for="same-adress">Utiliser mon adresse personnelle</label>
                                <input id="same-adress" type="checkbox">
                            </div>
                        </div>
                    @endif
                    <div class="row">
                        <div class="mb-3 col-md-4">
                            <label for="adresse_pro" class="form-label">Mon adresse</label>
                            <input type="text" class="form-control" id="adresse_pro" value="{{ Auth::user()->categorie->adresse_pro ?? old('adresse_pro') }}" name="adresse_pro" required>
                        </div>
                        <div class="mb-3 col-md-2">
                            <label for="code_postal_pro" class="form-label">Mon code postal</label>
                            <input type="text" class="form-control" id="code_postal_pro" value="{{ Auth::user()->categorie->code_postal_pro ?? old('code_postal_pro') }}" name="code_postal_pro" required>
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="ville_pro" class="form-label">Ma ville</label>
                            <input type="text" class="form-control" id="ville_pro" value="{{ Auth::user()->categorie->ville_pro ?? old('ville_pro') }}" name="ville_pro" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-2">
                            <label for="nombre_place" class="form-label">Nombre de place</label>
                            <input type="number" class="form-control" id="nombre_place" value="{{ Auth::user()->categorie->nombre_place ?? old('nombre_place') }}" name="nombre_place" min="0" required>
                        </div>
                        <div class="mb-3 col-md-2">
                            <label for="taux_horaire" class="form-label">Mon taux horaire</label>
                            <input type="number" class="form-control" id="taux_horaire" value="{{ Auth::user()->categorie->taux_horaire ?? old('taux_horaire') }}" name="taux_horaire" min="0" step="0.05" placeholder="0€" required>
                        </div>
                        <div class="mb-3 col-md-2">
                            <label for="taux_entretien" class="form-label">Mon taux entretien</label>
                            <input type="number" class="form-control" id="taux_entretien" value="{{ Auth::user()->categorie->taux_entretien ?? old('taux_entretien') }}" name="taux_entretien" min="0" step="0.05" placeholder="0€" required>
                        </div>
                        <div class="mb-3 col-md-2">
                            <label for="frais_repas" class="form-label">Frais de repas</label>
                            <input type="number" class="form-control" id="frais_repas" placeholder="0€" value="{{ Auth::user()->categorie->frais_repas ?? old('frais_repas') }}" name="frais_repas" min="0" step="0.05" required>
                        </div>
                    </div>
                    <div class="row prochaine_disponibilite">
                        <div class="mb-3 col-md-6">
                            <label for="date_prochaine_disponibilite" class="form-label">Prochaine disponibilité</label>
                            <input type="date" class="form-control" id="date_prochaine_disponibilite" value="{{ Auth::user()->categorie->prochaine_disponibilite ?? old('date_prochaine_disponibilite') }}" min="{{ date('Y-m-d') }}" name="date_prochaine_disponibilite">
                        </div>
                    </div>
                    <div class="row">
                        <div class="contenu p-2">
                            <div class="form-floating">
                                <textarea class="form-control my-2" placeholder="Leave a comment here" id="floatingTextarea2" name="description" style="height: 100px">
                                    {{ Auth::user()->categorie->description ?? old('description') }}
                                </textarea>
                                <label for="floatingTextarea2">Précisez comment vous travaillez, les activitées que vous faites avec les enfants...</label>
                            </div>
                        </div>
                    </div>
                    <div class="row px-2 d-flex justify-content-end">
                        <button class="col-md-4" type="sybmit">Enregistrer mes informations</button>
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
        </article>
        <article class="box box-md">
            <header class="box__header">
                <h4 class="box__header--titre">Mes critères</h4>
            </header>
            <div class="box__contenu p-4">
                <div class="row">
                    <div class="col-6">
                        <input type="hidden" class="user" value="{{ Auth::user()->categorie->id }}">
                        <div class="form-check">
                            <input class="form-check-input critere" type="checkbox" value="{{ old('week_end') }}" id="week_end" name="week_end" @if ($critere->week_end === 1) checked="checked" @endif>
                            <label class="form-check-label" for="week_end">Week-end</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input critere" type="checkbox" value="{{ old('ferie') }}" id="ferie" name="ferie" @if ($critere->ferie === 1) checked="checked" @endif>
                            <label class="form-check-label" for="ferie">Jours férié</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input critere" type="checkbox" value="{{ old('horaires_atypique') }}" id="horaires" name="horaires_atypique" @if ($critere->horaires_atypique === 1) checked="checked" @endif>
                            <label class="form-check-label" for="horaires">Horaires atypiques</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input critere" type="checkbox" value="{{ old('periscolaire') }}" id="periscolaire" name="periscolaire" @if ($critere->periscolaire === 1) checked="checked" @endif>
                            <label class="form-check-label" for="periscolaire">Périscolaire</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input critere" type="checkbox" value="{{ old('repas') }}" id="repas" name="repas" @if ($critere->repas === 1) checked="checked" @endif>
                            <label class="form-check-label" for="repas">Prise en charge des repas</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-check">
                            <input class="form-check-input critere" type="checkbox" value="{{ old('pas_animaux') }}" id="animaux" name="pas_animaux" @if ($critere->pas_animaux === 1) checked="checked" @endif>
                            <label class="form-check-label" for="animaux">Pas d'animaux</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input critere" type="checkbox" value="{{ old('lait_maternelle') }}" id="lait" name="lait_maternelle" @if ($critere->lait_maternelle === 1) checked="checked" @endif>
                            <label class="form-check-label" for="lait">Lait maternelle</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input critere" type="checkbox" value="{{ old('couches_lavable') }}" id="couches" name="couches_lavable" @if ($critere->couches_lavable === 1) checked="checked" @endif>
                            <label class="form-check-label" for="couches">Couches lavables</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input critere" type="checkbox" value="{{ old('non_fumeur') }}" id="fumeur" name="non_fumeur" @if ($critere->non_fumeur === 1) checked="checked" @endif>
                            <label class="form-check-label" for="fumeur">Non Fumeur</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input critere" type="checkbox" value="{{ old('deplacements') }}" id="deplacements" name="pas_deplacements" @if ($critere->pas_deplacements === 1) checked="checked" @endif>
                            <label class="form-check-label" for="deplacements">Aucun déplacements</label>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </section>

@endsection
