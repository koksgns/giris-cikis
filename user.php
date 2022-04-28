<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/helpers.php';


if (isset($UserID) && $UserID > 0) {


    require_once __DIR__ . '/header.php';
    if (isset($_POST[""])) {
        # code...
    }

?>
    <nav class="navbar navbar-expand-lg navbar-light " style="background-color: #e3f2fd;">
        <div class="container d-flex justify-content-between">
            <a class="navbar-brand" href="index.php">Ana Sayfa</a>
            <a class="nav-link " href="user.php">Profil</a>
            <a class="nav-link " href="logout.php">Çıkış Yap</a>
        </div>
    </nav>
    <section class="row mt-5">
        <div class="col-md-6 m-auto text-center">
            <img src="https://olleco.com/images/olle-logo.png" alt="logo" class="my-5">
            <h1 class="mt-3 mb-5"><?= $UserName ?></h1>
            <form action="" class="w-75 m-auto" id="passwordChange" onsubmit="return false">
                <h5 class="title-style text-center mt-3 mb-4">Parola Değiştir</h5>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-lock"></i></span>
                    <input type="password" name="new_password" class="form-control" placeholder="Yeni Parola" minlength="6">
                </div>
                <div class="input-group mb-5">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-lock"></i></span>
                    <input type="password" name="new_password_again" class="form-control" placeholder="Yeni Parola tekrar" minlength="6">
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-lock"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="Parola" minlength="6">
                </div>
                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-primary px-5">Giriş Yap</button>
                </div>
            </form>
        </div>
    </section>

<?php
    require_once __DIR__ . '/footer.php';
} else {
    header('Location: logout.php');
}
?>
<script>
    $("#passwordChange").on("submit", function(event) {
        var frm_value = $(this).serialize();
        //swal(frm_value)
        $.ajax({
            url: "ajax.php?request_type=passwordChange",
            type: 'POST',
            data: frm_value,
            success: function(e) {
                swal(e)
                if (e == "Parolanız başarıyla değişti. Lütfen tekrar giriş yapınız") {
                    setInterval(() => window.location.href = "logout.php?r=reset", 3500);
                }
            }
        });
    });
</script>