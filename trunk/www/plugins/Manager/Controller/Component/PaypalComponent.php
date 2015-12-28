<?php

class PaypalComponent extends Component { 
    
    public $name = 'Paypal';
    public $environment = 'sandbox';
    public $api_username = 'seller_1338535791_biz_api1.gmail.com';
    public $api_password = '1338535817';
    public $api_signature = 'AgjT7z9rruMgKjyPUg.CuR.zCCUzAaHKV3WGTsjlvSRiKFLJoynVzkdJ';
    
    public function initialize(&$controller) {
        $this->controller = $controller;
    }

    public function responseDecode ($arr) {
        if (is_array($arr)) {
            foreach ($arr as $key => $value) {
                $arr[$key] = urldecode($value);
            }
        }
        return $arr;
    }

    public function payPalHttpPost($methodName_, $nvpStr_) {
        $environment = $this->environment;

        // Set up your API credentials, PayPal end point, and API version.
        $API_UserName = urlencode($this->api_username);
        $API_Password = urlencode($this->api_password);
        $API_Signature = urlencode($this->api_signature);
        $API_Endpoint = "https://api-3t.paypal.com/nvp";
        if("sandbox" === $environment || "beta-sandbox" === $environment) {
            $API_Endpoint = "https://api-3t.$environment.paypal.com/nvp";
        }
        $version = urlencode('51.0');

        // Set the curl parameters.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);

        // Turn off the server and peer verification (TrustManager Concept).
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        // Set the API operation, version, and API signature in the request.
        $nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";

        // Set the request as a POST FIELD for curl.
        curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

        // Get response from the server.
        $httpResponse = curl_exec($ch);

        if(!$httpResponse) {
            exit("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')');
        }

        // Extract the response details.
        $httpResponseAr = explode("&", $httpResponse);

        $httpParsedResponseAr = array();
        foreach ($httpResponseAr as $i => $value) {
            $tmpAr = explode("=", $value);
            if(sizeof($tmpAr) > 1) {
                $httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
            }
        }

        if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
            exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
        }

        return $httpParsedResponseAr;
    }

    public function setExpressCheckout ($amount, $returnURL, $cancelURL) {
        $environment = $this->environment;
        
        // Set request-specific fields.
        $paymentAmount = urlencode($amount);
        $currencyID = urlencode('USD');                         // or other currency code ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
        $paymentType = urlencode('Authorization');              // or 'Sale' or 'Order'
        $returnURL = urlencode($returnURL);
        $cancelURL = urlencode($cancelURL);
        
        // Add request-specific fields to the request string.
        $nvpStr = "&Amt=$paymentAmount&ReturnUrl=$returnURL&CANCELURL=$cancelURL&PAYMENTACTION=$paymentType&CURRENCYCODE=$currencyID";
        
        // Execute the API operation; see the PPHttpPost function above.
        $httpParsedResponseAr = $this->payPalHttpPost('SetExpressCheckout', $nvpStr);
        if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
            // Redirect to paypal.com.
            $token = urldecode($httpParsedResponseAr["TOKEN"]);
            $payPalURL = "https://www.paypal.com/webscr&cmd=_express-checkout&token=$token";
            if("sandbox" === $environment || "beta-sandbox" === $environment) {
                $payPalURL = "https://www.$environment.paypal.com/webscr&cmd=_express-checkout&token=$token";
            }
            $this->controller->redirect($payPalURL);
        }
        return $this->responseDecode($httpParsedResponseAr);
    }

    public function getExpressCheckoutDetail ($token) {
        // Set request-specific fields.
        $token = urlencode(htmlspecialchars($token));

        // Add request-specific fields to the request string.
        $nvpStr = "&TOKEN=$token";

        // Execute the API operation; see the PPHttpPost function above.
        $httpParsedResponseAr = $this->payPalHttpPost('GetExpressCheckoutDetails', $nvpStr);

        if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
            // Extract the response details.
            $payerID = $httpParsedResponseAr['PAYERID'];
            $street1 = $httpParsedResponseAr["SHIPTOSTREET"];
            if(array_key_exists("SHIPTOSTREET2", $httpParsedResponseAr)) {
                $street2 = $httpParsedResponseAr["SHIPTOSTREET2"];
            }
            $city_name = $httpParsedResponseAr["SHIPTOCITY"];
            $state_province = $httpParsedResponseAr["SHIPTOSTATE"];
            $postal_code = $httpParsedResponseAr["SHIPTOZIP"];
            $country_code = $httpParsedResponseAr["SHIPTOCOUNTRYCODE"];
        }
        return $this->responseDecode($httpParsedResponseAr);
    }

    public function doExpressCheckout ($payer_id, $token, $amount) {
        // Set request-specific fields.
        $payerID = urlencode($payer_id);
        $token = urlencode($token);

        $paymentType = urlencode("Authorization");          // or 'Sale' or 'Order'
        $paymentAmount = urlencode($amount);
        $currencyID = urlencode("USD");                     // or other currency code ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')

        // Add request-specific fields to the request string.
        $nvpStr = "&TOKEN=$token&PAYERID=$payerID&PAYMENTACTION=$paymentType&AMT=$paymentAmount&CURRENCYCODE=$currencyID";

        // Execute the API operation; see the PPHttpPost function above.
        $httpParsedResponseAr = $this->payPalHttpPost('DoExpressCheckoutPayment', $nvpStr);

        return $this->responseDecode($httpParsedResponseAr);
    }
    
}