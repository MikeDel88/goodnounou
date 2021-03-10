const MESSAGES_ENFANT = document.querySelector('#js-messages-enfant');
const CONTENU = document.querySelector('.box__contenu');
const PARENT_ID = document.querySelector('#js-id-parent').value;

// Evenement si l'utilisateur change d'enfant pour voir ses messages
MESSAGES_ENFANT.addEventListener('change', async function () {
  let idEnfant = this.value;
  let response = await fetch(`${window.origin}/api/consulter/${PARENT_ID}/${idEnfant}`);
  let listeMessages = await response.json();
  reset();
  if (listeMessages.messages !== false) {
    await creationMessage(listeMessages.messages)
  }




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
  divMessage.id = "contenuMessages";
  divMessage.classList.add('box__contenu--liste-messages');
  let ul = document.createElement('ul');
  CONTENU.appendChild(divMessage);
  divMessage.appendChild(ul);

  listeMessages.forEach(message => {

    let li = document.createElement('li');
    let spanAuteur = document.createElement('span');
    let spanMessage = document.createElement('span');
    let spanDate = document.createElement('span');
    let linkDelete = document.createElement('span');

    li.setAttribute('data-id-message', message.id)
    li.classList.add('items')
    spanAuteur.classList.add('item-auteur');
    spanMessage.classList.add('item-message');
    spanDate.classList.add('item-date');
    linkDelete.classList.add('item-delete');
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
    linkDelete.addEventListener('click', async function () {
      let supprimer = await fetch(
        `${window.origin}/api/supprimer-message`, {
          method: 'DELETE',
          headers: {
            "Accept": "application/json",
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            idMessage: message.id,
          }),
        });
      let response = await supprimer.json();

      if (response.status === 'ok') {
        this.parentNode.remove();
      }
    })
  });
}
