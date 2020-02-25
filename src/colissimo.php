<?php

namespace Quimeboule\Colissimo;

use Carbon\Carbon;
use Quimeboule\Colissimo\ColissimoOtherMethods;

class Colissimo
{

    /**
     *  Envoie la requete à colissimo, convertie la réponse en json
     * @param xml corps du message en xml
     *  @return response
     */
    public function call($message){ 
        if(!is_object($message)){
            abort(501, '$message is not object');
        }    
        if (!$message->url || !$message->body) {
            abort(501, '$message must contain an url and a body in xml ');
        }
        $headers = [
            "Content-type: text/xml;charset=\"utf-8\"",
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
        ];
        
        //UPDATE HEADER WITH CONTENT LENGTH
        $headers[] = "Content-length: ".strlen($message->body);
        //CREATE REQUEST
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_URL, $message->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $message->body);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        //EXECUTE REQUEST
        return curl_exec($ch);     
    }

    /**
     * abort if config is not complete
     * @return boolean || abort
     */
    public function checkConfig(){
        if (!config('colissimo.password') || !config('colissimo.accountNumber')) {
            return (object) [
                'status' => 'error',
                'code' => 1,
                'message' => 'Require account number and password'
            ];
        }
        return (object) [
            'status' => 'success'
        ];
    }

    public function getCosts(){
        return config('colissimo.prices');
    }

    public function getZones(){
        return config('colissimo.zones');
    }

    public function getInsurances(){
        return config('colissimo.insurances');
    }

    public function getInsurancePrice($price){
        $method = new ColissimoOtherMethods();
        return $method->getInsurancePrice($price);
    }

    public function getRelays($datas){
        $method = new ColissimoPointRetrait();
        return $method->get($datas);
    }

    /**
     * @param Object $datads [country, retour, signature, relayId]
     * @return String productCode
     */
    public function getProductCode($datas){
        $outremer = 0;
        $international = 0;
        if (isset($datas->country) && $datas->country != 'FR') {
            $zone = collect(config('colissimo.zones'))->filter(function($item) use ($datas) {
                return collect($item)->keys()->contains(strtolower($datas->country));
            })->keys();
            if (isset($zone[0])) {
                if (in_array($zone[0], ['OM1',  'OM1'])) {
                   $outremer = 1;
                }else{
                    $international = 1;
                }
            }else{
                return 'INELIGIBLE';
            }
            //NON ELIGIBLE
        }
        if ($datas->retour == 1) {
            return ($outremer == 1 ? 'CORI' : ($international == 1 ? 'INELIGIBLE' : 'CORE'));
        }
        if ($datas->signature == 1) {
            return ($outremer == 1 ? 'CDS' : ($international == 1 ? 'DOS' : 'DOS'));
        }
        
        if ($datas->relayId) {
            $method = new ColissimoPointRetrait();
            $relay = $method->get([
                'id' => $datas->relayId,
                'date' => Carbon::now()->format('d/m/Y')
            ]);
            if($data = $relay->datas){
                if(isset($data['typeDePoint'])){
                    return $data['typeDePoint'];
                }
            }
            return ($outremer == 1 ? 'INELIGIBLE' : ($international == 1 ? 'CMT' : 'BPR'));
        }
        return ($outremer == 1 ? 'COM' : ($international == 1 ? 'DOM' : 'DOM'));
    }


     /**
     * @param String country code 
     * @return String zone
     */
    public function getZone($country){
        if ($country != 'FR') {
            $zone = collect(config('colissimo.zones'))->filter(function($item) use ($country) {
                return collect($item)->keys()->contains(strtolower($country));
            })->keys();
            return $zone[0];
            //NON ELIGIBLE
        }
        return 'FRANCE';
    }
}