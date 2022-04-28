<?php
$cipher = 'AES-256-CBC';
$key = 'klhjkgfehrel6556,idfhjkl34';
$iv = "asdlkGDES32lamjlsdf5161fsddmnlda";

$UserID = isset($_COOKIE["UserID"]) ? openssl_decrypt($_COOKIE["UserID"], $cipher, $key, 0, $iv) : false;
$UserName = isset($_COOKIE["UserName"]) ? openssl_decrypt($_COOKIE["UserName"], $cipher, $key, 0, $iv) : false;
if(!$UserID && !$UserName){
   $UserID = false;
}