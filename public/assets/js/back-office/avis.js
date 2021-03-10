let nounou_id = document.querySelector('#liste_avis').getAttribute('data-nounou-id');
const PAGINATION = document.querySelector('#pagination_avis');
const MESSAGES = document.querySelector('#messages_avis');
const NOTE_MAX = document.querySelectorAll('.note').length;
const FILTRE = document.querySelector('#filtre');
let url;


function creationMessage(message) {

  let p = document.createElement('p');
  p.classList.add('box__contenu--avis');
  let avisDate = document.createElement('span');
  avisDate.classList.add('box__contenu--avis-date')
  let avisNote = document.createElement('span');
  avisNote.classList.add('box__contenu--avis-note');
  let avisMessage = document.createElement('span');
  avisMessage.classList.add('box__contenu--avis-message');

  let identite = (message.nom === null || message.prenom === null) ? 'Anonyme' : `${message.nom} ${message.prenom}`;

  avisDate.innerHTML = `Le ${new Date(message.updated_at).toLocaleDateString('fr-FR')} par ${identite}`;
  avisNote.innerHTML = (message.note !== null) ? `Note : ${message.note}/${NOTE_MAX} ` : `aucune note`;
  avisMessage.innerHTML = `${message.avis}`;

  MESSAGES.appendChild(p);
  p.appendChild(avisDate);
  p.appendChild(avisNote);
  p.appendChild(avisMessage);

}

function loader() {
  document.querySelector('.spinner-border').parentNode.classList.toggle('visually-hidden');
  document.querySelector('#liste_avis').classList.toggle('visually-hidden');
}

function resetMessages() {
  let deleteMessages = Array.from(MESSAGES.children);
  deleteMessages.forEach(message => {
    message.remove();
  })
}

function resetPagination() {
  let deletePagination = Array.from(PAGINATION.children);
  deletePagination.forEach(message => {
    message.remove();
  })
}

function creationPagination(link) {
  let a = document.createElement('a');
  a.href = link.url;
  a.innerHTML = link.label;
  a.classList.add(`page_number${link.label}`);
  a.style.padding = '10px';
  PAGINATION.appendChild(a);
  if (link.active) {
    a.classList.add('is-current');
  }


  //Evenemement sur le click d'une page
  a.addEventListener('click', async function (e) {
    e.preventDefault();

    loader();

    document.querySelector('.is-current').classList.remove('is-current');
    if (FILTRE.value !== 'aucun') {
      url = `${window.origin}/api/avis/${nounou_id}/filtre=${FILTRE.value}?page=${link.label}`;
    } else {
      url = `${window.origin}/api/avis/${nounou_id}?page=${link.label}`;
    }
    fetch(url).then((element) => {
      loader();
      resetMessages();
      element.json().then((response) => {

        document.querySelector(`.page_number${response.avis.current_page}`).classList.add('is-current');

        response.avis.data.forEach(message => {
          creationMessage(message)
        })
      })
    })
  })
}

// Evenement sur le filtre
FILTRE.addEventListener('change', function (e) {
  e.preventDefault();

  loader();
  resetMessages();
  resetPagination();

  let value = encodeURI(this.value);
  fetch(`${window.origin}/api/avis/${nounou_id}/filtre=${value}`).then((response) => {

    if (response.ok) {

      loader();

      // Récupère la promesse
      response.json().then((element) => {
        if (element.avis.data.length !== 0) {
          // Boucle pour les messages
          element.avis.data.forEach(message => {
            creationMessage(message)
          })

          // Boucle pour les liens de pagination
          element.avis.links.forEach(link => {

            if (link.label !== 'Suivant &raquo;' && link.label !== '&laquo; Précédent') {
              creationPagination(link);
            }
          })
        } else {
          MESSAGES.innerHTML = 'Aucun avis';
        }
      })
    }
  })
})

window.addEventListener('load', function () {

  // Récupère l'ensemble des messages d'une assistante-maternelle
  fetch(`${window.origin}/api/avis/${nounou_id}`).then((response) => {

    if (response.ok) {

      loader();

      // Récupère la promesse
      response.json().then((element) => {

        if (element.avis.data.length !== 0) {
          // Boucle pour les messages
          element.avis.data.forEach(message => {
            creationMessage(message)
          })

          // Boucle pour les liens de pagination
          element.avis.links.forEach(link => {

            if (link.label !== 'Suivant &raquo;' && link.label !== '&laquo; Précédent') {
              creationPagination(link);
            }
          })
        } else {
          MESSAGES.innerHTML = 'Aucun avis';
        }
      })
    }

  })
})
