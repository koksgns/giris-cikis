<?php
session_start();
date_default_timezone_set('Europe/Istanbul');

try {
    $db = new PDO('mysql:host=localhost;dbname=giris_cikis', 'root', '');
} catch (PDOException $e) {
    die("Database bağlantı hatası");
}
/*
print_r($_SESSION);
session_destroy();
echo "<pre>";
$sorgu = $db->prepare("SELECT * FROM users");
$sorgu->execute();
if ($sorgu->rowCount()) {
    $sorgu = $sorgu->fetchAll(PDO::FETCH_OBJ);
    print_r($sorgu);
}
*/

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
    $kalan = 648000 - $fark;
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
        $k_sonuc =  $kalan >= 0 ? fark($kalan) : "Tamamlanmıştır";
        return "<b>Toplam: </b>" .sprintf("%02s", floor($saat) ). " : " .
            sprintf("%02s", $dakika_farki) . " : " .
            sprintf("%02s", $saniye_farki) . " &nbsp;  
            <b>Ortalama:</b> " . sprintf("%02s", $s_fi) . " : " . sprintf("%02s", $d_fi) . " : " . sprintf("%02s", $sa_fi) . "&nbsp;
            <b>Kalan:</b> " . $k_sonuc;
    } else {
        return sprintf("%02s", $saat_farki) . ":" . sprintf("%02s", $dakika_farki) . ":" . sprintf("%02s",  $saniye_farki);
    }
}
function fark($fark)
{
    $dakika = $fark / 60;
    $saniye_farki = floor($fark - (floor($dakika) * 60));
    $saat = $dakika / 60;
    $dakika_farki = floor($dakika - (floor($saat) * 60));

    return sprintf("%02s",floor($saat)) . ' : ' . sprintf("%02s",floor($dakika_farki)) . ' : ' . sprintf("%02s",floor($saniye_farki));
}
function toplam_saat_bul($Currency)
{
    //echo "<Pre>";
    //print_r($Currency);
    $toplam = 0;
    $sayac = 0;
    foreach ($Currency as $value) {
        if ($value["log_exit"] != null) {
            $sayac++;
            $toplam += $value["log_total"];
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
//echo $zaman3 = strtotime('+180 hours') - (time()); //1 saniye, 1 dakika, 1 saat, 1 gün, 1 hafta, 1 ay, 1 yıl ekle

//die();
//echo sure_fark_sonuc("648000","637200","637200");
//echo "<br>ad". $baslangicSaati = strtotime("24:00:00");



//die();
