<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Account number is the number Colissimo has provided to you or to your client
    |--------------------------------------------------------------------------
    |
    | this value is the number 
    |
    */
    'accountNumber' =>  env('COLISSIMO_ACCOUNT_NUMBER', null),
    /*
    |--------------------------------------------------------------------------
    | Password is the code to login to your colissimo dashboard. Colissimo has provided to you or to your client
    |--------------------------------------------------------------------------
    |
    | this value is the string 
    |
    */
    'password' =>  env('COLISSIMO_PASSWORD', null),
    /*
    |--------------------------------------------------------------------------
    |% of Carbon taxe is the taxe add on price by colissimo
    |--------------------------------------------------------------------------
    |
    | this value is the number 
    |
    */
    'carbon_taxe' =>  env('COLISSIMO_CARBON_TAXE', 0.0275)
];