let notes = document.querySelectorAll('.note');
const PARENT_ID = document.querySelector('#favoris').getAttribute('data-parent-id');
const NOUNOU_ID = document.querySelector('#favoris').getAttribute('data-nounou-id');

function ajoutEtoilePleine(infoNote) {
  for (i = infoNote; i > 0; i--) {
    let note = document.querySelector(`#note${i}`);
    note.classList.add('fas');
    note.style.color = 'rgb(255, 204, 0)';
  }
}

function enleveEtoilePleine(note) {
  if (!note.classList.contains('note-check')) {
    note.classList.remove('fas');
  }
}

function inscriptionNote(noteSaisie) {
  for (i = Number(noteSaisie); i > 0; i--) {
    let note = document.querySelector(`#note${i}`);
    note.classList.add('fas');
    note.classList.add('note-check');
  }
  for (j = Number(noteSaisie) + 1; j <= notes.length; j++) {
    let note = document.querySelector(`#note${j}`);
    note.classList.remove('fas');
    note.classList.remove('note-check');
  }
}

async function envoiNote(noteSaisie) {
  await fetch(
    `${window.origin}/api/recommandation/note`, {
      method: 'POST',
      headers: {
        "Accept": "application/json",
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        nounou: NOUNOU_ID,
        parent: PARENT_ID,
        note: noteSaisie
      }),
    }
  );
}

notes.forEach(star => {

  star.addEventListener('mouseover', function () {
    let infoNote = this.getAttribute('data-note');
    ajoutEtoilePleine(infoNote);
  })

  star.addEventListener('mouseout', function () {
    let notes = document.querySelectorAll('footer .fas');
    notes.forEach(note => {
      enleveEtoilePleine(note);
    })
  })

  // Lors d'un click, on enregistre la note inscrite par l'utilisateur
  star.addEventListener('click', async function () {
    let noteSaisie = this.getAttribute('data-note');
    inscriptionNote(noteSaisie);
    envoiNote(noteSaisie);
  })
});
