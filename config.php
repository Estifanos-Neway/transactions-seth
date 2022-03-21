<?php
// api keys
$clientId = "ID-23611179"; //must-be-edit
$clientSecret ="SECRET-95D5B4CB-C405-44E7-B8A6-DB1C8234F392"; //must-be-edit

// login password
$loginPassword = "pass me";

// sql info
$sqlInfo = array(
    "serverName" => "localhost", //must-be-edit
    "database" => "transactions_db3", //must-be-edit
    "table" => "transactions_table2",
    "username" => "stiv", //must-be-edit
    "password" => "0000" //must-be-edit
);

// stor infos
$channelName = "web"; //must-be-edit

// redirect url
$rootUrl = "http://localhost/seth-vaultspay"; //must-be-edit
$redirectUrl = $rootUrl;
$callbackUrl = $rootUrl."/callback.php";

// urls and paths
$baseUrl = "https://testapi.vaultspay.com/public/external/v1"; //must-be-edit
$getAuthPath = "/merchant-auth";
$allowedPaymentMethodsPath = "/get-vaultspay-allowed-payment-methods";
$initPaymentPath = "/initialize-merchant-payment";
?>