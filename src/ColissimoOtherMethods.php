<?php

namespace Quimeboule\Colissimo;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class ColissimoOtherMethods
{

    private $serviceUrl = 'http://sls.ws.coliposte.fr';

    private $url = 'https://ws.colissimo.fr/sls-ws/SlsServiceWS/2.0?wsdl';

    private $colissimo;

    private $region;

    private $zone;

    private $weight;

    public function __construct()
    {
        $this->colissimo = new \Quimeboule\Colissimo\Colissimo();
    }

    /**
     * Utile uniquement dans le cas de certaines destinations internationales. 
     * Retourne les options de retour compatibles avec la destination/l’option de livraison demandée (en fonction des zones tarifaires).
     * @param array $datas
     * @return array
     */
    public function getProductInter($datas){
        $check = $this->colissimo->checkConfig();
        if ($check->status == 'error') {
            return $check;
        }

        $rules = config('colissimo.rules.getProductInter');
        $validator = Validator::make($datas, $rules);
        if ($validator->fails()) {
            return (object) [
                'status' => 'error',
                'message' => $validator->errors()->first(),
                'code' => 2
            ];
        }
        //transform data with config data
        $datas = collect($datas);
        $datas->prepend(config('colissimo.password'), 'password');
        $datas->prepend(config('colissimo.accountNumber'), 'contractNumber');

        //create xml base
        $body = new \SimpleXMLElement('<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"  />'); 
        $body->addChild("soapenv:Header");
        $children = $body->addChild("soapenv:Body");
        $children = $children->addChild("sls:getProductInter", null, $this->serviceUrl);
        $children = $children->addChild("getProductInterRequest", null, ""); 
        //convert datas to xml
        $converter = new XMLConverter($datas);
        $converter->massArrayToXml($children);
        $body = $body->asXML();

        //construc message
        $message = (object) [
            'body' => $body,
            'url' => $this->url
        ];
        //Send request
        $output = $this->colissimo->call($message);
        //Format out put to xml
        $xmlResponse = $converter->outPutFormatToXML($output);
        //format xml to array
        $response = $converter->xmlToArray($xmlResponse);
        return $response;
    }

     /**
     * Fonctionne avec le produit Retour Colissimo France 
     * (numéro de colis généré via WS ou toute autre solution avec annonce) 
     * Permet de savoir si une adresse est éligible à l’expédition depuis une boîte aux lettres et connaître
     * la date et l’heure de prochain emport de colis à cette adresse. 
     * Utile pour les web marchands qui souhaitent proposer l’option retour boîte aux lettres à partir de leur site. 
     * @param array $datas
     * @return array 
     */
    public function getListMailBoxPickingDates($datas){
        $check = $this->colissimo->checkConfig();
        if ($check->status == 'error') {
            return $check;
        }

        $rules = config('colissimo.rules.getListMailBoxPickingDates');
        $validator = Validator::make($datas, $rules);
        if ($validator->fails()) {
            return (object) [
                'status' => 'error',
                'message' => $validator->errors()->first(),
                'code' => 2
            ];
        }
        //transform data with config data
        $datas = collect($datas);
        $datas->prepend(config('colissimo.password'), 'password');
        $datas->prepend(config('colissimo.accountNumber'), 'contractNumber');

        //create xml base
        $body = new \SimpleXMLElement('<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"  />'); 
        $body->addChild("soapenv:Header");
        $children = $body->addChild("soapenv:Body");
        $children = $children->addChild("sls:getListMailBoxPickingDates", null, $this->serviceUrl);
        $children = $children->addChild("getListMailBoxPickingDatesRetourRequest", null, ""); 
        //convert datas to xml
        $converter = new XMLConverter($datas);
        $converter->massArrayToXml($children);
        $body = $body->asXML();

        //construc message
        $message = (object) [
            'body' => $body,
            'url' => $this->url
        ];
        //Send request
        $output = $this->colissimo->call($message);
        //Format out put to xml
        $xmlResponse = $converter->outPutFormatToXML($output);
        //format xml to array
        $response = $converter->xmlToArray($xmlResponse);
        return $response;
    }

    /**
     * Fonctionne avec le produit Retour Colissimo France (n° colis généré via WS ou toute autre solution avec annonce).
     * Permet de faire une demande d’emport d’un colis retour depuis la boîte aux lettres du ré-expéditeur. 
     * Utile pour les web marchands qui souhaitent proposer l’option retour boîte aux lettres à partir de leur site.
     * @param array $datas
     */
    public function planPickup($datas){
        $check = $this->colissimo->checkConfig();
        if ($check->status == 'error') {
            return $check;
        }

        $rules = config('colissimo.rules.planPickup');
        $validator = Validator::make($datas, $rules);
        if ($validator->fails()) {
            return (object) [
                'status' => 'error',
                'message' => $validator->errors()->first(),
                'code' => 2
            ];
        }
        //transform data with config data
        $datas = collect($datas);
        $datas->prepend(config('colissimo.password'), 'password');
        $datas->prepend(config('colissimo.accountNumber'), 'contractNumber');

        //create xml base
        $body = new \SimpleXMLElement('<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"  />'); 
        $body->addChild("soapenv:Header");
        $children = $body->addChild("soapenv:Body");
        $children = $children->addChild("sls:planPickup", null, $this->serviceUrl);
        $children = $children->addChild("planPickupRequest", null, ""); 
        //convert datas to xml
        $converter = new XMLConverter($datas);
        $converter->massArrayToXml($children);
        $body = $body->asXML();

        //construc message
        $message = (object) [
            'body' => $body,
            'url' => $this->url
        ];
        //Send request
        $output = $this->colissimo->call($message);
        //Format out put to xml
        $xmlResponse = $converter->outPutFormatToXML($output);
        //format xml to array
        $response = $converter->xmlToArray($xmlResponse);
        return $response;
    }

