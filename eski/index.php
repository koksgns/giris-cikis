<?php
date_default_timezone_set('Europe/Istanbul');
$Currency = file_get_contents("veriler.json");
if (isset($_GET["ajax"])) {
    switch ($_GET["ajax"]) {
        case 'veri_ekle':
            $Currency = json_decode($Currency, true);
            if (end($Currency)["cikis"] != null) {
                $data["user"] = "1";
                $data["ay"] = date("m");
                $data["yil"] = date("Y");
                $data["gun"] = date("d-m-Y");
                $data["giris"] = date("H:i:s");
                $data["cikis"] = null;
                $data["sure"] = null;
                array_push($Currency, $data);
                $dosya = fopen("veriler.json", "w");
                if (fwrite($dosya, json_encode($Currency))) {
                    echo "1";
                } else {
                    echo "0";
                }
                fclose($dosya);
            } else {
                $son = $Currency[count($Currency) - 1];
                $cikis = date("H:i:s");
                $fark = strtotime(date("H:i:s")) - strtotime($son["giris"]);
                $Currency[count($Currency) - 1]["cikis"] = date("H:i:s");
                $Currency[count($Currency) - 1]["sure"] = $fark;
                $dosya = fopen("veriler.json", "w");
                if (fwrite($dosya, json_encode($Currency))) {
                    echo "2";
                } else {
                    echo "0";
                }
                fclose($dosya);
            }
            break;
        case 'veri_sil':
            $Currency = json_decode($Currency, true);
            unset($Currency[$_GET["id"]]);
            $dosya = fopen("veriler.json", "w");
            if (fwrite($dosya, json_encode(array_values($Currency)))) {
                echo "1";
            } else {
                echo "0";
            }
            fclose($dosya);
            break;
    }
} else {
    $Currency = array_reverse(json_decode($Currency, true));
    array_pop($Currency);
    $toplam = count($Currency);
    $veri_data = array();
    foreach ($Currency as $key => $value) {
        if (isset($veri_data[$value["ay"]])) {
            array_push($veri_data[$value["ay"]], $value);
        } else {
            $veri_data[$value["ay"]] = [$value];
        }
    }
    function turkcetarih_formati($format, $datetime = 'now')
    {
        $z = date("$format", strtotime($datetime));
        $gun_dizi = array(
            'Monday'    => 'Pazartesi',
            'Tuesday'   => 'Salı',
            'Wednesday' => 'Çarşamba',
            'Thursday'  => 'Perşembe',
            'Friday'    => 'Cuma',
            'Saturday'  => 'Cumartesi',
            'Sunday'    => 'Pazar',
            'January'   => 'Ocak',
            'February'  => 'Şubat',
            'March'     => 'Mart',
            'April'     => 'Nisan',
            'May'       => 'Mayıs',
            'June'      => 'Haziran',
            'July'      => 'Temmuz',
            'August'    => 'Ağustos',
            'September' => 'Eylül',
            'October'   => 'Ekim',
            'November'  => 'Kasım',
            'December'  => 'Aralık',
            'Mon'       => 'Pts',
            'Tue'       => 'Sal',
            'Wed'       => 'Çar',
            'Thu'       => 'Per',
            'Fri'       => 'Cum',
            'Sat'       => 'Cts',
            'Sun'       => 'Paz',
            'Jan'       => 'Oca',
            'Feb'       => 'Şub',
            'Mar'       => 'Mar',
            'Apr'       => 'Nis',
            'Jun'       => 'Haz',
            'Jul'       => 'Tem',
            'Aug'       => 'Ağu',
            'Sep'       => 'Eyl',
            'Oct'       => 'Eki',
            'Nov'       => 'Kas',
            'Dec'       => 'Ara',
        );
        foreach ($gun_dizi as $en => $tr) {
            $z = str_replace($en, $tr, $z);
        }
        if (strpos($z, 'Mayıs') !== false && strpos($format, 'F') === false) $z = str_replace('Mayıs', 'May', $z);
        return $z;
    }
    function sure_fark_sonuc($fark, $ort = null, $yaz = false)
    {
        if ($fark == null) {
            return null;
        }
        $dakika = $fark / 60;
        $saniye_farki = floor($fark - (floor($dakika) * 60));
        $saat = $dakika / 60;
        $dakika_farki = floor($dakika - (floor($saat) * 60));
        $gun = $saat / 24;
        $saat_farki = floor($saat - (floor($gun) * 24));
        if ($ort) {
            $d = $ort / 60;
            $sa_fi = floor($ort - (floor($d) * 60));
            $s = $d / 60;
            $d_fi = floor($d - (floor($s) * 60));
            $g = $s / 24;
            $s_fi = floor($s - (floor($g) * 24));
        }
        if ($yaz) {
            return "<b>" . floor($saat) . " </b>&nbsp;Saat <b>" . sprintf("%02s", $dakika_farki) . "</b>&nbsp;Dakika  <b>" . sprintf("%02s", $saniye_farki) . " </b>&nbsp;Saniye   <b>Ortalama</b> :" . sprintf("%02s", $s_fi) . ":" . sprintf("%02s", $d_fi) . ":" . sprintf("%02s", $sa_fi);
        } else {
            return sprintf("%02s", $saat_farki) . ":" . sprintf("%02s", $dakika_farki) . ":" . sprintf("%02s",  $saniye_farki);
        }
    }
    function toplam_saat_bul($Currency)
    {
        //echo "<Pre>";
        //print_r($Currency);
        $toplam = 0;
        $sayac = 0;
        foreach ($Currency as $value) {
            if ($value["cikis"] != null) {
                $sayac++;
                $toplam += $value["sure"];
            }
        }
        if ($toplam) {
            $ort = $toplam / $sayac;
            echo sure_fark_sonuc($toplam, $ort, true);
        } else {
            echo  $toplam;
        }
    }
    function ay_bul($id)
    {
        $ay_dizi = array(
            '1'     => 'Ocak',
            '2'     => 'Şubat',
            '3'     => 'Mart',
            '4'     => 'Nisan',
            '5'     => 'Mayıs',
            '6'     => 'Haziran',
            '7'     => 'Temmuz',
            '8'     => 'Ağustos',
            '9'     => 'Eylül',
            '10'    => 'Ekim',
            '11'    => 'Kasım',
            '12'    => 'Aralık'
        );
        return $ay_dizi[$id];
    }
?>
    <!doctype html>
    <html lang="tr">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <title>Giriş-Çıkış</title>
    </head>
    <body>
        <section class="container">
            <div class="row text-center my-5">
                <div class="col-md-8 m-auto">
                    <p id="kayit_tut" class="btn btn-primary p-5">Giris - Çıkış <br>Ekle</p>
                </div>
            </div>
        </section>
        <section class="container">
            <div class="accordion" id="accordionExample">
                <?php $aylik = 0; ?>
                <?php foreach ($veri_data as $key1 => $value1) : ?>
                    <?php $gunluk = 1; ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button  <?= $aylik == 0 ? "" : "collapsed" ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $key1 ?>" aria-expanded="true" aria-controls="collapse<?= $key1 ?>">
                                <span><strong><?= ay_bul(intval($key1)) . " / " . $value1[0]["yil"]  ?></strong></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <span><?php toplam_saat_bul($value1) ?></span>
                            </button>
                        </h2>
                        <div id="collapse<?= $key1 ?>" class="accordion-collapse collapse  <?= $aylik != 0 ? "" : "show" ?>" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <th>#</th>
                                            <th>Tarih</th>
                                            <th>Giriş</th>
                                            <th>Çıkış</th>
                                            <th>Süre</th>
                                            <th class=" text-center">Sil</th>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($value1 as $key => $value) : ?>
                                                <tr>
                                                    <th class="align-middle" style="width: 10%"><?= $gunluk ?></th>
                                                    <td class="align-middle"><?= turkcetarih_formati('j F Y l', $value["gun"]) ?></td>
                                                    <td class="align-middle"><?= $value["giris"] ?></td>
                                                    <?php if ($value["cikis"]) : ?>
                                                        <td class="align-middle"><?= $value["cikis"] ?></td>
                                                        <td class="align-middle"><?= sure_fark_sonuc($value["sure"]) ?></td>
                                                    <?php else : ?>
                                                        <td colspan="2" class="align-middle text-center"><?= "<i>" . sure_fark_sonuc(strtotime(date("H:i:s")) - strtotime($value["giris"])) . "</i>" ?></td>
                                                    <?php endif; ?>
                                                    <td class="align-middle text-center"><span class="btn btn-danger p-0 px-2" onclick="sil(<?= $toplam ?>)"> Sil </span> </td>
                                                </tr>
                                                <?php $toplam--; ?>
                                                <?php $gunluk++; ?>
                                            <?php endforeach ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php $aylik++; ?>
                <?php endforeach; ?>
            </div>
        </section>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    </body>
    </html>
    <script>
        $("#kayit_tut").on("click", function() {
            $.ajax({
                url: 'index.php?ajax=veri_ekle',
                type: 'POST',
                success: function(e) {
                    if (e == "1") {
                        swal("GİRİŞ", "Giriş başarılı", "success");
                    } else if (e == "2") {
                        swal("ÇIKIŞ", "Çıkış başarılı", "success");
                    } else {
                        swal("Olmadı!", "Beklenmedik bir hata oluştu", "error");
                    }
                    setInterval(() => window.location.reload(false), 3500);
                }
            });
        });
        function sil(id) {
            $.ajax({
                url: 'index.php?ajax=veri_sil&id=' + id,
                type: 'POST',
                success: function(e) {
                    swal(e);
                    if (e == "1") {
                        swal("SİL", "Kayıt Silindi", "success");
                    } else {
                        swal("Olmadı!", "Beklenmedik bir hata oluştu", "error");
                    }
                    setInterval(() => window.location.reload(false), 3500);
                }
            });
        }
    </script>
<?php
}
?>