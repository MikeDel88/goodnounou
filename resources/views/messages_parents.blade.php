@extends('layouts.back')
@section('content')
    <article class="box box-lg">
        <header>
            <h4>Les messages</h4>
        </header>
        <div id="messages" class="contenu">
            <input type="hidden" id="idParent" value="{{ Auth::user()->categorie->id }}">
            <select id="messages_enfant" class="form-select" aria-label="enfants" required>
                <option value="#" disabled selected>Selectionnez un enfant</option>
                @foreach ($enfants as $enfant)
                    <option value="{{ $enfant->id }}">
                        {{ ucFirst($enfant->prenom) }}
                    </option>
                @endforeach
            </select>
    </article>
    <script>
        const listeEnfants = document.querySelector('#messages_enfant');
        const contenu = document.querySelector('.contenu');
        const idParent = document.querySelector('#idParent').value;

        // Evenement si l'utilisateur change d'enfant pour voir ses messages
        listeEnfants.addEventListener('change', async function() {
            let idEnfant = this.value;
            let response = await fetch(`${window.origin}/api/consulter/${idParent}/${idEnfant}`);
            let listeMessages = await response.json();
            reset();
            await creationMessage(listeMessages.messages)

        })

        // Reset le DOM à chaque changement d'enfants
        function reset() {
            if (document.querySelector('#contenuMessages')) {
                document.querySelector('#contenuMessages').remove();
            }
        }

        // Création de la liste des messages d'un enfant dans le DOM
        async function creationMessage(listeMessages) {

            let divMessage = document.createElement('div');
            divMessage.id = "contenuMessages"
            let ul = document.createElement('ul');
            contenu.appendChild(divMessage);
            divMessage.appendChild(ul)

            listeMessages.forEach(message => {

                let li = document.createElement('li');
                let spanAuteur = document.createElement('span');
                let spanMessage = document.createElement('span');
                let spanDate = document.createElement('span');
                let linkDelete = document.createElement('span');

                li.setAttribute('data-id-message', message.id)
                spanMessage.classList.add('message');
                spanDate.classList.add('date');
                linkDelete.classList.add('delete');
                linkDelete.style.cursor = 'pointer';

                spanAuteur.innerHTML = `De : ${message.assistante_maternelle}`;
                spanMessage.innerHTML = message.contenu;
                spanDate.innerHTML = new Date(message.date).toLocaleDateString('fr-FR');
                linkDelete.innerHTML = '<i class="fas fa-trash"></i> Supprimer';


                ul.appendChild(li)
                li.appendChild(spanAuteur);
                li.appendChild(spanMessage);
                li.appendChild(spanDate);
                li.appendChild(linkDelete);

                // Evenement sur chaque lien de suppression
                linkDelete.addEventListener('click', async function() {
                    let supprimer = await fetch(
                        `${window.origin}/api/supprimer-message/${message.id}`)
                    let response = await supprimer.json();

                    if (response.status === 'ok') {
                        this.parentNode.remove();
                    }
                })
            });
        }

    </script>
@endsection
