<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <script src="https://kit.fontawesome.com/e21d3ce84f.js" crossorigin="anonymous"></script>
    <title>Schedule</title>
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

    //Add data to db and refresh
    if(isset($_POST["SID"])){
        $sql = "INSERT INTO schedule VALUES('".$_POST["SID"]."', '".$_POST["day"]."', '".$_POST["time"]."', '".$_POST["class"]."');";
        try{
            if ($conn->query($sql)) {
                header("Refresh:0");
                exit();
            } else {
                header("location: schedule.php?error=".$conn->error."&type=ERROR: Cannot add new entry!");
                exit();
            }
        }
        catch(Exception $e){
            header("location: schedule.php?error=".$conn->error."&type=ERROR: Cannot add new entry!");
            exit();
        }
    }

    //Edit db entry and refresh
    if(isset($_POST["SID_e"])){
        $sql = "UPDATE schedule SET subjectid = '".$_POST["SID_e"]."', day = '".$_POST["day_e"]."', time = '".$_POST["time_e"]."', class = '".$_POST["class_e"]."' WHERE day = '".$_POST["day_init"]."' AND time = '".$_POST["time_init"]."' AND class = '".$_POST["class_init"]."';";
        try{
            if ($conn->query($sql)) {
                header("Refresh:0");
                exit();
            } else {
                header("location: schedule.php?error=".$conn->error."&type=ERROR: Cannot edit entry!");
                exit();
            }
        }
        catch(Exception $e){
            header("location: schedule.php?error=".$conn->error."&type=ERROR: Cannot edit entry!");
            exit();
        }
    }

    //Delete db entry and refresh
    if(isset($_POST["delete"])){
        $sql = "DELETE FROM schedule WHERE day = '".$_POST["day_d"]."' AND time = '".$_POST["time_d"]."' AND class = '".$_POST["class_d"]."';";
        try{
            if ($conn->query($sql)) {
                header("Refresh:0");
                exit();
            } else {
                header("location: schedule.php?error=".$conn->error."&type=ERROR: Unable to delete entry!");
                exit();
            }
        }
        catch(Exception $e){
            header("location: schedule.php?error=".$conn->error."&type=ERROR: Unable to delete entry!");
            exit();
        }
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
        <span>
            <button id="addButton" onclick="openAddPopup()">ADD +</button>
        </span>
        <div class="container">
            <table id="tableSchedule">
                <thead>
                    <tr>
                        <th>Subject ID</th>
                        <th>Day</th>
                        <th>Time</th>
                        <th>Class</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $result = $conn->query("SELECT * FROM schedule ORDER BY subjectid");

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                              echo makeRow($row["subjectid"], $row["day"], $row["time"], $row["class"]);
                            }
                        } else {
                            echo "0 results";
                        }

                        function makeRow($SID, $day, $time, $class){
                            $buttons = "<td><button onclick=\"openEditPopup('".$SID."', '".$day."', '".$time."', '".$class."')\"><i class=\"fa fa-pencil\" aria-hidden=\"true\"></i></button><button onclick=\"openDeletePopup('".$SID."', '".$day."', '".$time."', '".$class."')\"><i class=\"fa fa-trash-o\" aria-hidden=\"true\"></i></button></td>";
                            $htmlstr = "<tr><td>".$SID."</td><td>".$day."</td><td>".$time."</td><td>".$class."</td>".$buttons."</tr>";
                            return $htmlstr;        
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="popup addPopup" id="addPopupSchedule" onclick="closeAddPopup();">    
        <div>
            <div class="addPopupHeader t2" onclick="event.stopPropagation();">
                <span><strong>CREATE NEW</strong></span>
                <button type="button" class="closeButton" onclick="closeAddPopup()">X</button>
            </div>
            <div class="addPopupBody t4" onclick="event.stopPropagation();">
                <form action="" method="post">
                    <label for="SID">Subject ID:</label>
                    <select name="SID" id="SID">
                        <?php
                            $sql = "SELECT subjectid FROM subject ORDER BY subjectid";
                            $result = $conn->query($sql);
                            while($row = $result->fetch_assoc()){
                                echo "<option value=\"".$row["subjectid"]."\">".$row["subjectid"]."</option>";
                            }               
                        ?>
                    </select>
                    <label for="day">Day:</label>
                    <select name="day" id="day">
                        <option value="Monday">Monday</option>
                        <option value="Tuesday">Tuesday</option>
                        <option value="Wednesday">Wednesday</option>
                        <option value="Thursday">Thursday</option>
                        <option value="Friday">Friday</option>
                        <option value="Saturday">Saturday</option>
                        <option value="Sunday">Sunday</option>
                    </select>
                    <label for="time">Time:</label>
                    <input type="time" name="time" id="time">
                    <label for="class">Class:</label>
                    <input type="text" name="class" id="class">
                    <button type="submit">ADD +</button>
                </form>
            </div>
        </div>
    </div>
    <div class="popup editPopup" id="editPopupSchedule" onclick="closeEditPopup();">    
        <div>
            <div class="editPopupHeader t2" onclick="event.stopPropagation();">
                <span><strong>EDIT ENTRY</strong></span>
                <button type="button" class="closeButton" onclick="closeEditPopup()">X</button>
            </div>
            <div class="editPopupBody t4" onclick="event.stopPropagation();">
                <form action="" method="post">
                    <label for="SID_e">Subject ID:</label>
                    <select name="SID_e" id="SID_e">
                        <?php
                            $sql = "SELECT subjectid FROM subject ORDER BY subjectid";
                            $result = $conn->query($sql);
                            while($row = $result->fetch_assoc()){
                                echo "<option value=\"".$row["subjectid"]."\">".$row["subjectid"]."</option>";
                            }               
                        ?>
                    </select>
                    <label for="day_e">Day:</label>
                    <select name="day_e" id="day_e">
                        <option value="Monday">Monday</option>
                        <option value="Tuesday">Tuesday</option>
                        <option value="Wednesday">Wednesday</option>
                        <option value="Thursday">Thursday</option>
                        <option value="Friday">Friday</option>
                        <option value="Saturday">Saturday</option>
                        <option value="Sunday">Sunday</option>
                    </select>
                    <label for="time_e">Time:</label>
                    <input type="time" name="time_e" id="time_e">
                    <label for="class_e">Class:</label>
                    <input type="text" name="class_e" id="class_e">
                    <input type="text" name="day_init" id="day_init" style="display: none;">
                    <input type="text" name="time_init" id="time_init" style="display: none;">
                    <input type="text" name="class_init" id="class_init" style="display: none;">
                    <button type="submit">EDIT</button>
                </form>
            </div>
        </div>
    </div>
    <div class="popup deletePopup" id="removePopupSchedule" onclick="closeAddPopup();">    
        <div>
            <div class="deletePopupHeader t2" onclick="event.stopPropagation();">
                <span><strong>Are you sure?</strong></span>
                <button type="button" class="closeButton"onclick="closeDeletePopup()">X</button>
            </div>
            <div class="deletePopupBody t4" id="deletePopupHeader" onclick="event.stopPropagation();">
                <form method="post">
                    <span id="deletePopupBody" class="t3"></span>
                    <input type="text" name="day_d" id="day_d" style="display: none;">
                    <input type="text" name="time_d" id="time_d" style="display: none;">
                    <input type="text" name="class_d" id="class_d" style="display: none;">
                    <button type="button" onclick="closeDeletePopup()">No</button>
                    <input type="submit" name="delete" value="Yes" id="1">
                </form>
            </div>
        </div>
    </div>
    <div class="popup" id="dbFailedPopup">
        <div class="popupheader t3">
            <span><strong id="dbFailedHeader">ERROR: Unknown Error!</strong></span>
        </div>
        <div class="popupbody t4">
            <p id="dbFailedBody">Unknown Error!</p>
            <button type="button" id="dbFailedAcc" class="t3" onclick="hidepopup()">OK</button>
        </div>
    </div>
    <script>
        const searchParams = new URLSearchParams(window.location.search);
        if(searchParams.has("error")){
            document.getElementById("dbFailedHeader").innerHTML = searchParams.get("type");
            document.getElementById("dbFailedBody").innerHTML = searchParams.get("error");
            document.getElementById("dbFailedPopup").style.display = "block";
            history.pushState({}, "", "schedule.php");
        }
        
        function closeAllPopup(){
            closeAddPopup();
            closeEditPopup();
            closeDeletePopup();
            closeSideBar();
            hidepopup();
        }

        function hidepopup(){
            document.getElementById("dbFailedPopup").style.display = "none";
        }

        function openSideBar(){
            closeAllPopup();
            document.getElementById("sideBar").style.display = "block";
        }

        function closeSideBar(){
            document.getElementById("sideBar").style.display = "none";
        }

        function openAddPopup(){
            closeAllPopup();
            document.getElementById("addPopupSchedule").style.display = "block";
        }

        function closeAddPopup(){
            document.getElementById("addPopupSchedule").style.display = "none";
        }

        function openEditPopup(SID, day, time, _class){
            closeAllPopup();
            document.getElementById("editPopupSchedule").style.display = "block";
            document.getElementById("SID_e").value = SID;
            document.getElementById("day_e").value = day;
            document.getElementById("time_e").value = time;
            document.getElementById("class_e").value = _class;
            document.getElementById("day_init").value = day;
            document.getElementById("time_init").value = time;
            document.getElementById("class_init").value = _class;
        }

        function closeEditPopup(){
            document.getElementById("editPopupSchedule").style.display = "none";
        }

        function openDeletePopup(SID, day, time, _class){
            closeAllPopup();
            document.getElementById("removePopupSchedule").style.display = "block";
            document.getElementById("deletePopupBody").innerHTML = "Delete " + SID + ": " + day + "," + time + " @ " + _class;
            document.getElementById("day_d").value = day;
            document.getElementById("time_d").value = time;
            document.getElementById("class_d").value = _class;
        }

        function closeDeletePopup(){
            document.getElementById("removePopupSchedule").style.display = "none";
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
