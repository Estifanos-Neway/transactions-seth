<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="./src/css/index.css">
</head>

<body>
    <?php
error_reporting(E_ERROR | E_PARSE);
// importing modules
require "./config.php";
require "./src/php/functions.php";

// getting incoming variables
$fullName = $_POST["full_name"];
$email = $_POST["email"];
$mt4 = $_POST["mt4"];
$country = $_POST["country"];
$amount = $_POST["amount"];
$currency = $_POST["currency"];
if(!($fullName && $email && $mt4 && $country && $amount && $currency)){
    // if one of the variables are missing
    error("Missing some of the variables.","Missing some required form fields.","./");
} else{
    // getting authorized to the VaultsPay api
    $authUrl = $baseUrl.$getAuthPath;
    $authResult = getAuth($authUrl,$clientId, $clientSecret);
    $authResult = json_decode($authResult,true);
    if(!($authResult && $authResult["message"] == "Successful.")){
        error("Can't auth.","Internal error (1)","./");
    } else{
        $accessToken =  $authResult["data"]["access_token"];
        if(!$accessToken){
            error("Can't auth(2).","Internal error (2)","./");
        } else{
           // getting allowed payment methods
           $paymentMethodUrl = $baseUrl.$allowedPaymentMethodsPath;
           $paymentMethodResult = getPaymentMethod($paymentMethodUrl, $accessToken, $currency, $channelName);
           $paymentMethodResult = json_decode($paymentMethodResult,true);
           if(!($paymentMethodResult && $paymentMethodResult["message"] == "Successful.")){
            error("Can't get payment methods.","Internal error (3)","./");
        } else{
            $schemaCode = $paymentMethodResult["data"][0]["code"];
            if(!$schemaCode){
                error("Currency not supported.","Currency not supported.","./");
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
                    $callbackUrl,
                    $redirectUrl,
                    $expiryInSeconds,
                    $channelName,
                    $email
                );
                $initPaymentResult = json_decode($initPaymentResult,true);
                if(!($initPaymentResult && $initPaymentResult["message"] == "Successful.")){
                    error("Can't init payment.","Internal error (4)","./");
                } else{
                    $paymentUrl = $initPaymentResult["data"]["paymentUrl"];
                    $paymentId = $initPaymentResult["data"]["paymentId"];
                    if(!($paymentUrl && $paymentId)){
                        error("Can't init payment(2).","Internal error (5)","./");
                    } else{
                        $transaction_id = $paymentId;
                        $full_name = $fullName;
                        $mt4_account = $mt4;
                        $addNewTransactionResult = addNewTransaction(
                            $sqlInfo,
                            $transaction_id,
                            $full_name,
                            $mt4_account,
                            $amount,
                            $email,
                            $country,
                            $currency
                        );
                        if($addNewTransactionResult !== TRUE){
                            error("transaction not saved.","Internal error (6)","./");
                        } else{
                            echo "
                            <script>
                            window.location.href = '".$paymentUrl."';
                            </script>
                            ";
                            exit;
                        }
                    }
                }
            }
        }
        }
    }
}
?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <!--
</body>
</html>