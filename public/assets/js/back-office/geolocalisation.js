window.onload = () => {

    class Map {
        mymap;
        marqueur;
        posLat = 0;
        posLng = 0;
        lat;
        lng;
        cercle;
        json;
        tableauMarqueurs = [];
        marqueurs;
        view;

        constructor(posLat, posLng, view) {
          this.posLat = posLat; // Latitude
          this.posLng = posLng; // Longitude
          this.view = view; // Hauteur de la view
          this.initMap(); // Initiation de la carte
          this.mymap.on('click', (e) => this.mapClickListen(e)); // Evenement sur le clique de la carte
          this.marqueurs = L.markerClusterGroup(); // Marqueurs de groupe
            document.querySelector('.js-search-submit').addEventListener('click', (e) => this.getSearch(
            e)); // Evenement sur la recherche
        }

        // Initialisation de la carte
        initMap() {
            this.mymap = L.map("detailsMap").setView([this.posLat, this.posLng], this.view);
            this.layer = L.tileLayer("https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png", {
                attribution: "&copy; les contributeurs & contributrices OpenStreetMap sous licence libre ODBl. Fond de carte par OpenStreetMap France sous licence libre CC BY-SA",
                minZoom: 1,
                maxZoom: 20
            }).addTo(this.mymap)
        }

        // Evenement sur le clique de la carte pour récupérer l'adresse
        async mapClickListen(e) {

            // Récupère les coordonnées du clic
            let pos = e.latlng

            this.lat = pos.lat
            this.lng = pos.lng

            // Je charge une ville en fonction des coordonnées
            let ville = await this.getAdresse(this.lat, this.lng)
            document.querySelector("#search").value = ville.display_name
            // Affiche le marqueur
            this.addMarker(pos);
            this.marqueur.bindPopup(`${ville.address.postcode}, ${ville.address.village}`)

        }

        // Ajout d'un marqueur sur la carte avec drag and drop
        async addMarker(pos) {

            // Reset du marqueur
            if (this.marqueur != undefined) {
                this.mymap.removeLayer(this.marqueur)
            }

            this.marqueur = L.marker(pos, {
                //On rend le marqueur déplaçable
                draggable: true
            })

            // ecoute du glisser déposer du marqueur
            this.marqueur.on("dragend", async function (e) {
                pos = e.target.getLatLng()
                this.lat = pos.lat
                this.lng = pos.lng
                let ville = await this.getAdresse(this.lat, this.lng)
                this.marqueur.bindPopup(`${ville.adresse.postcode}, ${ville.adresse.village}`)
            })
            this.marqueur.addTo(this.mymap)
        }

        // Récupère l'adresse sur l'API
        async getAdresse(lat, lng) {
            let response = await fetch(
                `https://nominatim.openstreetmap.org/reverse.php?lat=${lat}&lon=${lng}&zoom=18&format=jsonv2`
            );
            let json = await response.json();
            return json
        }

        // Execution de la recherche sur l'API
        async getSearch(e) {

            e.preventDefault();

            // Reset des marqueurs
            if (this.cercle != undefined) {
                this.mymap.removeLayer(this.cercle);

            }
            if (this.marqueur != undefined) {
                this.mymap.removeLayer(this.marqueur);
                this.tableauMarqueurs.forEach(marqueur => {
                    this.mymap.removeLayer(marqueur)
                })
                this.marqueurs.clearLayers()
            }

            // Récupération des données dans le DOM pour la recherche et la distance et les critères
            let recherche = encodeURI(document.querySelector("#search").value);
            let distance = document.querySelector('.js-distance').value;
            let criteres = document.querySelectorAll('.js-criteres');
            let criteresSelectionnes = [];

            criteres.forEach(critere => {
                if (critere.checked === true) {
                    criteresSelectionnes.push(critere.id);
                }
            })

            let rayon = distance * 1000

            // Recherche les coordonnées GPS sur l'API en fonction de l'adresse donnée
            let response = await fetch(
                `https://nominatim.openstreetmap.org/search?q=${recherche}&format=json&polygon_svg=1`
            )
            this.json = await response.json();

            this.lat = this.json[0].lat
            this.lng = this.json[0].lon
            let pos = [this.lat, this.lng]

            // Dessin d'un cercle autour de la recherche sur un rayon défini par l'utilisateur
            this.cercle = L.circle([this.lat, this.lng], {
                color: 'blue',
                fillColor: 'blue',
                fillOpacity: 0.2,
                radius: rayon
            }).addTo(this.mymap)
            this.mymap.setView(pos, 10)

            // Envoi de la requête en POST pour rechercher dans la base de données les assistantes maternelles dans le rayon
            let searchClients = await fetch(
                `${window.origin}/api/recherche`, {
                method: 'POST',
                headers: {
                    "Accept": "application/json",
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    lat: this.lat,
                    lng: this.lng,
                    criteres: criteresSelectionnes,
                    distance: distance
                }),
            });
          let clients = await searchClients.json()

            // Création du marquage sur la carte avec les résultats obtenus
            if (clients.result !== false) {
                clients.result.forEach(client => {
                    pos = [client.lat, client.lng]
                    this.marqueur = L.marker(pos)
                    this.marqueur.bindPopup(
                        `<a href="fiche/assistante-maternelle/${client.id}" target="_blank" rel="noopener noreferrer">${client.nom} ${client.prenom} <i class="fas fa-external-link-alt"></i></a>`
                    )
                    this.marqueurs.addLayer(this.marqueur)
                    this.tableauMarqueurs.push(this.marqueur)
                })

                let groupMarqueur = new L.featureGroup(this.tableauMarqueurs);
                this.mymap.fitBounds(groupMarqueur.getBounds().pad(0.5));
                this.mymap.addLayer(this.marqueurs)
            }
        }
    }



    // Demande de geolocalisation par le navigateur et création des objets
    if ("geolocation" in navigator) {
        navigator.geolocation.getCurrentPosition(function (position) {
            posLat = position.coords.latitude;
            posLng = position.coords.longitude;
            let map = new Map(posLat, posLng, 15);
            let myPos = {
                lat: posLat,
                lng: posLng
            };
            map.addMarker(myPos);
        }, function () {
            new Map(46.14939437647686, 2.1972656250000004, 6);
        })
    } else {
        new Map(46.14939437647686, 2.1972656250000004, 6)
    }

    // Inscription dans le DOM du nombre des kilomètres
    let range = document.querySelector('.js-distance');
    range.addEventListener('change', function () {
        let distance = document.querySelector('.js-distance-label');
        distance.innerHTML = `${this.value} km`
    })

}
