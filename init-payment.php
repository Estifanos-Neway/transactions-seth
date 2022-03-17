<?php
// importing modules
require "./config.php";
require "./src/php/functions.php";

// getting incoming variables
$fullName = $_POST[full_name];
$email = $_POST[email];
$mt4 = $_POST[mt4];
$country = $_POST[country];
$amount = $_POST[amount];
$currency = $_POST[currency];

if(!($fullName && $email && $mt4 && $country && $amount && $currency)){
    // if one of the variables are missing
    error("Missing some of the variables.");
} else{
    // getting authorized to the VaultsPay api
    $authUrl = $baseUrl.$getAuthPath;
    $authResult = getAuth($authUrl,$clientId, $clientSecret);
    $authResult = json_decode($authResult,true);
    if(!($authResult && $authResult["message"] == "Successful.")){
        error("Can't auth.");
    } else{
        $accessToken =  $authResult["data"]["access_token"];
        if(!$accessToken){
            error("Can't auth(2).");
        } else{
           // getting allowed payment methods
           $paymentMethodUrl = $baseUrl.$allowedPaymentMethodsPath;
           $paymentMethodResult = getPaymentMethod($paymentMethodUrl, $accessToken, $currency, $channelName);
           $paymentMethodResult = json_decode($paymentMethodResult,true);
           if(!($paymentMethodResult && $paymentMethodResult["message"] == "Successful.")){
            error("Can't get payment methods.");
        } else{
            $schemaCode = $paymentMethodResult["data"][0]["code"];
            if(!$schemaCode){
                error("Currency not supported.");
            } else{
                // initiating payment
                $initPaymentUrl = $baseUrl.$initPaymentPath;
                $callBackUrl = "";
                $expiryInSeconds = 5*60;

                $initPaymentResult = initPayment(
                    $initPaymentUrl,
                    $accessToken,
                    $schemaCode,
                    $amount,
                    $callBackUrl,
                    $redirectUrl,
                    $expiryInSeconds,
                    $channelName,
                    $email
                );
                $initPaymentResult = json_decode($initPaymentResult,true);
                if(!($initPaymentResult && $initPaymentResult["message"] == "Successful.")){
                    error("Can't init payment.");
                } else{
                    $paymentUrl = $initPaymentResult["data"]["paymentUrl"];
                    $paymentId = $initPaymentResult["data"]["paymentId"];
                    if(!($paymentUrl && $paymentId)){
                        error("Can't init payment(2).");
                    } else{
                        // echo $paymentUrl;
                        header("Location: ".$paymentUrl);
                        exit;
                    }
                }
            }
        }
        }
    }
}
?>