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
    @if ($contrat->status_id === 2)
        <article class="box box-lg">
            <header>
                <h4>
                    Ajouter un horaire de garde
                </h4>
                <div class="nombre_heures mx-1">

                </div>
            </header>
            <div class="contenu">
                <form action="/horaires/ajouter" method="POST" class="container">
                    @csrf
                    <input type="hidden" id="contrat" name="contrat_id" value="{{ $contrat->id }}" required>
                    <input type="hidden" name="debut_contrat" value="{{ $contrat->date_debut }}" required>
                    <input type="hidden" id="heures_garde" name="nombre_heures" required>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="garde">Garde du</span>
                        <input type="date" name="jour_garde" class="form-control" aria-label="date garde"
                            aria-describedby="garde" min="{{ $contrat->date_debut }}" required>
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
    <script>
        // Classe qui permet transformer un horaire en minutes et connaître la différence d'heures entre le départ et la fin
        class Horaires {

            horaire_debut;
            horaire_fin;
            heures_debut;
            heures_fin;
            minutes_debut;
            minutes_fin;
            debut;
            fin;
            minutes;

            constructor(horaire_debut, horaire_fin) {

                // Stock l'horaire string sous forme de tableau [heures, minutes]
                this.horaire_debut = this.separator(horaire_debut);
                this.horaire_fin = this.separator(horaire_fin);

                // Transforme les heures en minutes
                this.heures_debut = this.transformHeuresMinutes(this.horaire_debut[0]);
                this.heures_fin = this.transformHeuresMinutes(this.horaire_fin[0]);

                // Les minutes
                this.minutes_debut = this.horaire_debut[1];
                this.minutes_fin = this.horaire_fin[1];

                // Fait le cumul des heures + des minutes
                this.debut = this.cumulHeuresMinutes(this.heures_debut, this.minutes_debut);
                this.fin = this.cumulHeuresMinutes(this.heures_fin, this.minutes_fin);

                // Faire la différence de minutes entre début et la fin des horaires
                this.minutes = this.differenceMinutes(this.debut, this.fin);

            }

            separator(str) {
                return str.split(':');
            }

            transformHeuresMinutes(horaire) {
                return horaire * 60;
            }

            cumulHeuresMinutes(heures, minutes) {
                return heures + minutes;
            }

            differenceMinutes(debut, fin) {
                let difference = fin - debut;

                if (difference >= 0) {
                    return fin - debut;
                } else {
                    return 0;
                }
            }

            calculNombreHeuresParJour() {

                // Met les minutes en nombre d'heure
                let nombreHeures = parseInt(this.minutes / 60);

                // Inscrit le nombre de minutes restante
                let nombreMinuteRestante = (this.minutes % 60);

                let minutes;

                if (nombreMinuteRestante < 10) {
                    minutes = `0${nombreMinuteRestante}`;
                } else {
                    minutes = `${nombreMinuteRestante}`;
                }

                if (nombreHeures > 0) {
                    return `${Math.floor(nombreHeures/100)}h${minutes}`;
                } else {
                    return `${minutes}min`
                }

            }
        }

        // Les champs input pour les horaires de garde
        let heure_depart = document.querySelector('.heure_debut')
        let heure_fin = document.querySelector('.heure_fin')


        // Si l'utilisateur change un horaire de départ
        heure_depart.addEventListener('change', function() {
            let heureFin = document.querySelector('.heure_fin').value;
            if (heureFin !== '') {
                let horaire = new Horaires(this.value, heureFin)
                let heuresGarde = horaire.calculNombreHeuresParJour();
                document.querySelector('.nombre_heures').innerHTML = heuresGarde;
                document.querySelector('#heures_garde').value = heuresGarde;
            }
        })

        // Si l'utilisateur change un horaire de fin
        heure_fin.addEventListener('change', function() {
            let heureDebut = document.querySelector('.heure_debut').value;
            if (heureDebut !== '') {
                let horaire = new Horaires(heureDebut, this.value)
                let heuresGarde = horaire.calculNombreHeuresParJour();
                document.querySelector('.nombre_heures').innerHTML = heuresGarde;
                document.querySelector('#heures_garde').value = heuresGarde;
            }
        })



        /**
         * 
         * Chercher tous les horaires d'un mois de façon asynchrone
         * 
         * */


        // Format json type de réponse du serveur
        let objet = [{
            'heure_debut': '9:00',
            'heure_fin': '10:30'
        }]

        let inputMois = document.querySelector('#mois')
        let inputAnnees = document.querySelector('#annee')
        // Stock le numéro du contrat associé aux horaires
        let contrat = document.querySelector('#contrat').value;

        inputMois.addEventListener('change', async function() {

            let mois = this.value
            let annee = document.querySelector('#annee').value;
            let horaires = await getHoraires(contrat, mois, annee);
            let nombreHeuresMois = await cumulHeuresMois(horaires);

        })

        inputAnnees.addEventListener('change', async function() {

            let annee = this.value
            let mois = document.querySelector('#mois').value;
            let horaires = await getHoraires(contrat, mois, annee);
            let nombreHeuresMois = await cumulHeuresMois(horaires);

        })

        // Récupère la liste des horaires pour un contrat en fonction du mois et de l'année
        async function getHoraires(contrat, mois, annee) {
            let response = await fetch(
                `${window.origin}/api/horaires/${contrat}/${mois}/${annee}`
            );
            let horaires = await response.json();
            return horaires;
        }

        // Calcul le cumul des horaires du mois, doit servir avec la reponse en JSON
        async function cumulHeuresMois(horaires) {

            let heures = [];
            horaires.forEach(horaire => {
                heures.push(new Horaires(horaire.heure_debut, horaire.heure_fin).minutes)
            })
            let nombresHeures = 0;
            heures.forEach(heure => {
                nombresHeures = nombresHeures + heure
            });
            let nrHour = parseInt(nombresHeures / 60);
            let nrMinutes = nombresHeures % 60;
            return `${Math.floor(nrHour/100)}h${nrMinutes}`;
        }

    </script>
@endsection
