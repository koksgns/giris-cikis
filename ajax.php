<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/helpers.php';

if (isset($UserID) && $UserID >0) {

    if (isset($_REQUEST["request_type"])) {
        switch ($_REQUEST["request_type"]) {

            case 'veri_sil':
                if (isset($_POST["id"])) {
                    $sorgu = $db->prepare("SELECT * FROM logs WHERE log_id=:log_id AND log_user_id=:log_user_id");
                    $sorgu->execute([":log_id" => $_POST["id"], ":log_user_id" => $UserID]);
                    if ($sorgu->rowCount()) {
                        $personel_sii = $db->prepare("DELETE FROM logs WHERE log_id=:log_id AND log_user_id=:log_user_id");
                        $personel_sii->execute([":log_id" => $_POST["id"], ":log_user_id" => $UserID]);
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
                $sorgu->execute([":id" => $UserID]);
                if ($sorgu->rowCount()) {
                    $Currency = $sorgu->fetch(PDO::FETCH_ASSOC);

                    if ($Currency["log_exit"] != null) {
                        $log_user_id = $UserID;
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
                    $log_user_id = $UserID;
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

            case 'passwordChange':
                $new_password = isset($_POST["new_password"]) && ($_POST["new_password"] != null) == true ? md5($_POST["new_password"]) : false;
                $new_password_again = isset($_POST["new_password_again"]) && ($_POST["new_password_again"] != null) == true ? md5($_POST["new_password_again"]) : false;
                $password = isset($_POST["password"]) && ($_POST["password"] != null) == true ? md5($_POST["password"]) : false;
                if ($new_password && $new_password_again && $password) {
                    if ($new_password == $new_password_again) {
                        $sorgu = $db->prepare("SELECT user_id,user_name FROM users WHERE user_id=:user_id AND user_password=:user_password");
                        $sorgu->execute([":user_id" => $UserID, ":user_password" => $password]);
                        if ($sorgu->rowCount()) {
                            $guncelle = $db->prepare("UPDATE users SET user_password=:user_password WHERE user_id =:user_id");
                            $guncelle->execute([':user_password' => $new_password,  ':user_id' =>  $UserID]);
                            if ($guncelle) {
                                echo "Parolanız başarıyla değişti. Lütfen tekrar giriş yapınız";
                            } else {
                                echo "Beklenmedik bir hata oluştu";
                            }
                        } else {
                            echo "Mevcut parolanız hatalı girdiniz. Lütfen tekrar deneyiniz";
                        }
                    } else {
                        echo "Yeni parola ve yeni parola tekrarı aynı değildir.";
                    }
                } else {
                    echo "Lütfen boş alan bırakmayınız";
                }
                break;
            case 'new':
                if (isset($_GET["mail"]) && isset($_GET["name"])) {
                    $sorgu = $db->prepare("SELECT *FROM users WHERE user_email=:user_email ");
                    $sorgu->execute([":user_email" => $_GET["mail"]]);
                    if (!$sorgu->rowCount()) {
                        $ekle = $db->prepare(" INSERT INTO users SET
                        user_email=:user_email,
                        user_password=:user_password,
                        user_name=:user_name
                        ");
                        $ekle->execute([
                            ":user_email" => $_GET["mail"],
                            ":user_password" => md5("123123"),
                            ":user_name" => $_GET["name"]
                        ]);

                        if ($ekle) {
                            echo "Yeni kişi eklendi";
                        } else {
                            echo "Beklenmedik bir hata oluştu. Lütfen tekrar deneyiniz.";
                        }
                    } else {
                        echo "E-posta adresi zaten kayıtlı";
                    }
                } else {
                    echo "İstenilen parametreler gelmedi";
                }
                break;
            default:
                echo "Beklenmedik bir hata oluştu. Lütfen tekrar deneyiniz.";
                break;
        }
    }
}else{
    echo "Lütfen Giriş Yapın<br>";
    echo '<a href="login.php">Giriş Yap</a>';
}