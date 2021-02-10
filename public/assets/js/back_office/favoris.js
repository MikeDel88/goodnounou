document.addEventListener('DOMContentLoaded', function () {
    let favoris = document.querySelector('.fa-heart')
    let nounou = document.querySelector('input[name="favoris"]').getAttribute('data-nounou-id')
    let parent = document.querySelector('input[name="favoris"]').getAttribute('data-parent-id')
    let hearth;

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

        let response = await fetch(

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
        let msg = await response.json();
        console.log(msg.status)

    })
})

