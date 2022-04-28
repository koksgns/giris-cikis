<?php
    setcookie('UserID', null, -1, '/');
    setcookie('UserName', null, -1, '/');
    
header('Location:index.php');