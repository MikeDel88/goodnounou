const nounouId = document.querySelector('#liste_avis').getAttribute('data-nounou-id');
    const pagination = document.querySelector('#pagination_avis');
    const messages = document.querySelector('#messages_avis');
    const noteMax = document.querySelectorAll('.note').length;
    const FILTRE = document.querySelector('#filtre');
    let url;


    function creationMessage(message){

        let p = document.createElement('p');
        p.classList.add('avis');
        let avisDate = document.createElement('span');
        avisDate.classList.add('avis_date')
        let avisNote = document.createElement('span');
        avisNote.classList.add('avis_note');
        let avisMessage = document.createElement('span');
        avisMessage.classList.add('avis_message');

        let identite = (message.nom === null || message.prenom === null) ? 'Anonyme' : `${message.nom} ${message.prenom}`;

        avisDate.innerHTML = `Le ${new Date(message.updated_at).toLocaleDateString('fr-FR')} par ${identite}`;
        avisNote.innerHTML = (message.note !== null) ? `Note : ${message.note}/${noteMax} ` : `aucune note`;
        avisMessage.innerHTML = `${message.avis}`;

        messages.appendChild(p);
        p.appendChild(avisDate);
        p.appendChild(avisNote);
        p.appendChild(avisMessage);

    }
    function loader(){
        document.querySelector('.spinner-border').parentNode.classList.toggle('visually-hidden');
        document.querySelector('#liste_avis').classList.toggle('visually-hidden');
    }
    function resetMessages(){
        let deleteMessages = Array.from(messages.children);
        deleteMessages.forEach(message => {
            message.remove();
        })
    }
    function resetPagination(){
        let deletePagination = Array.from(pagination.children);
        deletePagination.forEach(message => {
            message.remove();
        })
    }
    function creationPagination(link){
        let a = document.createElement('a');
        a.href = link.url;
        a.innerHTML = link.label;
        a.classList.add(`page_number${link.label}`);
        a.style.padding = '10px';
        pagination.appendChild(a);
        if(link.active){
            a.classList.add('page_current');
        }


        //Evenemement sur le click d'une page
        a.addEventListener('click', async function(e){
            e.preventDefault();

            loader();

            document.querySelector('.page_current').classList.remove('page_current');
            if(FILTRE.value !== 'aucun'){
                url = `${window.origin}/api/avis/${nounouId}/filtre=${FILTRE.value}?page=${link.label}`;
            }else{
                url = `${window.origin}/api/avis/${nounouId}?page=${link.label}`;
            }
            fetch(url).then((element) => {
                loader();
                resetMessages();
                element.json().then((response) => {

                    document.querySelector(`.page_number${response.avis.current_page}`).classList.add('page_current');

                    response.avis.data.forEach(message => {
                        creationMessage(message)
                    })
                })
            })
        })
    }

    // Evenement sur le filtre
    FILTRE.addEventListener('change', function(e){
        e.preventDefault();

        loader();
        resetMessages();
        resetPagination();

        let value = encodeURI(this.value);
        fetch(`${window.origin}/api/avis/${nounouId}/filtre=${value}`).then((response) => {

            if(response.ok){

                loader();

                // Récupère la promesse
                response.json().then((element) => {
                    if(element.avis.data.length !== 0){
                        // Boucle pour les messages
                        element.avis.data.forEach(message => {
                            creationMessage(message)
                        })

                        // Boucle pour les liens de pagination
                        element.avis.links.forEach(link => {

                            if(link.label !== 'Suivant &raquo;' && link.label !== '&laquo; Précédent'){
                                creationPagination(link);
                            }
                        })
                    }else{
                        messages.innerHTML = 'Aucun avis';
                    }
                })
            }
        })
    })

    window.addEventListener('load', function(){

        // Récupère l'ensemble des messages d'une assistante-maternelle
        fetch(`${window.origin}/api/avis/${nounouId}`).then((response) => {

            if(response.ok){

                loader();

                // Récupère la promesse
                response.json().then((element) => {

                    if(element.avis.data.length !== 0){
                        // Boucle pour les messages
                        element.avis.data.forEach(message => {
                            creationMessage(message)
                        })

                        // Boucle pour les liens de pagination
                        element.avis.links.forEach(link => {

                            if(link.label !== 'Suivant &raquo;' && link.label !== '&laquo; Précédent'){
                                creationPagination(link);
                            }
                        })
                    }else{
                        messages.innerHTML = 'Aucun avis';
                    }
                })
            }

        })
    })
