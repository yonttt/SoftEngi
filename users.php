<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <title>Users</title>
    <?php
    $email = "";
    $name = "";
    if(isset($_COOKIE["login"])){
        $email = $_COOKIE["login"];
        $name = $_COOKIE["name"];
    }
    else{
        header("Location: login.php", true);
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
    ?>

<style>
.overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent overlay */
  display: block; /* Now displayed */
}
.popup-content {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background-color: white;
  padding: 20px;
  border: 1px solid #ddd;
}
</style>
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
                        <li><a href="schedule.php" onclick="clearLogin();">Schedule</a></li>
                    </ul>
                </li>
                <li><a href="authenticate.php?status=c" style="color: #b43;">Log Out</a></li>
            </ul>
        </div>
    </div>
    <div id="main">
        <div class="container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <?php
                            if($email == "admin"){echo "<th>Password</th>";}
                        ?>
                        <th>Name</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $result = $conn->query("SELECT * FROM login");

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                              echo makeRow($row["UID"], $row["email"], $row["pass"], $row["name"]);
                            }
                        } else {
                            echo "0 results";
                        }

                        function makeRow($id, $username, $password, $name){
                            $pwstr = ($_COOKIE["login"] != "admin") ? "" : "<td>".$password."</td>";
                            $htmlstr = "<tr><td>".$id."</td><td>".$username."</td>".$pwstr."<td>".$name."</td><td><button onclick=\"showEditPopup()\">Edit</button><button onclick=\"delete()\">Delete</button></td></tr>";
                            return $htmlstr;        
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="overlay" id="editPopup" style="display: none;">
  <div class="popup-content">
  <button type="button" class="exitButton" onclick="closeEditPopup()">X</button>
    <h2>Profile Edit</h2>
    <form action="Edit.php" method="post" id="editprofile">
                <label for="uid" class="cam f3">ID</label><br>
                <input type="text" name="id" id="id" placeholder="ID" class="cam f3"><br>

                <label for="password" class="cam f3">Username</label><br>
                <input type="text" name="username" id="username" placeholder="Username" class="cam f5"><br>
                
                <label for="password" class="cam f3">Password</label><br>
                <input type="text" name="password" id="password" placeholder="Password" class="cam f5"><br>

                <label for="uid" class="cam f3">Name</label><br>
                <input type="text" name="name" id="name" placeholder="Name" class="cam f3"><br>
            </form>
 <button type="button" >Submit</button>
  </div>
</div>
    <script>
        function closeSideBar(){
            document.getElementById("sideBar").style.display = "none";
        }

        function openSideBar(){
            document.getElementById("sideBar").style.display = "block";
        }
        
        function closeEditPopup(){
            document.getElementById("editPopup").style.display = "none";
        }


        function showEditPopup(){
            document.getElementById("editPopup").style.display = "block";
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
