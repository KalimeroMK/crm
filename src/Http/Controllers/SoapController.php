<?php

namespace Kalimeromk\Crm\Http\Controllers

{

    use Exception;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Routing\Controller;
    use Kalimeromk\Crm\Crm;
    use Kalimeromk\Crm\Http\Request\AAListingRequest;
    use Kalimeromk\Crm\Http\Request\LEOSSCurrentViewRequest;


    class SoapController extends Controller
    {

        protected Crm $crm;
        public function __construct(Crm $crm)
        {
            $this->crm = $crm;
        }

        /**
         * Send a SOAP request with a signed XML payload for LEOSSCurrentView.
         *
         * @return JsonResponse
         */
        public function LEOSSCurrentView(LEOSSCurrentViewRequest $request)
        {
            try {
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
         * @return JsonResponse
         */
        public function AAListing(AAListingRequest $request)
        {
            try {
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
