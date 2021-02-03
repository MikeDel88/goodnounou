@extends('layouts.back')
@section('content')
    <article class="box box-lg">
        <header>
            <h4>Ma fiche de renseignement</h4>
            <div class="form-check form-switch">
                <input class="form-check-input visibilite" data-client-id="{{ Auth::user()->categorie->id }}"
                    type="checkbox" id="flexSwitchCheckDefault" name="visible" @if (Auth::user()->categorie->visible === 1) checked="checked" @endif>
                <label class="form-check-label" for="flexSwitchCheckDefault">visible</label>
            </div>
        </header>
        <div class="contenu">
            <ul>
                <li>Identité : {{ Auth::user()->nom }} {{ Auth::user()->prenom }}
                    ({{ Carbon\Carbon::parse(Auth::user()->date_naissance)->age }} ans) </li>
                <li>Téléphone : {{ Auth::user()->telephone ?? 'non renseigné' }}</li>
                <li>Email : {{ Auth::user()->email_contact ?? 'non renseigné ' }}</li>
                <li>Membre depuis le : {{ Auth::user()->created_at->translatedFormat('j F Y') }}</li>
                <li class="text-end">
                    <a href=" /users/{{ Auth::user()->id }}/edit">Modifier mes
                        informations</a>
                </li>
            </ul>
        </div>
    </article>
    <section>
        <article class="box box-lg">
            <header>
                <h4>Mes informations professionnelles</h4>
                <div class="form-check form-switch">
                    <input class="form-check-input disponible" type="checkbox" id="flexSwitchCheckChecked" @if (Auth::user()->categorie->disponible === 1) checked="checked" @endif>
                    <label class="form-check-label" for="flexSwitchCheckChecked">Disponible</label>
                </div>
            </header>
            <div class="contenu p-4">
                <form action="/fiche/{{ Auth::user()->categorie->id }}" method="POST">
                    @csrf
                    <div class="row my-2">
                        <div class="mb-3 col-md-4">
                            <label for="date_debut" class="form-label">Début dans le métier</label>
                            <input type="date" class="form-control" id="date_debut"
                                value="{{ Auth::user()->categorie->date_debut ?? old('date_debut') }}" min="1950-01-01"
                                max="{{ date('Y-m-d') }}" name="date_debut" required>
                        </div>
                        <div class="mb-3 col-md-8">
                            <label for="formation" class="form-label">Ma formation</label>
                            <input type="text" class="form-control" id="formation"
                                value="{{ Auth::user()->categorie->formation ?? old('formation') }}" name="formation"
                                required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-4">
                            <label for="adresse_pro" class="form-label">Mon adresse</label>
                            <input type="text" class="form-control" id="adresse_pro"
                                value="{{ Auth::user()->categorie->adresse_pro ?? old('adresse_pro') }}"
                                name="adresse_pro" required>
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="ville_pro" class="form-label">Ma ville</label>
                            <input type="text" class="form-control" id="ville_pro"
                                value="{{ Auth::user()->categorie->ville_pro ?? old('ville_pro') }}" name="ville_pro"
                                required>
                        </div>
                        <div class="mb-3 col-md-2">
                            <label for="code_postal_pro" class="form-label">Mon code postal</label>
                            <input type="text" class="form-control" id="code_postal_pro"
                                value="{{ Auth::user()->categorie->code_postal_pro ?? old('code_postal_pro') }}"
                                name="code_postal_pro" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-2">
                            <label for="nombre_place" class="form-label">Nombre de place</label>
                            <input type="number" class="form-control" id="nombre_place"
                                value="{{ Auth::user()->categorie->nombre_place ?? old('nombre_place') }}"
                                name="nombre_place" min="0" required>
                        </div>
                        <div class="mb-3 col-md-2">
                            <label for="taux_horaire" class="form-label">Mon taux horaire</label>
                            <input type="number" class="form-control" id="taux_horaire"
                                value="{{ Auth::user()->categorie->taux_horaire ?? old('taux_horaire') }}"
                                name="taux_horaire" min="0" step="0.05" placeholder="0€" required>
                        </div>
                        <div class="mb-3 col-md-2">
                            <label for="taux_entretien" class="form-label">Mon taux entretien</label>
                            <input type="number" class="form-control" id="taux_entretien"
                                value="{{ Auth::user()->categorie->taux_entretien ?? old('taux_entretien') }}"
                                name="taux_entretien" min="0" step="0.05" placeholder="0€" required>
                        </div>
                        <div class="mb-3 col-md-2">
                            <label for="frais_repas" class="form-label">Frais de repas</label>
                            <input type="number" class="form-control" id="frais_repas" placeholder="0€"
                                value="{{ Auth::user()->categorie->frais_repas ?? old('frais_repas') }}"
                                name="frais_repas" min="0" step="0.05" required>
                        </div>
                    </div>
                    <div class="row prochaine_disponibilite">
                        <div class="mb-3 col-md-6">
                            <label for="date_prochaine_disponibilite" class="form-label">Prochaine disponibilité</label>
                            <input type="date" class="form-control" id="date_prochaine_disponibilite"
                                value="{{ Auth::user()->categorie->date_prochaine_disponibilite ?? old('date_prochaine_disponibilite') }}"
                                min="{{ date('Y-m-d') }}" name="date_prochaine_disponibilite">
                        </div>
                    </div>
                    <div class="row">
                        <div class="contenu p-2">
                            <div class="form-floating">
                                <textarea class="form-control my-2" placeholder="Leave a comment here"
                                    id="floatingTextarea2" name="description"
                                    style="height: 100px">{{ Auth::user()->categorie->description ?? old('description') }}</textarea>
                                <label for="floatingTextarea2">Précisez comment vous travaillez, les activitées que vous
                                    faites
                                    avec les
                                    enfants...</label>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="sybmit">Enregistrer mes informations</button>
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
            <header>
                <h4>Mes critères</h4>
            </header>
            <div class="contenu p-4">
                <div class="row">
                    <div class="col-6">
                        <div class="form-check">
                            <input class="form-check-input critere" type="checkbox" value="{{ old('week_end') }}"
                                id="flexCheckDefault" name="week_end" @if ($critere->week_end === 1) checked="checked" @endif>
                            <label class="form-check-label" for="flexCheckDefault">
                                Week-end
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input critere" type="checkbox" value="{{ old('ferie') }}"
                                id="flexCheckDefault" name="ferie" @if ($critere->ferie === 1) checked="checked" @endif>
                            <label class="form-check-label" for="flexCheckDefault">
                                Jours férié
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input critere" type="checkbox"
                                value="{{ old('horaires_atypique') }}" id="flexCheckDefault" name="horaires_atypique" @if ($critere->horaires_atypique === 1) checked="checked" @endif>
                            <label class="form-check-label" for="flexCheckDefault">
                                Horaires atypiques
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input critere" type="checkbox" value="{{ old('periscolaire') }}"
                                id="flexCheckDefault" name="periscolaire" @if ($critere->periscolaire === 1) checked="checked" @endif>
                            <label class="form-check-label" for="flexCheckDefault">
                                Périscolaire
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input critere" type="checkbox" value="{{ old('repas') }}"
                                id="flexCheckDefault" name="repas" @if ($critere->repas === 1) checked="checked" @endif>
                            <label class="form-check-label" for="flexCheckDefault">
                                Prise en charge des repas
                            </label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-check">
                            <input class="form-check-input critere" type="checkbox" value="{{ old('animaux') }}"
                                id="flexCheckDefault" name="animaux" @if ($critere->animaux === 1) checked="checked" @endif>
                            <label class="form-check-label" for="flexCheckDefault">
                                Animaux
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input critere" type="checkbox" value="{{ old('lait_maternelle') }}"
                                id="flexCheckDefault" name="lait_maternelle" @if ($critere->lait_maternelle === 1) checked="checked" @endif>
                            <label class="form-check-label" for="flexCheckDefault">
                                Lait maternelle
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input critere" type="checkbox" value="{{ old('couches_lavable') }}"
                                id="flexCheckDefault" name="couches_lavable" @if ($critere->couches_lavable === 1) checked="checked" @endif>
                            <label class="form-check-label" for="flexCheckDefault">
                                Couches lavables
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input critere" type="checkbox" value="{{ old('fumeur') }}"
                                id="flexCheckDefault" name="fumeur" @if ($critere->fumeur === 1) checked="checked" @endif>
                            <label class="form-check-label" for="flexCheckDefault">
                                Fumeur
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input critere" type="checkbox" value="{{ old('deplacements') }}"
                                id="flexCheckDefault" name="deplacements" @if ($critere->deplacements === 1) checked="checked" @endif>
                            <label class="form-check-label" for="flexCheckDefault">
                                Déplacements
                            </label>
                        </div>
                    </div>

                </div>
            </div>
        </article>
    </section>


    <script>
        /* Affiche l'input des prochaines disponibilités si la checkbox disponible n'est pas vrai */
        let nextDispo = document.querySelector('.prochaine_disponibilite');
        let checkNextDispo = document.querySelector('.disponible');
        if (checkNextDispo.getAttribute('checked') === 'checked') {
            nextDispo.style.display = 'none'
            nextDispo.value = null;
        }
        checkNextDispo.addEventListener('change', function() {
            if (checkNextDispo.getAttribute('checked') === 'checked') {
                this.removeAttribute('checked');
                nextDispo.style.display = 'block'
            } else {
                nextDispo.style.display = 'none';
                nextDispo.value = null;
                this.setAttribute('checked', 'checked');
            }
        })




        // let inputVisibilite = document.querySelector('.visibilite');

        // inputVisibilite.addEventListener('change', async function() {

        //     let clientId = this.getAttribute('data-client-id');
        //     let method;
        //     if (this.getAttribute('checked') === 'checked') {
        //         this.removeAttribute('checked');
        //         method = 'DELETE';
        //     } else {
        //         method = 'POST';
        //         this.setAttribute('checked', 'checked');
        //     }

        //     let response = await fetch(

        //         `${window.origin}/api/user`, {
        //             method: method,
        //             headers: {
        //                 "Accept": "application/json",
        //                 "Content-Type": "application/json",
        //             },
        //             body: JSON.stringify({
        //                 id: clientId
        //             }),
        //         }
        //     );
        // let msg = await response.json();
        // console.log(msg.status);

        // })

    </script>


@endsection