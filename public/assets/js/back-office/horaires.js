window.onload = () => {

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
            return parseInt(horaire) * 60;
        }

        cumulHeuresMinutes(heures, minutes) {
            return parseInt(heures) + parseInt(minutes);
        }

        differenceMinutes(debut, fin) {
            let difference = parseInt(fin) - parseInt(debut);

            if (difference >= 0) {
                return difference;
            } else {
                return 0;
            }
        }

        calculNombreHeuresParJour() {

            // Met les minutes en nombre d'heure
            let nombreHeures = parseInt(this.minutes / 60);

            // Inscrit le nombre de minutes restante
            let nombreMinuteRestante = parseInt(this.minutes % 60);

            let minutes;

            if (nombreMinuteRestante < 10) {
                minutes = `0${nombreMinuteRestante}`;
            } else {
                minutes = `${nombreMinuteRestante}`;
            }

            if (nombreHeures > 0) {
                return `${Math.floor(nombreHeures)}h${minutes}`;
            } else {
                return `${minutes}min`
            }

        }
    }

    // Les champs input
    let heure_depart = document.querySelector('.heure_debut')
    let heure_fin = document.querySelector('.heure_fin')
    let inputMois = document.querySelector('#mois')
    let inputAnnees = document.querySelector('#annee')
    const contrat = document.querySelector('#contrat').value; // Stock le numéro du contrat associé aux horaires
    let mois;
    let annee;

    // Permet de faire le calcul du nombre d'heure de garde à ajouter et de l'afficher dans le DOM
    function affichageHeuresGarde(debut, fin) {
        let horaire = new Horaires(debut, fin)
        let heuresGarde = horaire.calculNombreHeuresParJour();
        document.querySelector('.nombre_heures').innerHTML = heuresGarde;
        document.querySelector('#heures_garde').value = heuresGarde;
    }

    // Faire un reset de la div DetailMois dans le DOM
    function resetDetailMois() {
        if (document.querySelector('#detail_mois')) {
            document.querySelector('#detail_mois').remove();
        }
    }

    // Récupère la liste des horaires pour un contrat en fonction du mois et de l'année
    async function getHoraires(contrat, mois, annee) {
        let response = await fetch(
            `${window.origin}/api/horaires/${contrat}/${mois}/${annee}`
        );
        let horaires = await response.json();
        return horaires.horaire;
    }

    // Calcul le cumul des horaires du mois, doit servir avec la reponse en JSON
    async function cumulHeuresMois(horaires) {

        // Traitement de chaque objet horaire
        let heures = []; // Tableau contenant chaque horaire sous forme de minutes
        horaires.forEach(horaire => {
            heures.push(new Horaires(horaire.heure_debut, horaire.heure_fin).minutes)
        })

        // Addition du nombre d'heure dans le tableau
        let nombresHeures = 0;
        heures.forEach(heure => {
            nombresHeures = nombresHeures + parseInt(heure)
        });

        // Traitement du nombre d'heures pour le ramener en format heures:minutes
        let nbHour = parseInt(nombresHeures / 60);
        let nbMinutes = parseInt(nombresHeures) % 60;

        // Stockage du résultat pour l'afficher
        if (nbHour > 0) {
            if (nbMinutes < 10) {
                return `${Math.floor(nbHour)}h0${nbMinutes}`;
            }
            return `${Math.floor(nbHour)}h${nbMinutes}`;
        } else {
            return `${nbMinutes}min`
        }

    }

    // Si l'utilisateur change un horaire de départ
    heure_depart.addEventListener('change', function () {
        let heureFin = document.querySelector('.heure_fin').value;
        if (heureFin !== '') {
            let value = this.value;
            affichageHeuresGarde(value, heureFin);
        }
    })

    // Si l'utilisateur change un horaire de fin
    heure_fin.addEventListener('change', function () {
        let heureDebut = document.querySelector('.heure_debut').value;
        if (heureDebut !== '') {
            let value = this.value;
            affichageHeuresGarde(heureDebut, value);
        }
    })

    // traitement asynchorne pour récupérer les horaires dans un mois selectionné
    inputMois.addEventListener('change', async function () {
        mois = this.value;
        annee = document.querySelector('#annee').value;
        let horaires = await getHoraires(contrat, mois, annee);
        resetDetailMois();
        if (horaires.length > 0) {
            await creationDetailMois(horaires);
        }

    })

    // traitement asynchorne pour récupérer les horaires dans une année selectionné
    inputAnnees.addEventListener('change', async function () {

        annee = this.value;
        mois = document.querySelector('#mois').value;
        let horaires = await getHoraires(contrat, mois, annee);
        resetDetailMois();
        if (horaires.length > 0) {
            await creationDetailMois(horaires);
        }

    })

    // Création dans le DOM du detail du mois + application evenement si on supprime un horaire
    async function creationDetailMois(horaires) {

        let container = document.querySelector('.selection_mois')
        let listeHoraire; // Liste Li des horaires
        let span; // Les horaires
        let iconDelete; // L'icône pour supprimer l'horaire

        // Création des balises pour le DOM
        let div = document.createElement('div')
        let listeHoraires = document.createElement('ul');
        let totalHeures = document.createElement('div');
        let linkPDF = document.createElement('a');

        // Récupère le nombre d'heures dans le mois
        let nombreHeuresMois = await cumulHeuresMois(horaires);


        div.id = 'detail_mois';
        listeHoraires.classList.add('list-group', 'my-3');

        container.appendChild(div);
        div.appendChild(listeHoraires);

        // Pour chaque horaires récupérer on fait un traitement pour l'afficher dans le DOM
        horaires.forEach(horaire => {

            listeHoraire = document.createElement('li');
            span = document.createElement('span');
            iconDelete = document.createElement('i');

            listeHoraire.classList.add('list-group-item', 'd-flex', 'justify-content-between',
                'align-items-center');

            span.innerHTML =
                `Le ${new Date(horaire.jour_garde).toLocaleDateString('fr-FR')} de ${horaire.heure_debut.substring(0, 5)} à ${horaire.heure_fin.substring(0, 5)} (${horaire.nombre_heures})`;
            span.setAttribute('data-horaire-id', `${horaire.id}`);
            span.setAttribute('data-horaire-jour', `${horaire.jour_garde}`);

            iconDelete.classList.add('fas', 'fa-calendar-times', 'text-danger', 'supprimer_horaire');
            iconDelete.setAttribute('title', "Supprimer l'horaire");
            iconDelete.style.cursor = 'pointer';

            listeHoraires.appendChild(listeHoraire);
            listeHoraire.appendChild(span);
            listeHoraire.appendChild(iconDelete);

        })

        // Affichage du total des heures pour le mois
        totalHeures.innerHTML = `Total d'heures de garde ${nombreHeuresMois} pour ${horaires.length} jours`;
        div.appendChild(totalHeures);
        linkPDF.setAttribute('href', `/pdf/horaires/${contrat}/${mois}/${annee}`);
        linkPDF.classList.add('d-block', 'text-end', 'my-3');
        linkPDF.innerHTML = '<i class="fas fa-file-pdf"></i> Télécharger les horaires';
        div.appendChild(linkPDF);

        // Evenement sur l'action de supprimer un horaire
        document.querySelectorAll('.supprimer_horaire').forEach(horaire => {
            horaire.addEventListener('click', async function () {

                let idHoraire = parseInt(this.previousElementSibling.getAttribute(
                    'data-horaire-id'));

                if (Number.isInteger(idHoraire)) {
                    let supprimer = await fetch(
                        `${window.origin}/api/horaire/supprimer`, {
                        method: 'DELETE',
                        headers: {
                            "Accept": "application/json",
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            contrat: contrat,
                            horaire: idHoraire,
                        }),
                    });
                    let response = await supprimer.json();

                    // Si la suppression s'est bien passé
                    if (response.status === 'ok') {

                        this.parentNode.remove(); // On supprime la ligne du DOM

                        // On chercher l'index ou se situe la date de l'horaire à retirer
                        let valeur = horaires.findIndex(element => element.jour_garde === this
                            .previousElementSibling.getAttribute('data-horaire-jour'));

                        // On le supprime du tableau
                        horaires.splice(valeur, 1);

                        // Si le tableau contient encore des horaires
                        if (horaires.length > 0) {

                            let nouvelleValeur = await cumulHeuresMois(horaires);

                            totalHeures.innerHTML = `Total d'heures de garde ${nouvelleValeur} pour ${horaires.length} jours`;

                        } else {
                            // Suppression du contenu DOM
                            resetDetailMois();
                        }
                    }
                }

            })
        })

    }
}
