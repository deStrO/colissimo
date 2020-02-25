<?php

namespace Quimeboule\Colissimo;

use Carbon\Carbon;
use Quimeboule\Colissimo\XMLConverter;
use Illuminate\Support\Facades\Validator;

class ColissimoGenerateLabel
{

    private $serviceUrl = 'http://sls.ws.coliposte.fr';

    private $url = 'https://ws.colissimo.fr/sls-ws/SlsServiceWS?wsdl';

    private $colissimo;

    public function __construct()
    {
        $this->colissimo = new \Quimeboule\Colissimo\Colissimo();
    }

    /**
     * Validate all datas before request
     * @param array $datas
     * @return string | null $validator first message
     */
    public function checkDatas($datas){
        $rules = $this->getRules('colissimo.rules.default');
        //verifie si la destination nécessite une déclaration douaniere de type CN23 et ajoute les regles de validation 
        if (true) {
           $rules = $rules->merge($this->getRules('colissimo.rules.conditioned', 'letter.customsDeclarations'));
        }


        $validator = Validator::make($datas->toArray(), $rules->toArray());
        if ($validator->fails()) {
            return (object) [
                'status' => 'error',
                'message' => $validator->errors()->first(),
                'code' => 2
            ];
        }
        return (object) [
            'status' => 'success',
            'message' => null
        ];
    }
    
    /**
     * @param array $datas données à transmettre à colissimo sour forme d'array
     * @return Array 
     */
    public function get($datas){
        //check config datas
        $check = $this->colissimo->checkConfig();
        if ($check->status == 'error') {
            return $check;
        }

        //transform data with config data
        $datas = collect($datas);
        $datas->prepend(config('colissimo.password'), 'password');
        $datas->prepend(config('colissimo.accountNumber'), 'contractNumber');

        //check datas
        $check = $this->checkDatas($datas);
        if ($check->status == 'error') {
            return $check;
        }

        //create xml base
        $body = new \SimpleXMLElement('<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"  />'); 
        $body->addChild("soapenv:Header");
        $children = $body->addChild("soapenv:Body");
        $children = $children->addChild("sls:generateLabel", null, $this->serviceUrl);
        $children = $children->addChild("generateLabelRequest", null, ""); 
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
        if (isset($response['soapBody']['ns2generateLabelResponse']['return'])) {
            $response = collect($response['soapBody']['ns2generateLabelResponse']['return']);
            if (isset($response['messages']['type']) && $response['messages']['type'] == 'ERROR') {
                return (object) [
                    'status' => 'error',
                    'message' => $response['messages']['messageContent'],
                    'code' => $response['messages']['type']
                ];
            }
            return (object) [
                'status' => 'success',
                'datas' => $response['labelResponse'],
            ];
        }
        return $response;
    }

    private function getRules($name, $key = null){
        $rules = collect();
        foreach (config($name) as $k => $rule) {
            if (is_array($rule)) {
                $rules = $rules->merge($this->createRule($rule, $k));
                continue;
            }
            $rules = $rules->merge([$k => $rule]);
        }
        
        if ($key) {
            return $rules->filter(function($item, $k) use ($key){
                $keys = explode('.', $k);
                array_pop($keys);
                return  implode('.', $keys) == $key;
            });
        }
        return $rules;
    }

    /**
     * @param array || string  $value
     * @param string $key
     * change to config format to colissimo format
     */
    private function createRule($value, $key){
        $collect = collect($value);
        return $collect->mapWithKeys(function($item, $k) use ($key){
            if (is_array($item)) {
                return $this->createRule($item, $key.'.'.$k);
            }
            return [$key.'.'.$k => $item];
        });
    }
}