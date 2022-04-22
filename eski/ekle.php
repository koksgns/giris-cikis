<?php
require '../helpers.php';
echo "<pre>";
$Currency = file_get_contents("veriler.json");
$Currency = json_decode($Currency, true);

//print_r($Currency);
//die("kayıt yapıldı");
foreach ($Currency as $key => $value) {

    $log_user_id = $value["user"];
    $log_month = $value["ay"];
    $log_year = $value["yil"];
    $log_day = $value["gun"];
    $log_enter = $value["giris"];
    $log_exit = $value["cikis"];
    $log_total = $value["sure"];


    $ekle = $db->prepare(" INSERT INTO logs SET
        log_user_id=:log_user_id,
        log_month=:log_month,
        log_year=:log_year,
        log_day=:log_day,
        log_enter=:log_enter,
        log_exit=:log_exit,
        log_total=:log_total
");
    $ekle->execute([
        ":log_user_id" => $log_user_id,
        ":log_month" => $log_month,
        ":log_year" => $log_year,
        ":log_day" => $log_day,
        ":log_enter" => $log_enter,
        ":log_exit" => $log_exit,
        ":log_total" => $log_total
    ]);

    if ($ekle) {
        echo $key."basarili<br>";
    }
}
die("dur");
$ekle = $db->prepare(" INSERT INTO logs SET
log_user_id=:log_user_id,
log_month=:log_month,
log_year=:log_year,
log_day=:log_day,
log_enter=:log_enter,
log_exit=:log_exit,
log_total=:log_total
");
$ekle->execute([
    ":log_user_id" => $log_user_id,
    ":log_month" => $log_month,
    ":log_year" => $log_year,
    ":log_day" => $log_day,
    ":log_enter" => $log_enter,
    ":log_exit" => $log_exit,
    ":log_total" => $log_total
]);
