<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <title>Home</title>
    <?php
        $servername = "localhost";
        $username = "localhost";
        $password = "";
        
        // Create connection
        $conn = new mysqli($servername, $username, $password, "puis");
        
        // Check connection
        if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
        }
    
        //Authentification
        $email = "";
        $name = "";
        if(isset($_COOKIE["login"])){
            $sql = "SELECT name FROM login WHERE username = \"" . $_COOKIE["login"] . "\" AND pass = \"" . $_COOKIE["password"] . "\";";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                setcookie("login", $_COOKIE["login"], time() + (86400 * 7));
                setcookie("password", $_COOKIE["password"], time() + (86400 * 7));
                setcookie("name", $result->fetch_assoc()["name"], time() + (86400 * 7));
                $email = $_COOKIE["login"];
                $name = $_COOKIE["name"];
            } else {
                setcookie("login", "", time() - 86400);
                setcookie("password", "", time() - 86400);
                setcookie("name", "", time() - 86400);
                header("Location: login.php", true, 303);
                exit();
            }
        }
        else{
            header("Location: login.php", true);
            exit();
        }
    ?>
</head>
<body class="block">
    <header>
        <div id="header">
            <button id="hamburger" title="Main Menu" type="button" onclick="openSideBar()"><span>≡</span></button>
            <strong id="h1">
                PUIS ®
            </strong>
            <span id="h2">
                <span id="h2email">
                    <?php echo $email?>
                </span>|
                <span id="h2name">
                    <?php echo $name?>
                </span>
            </span>
            <div id="logo">
                <img id="imglogo" src="images/logo-presuniv.png" title="President University" alt="logo">
            </div>
        </div>
    </header>
    <div id="main">
        <h1>Lorem Ipsum</h1><br>
        <h2>Lorem ipsum dolor sit amet</h2><br><br>
        <p style="width: 30vw; text-align: justify;">Lorem ipsum dolor sit amet, consectetur adipiscing elit, 
            sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis 
            nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure 
            dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur 
            sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
        </p>
    </div>
    <div class="popup" id="sideBar" onclick="closeSideBar();">    
        <div class="popupheader t2" onclick="event.stopPropagation();">
            <span><strong><?php echo $name ?></strong></span>
            <button type="button" class="closeButton" onclick="closeSideBar()">X</button>
        </div>
        <div class="popupbody t4" onclick="event.stopPropagation();">
            <ul>
                <li><a href="home.php">Home</a></li>
                <li onclick="toggleListDatabase()">
                    <a>Database</a>
                </li>
                <li class="hidden" id="listDatabase">
                    <ul>
                        <li><a href="users.php">Users</a></li>
                        <li><a href="subjects.php">Subjects</a></li>
                        <li><a href="schedule.php">Schedule</a></li>
                    </ul>
                </li>
                <li><a href="authenticate.php?status=c" style="color: #b43;">Log Out</a></li>
            </ul>
        </div>
    </div>
    <script>
        function closeSideBar(){
            document.getElementById("sideBar").style.display = "none";
        }

        function openSideBar(){
            document.getElementById("sideBar").style.display = "block";
        }

        function toggleListDatabase(){
            if(document.getElementById("listDatabase").style.display == "block"){
                document.getElementById("listDatabase").style.display = "none";
            }
            else{
                document.getElementById("listDatabase").style.display = "block";
            }
        }
    </script>
</body>
</html>

<?php

?>