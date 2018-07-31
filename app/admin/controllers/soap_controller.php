<?php 
    class SoapController extends Controller
    {
        public $uses = array();

        public $components = array(
            'Soap' => array(
                'wsdl' => 'wsdl', //the file name in the view folder
                'action' => 'call', //soap service method / handler
            ),
        );

        /**
         * A soap call 'soap_wsdl' is handled here.
         */
        public function soap_wsdl()
        {
            //will be handled by SoapComponent
        }

        /**
         * A soap call 'soap_service' is handled here.
         */
        public function soap_call()
        {
            //will be handled by SoapComponent
        }
    }
