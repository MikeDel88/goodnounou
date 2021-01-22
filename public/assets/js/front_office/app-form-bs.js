// Validation du formulaire côté client
let button = document.querySelector('button');
(function () {
    'use strict'

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    let forms = document.querySelectorAll('.needs-validation')
    // Loop over them and prevent submission
    Array.prototype.slice.call(forms)

        .forEach(function (form) {
            form.addEventListener('submit', function (event) {


                button.innerHTML = 'Loading...';
                button.setAttribute('disabled', '');

                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()

                    button.innerHTML = 'Valider';
                    button.removeAttribute('disabled', '')

                }
                form.classList.add('was-validated')

            }, false)
        })
})()

// Selection d'une catégorie lors de 'inscription
let selectionCategorie = document.querySelectorAll('#en-tete img');
selectionCategorie.forEach(image => {
    image.addEventListener('click', function () {
        selectionCategorie.forEach(selection => {
            selection.classList.remove('img-selected');
        })
        let id = image.getAttribute('data-id');
        document.querySelector('.categorie').setAttribute('value', id);
        image.classList.add('img-selected');
    })
});

