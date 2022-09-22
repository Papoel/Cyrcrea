const cartIcon = document.querySelector('#cart-icon');
const cartHidden = document.querySelector('#cart-hidden');

// Cacher par défaut l'élément cartHidden
cartHidden.style.display = 'none';

// 1. Au survol de l'élément cartIcon, afficher l'élément cartHidden
// 2. l'élément s'affiche avec une jolie transition de la droite vers la gauche
// 3. L'élément est affiché en position absolue et index 1000
// 3. Je peux agir sur l'élément cartHidden pour supprimer un produit
cartIcon.addEventListener('mouseover', () => {
    cartHidden.style.display = 'block';
    cartHidden.style.transition = 'all 0.5s ease-in-out';
    cartHidden.style.position = 'absolute';
    cartHidden.style.right = '0';
    cartHidden.style.zIndex = '1000';
});

// 1. Lorsque je ne survol pas cartHidden ou cartIcon, je cache l'élément cartHidden

cartHidden.addEventListener('mouseleave', () => {
    cartHidden.style.display = 'none';
});




// cartIcon.addEventListener('mouseover', () => {
//     cartHidden.style.display = 'block';
//     cartHidden.style.position = 'absolute';
//     cartHidden.style.right = '0';
//     cartHidden.style.zIndex = '1000';
// });

// Quand je quitte l'icône cart je cache le récap panier
// cartIcon.addEventListener('mouseout', () => {
//     cartHidden.style.display = 'none';
// });
