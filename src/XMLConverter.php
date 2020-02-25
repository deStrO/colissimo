<?php

namespace Quimeboule\Colissimo;

use Carbon\Carbon;

class XMLConverter
{

    /**
     * @param array $datas 
     */
    public function __construct($datas)
    {
        $this->datas = $datas;
    }

     /**
     * Retourne les datas de la requetes en format xml
     * @param xml
     * @return xml 
     */
    public function massArrayToXml($xml){
        $datas = collect($this->datas);
        $xml = $this->arrayToXml($datas, $xml);
        return $xml;
    }

    /**
     * Convert simple array to xml
     * @return xml 
     */
    private function arrayToXml($array, $xml){
        foreach ($array as $key => $value) {
            if($key == 'articles'){
                collect($value)->each(function($item) use($xml) {
                    $article = $xml->addChild('article', null, '');
                    collect($item)->each(function($i, $k) use ($article){
                        $article->addChild($k, $i, '');
                    });
                });
            }else{
                if(is_array($value)){
                    $child = $xml->addChild($key, null, '');
                    $this->arrayToXml($value, $child);
                }else{
                    switch ($key) {
                        case 'weight':
                            $value = $value;
                            break;
                        default:
                            $value = trim($value);
                            break;
                    }
                    $xml->addChild($key, $value, '');
                }
            }
        }
        return $xml;
    }

    

    /**
     * Convertie la réponse de requête de xml vers array
     * @var xml
     * @return Array 
     */
    public function xmlToArray($response){
        $response = preg_replace("/(<\/?)(\w+):([^>]*>)/", '$1$2$3', $response);
        $response = json_encode(simplexml_load_string($response));
        return json_decode($response, true);
    }

    /**
     * Convertie la réponse colissimo en xml
     * @return xml 
     */
    public function outPutFormatToXML($datas){
        $content = array ();
        $matches = array ();
        preg_match_all ( '/--uuid:/', $datas, $matches, PREG_OFFSET_CAPTURE ); 
        for($i = 0; $i < count ( $matches [0] ) -1; $i ++) {
            if ($i + 1 < count ( $matches [0] )) {
                $content [$i] = substr($datas, $matches[0][$i][1], $matches[0][$i + 1][1] - $matches[0][$i][1] );
            } else {
                $content [$i] = substr($datas, $matches [0][$i][1], strlen($datas));
            }
        } 
        $uuid = null;
        $attachments = array ();
        $soapResponse = array (); 
        foreach ($content as $part) { 
             if($uuid == null){
                $uuidStart = 0;
                $uuidEnd = 0;
                $uuidStart = strpos($part, '/--uuid:/', 0)+strlen('/--uuid:/');
                $uuidEnd = strpos($part, "\r\n", $uuidStart);
                $uuid = substr($part, $uuidStart, $uuidEnd-$uuidStart);
             }
             $header = $this->extractHeader($part);
             if(count($header) > 0){
                if(strpos($header['Content-Type'], 'type="text/xml"') !== FALSE){
                    $soapResponse['header'] = $header;
                    $soapResponse['data'] = trim(substr($part, $header['offsetEnd']));
                } else {
                    $attachment['header'] = $header;
                    $attachment['data'] = trim(substr($part, $header['offsetEnd']));
                    array_push($attachments, $attachment);
                }
             }
        } 
        if (!isset($soapResponse['data'])) {
            return $datas;
        }
        return $soapResponse['data'];
    }

    /**
     * Exclude the header from the Web Service response
     * @param string $part
     * @return array $header
     */
    private function extractHeader($part){
        $header = array();
        $headerLineStart = strpos($part, 'Content-', 0);
        $endLine = 0;
        while($headerLineStart !== FALSE){
            $header['offsetStart'] = $headerLineStart;
            $endLine = strpos($part, "\r\n", $headerLineStart);
            $headerLine = explode(': ', substr($part, $headerLineStart, $endLine-$headerLineStart));
            $header[$headerLine[0]] = $headerLine[1];
            $headerLineStart = strpos($part, 'Content-', $endLine);
        }
        $header['offsetEnd'] = $endLine; 
        return $header;
    }
}