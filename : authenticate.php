<?php
if(isset($_GET["status"]) && $_GET["status"] == "c"){
setcookie("login", "", time() - 86400);
setcookie("password", "", time() - 86400);
setcookie("name", "", time() - 86400);
header("Location: login.php", true, 303);
exit();
}
$servername = "localhost";
$username = "localhost";
$password = "";
// Create connection
$conn = new mysqli($servername, $username, $password, "puis");
// Check connection
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}
if(isset($_POST["uid"])){
$pass = hash("sha256", $_POST["password"]);
$sql = "SELECT name FROM login WHERE username = \"" . $_POST["uid"] .
"\" AND pass = \"" . $pass . "\";";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
setcookie("login", $_POST["uid"], time() + (86400 * 7));
setcookie("password", $pass, time() + (86400 * 7));
setcookie("name", $result->fetch_assoc()["name"], time() + (86400
* 7));
header("Location: home.php", true);
exit();
} else {
setcookie("login", "", time() - 86400);
setcookie("password", "", time() - 86400);
setcookie("name", "", time() - 86400);
header("Location: login.php?status=f", true, 303);
Page 25 of 80
exit();
}
$conn->close();
}
if(isset($_COOKIE["login"])){
$sql = "SELECT username, pass FROM login WHERE username = \"" .
$_COOKIE["login"] . "\" AND pass = \"" . $_COOKIE["password"] . "\";";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
setcookie("login", $_COOKIE["login"], time() + (86400 * 7));
setcookie("password", $_COOKIE["password"], time() + (86400 * 7));
setcookie("name", $_COOKIE["name"], time() + (86400 * 7));
header("Location: home.php", true);
exit();
} else {
header("Location: login.php?status=f", true, 303);
exit();
}
$conn->close();
}
header("Location: login.php", true, 303);
exit();
?>
