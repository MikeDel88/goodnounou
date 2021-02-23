@extends('layouts.back')
@section('content')
    <article class="box box-lg">
        <header>
            <h4>Créer un message</h4>
        </header>
        <div class="contenu">
            <form method="POST" action="message/ajouter">
                @csrf
                <div class="row d-flex flex-wrap">
                    <div class="col-md-6 my-2">
                        <select name="enfant" id="enfant" class="form-select" aria-label="enfants" required>
                            <option value="#" disabled selected>Selectionnez un enfant</option>
                            @foreach ($contrats as $contrat)
                                <option value="{{ $contrat->enfant_id }}">{{ ucFirst($contrat->enfant->prenom) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 my-2">
                        <input type="date" class="form-control" id="jour_garde"
                            value="{{ old('jour_garde') ?? date('Y-m-d') }}" max="{{ date('Y-m-d') }}" name="jour_garde"
                            required>
                    </div>
                </div>
                <div class="row p-2 form-floating">
                    <textarea class="form-control" placeholder="Message concernant l'enfant" id="floatingTextarea"
                        name="message" style="height: 200px"></textarea>
                    <label for="floatingTextarea">Message concernant l'enfant pour les parents</label>
                </div>
                <div class="row mx-2">
                    <button type="submit" class="col-md-2">Enregistrer</button>
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
    <article class="box box-lg">
        <header>
            <h4>Voir les messages</h4>
        </header>
        <div id="messages" class="contenu">
            <input type="hidden" id="assistante_maternelle_id" value="{{ Auth::user()->categorie->id }}">
            <select id="messages_enfant" class="form-select" aria-label="enfants" required>
                <option value="#" disabled selected>Selectionnez un enfant</option>
                @foreach ($contrats as $contrat)
                    <option value="{{ $contrat->enfant_id }}">
                        {{ ucFirst($contrat->enfant->prenom) }}
                    </option>
                @endforeach
            </select>
    </article>
    <form action="/message/modifier" method="POST" class="modal fade" id="modificationMessage" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        @csrf
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalNomEnfant"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row m-3 p-2 form-floating">
                    <input type="hidden" name="enfant" id="idEnfant">
                    <input type="hidden" name="id_message" id="idMessage">
                    <textarea class="form-control" placeholder="Message concernant l'enfant" id="contenu" name="contenu"
                        style="height: 200px"></textarea>
                    <label for="contenu">Message concernant l'enfant pour les parents</label>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </div>
        </div>
    </form>
    <script>
        const listeEnfants = document.querySelector('#messages_enfant');
        const assMatId = document.querySelector('#assistante_maternelle_id').value;

        // Evenements pour rechercher les messages liés à un enfant
        listeEnfants.addEventListener('change', async function() {
            reset();
            let enfantId = this.value;
            let response = await fetch(
                `${window.origin}/api/messages/${assMatId}/${enfantId}`
            );
            let listeMessages = await response.json();
            await messages(listeMessages.messages)

            let update = document.querySelectorAll('.edit')
            if (update) {
                update.forEach(message => {
                    message.addEventListener('click', editMessage)
                })
            }

        })

        // Reset des messages existants lors d'une nouvelle recherche
        function reset() {
            if (document.querySelector('#messages ul')) {
                document.querySelector('#messages ul').remove();
            }
        }

        // Fenêtre modal qui sert à modifier un message existant pour une assistante-maternelle
        function editMessage() {

            let indexEnfant = document.querySelector('#messages_enfant').selectedIndex;
            let idEnfant = document.querySelector('#messages_enfant').value;
            let nomEnfant = document.querySelector('#messages_enfant').options[indexEnfant].innerHTML;
            let messageActuel = this.previousElementSibling.previousElementSibling.innerHTML;
            let idMessage = this.getAttribute('data-id-message');

            document.querySelector('#modalNomEnfant').innerHTML = nomEnfant;
            document.querySelector('#contenu').value = messageActuel;
            document.querySelector('#idEnfant').value = idEnfant;
            document.querySelector('#idMessage').value = idMessage;

        }

        // Création des élements du DOM pour afficher les messages d'un enfant
        async function messages(listeMessages) {

            let divMessages = document.querySelector('#messages');
            let ul = document.createElement('ul');
            divMessages.appendChild(ul);

            listeMessages.forEach(message => {

                let li = document.createElement('li');
                let spanEnfant = document.createElement('span');
                let spanDate = document.createElement('span');
                let linkModif = document.createElement('a');

                ul.appendChild(li);
                li.appendChild(spanEnfant);
                li.appendChild(spanDate);
                li.appendChild(linkModif);

                spanEnfant.classList.add('message');
                spanDate.classList.add('date');
                linkModif.classList.add('edit');
                linkModif.href = "#modificationMessage"

                spanEnfant.innerHTML = message.contenu;
                spanDate.innerHTML = new Date(message.jour_garde).toLocaleDateString('fr-FR');
                linkModif.innerHTML = 'Modifier';

                linkModif.setAttribute('data-id-message', message.id)
                linkModif.setAttribute('data-bs-toggle', 'modal')
                linkModif.setAttribute('data-bs-target', '#modificationMessage')

            });

        }

    </script>
@endsection
