<?php

namespace Quimeboule\Colissimo;

use Carbon\Carbon;
use Quimeboule\Colissimo\XMLConverter;
use Illuminate\Support\Facades\Validator;

class ColissimoPointRetrait
{

    private $url = 'https://ws.colissimo.fr/pointretrait-ws-cxf/PointRetraitServiceWS/2.0?wsdl';

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
        $rules = [
            'address' => 'nullable|max:200',
            'zipCode' => 'required|size:5' ,
            'city' => 'required|max:50',
            'weight' => 'nullable|numeric',
            'shippingDate' => 'required|date_format:d/m/Y',
            'filterRelay' => 'nullable|boolean',
            'requestId' => 'nullable|size:64',
            'lang' => 'nullable|size:2',
            'optionInter' => 'nullable|boolean'
        ];
        $validator = Validator::make($datas, $rules);
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
        
        //transform data with config data
        $datas = collect($datas);
        $datas->prepend(config('colissimo.password'), 'password');
        $datas->prepend(config('colissimo.accountNumber'), 'accountNumber');
        //map datas for colissimo format
        $datas = $datas->map(function($item, $key){
            if ($key == 'weight') {
                return (int) ceil($item);
            }
            return trim($item);
        })->toArray();

        //check datas
        if(!isset($datas['id'])){
            $check = $this->checkDatas($datas);
            if ($check->status == 'error') {
                return $check;
            }
        }
        //check config datas
        $check = $this->colissimo->checkConfig();
        if ($check->status == 'error') {
            return $check;
        }
        //create xml base
        $body = new \SimpleXMLElement('<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:v2="http://v2.pointretrait.geopost.com/" />'); 
        $body->addChild("soapenv:Header");
        $children = $body->addChild("soapenv:Body");
        $point = (isset($datas['id']) ? 'findPointRetraitAcheminementByID' : 'findRDVPointRetraitAcheminement');
        $children = $children->addChild("v2:".$point, null, 'http://v2.pointretrait.geopost.com/');
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
        
        if (isset($response['soapBody']['ns2'.$point.'Response']['return'])) {
            $response = collect($response['soapBody']['ns2'.$point.'Response']['return']);
            if ($response['errorCode'] && $response['errorCode'] != 0) {
                return (object) [
                    'status' => 'error',
                    'message' => $response['errorMessage'],
                    'code' => $response['errorCode']
                ];
            }
            if(isset($datas['id'])){
                return (object) [
                    'status' => 'success',
                    'datas' => $response['pointRetraitAcheminement']
                ];
            }
            return (object) [
                'status' => 'success',
                'datas' => $response['listePointRetraitAcheminement'],
                'wsRequestId' => $response['wsRequestId'],
                'qualiteReponse' => $response['qualiteReponse'],
                'rdv' => $response['rdv']
            ];
        }
        return $response;
    }
}