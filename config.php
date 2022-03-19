<?php
// api keys
$clientId = "ID-23611179";
$clientSecret ="SECRET-95D5B4CB-C405-44E7-B8A6-DB1C8234F392";

// login password
$loginPassword = "pass me";
// sql info
$sqlInfo = array(
    "serverName" => "localhost",
    "database" => "transactions_db3",
    "table" => "transactions_table2",
    "username" => "stiv",
    "password" => "0000"
);

// stor infos
$channelName = "web";

// redirect url
$redirectUrl = "https://mrgideon.000webhostapp.com/seth-payment";
$callbackUrl = "https://mrgideon.000webhostapp.com/seth-payment/callback.php";

// urls and paths
$baseUrl = "https://testapi.vaultspay.com/public/external/v1";
$getAuthPath = "/merchant-auth";
$allowedPaymentMethodsPath = "/get-vaultspay-allowed-payment-methods";
$initPaymentPath = "/initialize-merchant-payment";
?>