document.addEventListener('DOMContentLoaded', function () {
    const favoris = document.querySelector('.fa-heart')
    const nounou = document.querySelector('input[name="favoris"]').getAttribute('data-nounou-id')
    const parent = document.querySelector('input[name="favoris"]').getAttribute('data-parent-id')
  let hearth;

  var toastEl = document.querySelector('.toast');
  let toastBody = document.querySelector('.toast-body');
  let favorisInformations = new bootstrap.Toast(toastEl, {'delay': 2000});


    favoris.addEventListener('click', async function () {

        if (this.classList.contains('far')) {
            this.classList.remove('far');
            this.classList.add('fas');
            hearth = true
        } else if (this.classList.contains('fas')) {
            this.classList.remove('fas');
            this.classList.add('far');
            hearth = false
        }

        let response =await fetch(

            `${window.origin}/api/favoris`, {
            method: 'POST',
            headers: {
                "Accept": "application/json",
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                nounou: nounou,
                parent: parent,
                favoris: hearth
            }),
          }
        );
      let informations = await response.json();
      if (informations.status === 'ajoute') {
        toastBody.innerHTML = 'Favoris bien ajouté'
      } else {
        toastBody.innerHTML = 'Favoris bien supprimé'
      }
      favorisInformations.show();

    })
})

