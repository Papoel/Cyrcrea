Début du travail sur le projet : 10-07-2022 20:00

| #   | A Faire                                                                                                    | En cours | Fait | Bugs / Solution                                                                                    | Abandonné |
|-----|------------------------------------------------------------------------------------------------------------|----------|------|----------------------------------------------------------------------------------------------------|-----------|
| 1   | Afficher le password en clair                                                                              |          |      | password Hash donc impossible de l'afficher                                                        | X         |
| 2   | Création du template Commande                                                                              |          |      |                                                                                                    |           |
| 3   | Gestion des Commandes                                                                                      |          |      |                                                                                                    |           |
| 4   | Gestion du Panier                                                                                          |          |      |                                                                                                    |           |
| 5   | Refaire le design du mail 'Confirmation Inscription'                                                       |          | x    | L'envoie du mail est en HTML Brut / https://symfonycasts.com/screencast/mailer/embedded-image#play |           |
| 6   | Écrire un script qui permet à l'utilisateur d'afficher son mot de passe lorsqu'il se connecte ou s'inscrit |          | X    |                                                                                                    |           |    
| 7   | Création interface Administration                                                                          |          | x    |                                                                                                    |           |
| 8   | Administration : Crud PRODUCT                                                                              |          | x    |                                                                                                    |           |
| 9   | Administration : Crud CATEGORIES                                                                           |          | x    |                                                                                                    |           |

Un produit à un nombre en stock et un tarif unitaire

## Achat d'un produit

- La quantité ajoutable au panier doit être positive et inférieure ou égale à la quantité en stock.
- Le panier est stocké dans la session.
- Lorsqu'un produit est ajouté au panier, il est ajouté à la session 'panier'.
- Le panier est un tableau de produits.
- Le stock de produits est modifié lorsqu'un produit est ajouté au panier.
- Le panier est affiché dans la vue cart.html.twig.
- Le panier est traité dans le contrôleur CartController.
- CartController fait appel au service panier pour être traité.
- Le panier devient une commande au status 'En attente'.
- La commande est traitée dans le contrôleur OrderController.
- La commande est envoyée au service OrderService pour être traitée.
- La commande passe au status 'En cours de traitement'.
- L'administrateur change le status de la commande en 'Expédié'.
- Lorsque le livreur arrive au point de livraison, le status de la commande passe au status 'Livré'.

## Workflow de la commande

| Statut                 | Action                                                                                                                                |
|------------------------|---------------------------------------------------------------------------------------------------------------------------------------|
| En attente             | Création de la commande                                                                                                               |
| En cours de traitement | Le commerçant à reçu et prépare la commande                                                                                           |
| Expédié                | Le commerçant à préparé la commande et à livré le colis - Le N° de suivi devra être ajouté pour que le client puisse suivre son colis |
| Livré                  | Lorsque le client réceptionne le colis                                                                                                |

## Action dans les Contrôleurs et Entités

- Ajouter un produit au panier
    - product.stock moins 1
    - order.products ajouter le produit
    - order.quantity = nombre de produits dans la commande
    - order.price = product.price * order.quantity
    - order.carrierName = choix du mode de livraison (Entity Carrier)
    - order.carrierPrice = Prix du mode de livraison (Entity Carrier)
    - order.totalPrice = order.price + order.carrierPrice + TVA
    - order.status = "En attente"
    - order.createdAt = Date du jour
    - Le panier est sauvegardé dans la session

- Validation de la commande
  - Envoie du mail de confirmation de commande
  - order.status = "En cours de traitement"
  - product.stock = Mise a jour du stock
  - Suppression du panier
  - Redirection vers la page de confirmation de commande

- Supprimer un produit du panier
- Vider le panier
- Récupérer le panier

<hr>

Combien de temps le panier est sauvegardé et comment ?

<hr>

Chose à Faire :

- [ ] Responsive Login
- [ ] Responsive Register
- [ ] Responsive Password Forget
