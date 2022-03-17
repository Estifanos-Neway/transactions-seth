<?php
function error($message){
    echo $message;
    return null;
}
function createContext($method, $header, $content){
    $content = http_build_query($content);
    $options = array(
        'http'=>array(
          'method'=>$method,
          'header'  => $header,
          'content'=>$content
        )
      );
    return stream_context_create($options);
}
function getAuth($authUrl, $clientId, $clientSecret) {
    $content = array(
        'clientId' => $clientId,
        'clientSecret' => $clientSecret
    );
    $context = createContext("POST", NULL, $content);
    try {
        return file_get_contents($authUrl,false,$context);
    } catch (\Throwable $th) {
        return false;
    }
}

function getPaymentMethod($paymentMethodUrl, $accessToken, $currencyCode, $channelName){
    $content = array(
        'currencyCode' => $currencyCode,
        'channelName' => $channelName
    );
    $header = "accessToken:".$accessToken;
    $context = createContext("POST", $header, $content);
    try {
        return file_get_contents($paymentMethodUrl,false,$context);
    } catch (\Throwable $th) {
        return false;
    }
}

function initPayment(
    $initPaymentUrl,
    $accessToken,
    $schemaCode,
    $amount,
    $callBackUrl,
    $redirectUrl,
    $expiryInSeconds,
    $channelName,
    $clientReference
    ){
        $content = array(
            'schemaCode' => $schemaCode,
            'amount' => $amount,
            'callBackUrl' => $callBackUrl,
            'redirectUrl' => $redirectUrl,
            'expiryInSeconds' => $expiryInSeconds,
            'channelName' => $channelName,
            'clientReference' => $clientReference
        );
        // echo json_encode($content);
        $header = "accessToken:".$accessToken;
        $context = createContext("POST", $header, $content);
        try {
            return file_get_contents($initPaymentUrl,false,$context);
        } catch (\Throwable $th) {
            return false;
        }
    }
?>