    public function importPriceOfPdf($route){
        $sheets = Excel::toCollection([], $route);
        $keys = null;
        $fileContent = collect();
        foreach ($sheets as $key => $sheet) {
            $values = collect();
            foreach ($sheet as $index => $row) {
                if($index == 0){
                    $keys = $row;
                    continue;
                }
                $row = collect($row);
                $row = $row->mapWithKeys(function($item, $key) use ($keys){
                    return [$keys[$key] => $item];
                })->filter(function($item, $k){
                    return $item !== null;
                });
                if ($row->count() > 0) {
                    $values->push($row);
                }
            }
            $fileContent->put($this->region[$key], $values);
        }
        $datas = var_export($fileContent->toArray(), true);
        $content = '<?php return ';
        $content .= $datas.';';
        file_put_contents(__DIR__.'/../config/prices.php', $content);
        return $datas;
    }

    /**
     * Get price function to address, weight && zones
     * @param object address
     * @param string weight
     * @param string type // voir config/prices.php
     * @return object
     */
    public function getPrice($address, $weight, $type = null){
        $prices = config('colissimo.prices');
        if (!is_object($address) || !$address->countryCode) {
            return (object) [
                'status' => 'error',
                'message' => "Vous devez renseigner une adresse sous forme d'objet, {street, postalCode, city, countryCode}",
                'code' => 'ADDRESS_ERROR'
            ];
        }
        
        //DETECT ZONE
        if ($address->countryCode != 'FR') {
            $zones = collect(config('colissimo.zones'));
            $zones = $zones->filter(function($item) use ($address){
                $countryCode = strtolower($address->countryCode);
                if (collect($item)->keys()->contains($countryCode)) {
                    //IF EXCEPTION
                    if ($item[$countryCode]['limit'] == 1) {
                        //IF IN EXCLUDE
                        if(count($item[$countryCode]['excludes']) > 0){
                            foreach ($item[$countryCode]['excludes'] as $key => $ex) {
                                if (($address->postalCode > $ex['start'] && $address->postalCode < $ex['end']) || $address->postalCode == $ex['start'] || $address->postalCode < $ex['end']) {
                                    return false;
                                }
                            }
                        }else{
                            //ELSE IN INCLUDE
                            foreach ($item[$countryCode]['includes'] as $key => $ex) {
                                if (($address->postalCode > $ex['start'] && $address->postalCode < $ex['end']) || $address->postalCode == $ex['start'] || $address->postalCode < $ex['end']) {
                                    return true;
                                }
                            }
                            return false;
                        }
                    }
                    return true;
                }
                return false;
            });
            $this->region = 'europe';
            if (in_array($zones->keys()->first(), ['ZONE5', 'ZONE6'])) {
                $this->region = 'internationale';
            }
            if (in_array($zones->keys()->first(), ['OM1', 'OM2'])) {
                $this->region = 'outremer';
            }
            $this->zone = $zones->keys()->first();
            $prices = $prices[$this->region];
        }else{   
            $this->region = 'france';
            $prices = $prices['france'];
        }

        //SEARCH PRICE
        $price = null;
        foreach ($prices as $key => $item) {
            if ($item['POIDS'] > $weight) {
                $this->weight = $item['POIDS'];
                $price = $item;
                break;
            }
        }
        //RETURN PRICE FUNCTION TO TYPE
        if ($type) {
            if ($this->region != 'france') {
                $type = $type.' '.$this->zone;
            }
            if (isset($price[$type])) {
                return (object) [
                    'status' => 'success',
                    'price' => $price[$type] * 1.225,
                    'region' => $this->region,
                    'type' => $type,
                    'zone' => $this->zone,
                    'weight_entered' => $weight,
                    'weight' => $this->weight,
                    'address' => $address
                ];
            }
            $types = collect($price)->filter(function($item, $key){
                return $key != 'POIDS';
            })->keys()->implode(',');
            return (object) [
                'status' => 'error',
                'message' => "Le type ".$type." n'existe pas. Les types disponibles pour la région ".$this->region." sont: ".$types,
                'code' => 'TYPE_ERROR'
            ];
        }
        return (object) [
            'status' => 'success',
            'price' => collect($price)->map(function($item, $key){
                return $key == 'POIDS' ? $item : $item * 1.225; 
            }),
            'region' => $this->region,
            'type' => $type,
            'zone' => $this->zone,
            'weight_entered' => $weight,
            'weight' => $this->weight,
            'address' => $address
        ];
    }

    /**
     * @param number price
     * @return object
     */
    public function getInsurancePrice($price){
        $insurancs = config('colissimo.insurances');
        foreach ($insurancs as $key => $item) {
            if ($item['max_value'] > $price) {
                return $item;
            }
        }
    }
}