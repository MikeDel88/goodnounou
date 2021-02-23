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

        listeEnfants.addEventListener('change', async function() {
            let idEnfant = this.value;
            let response = await fetch(`${window.origin}/api/consulter/${idParent}/${idEnfant}`);
            let listeMessages = await response.json();
            await creationMessage(listeMessages.messages)
        })

        async function creationMessage(listeMessages) {

            let divMessage = document.createElement('div');
            let ul = document.createElement('ul');
            contenu.appendChild(divMessage);
            divMessage.appendChild(ul)

            listeMessages.forEach(message => {

                let li = document.createElement('li');
                let spanAuteur = document.createElement('span');
                let spanMessage = document.createElement('span');
                let spanDate = document.createElement('span');

                spanMessage.classList.add('message');
                spanDate.classList.add('date');

                spanAuteur.innerHTML = `De : ${message.assistante_maternelle}`;
                spanMessage.innerHTML = message.contenu;
                spanDate.innerHTML = new Date(message.date).toLocaleDateString('fr-FR');

                ul.appendChild(li)
                li.appendChild(spanAuteur);
                li.appendChild(spanMessage);
                li.appendChild(spanDate);
            });
        }

    </script>
@endsection
