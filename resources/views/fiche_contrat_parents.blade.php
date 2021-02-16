@extends('layouts.back')
@section('content')
    <article class="box box-lg">
        <header>
            <h4>
                Fiche du contrat n°{{ $contrat->id }} - Début le
                {{ Carbon\Carbon::parse($contrat->date_debut)->translatedFormat('j F Y') }}
            </h4>
            <div>
                <a href="{{ route('contrats') }}" class="px-3"><i class="fas fa-arrow-left"></i></a>
            </div>
        </header>
        <div class="contenu d-flex justify-content-start flex-wrap">
            <div class="p-2">
                <ul>
                    <li>Identité assistante maternelle :
                        {{ "{$contrat->assistanteMaternelle->categorie->nom} {$contrat->assistanteMaternelle->categorie->prenom}" }}
                    </li>
                    <li>Enfant : {{ $contrat->enfant->prenom }}</li>
                    <li>Nombre d'heures prévu par semaine: {{ $contrat->nombre_heures }}h</li>
                    <li>Nombre de semaines prévu (hors 5 semaines de congés de l'assistante maternelle) sur l'année :
                        {{ $contrat->nombre_semaines }}
                    </li>
                    <li>Nombre d'heures lissés par mois : {{ $nombre_heures_lisse }}h</li>
                    <li>Fériés : @if ($contrat->assistanteMaternelle->criteres->ferie === 0)
                        non travaillé @else possible @endif
                    </li>
                </ul>
            </div>
            <div class="p-2">
                <ul>
                    <li>Taux horaires net : {{ $contrat->taux_horaire }}€</li>
                    <li>Taux d'entretien par jour : {{ $contrat->taux_entretien }}€</li>
                    <li>Frais repas par jour (si pris en charge):
                        {{ "$contrat->frais_repas € " ?? 'non pris en charge' }}
                    </li>
                    <li>Salaire mensuel moyen à verser (hors heures supplémentaires) : {{ $salaire_mensuel }}€</li>
                </ul>
            </div>
        </div>
    </article>
    <article class="box box-lg">
        <header>
            <h4>
                Ajouter un horaire de garde
            </h4>
            <div class="nombre_heures">

            </div>
        </header>
        <div class="contenu">
            <form action="#" method="POST" class="container">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="garde">Garde du</span>
                    <input type="date" name="jour" class="form-control" aria-label="date garde" aria-describedby="garde"
                        required>
                </div>
                <div class="row mb-3">
                    <div class="input-group">
                        <span class="input-group-text">Déposer</span>
                        <input type="time" aria-label="déposer à" name="heure_debut" class="form-control heure_debut"
                            step="300" required>
                        <input type="text" aria-label="déposer par" name="deposer_par" class="form-control"
                            placeholder="nom du déposant">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="input-group">
                        <span class="input-group-text">Récupérer</span>
                        <input type="time" aria-label="récupérer à" name="heure_fin" class="form-control heure_fin"
                            step="300" required>
                        <input type="text" aria-label="récupérer par" name="recuperer_par" class="form-control"
                            placeholder="nom du récupérant">
                    </div>
                </div>
                <div class="form-floating">
                    <textarea class="form-control" placeholder="Laissez un commentaire ici" id="commentaires"
                        name="description"></textarea>
                    <label for="commentaires">Commentaires</label>
                </div>
                <div class="row d-flex d-flex justify-content-end my-3">
                    <button class="col-md-2" type="submit">Saisir</button>
                </div>
            </form>
        </div>
    </article>
    <article class="box box-lg">
        <header>
            <h4>
                Voir un mois dans le détail
            </h4>
        </header>
        <div class="contenu">
            <form action="#" class="row">
                <div class="col-4">
                    <select class="form-select" aria-label="Mois" required>
                        <option value="#" selected>Selectionnez un mois</option>
                        @foreach ($mois as $numero => $libelle)
                            <option value="{{ $numero }}">{{ $libelle }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-4">
                    <select class="form-select" aria-label="Année" required>
                        <option value="{{ date('Y') - 1 }}">{{ date('Y') - 1 }}</option>
                        <option value="{{ date('Y') }}" selected>{{ date('Y') }}</option>
                        <option value="{{ date('Y') + 1 }}">{{ date('Y') + 1 }}</option>
                    </select>
                </div>
                <div class="col-2">
                    <button type="submit" class="p-1">Rechercher</button>
                </div>
            </form>
        </div>
    </article>
    <script>
        let heure_depart = document.querySelector('.heure_debut')

        let heure_fin = document.querySelector('.heure_fin')
        let debut;
        let fin;
        let nombre_heures = debut - fin;

        heure_depart.addEventListener('change', function() {
            console.log(typeof(this.value))
            console.log(this.value)
        })

        heure_fin.addEventListener('change', function() {
            console.log(typeof(this.value))
            console.log(this.value)

        })

    </script>
@endsection
