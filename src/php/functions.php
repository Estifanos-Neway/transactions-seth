<?php
function error($reason, $message="Internal error.",$retryPath = "/"){
    echo
    '<div class="card">
    <div class="card-body">
        <h5 class="card-title text-danger">Request failed!</h5>
        <p class="card-text">
            Sorry, your request is not served.
            <br>
            Reason: '.$message.'
        </p>
        <button onclick="history.back()" class="btn btn-secondary btn-sm ps-3 pe-3">< Retry</button>
    </div>
</div>';
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
function getConnection($sqlInfo){
    $conn = new mysqli(
            $sqlInfo["serverName"],
            $sqlInfo["username"],
            $sqlInfo["password"],
            $sqlInfo["database"]
        );
        if ($conn->connect_error) {
            return FALSE;
        } else {
            return $conn;
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
        $conn = getConnection($sqlInfo);
        if (!$conn) {
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
function getTransactions($sqlInfo,$condition=NULL){
    $conn = getConnection($sqlInfo);
    if (!$conn) {
        return FALSE;
    }
    if($condition) {
        if (strpos($condition,">=") !== false){
            $comparator = ">=";
        } elseif(strpos($condition,"<=") !== false){
            $comparator = "<=";
        } elseif(strpos($condition,">") !== false){
            $comparator = ">";
        } elseif(strpos($condition,"<") !== false){
            $comparator = "<";
        } elseif(strpos($condition,"=") !== false){
            $comparator = "=";
        }
        if($comparator){
            $condition = str_replace($comparator,$comparator."'",$condition)."'";
        } else {
            $condition = "CONCAT(
                id, '',
                time, '' ,
                transaction_id, '', 
                full_name, '', 
                mt4_account, '', 
                amount, '',
                email, '',
                country, '',
                currency, '',
                success
                ) LIKE '%".$condition."%'";
        }
    }
    $sql = "SELECT * FROM ".$sqlInfo["table"].($condition?(" where ".strtolower($condition)):"");
    $result = $conn->query($sql);
    $result_array = [];
    while($row = $result->fetch_assoc()) {
        $result_array[] = $row;
    }
    $conn->close();
    return $result_array;
}

function updateSuccess($sqlInfo,$transaction_id,$success){
    try {
        $conn = getConnection($sqlInfo);
        if (!$conn) {
            return FALSE;
        }
    } catch (\Throwable $th) {
        return $th;
    }
    $sql = "UPDATE "
    .$sqlInfo["table"]
    ." SET success=".$success
    ." WHERE transaction_id='".$transaction_id."'";
    try {
        $result = $conn->query($sql);
        return $result === TRUE? TRUE :FALSE;
    } catch (\Throwable $th) {
        return $th;
    } finally{
        $conn->close();
    }
}
function hashPassword($password){
    return hash(
        "sha256",
        $password
    );
}
?>