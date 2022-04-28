<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/helpers.php';
if (!$UserID) {
    header('Location: login.php');
}


require_once __DIR__ . '/header.php';
?>
<nav class="navbar navbar-expand-lg navbar-light " style="background-color: #e3f2fd;">
    <div class="container d-flex justify-content-between">
        <a class="navbar-brand" href="index.php">Ana Sayfa</a>
        <a class="nav-link " href="user.php">Profil</a>
        <a class="nav-link " href="logout.php">Çıkış Yap</a>
    </div>
</nav>
<section class="container">
    <div class="row text-center my-5">
        <div class="col-md-8 m-auto">
            <p id="kayit_tut" class="btn btn-primary p-5">Giris - Çıkış <br>Ekle</p>
        </div>
    </div>
</section>
<?php
$sorgu = $db->prepare("SELECT *FROM logs WHERE log_user_id=:id ORDER BY log_id DESC");
$sorgu->execute([":id" => $UserID]);
if ($sorgu->rowCount()) :
    $Currency = $sorgu->fetchAll(PDO::FETCH_ASSOC);
    $veri_data = array();
    foreach ($Currency as $key => $value) {
        if (isset($veri_data[$value["log_month"]])) {
            array_push($veri_data[$value["log_month"]], $value);
        } else {
            $veri_data[$value["log_month"]] = [$value];
        }
    }
?>
    <section class="container">
        <div class="accordion" id="accordionExample">
            <?php $aylik = 0; ?>
            <?php foreach ($veri_data as $key1 => $value1) : ?>
                <?php $gunluk = 1; ?>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button  <?= $aylik == 0 ? "" : "collapsed" ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $key1 ?>" aria-expanded="true" aria-controls="collapse<?= $key1 ?>">
                            <span><strong><?= ay_bul(intval($key1)) . " / " . $value1[0]["log_year"]  ?></strong></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <span><?php toplam_saat_bul($value1) ?></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
                                                <td class="align-middle"><?= turkcetarih_formati('j F Y l', $value["log_day"]) ?></td>
                                                <td class="align-middle"><?= $value["log_enter"] ?></td>
                                                <?php if ($value["log_exit"]) : ?>
                                                    <td class="align-middle"><?= $value["log_exit"] ?></td>
                                                    <td class="align-middle"><?= sure_fark_sonuc($value["log_total"]) ?></td>
                                                <?php else : ?>
                                                    <td colspan="2" class="align-middle text-center"><?= "<i>" . sure_fark_sonuc(strtotime(date("H:i:s")) - strtotime($value["log_enter"])) . "</i>" ?></td>
                                                <?php endif; ?>
                                                <td class="align-middle text-center"><span class="btn btn-danger p-0 px-2" onclick="sil(<?= $value['log_id'] ?>)"> Sil </span> </td>
                                            </tr>
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
<?php else : ?>
    <div class="alert alert-info container text-center"><b>Kayıt bulunmamaktadır!</b></div>
<?php endif; ?>

<?php require_once __DIR__ . '/footer.php'; ?>


<script>
    $("#kayit_tut").on("click", function() {
        $.ajax({
            url: 'ajax.php?request_type=veri_ekle',
            type: 'POST',
            success: function(e) {

                if (e == "1") {
                    swal("GİRİŞ", "Giriş başarılı", "success");
                } else if (e == "2") {
                    swal("ÇIKIŞ", "Çıkış başarılı", "success");
                } else {
                    swal(e);
                }
                setInterval(() => window.location.reload(false), 3500);
            }
        });
    });

    function sil(id) {
        if (confirm('Veri kaydını silmek istediğinizden emin misiniz?')) {
            $.ajax({
                url: 'ajax.php?request_type=veri_sil',
                type: 'POST',
                data: {
                    id: id
                },
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
    }
</script>