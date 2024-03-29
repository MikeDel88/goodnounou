document.addEventListener('DOMContentLoaded', function () {

    const modalEvent = new bootstrap.Modal(document.getElementById('modalEvent')); // Créer un objet modal
    const id = document.querySelector('#calendar').getAttribute(
        'data-planning'); // Récupère l'id de l'utilisateur
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridWeek',
        nowIndicator: true,
        aspectRatio: 2,
        contentHeight: 600,
        expandRows: true,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
        },
        weekNumbers: true,
        locale: 'fr',
        themeSystem: 'bootstrap',
        slotLabelInterval: '00:30',
        eventClick: function (info) {

            modalEvent.show();

            let modalBody = document.querySelector('.modal-body');
            let modalTitle = document.querySelector('.modal-title');
            modalTitle.innerHTML =
                `${info.event.title} - Le ${new Date(info.event.start).toLocaleDateString('fr-Fr')}`
            modalBody.innerHTML =
                `${info.event.extendedProps.parent} - Durée ${info.event.extendedProps.nbrHeures}`
        }

    });

    calendar.render();
    calendar.updateSize()

    // Récupère l'ensemble des rendez-vous d'un utilisateur
    async function getAllEvents() {

        let response = await fetch(`${window.origin}/api/planning/${id}`);
        json = await response.json();
        json.events.forEach(event => {

            let nomEnfant = event.enfant;

            event.horaires.forEach(horaire => {

                let depose = (horaire.depose_par === null) ? 'non renseigné' :
                    `${horaire.depose_par}`;
                let recupere = (horaire.recupere_par === null) ?
                    'non renseigné' :
                    `${horaire.recupere_par}`;

                let calendarEventStart = {
                    title: `${nomEnfant} (arrivée)`,
                    backgroundColor: "#47A0AD",
                    borderColor: "#47A0AD",
                    start: `${horaire.jour_garde} ${horaire.heure_debut}`,
                    extendedProps: {
                        parent: `Déposé par : ${depose}`,
                        nbrHeures: horaire.nombre_heures
                    },
                }
                let calendarEventEnd = {
                    title: `${nomEnfant} (départ)`,
                    backgroundColor: "#234F80",
                    borderColor: "#234F80",
                    start: `${horaire.jour_garde} ${horaire.heure_fin}`,
                    extendedProps: {
                        parent: ` Récupéré par : ${recupere}`,
                        nbrHeures: horaire.nombre_heures
                    },
                }
                calendar.addEvent(calendarEventStart);
                calendar.addEvent(calendarEventEnd);

            })
        })
    }
    getAllEvents();
})
