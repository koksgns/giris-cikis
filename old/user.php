<?php

require_once __DIR__.'/config.php';
//coocike oku
$userId = openssl_decrypt($encrypted, $cipher, $key, 0, $iv);

if(!(isset($userId) && $userId>0)){
    header('Location: login.php');


}
