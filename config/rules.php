<?php 

return [
    
    /*
    |--------------------------------------------------------------------------
    | return all rules for validate labels generation required
    |--------------------------------------------------------------------------
    |
    | this value is the array 
    |
    */
    'default' => [
        'outputFormat' => [
            'x' => 'nullable|numeric|min:-9999|max:9999',
            'y' => 'nullable|numeric|min:-9999|max:9999',
            'outputPrintingType' => 'required|in:ZPL_10x15_203dpi,ZPL_10x15_300dpi,DPL_10x15_203dpi,DPL_10x15_300dpi,PDF_10x15_300dpi,PDF_A4_300dpi',
            'returnType' => 'nullable|in:SendPDFByMail,SendPDFLinkByMail',
        ],
        'letter' => [
            'service' => [
                'productCode' => 'required|in:CORE,A2P,CORI,BPR,ACP,CDI,CMT,BDP,PCS,DOM,DOS,BOS,BOM,COLI,CDS,COM',
                'depositDate' => 'required|date_format:Y-m-d|after:now',
                'mailBoxPicking' => 'nullable|boolean',
                'mailBoxPickingDate' => 'required_if:letter.service.mailBoxPicking,1|date_format:Y-m-d',
                'totalAmount' => 'nullable|numeric',
                'orderNumber' => 'nullable|string|min:1|max:30',
                'commercialName' => 'required_if:letter.service.productCode,A2P,BPR,BDP,CMT|string',
                'returnTypeChoice' => 'nullable|in:2,3',
            ],
            'parcel' => [
                'insuranceValue' => 'nullable',
                'weight' => 'required|numeric|min:0',
                'nonMachinable' => 'nullable|boolean',
                'COD' => 'nullable|boolean',
                'CODAmount' => 'required_if:COD,1|numeric',
                'returnReceipt' => 'nullable|boolean',
                'instructions' => 'nullable|string|min:1|max:35',
                'pickupLocationId' => 'required_if:letter.service.productCode, A2P,BPR,ACP,CDI,CMT,BDP,PCS|nullable|numeric',
                'ftd' => 'nullable|boolean'
            ],
            'sender' => [
                'senderParcelRef' => 'nullable|min:1|max:17',
                'address' => [
                    'companyName' => 'nullable|min:1|max:35',
                    'lastName' => 'required_without:letter.sender.address.companyName|string|min:1|max:35',
                    'firstName' => 'required_without:letter.sender.address.companyName|string|min:1|max:35',
                    'line0' => 'nullable|min:1|max:35', // Etage, couloir, escalier, appartement. 
                    'line1' => 'nullable|min:1|max:35', // Entrée, bâtiment, immeuble, résidence. 
                    'line2' => 'required|min:1|max:35', // Numéro et libellé de voie. 
                    'line3' => 'nullable|min:1|max:35', //Lieu-dit ou autre mention. 
                    'countryCode' => 'required|string|max:2',
                    'city' => 'required|min:1|max:35',
                    'zipCode' => 'required|max:5',
                    'phoneNumber' => 'nullable|min:10|max:12',
                    'mobileNumber' => 'nullable',
                    'doorCode1' => 'nullable|min:1|max:8',
                    'doorCode2' => 'nullable|min:1|max:8',
                    'email' => 'nullable|min:5|max:80',
                    'intercom' => 'nullable|min:1|max:30',
                    'language' => 'nullable|max:2'
                ]
            ],
            'addressee' => [
                'addresseeParcelRef' => 'nullable|min:1|max:17', //Elle permet d’afficher la référence destinataire seulement sur l’étiquette.                
                'codeBarForReference' => 'nullable|boolean', //Permet d’indiquer (ou non) la référence de la commande du destinataire sous la forme d’un code-barres sur l’étiquette du colis. 
                'serviceInfo' => 'nullable', //Nom du service dans la société. 
                'address' => [
                    'companyName' => 'nullable|min:1|max:35',
                    'lastName' => 'required_without:letter.sender.address.companyName|string|min:1|max:35',
                    'firstName' => 'required_without:letter.sender.address.companyName|string|min:1|max:35',
                    'line0' => 'nullable|min:1|max:35', // Etage, couloir, escalier, appartement. 
                    'line1' => 'nullable|min:1|max:35', // Entrée, bâtiment, immeuble, résidence. 
                    'line2' => 'required|min:1|max:35', // Numéro et libellé de voie. 
                    'line3' => 'nullable|min:1|max:35', //Lieu-dit ou autre mention. 
                    'countryCode' => 'required|string|max:2',
                    'city' => 'required|min:1|max:35',
                    'zipCode' => 'required|max:5',
                    'phoneNumber' => 'nullable|min:10|max:12',
                    'mobileNumber' => 'nullable',
                    'doorCode1' => 'nullable|min:1|max:8',
                    'doorCode2' => 'nullable|min:1|max:8',
                    'email' => 'nullable|min:5|max:80',
                    'intercom' => 'nullable|min:1|max:30',
                    'language' => 'nullable|max:2'
                ]
            ]
        ],
        'fields' => [
            'CPASSid' => 'nullable|max:32', // colisssimo pass
            'EORI' => 'nullable|max:32', //EORI (Economics Operators Registration and Identification) 
            'GST' => 'nullable|max:32',//GST (Identifiant taxe australienne) 
        ]        
    ],
    /*
    |--------------------------------------------------------------------------
    | return all rules for validate getProcutInter Method
    |--------------------------------------------------------------------------
    |
    | this value is the array 
    |
    */
     'getProductInter' => [
        'productCode' => 'required|in:CORE,A2P,BPR,ACP,CDI,CMT,BDP,PCS,DOM,DOS,BOS,BOM,COLI',
        'insurance' => 'nullable|boolean',
        'nonMachinable' => 'nullable|boolean',
        'returnReceipt' => 'nullable|boolean',
        'countryCode' => 'required|string|max:2',
        'zipCode' => 'required|max:5'
     ],
     /*
    |--------------------------------------------------------------------------
    | return all rules for validate getListMailBoxPickingDates Method
    |--------------------------------------------------------------------------
    |
    | this value is the array 
    |
    */
    'getListMailBoxPickingDates' => [
        'sender.line2' => 'required|min:1|max:35', // Numéro et libellé de voie. 
        'sender.countryCode' => 'required|string|max:2',
        'sender.city' => 'required|min:1|max:35',
        'sender.zipCode' => 'required|max:5'
     ],
    /*
    |--------------------------------------------------------------------------
    | return all rules for validate planPickup Method
    |--------------------------------------------------------------------------
    |
    | this value is the array 
    |
    */
    'planPickup' => [
        'parcelNumber' => 'required|min:1|max:35',
        'mailBoxPickingDate' => 'required|date_format:Y-m-d',
        'sender.lastName' => 'required|string|min:1|max:35',
        'sender.firstName' => 'required|string|min:1|max:35',
        'sender.line2' => 'required|min:1|max:35', // Numéro et libellé de voie. 
        'sender.countryCode' => 'required|string|max:2',
        'sender.city' => 'required|min:1|max:35',
        'sender.zipCode' => 'required|max:5',
        'sender.email' => 'required|max:80'
     ],
    /*
    |--------------------------------------------------------------------------
    | return all rules for validate getTracking Method
    |--------------------------------------------------------------------------
    |
    | this value is the array 
    |
    */
    'getTracking' => [
        'skybillNumber' => 'required|min:12|max:13'
     ],
    /*
    |--------------------------------------------------------------------------
    | return all rules for validate labels generation not required
    |--------------------------------------------------------------------------
    |
    | this value is the array 
    |
    */
    'conditioned' => [
        'letter' => [
            'customsDeclarations' => [
                'includeCustomsDeclarations' => 'nullbale|boolean',
                'content' => [
                    'description' => 'required|string|min:1|max:64',
                    'quantity' => 'required|numeric',
                    'weight' => 'required|numeric',
                    'value' => 'required|numeric',
                    'hsCode' => 'required_if:letter.customesDeclarations.content.category,3|in:6,8,10',
                    'originCountry' => 'required_if:letter.customesDeclarations.content.category,3|string|min:2|max:2',
                    'currency' => 'nullable|string|in:USD,EUR,CHF,GBP,CNY,JPY,CAD,AUD,HKD',
                    'artref' => 'nullable|min:1|max:44',
                    'originalIdent' => 'required|string|max:1',
                    'category' => [
                        'value' => 'required|max:1',
                    ],
                    'original' => [
                        'originalIdent' => 'nullable|max:35',
                        'originalInvoiceNumber' => 'nullable|min:1|max:64',
                        'originalInvoiceDate' => 'nullable|date_format:Y-m-d',
                        'originalParcelNumber' => 'nullable|min:1|max:35',
                    ]
                ],
                'importersReference' => 'nullable|min:1|max:35',
                'importersContact' => 'nullable|max:35',
                'officeOrigin' => 'nullable|max:35',
                'comments' => 'nullable|max:35',
                'invoiceNumber' => 'nullable|min:1|max:35',
                'licenceNumber' => 'nullable|min:1|max:35',
                'certificatNumber' => 'nullable|min:1|max:35',
                'importerAddress' => [
                    'companyName' => 'nullable|max:35',
                    'lastName' => 'nullable|string|max:35',
                    'firstName' => 'nullable|string|max:29',
                    'line0' => 'nullable|max:35',
                    'line1' => 'nullable|max:35',
                    'line2' => 'nullable|max:35',
                    'line3' => 'nullable|max:35',
                    'countryCode' => 'nullable|max:2',
                    'city' => 'nullable|max:35',
                    'zipCode' => 'nullable|max:5',
                    'phoneNumber' => 'nullable|max:15',
                    'mobileNumber' => 'nullable|max:10',
                    'doorCode1' => 'nullable|max:8',
                    'doorCode2' => 'nullable|max:8',
                    'email' => 'nullable|max:80',
                    'intercom' => 'nullable|max:30',
                    'Language' => 'nullable|max:2',
                ]
            ]
        ]
    ]
];