<?php
// api keys
$clientId = "ID-23611179";
$clientSecret ="SECRET-95D5B4CB-C405-44E7-B8A6-DB1C8234F392";

// sql info
$sqlInfo = array(
    "serverName" => "localhost",
    "database" => "transactions_db",
    "table" => "transactions_table",
    "username" => "stiv",
    "password" => "0000"
);

// urls and paths
$baseUrl = "https://testapi.vaultspay.com/public/external/v1";
$getAuthPath = "/merchant-auth";
$allowedPaymentMethodsPath = "/get-vaultspay-allowed-payment-methods";
$initPaymentPath = "/initialize-merchant-payment";

// stor infos
$channelName = "web";

// redirect url
$redirectUrl = "https://google.com";
?>