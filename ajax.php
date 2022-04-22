<?php
require 'helpers.php';

if (isset($_REQUEST["request_type"])) {
    switch ($_REQUEST["request_type"]) {
        case 'login':
            $user = isset($_POST["user"]) == true ? $_POST["user"] : false;
            $password = isset($_POST["password"]) == true ? $_POST["password"] : false;
            $remember_me = isset($_POST["remember_me"]) == true ? $_POST["remember_me"] : false;
            if ($user && $password) {
                $sorgu = $db->prepare("SELECT user_id,user_name FROM users WHERE user_email=:user_email AND user_password=:user_password");
                $sorgu->execute([":user_email" => $user, ":user_password" => md5($password)]);
                if ($sorgu->rowCount()) {
                    $sorgu = $sorgu->fetch(PDO::FETCH_OBJ);
                    $_SESSION["id"] = $sorgu->user_id;
                    $_SESSION["user_name"] = $sorgu->user_name;
                    if ($remember_me) {
                        $_SESSION["email"] = $user;
                        $_SESSION["password"] = $password;
                    } else {
                        unset($_SESSION["email"]);
                        unset($_SESSION["password"]);
                    }
                    echo "1";
                } else {
                    echo "Girdiğiniz bilgilere ait hesap bulunamadı. Lütfen tekrar deneyiniz";
                }
            } else {
                echo "Lütfen boş alan bırakmayınız";
            }
            break;
        case 'veri_sil':
            if (isset($_POST["id"])) {
                $sorgu = $db->prepare("SELECT * FROM logs WHERE log_id=:log_id AND log_user_id=:log_user_id");
                $sorgu->execute([":log_id" => $_POST["id"], ":log_user_id" => $_SESSION["id"]]);
                if ($sorgu->rowCount()) {
                    $personel_sii = $db->prepare("DELETE FROM logs WHERE log_id=:log_id AND log_user_id=:log_user_id");
                    $personel_sii->execute([":log_id" => $_POST["id"], ":log_user_id" => $_SESSION["id"]]);
                    if ($personel_sii) {
                        echo "1";
                    } else {
                        echo "0";
                    }
                } else {
                    echo "0";
                }
            } else {
                echo "0";
            }

            break;

        case 'veri_ekle':
            $sorgu = $db->prepare("SELECT *FROM logs WHERE log_user_id=:id ORDER BY log_id DESC");
            $sorgu->execute([":id" => $_SESSION["id"]]);
            if ($sorgu->rowCount()) {
                $Currency = $sorgu->fetch(PDO::FETCH_ASSOC);

                if ($Currency["log_exit"] != null) {
                    $log_user_id = $_SESSION["id"];
                    $log_month = date("m");
                    $log_year = date("Y");
                    $log_day = date("d-m-Y");
                    $log_enter = date("H:i:s");

                    $ekle = $db->prepare(" INSERT INTO logs SET
                            log_user_id=:log_user_id,
                            log_month=:log_month,
                            log_year=:log_year,
                            log_day=:log_day,
                            log_enter=:log_enter
                    ");
                    $ekle->execute([
                        ":log_user_id" => $log_user_id,
                        ":log_month" => $log_month,
                        ":log_year" => $log_year,
                        ":log_day" => $log_day,
                        ":log_enter" => $log_enter
                    ]);

                    if ($ekle) {
                        echo "1";
                    } else {
                        echo "0";
                    }
                } else {

                    $cikis = date("H:i:s");
                    $fark = strtotime(date("H:i:s")) - strtotime($Currency["log_enter"]);

                    $guncelle = $db->prepare("UPDATE logs SET log_exit=:log_exit, log_total=:log_total WHERE log_id =:log_id");
                    $guncelle->execute([':log_exit' => $cikis, ':log_total' => $fark, ':log_id' =>  $Currency["log_id"]]);

                    if ($guncelle) {
                        echo "2";
                    } else {
                        echo "0";
                    }
                }
            } else {
                $log_user_id = $_SESSION["id"];
                $log_month = date("m");
                $log_year = date("Y");
                $log_day = date("d-m-Y");
                $log_enter = date("H:i:s");

                $ekle = $db->prepare(" INSERT INTO logs SET
                            log_user_id=:log_user_id,
                            log_month=:log_month,
                            log_year=:log_year,
                            log_day=:log_day,
                            log_enter=:log_enter
                    ");
                $ekle->execute([
                    ":log_user_id" => $log_user_id,
                    ":log_month" => $log_month,
                    ":log_year" => $log_year,
                    ":log_day" => $log_day,
                    ":log_enter" => $log_enter
                ]);

                if ($ekle) {
                    echo "1";
                } else {
                    echo "0";
                }
            }
            break;

        default:
            echo "Beklenmedik bir hata oluştu. Lütfen tekrar deneyiniz.";
            break;
    }
}
