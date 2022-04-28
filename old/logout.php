<?php
session_start();
if(isset($_GET["r"])){
    if($_GET["r"] = "reset"){
        unset($_SESSION["email"]);
        unset($_SESSION["password"]); 
    }
}
unset($_SESSION["id"]);
unset($_SESSION["user_name"]);
header("Location:index.php");
?>