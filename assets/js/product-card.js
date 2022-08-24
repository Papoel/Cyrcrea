// Attendre que le DOM soit chargé avant d'exécuter le script
window.onload = () => {
    // Faire apparaitre icône et bouton ajout panier au survol
    const cards = document.querySelectorAll('.product-box');
    [...cards].forEach((card) => {
        card.addEventListener('mouseover', function () {
            card.classList.add('is-hover');
        });
        card.addEventListener('mouseleave', function () {
            card.classList.remove('is-hover');
        });
    });

    // Gestion du système de notification de produit

    // 1. Je récupère toutes les étoiles et la note attribuée
    const stars = document.querySelectorAll('.bi-star');
    let note = document.querySelector('#note').getAttribute('data-id');

    // 2. Boucle sur les étoiles pour les colorer
    let star;

    for (star of stars) {
        star.addEventListener('mouseover', function () {
            console.log(note);

            resetStars();
            this.style.color = 'crimson';

            // L'élément qui précède mon étoile
            let previousStar = this.previousElementSibling;

            while(previousStar) {
                // On passe l'étoile précédente en couleur
                previousStar.style.color = 'crimson';

                // On récupère l'étoile qui la précède
                previousStar = previousStar.previousElementSibling
            }
        });

        // 4. Ecouter le click
        star.addEventListener('click', function () {
            note.value = this.dataset.value
            console.log();
        });
    }

    // 3. Boucler sur les étoiles pour retirer la couleur
    function resetStars() {
        let star;
        for (star of stars){
            star.style.color = 'inherit'
        }
    }
}

// TODO: Faire le système de notation de produit
