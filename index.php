<?php
require_once 'helpers.php';
?>
<!doctype html>
<html lang="tr">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <title>Giriş-Çıkış</title>
</head>

<body>
    <?php if (isset($_SESSION["id"])) : ?>

        <nav class="navbar navbar-expand-lg navbar-light " style="background-color: #e3f2fd;">
            <div class="container d-flex justify-content-between">
                <a class="navbar-brand" href="#">Giriş - Çıkış</a>
                <p class="mt-2 p-0"><b><?= $_SESSION["user_name"] ?></b></p>
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
        $sorgu->execute([":id" => $_SESSION["id"]]);
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
    <?php else : ?>
        <section class="row mt-5">
            <div class="col-md-6 m-auto text-center">
                <img src="https://olleco.com/images/olle-logo.png" alt="logo" class="my-5">
                <h4 class="title-style text-center mt-3 mb-4">Giriş Yap</h4>
                <form action="" method="get" class="w-75 m-auto" onsubmit="return false">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1"><i class="fa fa-user"></i></span>
                        <input type="email" name="user" class="form-control" placeholder="E-posta" value="<?= isset($_SESSION["email"]) ? $_SESSION["email"] : null ?>">
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1"><i class="fa fa-lock"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="Parola" minlength="6" value="<?= isset($_SESSION["password"]) ? $_SESSION["password"] : null ?>">
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="mb-3 form-check">
                            <input type="checkbox" name="remember_me" class="form-check-input" id="remember_me">
                            <label class="form-check-label" for="remember_me">Beni Hatırla</label>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <button type="submit" class="btn btn-primary px-5">Giriş Yap</button>
                    </div>
                </form>
            </div>
        </section>
    <?php endif; ?>
    <section class="container">
        <div class="row text-center my-5">
            <div class="col-md-8 m-auto">
            </div>
        </div>
    </section>
    <section class="container">
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script>
        $("form").on("submit", function(event) {
            var frm_value = $(this).serialize();
            $.ajax({
                url: "ajax.php?request_type=login",
                type: 'POST',
                data: frm_value,
                success: function(e) {
                    if (e == "1") {
                        location.reload();
                    } else {
                        swal(e);
                    }
                }
            });
        });

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
                        swal("Olmadı!", "Beklenmedik bir hata oluştu", "error");
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
</body>

</html>