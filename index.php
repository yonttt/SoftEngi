<?php
if(isset($_COOKIE["login"])){
    echo "<script>window.location.replace('http://localhost/Database/authenticate.php');</script>";
    exit();
}
else{
    echo "<script>window.location.replace('http://localhost/Database/login.php');</script>";
    exit();
}
?>