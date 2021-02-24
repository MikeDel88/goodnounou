@extends('layouts.back')
@section('content')
    <article class="box box-lg">
        <header>
            <h4>Mon Agenda</h4>
        </header>

        {{-- Planning --}}
        <div id='calendar' data-planning="{{ Auth::user()->id }}" class="contenu position-relative"
            style="border-right:none"></div>

        {{-- Modal en cas de clique sur un evenement --}}
        <div id="modalEvent" class="modal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                    </div>
                </div>
            </div>
        </div>
    </article>


    <script>
        document.addEventListener('DOMContentLoaded', function() {

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
                eventClick: function(info) {

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
                            `Déposé par  ${horaire.depose_par}`;
                        let recupere = (horaire.recupere_par === null) ?
                            'non renseigné' :
                            `Récupéré par ${horaire.recupere_par}`;

                        let calendarEventStart = {
                            title: `${nomEnfant} (arrivé)`,
                            backgroundColor: "#47A0AD",
                            borderColor: "#47A0AD",
                            start: `${horaire.jour_garde} ${horaire.heure_debut}`,
                            extendedProps: {
                                parent: `${depose}`,
                                nbrHeures: horaire.nombre_heures
                            },
                        }
                        let calendarEventEnd = {
                            title: `${nomEnfant} (départ)`,
                            backgroundColor: "#234F80",
                            borderColor: "#234F80",
                            start: `${horaire.jour_garde} ${horaire.heure_fin}`,
                            extendedProps: {
                                parent: `${recupere}`,
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

    </script>
@endsection
