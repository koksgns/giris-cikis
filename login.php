<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/helpers.php';
if (isset($UserID) && $UserID >0) {
    header('Location:index.php');   
}else{
    if (isset($_POST["user"]) && isset($_POST["password"])) {
        $user = isset($_POST["user"]) == true ? $_POST["user"] : false;
        $password = isset($_POST["password"]) == true ? $_POST["password"] : false;
        $remember_me = isset($_POST["remember_me"]) == true ? $_POST["remember_me"] : false;
        if ($user && $password) {
            $sorgu = $db->prepare("SELECT * FROM users WHERE user_email=:user_email AND user_password=:user_password");
            $sorgu->execute([":user_email" => $user, ":user_password" => md5($password)]);
            if ($sorgu->rowCount()) {
                $sorgu = $sorgu->fetch(PDO::FETCH_OBJ);

                echo $user_id = openssl_encrypt($sorgu->user_id, $cipher, $key, 0, $iv);
                echo $user_name = openssl_encrypt($sorgu->user_name, $cipher, $key, 0, $iv);
                if ($remember_me) {

                    setcookie("UserID", $user_id, time() + (86400 * 30), "/");
                    setcookie("UserName", $user_name, time() + (86400 * 30), "/");
                } else {

                    setcookie("UserID", $user_id, null, "/");
                    setcookie("UserName", $user_name, null, "/");
                }
                header('Location: index.php');
            } else {
                $msg = "Girdiğiniz bilgilere ait hesap bulunamadı. Lütfen tekrar deneyiniz";
            }
        } else {
            $msg = "Lütfen boş alan bırakmayınız";
        }
    }
?>
    <section class="row mt-5">
        <div class="col-md-6 m-auto text-center">
            <img src="https://olleco.com/images/olle-logo.png" alt="logo" class="my-5">
            <h4 class="title-style text-center mt-3 mb-4">Giriş Yap</h4>
            <?php if (isset($msg)) : ?>
                <div class="alert alert-danger w-75 m-auto my-5"><?= $msg ?></div>
            <?php endif; ?>
            <form method="POST" class="w-75 m-auto" id="giris_frm">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-user"></i></span>
                    <input type="email" name="user" class="form-control" placeholder="E-posta" value="<?= isset($_SESSION["email"]) ? $_SESSION["email"] : null ?>" autofocus>
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
<?php
    require_once __DIR__ . '/footer.php';
}
?>