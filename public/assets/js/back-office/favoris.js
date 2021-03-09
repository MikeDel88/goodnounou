document.addEventListener('DOMContentLoaded', function () {
    const favoris = document.querySelector('.fa-heart')
    const nounou = document.querySelector('input[name="favoris"]').getAttribute('data-nounou-id')
    const parent = document.querySelector('input[name="favoris"]').getAttribute('data-parent-id')
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

        await fetch(

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

    })
})

