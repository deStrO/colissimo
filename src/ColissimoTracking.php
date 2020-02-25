<?php

namespace Quimeboule\Colissimo;

use Quimeboule\Colissimo\XMLConverter;
use Illuminate\Support\Facades\Validator;

class ColissimoTracking
{

    private $serviceUrl = 'https://www.coliposte.fr/tracking-chargeur-cxf/TrackingServiceWS/track';

    /**
     * Utile uniquement dans le cas de certaines destinations internationales. 
     * Retourne les options de retour compatibles avec la destination/l’option de livraison demandée (en fonction des zones tarifaires).
     * @param array $datas
     * @return array
     */
    public function getTracking($datas){
        $datas = collect($datas);
        $datas->prepend(config('colissimo.password'), 'password');
        $datas->prepend(config('colissimo.accountNumber'), 'accountNumber');
        
        $stringDatas = $datas->map(function($item, $key){
            return $key.'='.trim($item);
        })->values()->implode('&');
        
        $url = $this->serviceUrl.'?'.$stringDatas;
        $xmlResponse = file_get_contents($url);
        $converter = new XMLConverter($datas);
        $response = $converter->xmlToArray($xmlResponse);
        if (isset($response['soapBody']['ns1trackResponse']['return'])) {
            $response = collect($response['soapBody']['ns1trackResponse']['return']);
            if (isset($response['errorCode']) && $response['errorCode'] != 0) {
                return (object) [
                    'status' => 'error',
                    'errorCode' => $response['errorCode'],
                    'errorMessage' => $response['errorMessage'],
                    'skybillNumber' => $response['skybillNumber']
                ]; 
            }
            return (object) [
                'status' => 'success',
                'eventDate' => $response['eventDate'],
                'eventLibelle' => $response['eventLibelle'],
                'eventSite' => $response['eventSite'],
                'eventCode' => $response['eventCode']
            ]; 
        }
        return (object) $response; 
    }

}