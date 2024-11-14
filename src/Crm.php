<?php

namespace Kalimero\Crm;

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
        $productName = config('crm.keys.product_name') ?? 'default_product_name';
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
        $productName = config('crm.keys.aaListing') ?? 'default_aa_listing';
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
        $objDSig = new XMLSecurityDSig();
        $objDSig->setCanonicalMethod(XMLSecurityDSig::EXC_C14N);

        $objDSig->addReference(
            $doc,
            XMLSecurityDSig::SHA1,
            ['http://www.w3.org/2000/09/xmldsig#enveloped-signature'],
            ['force_uri' => true]
        );

        // Load private key from PEM file
        $privateKeyPath = config('crm.private_key_path');
        if (!is_string($privateKeyPath) || !file_exists($privateKeyPath)) {
            throw new Exception("Invalid private key path.");
        }

        $objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA1, ['type' => 'private']);
        $objKey->loadKey($privateKeyPath, true);

        $objDSig->sign($objKey);

        // Load public certificate from PEM file
        $publicCertPath = config('crm.public_cert_path');
        if (!is_string($publicCertPath) || !file_exists($publicCertPath)) {
            throw new Exception("Invalid public certificate path.");
        }

        $certContent = file_get_contents($publicCertPath);
        if ($certContent === false) {
            throw new Exception("Failed to load public certificate.");
        }

        $objDSig->add509Cert($certContent);

        $element = $doc->documentElement;
        if ($element === null) {
            throw new Exception("Document element is null, unable to append signature.");
        }

        $objDSig->appendSignature($element);
    }

    /**
     * Make the SOAP request.
     *
     * @param  string  $signedXmlPayload
     * @return JsonResponse
     * @throws Exception
     */
    public function makeSoapRequest(string $signedXmlPayload): JsonResponse
    {
        try {
            $wsdlPath = config('soap.wsdl.crm');
            if (!is_string($wsdlPath)) {
                throw new Exception("Invalid WSDL path in configuration.");
            }

            $client = new SoapClient(Storage::path($wsdlPath), [
                'cache_wsdl' => WSDL_CACHE_NONE,
            ]);

            $response = $client->ProcessSignedRequest(['parameters' => $signedXmlPayload]);

            if (!is_object($response)) {
                throw new Exception("Invalid response type from SOAP request.");
            }

            // Process and list the CrmResultItem elements from the response
            return $this->listCrmResultItems($response);
        } catch (SoapFault $e) {
            throw new Exception("SOAP Error: " . $e->getMessage());
        }
    }

    /**
     * @param object $response
     * @return JsonResponse
     */
    public function listCrmResultItems(object $response): JsonResponse
    {
        try {
            if (!isset($response->ProcessSignedRequestResult)) {
                throw new Exception("Invalid response format.");
            }
            $responseXml = $response->ProcessSignedRequestResult;
            $xml = new SimpleXMLElement($responseXml);

            $jsonString = json_encode($xml);
            if ($jsonString === false) {
                throw new Exception("Failed to encode XML to JSON.");
            }

            return response()->json(json_decode($jsonString, true));
        } catch (Exception $e) {
            Log::error("Error parsing XML: " . $e->getMessage());
            return response()->json(['error' => 'Error parsing XML response.'], 500);
        }
    }
}
