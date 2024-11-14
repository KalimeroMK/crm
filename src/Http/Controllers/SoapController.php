<?php

namespace Kalimero\Crm\Http\Controllers

{

    use Exception;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Routing\Controller;
    use Kalimero\Crm\Crm;
    use Kalimero\Crm\Http\Request\AAListingRequest;
    use Kalimero\Crm\Http\Request\LEOSSCurrentViewRequest;


    class SoapController extends Controller
    {

        protected Crm $crm;

        // Dependency injection of the Crm class into the controller
        public function __construct(Crm $crm)
        {
            $this->crm = $crm;
        }

        /**
         * Send a SOAP request with a signed XML payload for LEOSSCurrentView.
         *
         * @param  LEOSSCurrentViewRequest  $request
         * @return JsonResponse
         */
        public function LEOSSCurrentView(LEOSSCurrentViewRequest $request)
        {
            try {
                // Using the Crm class to prepare and send the request
                $xmlPayload = $this->crm->prepareXmlPayloadActive($request->input('number'));
                $signedXmlPayload = $this->crm->signXml($xmlPayload);
                $response = $this->crm->makeSoapRequest($signedXmlPayload);

                return response()->json(['data' => json_decode($response)], 200);
            } catch (Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }

        /**
         * Send a SOAP request with a signed XML payload for AA Listing.
         *
         * @param  AAListingRequest  $request
         * @return JsonResponse
         */
        public function AAListingForInsightSolution(AAListingRequest $request)
        {
            try {
                // Using the Crm class to prepare and send the request
                $xmlPayload = $this->crm->prepareXmlPayloadAaListing($request->input('number'),
                    $request->input('date') ?? 2024);
                $signedXmlPayload = $this->crm->signXml($xmlPayload);
                $response = $this->crm->makeSoapRequest($signedXmlPayload);

                return response()->json(['data' => json_decode($response)], 200);
            } catch (Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }
    }
}
