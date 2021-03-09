/* Affiche l'input des prochaines disponibilités si la checkbox disponible n'est pas vrai */
let nextDispo = document.querySelector('.prochaine_disponibilite');
let checkNextDispo = document.querySelector('.disponible');
if (checkNextDispo.getAttribute('checked') === 'checked') {
    nextDispo.style.display = 'none'
    nextDispo.value = null;
}

// Permet de saisir si le champ visible est vrai ou faux
let inputVisibilite = document.querySelector('.visibilite');
inputVisibilite.addEventListener('change', async function () {

    let clientId = this.getAttribute('data-client-id');
    let method;
    let visible;
    if (this.getAttribute('checked') === 'checked') {
        this.removeAttribute('checked');
        method = 'DELETE';
        visible = false
    } else {
        method = 'PUT';
        this.setAttribute('checked', 'checked');
        visible = true;
    }

    await fetch(

        `${window.origin}/api/assistante-maternelle/fiche/${clientId}`, {
        method: method,
        headers: {
            "Accept": "application/json",
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            id: clientId,
            visible: visible
        }),
    }
    );

})

// Permet de saisir si le champ disponible est vrai ou faux
let inputDisponible = document.querySelector('.disponible');
inputDisponible.addEventListener('change', async function () {

    let nextDispo = document.querySelector('.prochaine_disponibilite');
    let clientId = this.getAttribute('data-client-id');
    let method;
    let disponible;
    if (this.getAttribute('checked') === 'checked') {
        this.removeAttribute('checked');
        method = 'DELETE';
        disponible = false;
        nextDispo.style.display = 'block'
    } else {
        method = 'PUT';
        this.setAttribute('checked', 'checked');
        disponible = true;
        nextDispo.style.display = 'none';
        nextDispo.value = null;
    }

    await fetch(

        `${window.origin}/api/assistante-maternelle/fiche/${clientId}`, {
        method: method,
        headers: {
            "Accept": "application/json",
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            id: clientId,
            disponible: disponible
        }),
    }
    );

})

let criteres = document.querySelectorAll('.critere');
criteres.forEach(critere => {
    critere.addEventListener('change', async function () {
        let clientId = document.querySelector('.user').value;
        let name = this.getAttribute('name');
        let method;
        let value;
        if (this.getAttribute('checked') === 'checked') {
            this.removeAttribute('checked');
            method = 'DELETE';
            value = false;
        } else {
            method = 'PUT';
            this.setAttribute('checked', 'checked');
            value = true;
        }

        await fetch(

            `${window.origin}/api/assistante-maternelle/critere/${clientId}`, {
            method: method,
            headers: {
                "Accept": "application/json",
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                id: clientId,
                critere: name,
                value: value
            }),
        }
        );
    })
})


// Permet de reprendre l'adresse personnel pour l'adresse pro
let sameAdress = document.querySelector('#same-adress');
const SELECTEUR = {
    'adresse_pro' : document.querySelector('#adresse_pro'),
    'code_postal_pro' : document.querySelector('#code_postal_pro'),
    'ville_pro' : document.querySelector('#ville_pro')
}
const ADRESSE_PRO = SELECTEUR.adresse_pro.value;
const CODE_POSTAL_PRO =  SELECTEUR.code_postal_pro.value;
const VILLE_PRO = SELECTEUR.ville_pro.value;
const ADRESSE = document.querySelector('#adresse-perso').innerHTML;
const CODE_POSTAL = document.querySelector('#code-postal-perso').innerHTML;
const VILLE = document.querySelector('#ville-perso').innerHTML;

function adresseComplete(adresse, cp, ville){
    SELECTEUR.adresse_pro.value = adresse;
    SELECTEUR.code_postal_pro.value = cp;
    SELECTEUR.ville_pro.value = ville;
}


sameAdress.addEventListener('change', function(){
    if(this.checked){
        adresseComplete(ADRESSE, CODE_POSTAL, VILLE)
    }else{
        adresseComplete(ADRESSE_PRO, CODE_POSTAL_PRO, VILLE_PRO)
    }
})
