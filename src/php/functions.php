<?php
function error($reason, $message="Internal error."){
    header("Location: ./failed.php?m=".$message);
    exit;
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
        $header = "accessToken:".$accessToken;
        $context = createContext("POST", $header, $content);
        try {
            return file_get_contents($initPaymentUrl,false,$context);
        } catch (\Throwable $th) {
            return false;
        }
    }
function addNewTransaction(
    $sqlInfo,
    $transaction_id,
    $full_name,
    $mt4_account,
    $amount,
    $email,
    $country,
    $currency
){
    try {
        $conn = new mysqli(
            $sqlInfo["serverName"],
            $sqlInfo["username"],
            $sqlInfo["password"],
            $sqlInfo["database"]
        );
        if ($conn->connect_error) {
            return FALSE;
        }
    } catch (\Throwable $th) {
        return $th;
    }
    $sql = "INSERT INTO "
    .$sqlInfo["table"]
    ." (transaction_id,full_name,mt4_account,amount,email,country,currency) VALUES ('"
    .$transaction_id."','"
    .$full_name."','"
    .$mt4_account."','"
    .$amount."','"
    .$email."','"
    .$country."','"
    .$currency."')";
    try {
        $result = $conn->query($sql);
        return $result === TRUE? TRUE :FALSE;
    } catch (\Throwable $th) {
        return $th;
    } finally{
        $conn->close();
    }
}
?>