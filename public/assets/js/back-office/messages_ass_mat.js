const listeEnfants = document.querySelector('#messages_enfant');
const assMatId = document.querySelector('#assistante_maternelle_id').value;

// Evenements pour rechercher les messages liés à un enfant
listeEnfants.addEventListener('change', async function () {
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
  ul.classList.add('box__contenu--liste-messages')
  divMessages.appendChild(ul);

  listeMessages.forEach(message => {

    let li = document.createElement('li');
    li.classList.add('items')
    let spanEnfant = document.createElement('span');
    let spanDate = document.createElement('span');
    let linkModif = document.createElement('a');

    ul.appendChild(li);
    li.appendChild(spanEnfant);
    li.appendChild(spanDate);
    li.appendChild(linkModif);

    spanEnfant.classList.add('item-message');
    spanDate.classList.add('item-date');
    linkModif.classList.add('item-edit');
    linkModif.href = "#modificationMessage"

    spanEnfant.innerHTML = message.contenu;
    spanDate.innerHTML = new Date(message.jour_garde).toLocaleDateString('fr-FR');
    linkModif.innerHTML = 'Modifier';

    linkModif.setAttribute('data-id-message', message.id)
    linkModif.setAttribute('data-bs-toggle', 'modal')
    linkModif.setAttribute('data-bs-target', '#modificationMessage')

  });

}
