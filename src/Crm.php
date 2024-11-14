<?php

namespace Kalimeromk\Crm;

use DOMDocument;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RobRichards\XMLSecLibs\XMLSecurityDSig;
use RobRichards\XMLSecLibs\XMLSecurityKey;
use SimpleXMLElement;
use SoapClient;
use SoapFault;

class Crm
{
    public function prepareXmlPayloadActive(int $number): string
    {
        $productName =  config('crm.keys.PRODUCT_NAME');
        return <<<XML
<?xml version="1.0" encoding="utf-8"?>
<CrmRequest ProductName="$productName">
    <Parameters TemplateName="CVLEInfo"> 
        <Parameter Name="@LEID">$number</Parameter>
    </Parameters>
    <Parameters TemplateName="CVUnits"> 
        <Parameter Name="@LEID">$number</Parameter>
    </Parameters>
    <Parameters TemplateName="CVActors"> 
        <Parameter Name="@LEID">$number</Parameter>
    </Parameters> 
    <Parameters TemplateName="CVOwners"> 
        <Parameter Name="@LEID">$number</Parameter>
    </Parameters> 
    <Parameters TemplateName="CVActivities"> 
        <Parameter Name="@LEID">$number</Parameter>
    </Parameters>
    <Parameters TemplateName="CVMembership">
        <Parameter Name="@LEID">$number</Parameter>
    </Parameters> 
    <Parameters TemplateName="CVFounding"> 
        <Parameter Name="@LEID">$number</Parameter>
    </Parameters>
    <Parameters TemplateName="CVLECourt"> 
        <Parameter Name="@LEID">$number</Parameter>
    </Parameters>
</CrmRequest>
XML;
    }

    public function prepareXmlPayloadAaListing(int $number, int $year): string
    {
        $productName = config('crm.keys.AA_LISTING');
        return <<<XML
<?xml version="1.0" encoding="utf-8"?>
<CrmRequest ProductName="$productName">
    <Parameters TemplateName="CVLEInfo">
        <Parameter Name="@LEID">$number</Parameter>
        <Parameter Name="@Year">$year</Parameter>
   </Parameters>
   <Parameters TemplateName="AAListingInfo">
        <Parameter Name="@LEID">$number</Parameter>
        <Parameter Name="@Year">$year</Parameter>
   </Parameters>
</CrmRequest>
XML;
    }

    /**
     * Sign the XML payload using PEM files for the private key and certificate.
     *
     * @param  string  $xmlPayload
     * @return string
     * @throws Exception
     */
    public function signXml(string $xmlPayload): string
    {
        // Load the XML payload
        $doc = new DOMDocument();
        if (!$doc->loadXML($xmlPayload)) {
            throw new Exception("Failed to load XML payload.");
        }
        $doc->preserveWhiteSpace = true;

        $this->signDocument($doc);

        $signedXml = $doc->saveXML();
        if ($signedXml === false) {
            throw new Exception("Failed to sign the XML document.");
        }

        return $signedXml;
    }

    /**
     * Sign the provided DOMDocument with the PEM private key and certificate.
     *
     * @param  DOMDocument  $doc
     * @throws Exception
     */
    private function signDocument(DOMDocument $doc): void
    {
        $objDSig = new XMLSecurityDSig(false);
        $objDSig->setCanonicalMethod(XMLSecurityDSig::EXC_C14N);

        $objDSig->addReference(
            $doc,
            XMLSecurityDSig::SHA1,
            ['http://www.w3.org/2000/09/xmldsig#enveloped-signature'],
            ['force_uri' => true]
        );

        // Load private key from PEM file
        $privateKeyPath = storage_path('keys/private_key.pem');
        $objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA1, ['type' => 'private']);
        $objKey->loadKey($privateKeyPath, true);

        $objDSig->sign($objKey);

        // Load public certificate from PEM file
        $publicCertPath = storage_path('keys/public_cert.pem');
        $objDSig->add509Cert(file_get_contents($publicCertPath));

        $objDSig->appendSignature($doc->documentElement);
    }

    /**
     * @param  string  $signedXmlPayload
     * @return JsonResponse|bool|string
     * @throws Exception
     */
    public function makeSoapRequest(string $signedXmlPayload): JsonResponse|bool|string
    {
        try {
            $client = new SoapClient(Storage::path(config('crm.CRM')), [
                'cache_wsdl' => WSDL_CACHE_NONE,
            ]);
            $response = $client->ProcessSignedRequest(['parameters' => $signedXmlPayload]);
            return $this->listCrmResultItems($response);
        } catch (SoapFault $e) {
            throw new Exception("SOAP Error: " . $e->getMessage());
        }
    }

    /**
     * @param $response
     * @return bool|JsonResponse|string
     */
    public function listCrmResultItems($response): bool|JsonResponse|string
    {
        try {
            if (!isset($response->ProcessSignedRequestResult)) {
                throw new Exception("Invalid response format.");
            }
            $responseXml = $response->ProcessSignedRequestResult;
            $xml = new SimpleXMLElement($responseXml);
            return json_encode($xml);
        } catch (Exception $e) {
            Log::error("Error parsing XML: " . $e->getMessage());
            return response()->json(['error' => 'Error parsing XML response.'], 500);
        }
    }
}
