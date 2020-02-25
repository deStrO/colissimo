<?php 
   /**
    * 												
    *  6 niveaux d’assurance optionnelle										
    */
   return [
      [
         'max_value' => 150,
         'condition' => ['AVEC SIGNATURE', 'RETRAIT'],
         'cost' => 0.9,
         'region' => 'france'
      ],
      [
         'max_value' => 300,
         'condition' => ['AVEC SIGNATURE', 'RETRAIT'],
         'cost' => 1.8,
         'region' => 'france'
      ],
      [
         'max_value' => 500,
         'condition' => ['AVEC SIGNATURE', 'RETRAIT'],
         'cost' => 3,
         'region' => 'france'
      ],
      [
         'max_value' => 1000,
         'condition' => ['AVEC SIGNATURE', 'RETRAIT'],
         'cost' => 6,
         'region' => 'france'
      ],
      [
         'max_value' => 2000,
         'condition' => ['AVEC SIGNATURE'],
         'cost' => 12,
         'region' => 'france'
      ],
      [
         'max_value' => 5000,
         'condition' => ['AVEC SIGNATURE'],
         'cost' => 30,         
         'region' => 'france'
      ]
   ];