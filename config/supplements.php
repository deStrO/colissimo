<?php 

   /**
    * 												
    *  Vous permettre, ainsi qu’à vos clients destinataires, de bénéficier d’une livraison
    *  à domicile de qualité, nécessite pour Colissimo la mise à disposition par vos soins de toutes
    *  les informations utiles et fiables pour une livraison réussie.
    *  A ce titre, les informations transmises dans les annonces et imprimées sur les
    *  étiquettes de transport, sont des données essentielles à la livraison de vos colis.
    *  En effet, une information erronée ou manquante a des impacts directs sur le service
    *  de livraison que nous réalisons et influe sur l’information communiquée au destinataire.
    *  Ainsi le supplément qualité de l’annonce peut vous être appliqué en cas de nonconformité des informations transmises :										
    */
   return [
        [
            'supplements' => 'Absence d’EDI le jour J avant minuit*',
            'definition' => 'Absence ou arrivée en J après  minuit par rapport à la prise en charge',
            'cost' => '0.10'
        ],
        [
            'supplements' => 'Adresse non transmise',
            'definition' => 'Pas d’adresse',
            'cost' => '0.10'
        ],
        [
            'supplements' => 'Problème CP/Commune',
            'definition' => 'Le code postal et la commune ne sont pas compatibles',
            'cost' => '0.90'
        ],
        [
            'supplements' => 'Absence e-mail/téléphone',
            'definition' => 'E-mail et téléphone absents (présence obligatoire du mail ou du téléphone)',
            'cost' => '0.10'
        ],
        [
            'supplements' => 'Colis non mécanisable',
            'definition' => 'Tout colis ne répondant pas à une seule des caractéristiques du colis standard défini 
            dans nos conditions générales de ventes nécessite un traitement spécifique',
            'cost' => '6.00'
        ],
        [
            'supplements' => 'Colis non admis',
            'definition' => 'Par principe un colis non admis ne doit pas être remis à La Poste. 
            Pour tous les modes de distribution les envois ne répondant ni aux conditions des colis mécanisables 
            ni à celles des colis non mécanisables définis dans les conditions générales ne sont pas admis dans le réseau postal. 
            Un colis est non admis notamment pour les raisons suivantes : - poids - emballage - dimensions - conditionnement',
            'cost' => '50.00'
        ],
        [
            'supplements' => 'Coefficient d’Ajustement Pétrole (CAP)',
            'definition' => 'Le CAP est un supplément lié au prix du carburant. En fonction du mode de transport utilisé pour acheminer le colis, 
            le client se verra appliquer l’ajustement pétrole correspondant au transport par route ou par avion. 
            Ce coefficient est appliqué sur le montant de la facture HT, après remise (services optionnels, suppléments tarifaires, 
            frais divers, droits et taxes exclus) en pourcentage et variable chaque mois. 
            Le CAP est disponible sur www.laposte.fr/colissimo-entreprise**',
            'cost' => 'Pourcentage variable tous les mois '
        ],
        [
            'supplements' => 'Poids volumétrique',
            'definition' => 'Le poids volumétrique est appliqué sur les colis à destination de l’international 
            (liste des pays concernés sur www.laposte.fr/fiches-pays-colis**) et de l’Outre-Mer transportés par avion. 
            La Poste appliquera le tarif correspondant au poids volumétrique, si ce dernier est supérieur au poids réel du colis.',
            'cost' => 'Lxlxh (en cm)/5000'
        ],
   ];