const notes = document.querySelectorAll('.note');
const parent = document.querySelector('#favoris').getAttribute('data-parent-id');
const nounou = document.querySelector('#favoris').getAttribute('data-nounou-id');

function ajoutEtoilePleine(infoNote) {
    for (i = infoNote; i > 0; i--) {
        let note = document.querySelector(`#note${i}`);
        note.classList.add('fas');
        note.style.color = 'rgb(255, 204, 0)';
    }
}

function enleveEtoilePleine(note) {
    if (!note.classList.contains('noteCheck')) {
        note.classList.remove('fas');
    }
}

function inscriptionNote(noteSaisie) {
    for (i = Number(noteSaisie); i > 0; i--) {
        let note = document.querySelector(`#note${i}`);
        note.classList.add('fas');
        note.classList.add('noteCheck');
    }
    for (j = Number(noteSaisie) + 1; j <= notes.length; j++) {
        let note = document.querySelector(`#note${j}`);
        note.classList.remove('fas');
        note.classList.remove('noteCheck');
    }
}

async function envoiNote(noteSaisie) {
    let response = await fetch(
        `${window.origin}/api/recommandation/note`, {
        method: 'POST',
        headers: {
            "Accept": "application/json",
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            nounou: nounou,
            parent: parent,
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