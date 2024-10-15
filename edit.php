<?php
if(isset($_POST["uid"])){
    $pass = hash("sha256", $_POST["password"]);
    $sql = "SELECT name FROM login WHERE email = \"" . $_POST["uid"] . "\" AND pass = \"" . $pass . "\";";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        setcookie("login", $_POST["uid"], time() + (86400 * 7));
        setcookie("password", $pass, time() + (86400 * 7));
        setcookie("name", $result->fetch_assoc()["name"], time() + (86400 * 7));
        header("Location: home.php", true);
        exit();
    } else {
        setcookie("login", "", time() - 86400);
        setcookie("password", "", time() - 86400);
        setcookie("name", "", time() - 86400);
        header("Location: login.php?status=f", true, 303);
        exit();
    }
    $conn->close();
}
?>
