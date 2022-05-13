# BusinessCase
- Mise en place des validations
- Mise en place de groups
- Filtre sur les Cities (By name)
- Custom route pour le nb de visits (DQL) :
    + Montant Total des ventes
    + nb de commandes
    + nb de paniers moyen
    + nb de nouveaux clients
    + % de conversion paniers (% entre le nb de visites et le nombre de paniers créés)
    + % de conversion commandes (% entre le nb de paniers et le nombre de
    commandes créées)
    + % de récurrence de commandes clients (un client déjà inscrit à re-commander,
rapport entre le nb de commandes avec nouveaux clients sur la plage
sélectionnée et le nb de commandes avec clients existants)
    + Total de produits vendus triés par ordre décroissant (Le produit le plus vendu
    sera en tête de liste, afficher le nombre d’unités vendues pour chaque produit) (à tester, la fonction se trouve dans product repository)

- Reste à faire :
    - % de paniers abandonnées (% de paniers qui n’ont pas été convertis en
commandes)
    
- Installation et configuration de webpack
- Préparation des blocks dans template/base.html.twig