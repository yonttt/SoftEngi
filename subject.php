<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <script src="https://kit.fontawesome.com/e21d3ce84f.js" crossorigin="anonymous"></script>
    <title>Subjects</title>
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
        $sql = "INSERT INTO subject VALUES('".$_POST["SID"]."', '".$_POST["subjectName"]."', '".$_POST["LID"]."');";
        try{
            if ($conn->query($sql)) {
                header("Refresh:0");
                exit();
            } else {
                header("location: subjects.php?error=".$conn->error."&type=ERROR: Cannot add new entry!");
                exit();
            }
        }
        catch(Exception $e){
            header("location: subjects.php?error=".$conn->error."&type=ERROR: Cannot add new entry!");
            exit();
        }
    }

    //Edit db entry and refresh
    if(isset($_POST["SID_e"])){
        $sql = "UPDATE subject SET subjectid = '".$_POST["SID_e"]."', subjectname = '".$_POST["subjectName_e"]."', lecturerid = '".$_POST["LID_e"]."' WHERE subjectid = '".$_POST["idEdit"]."';";
        try{
            if ($conn->query($sql)) {
                header("Refresh:0");
                exit();
            } else {
                header("location: subjects.php?error=".$conn->error."&type=ERROR: Cannot edit entry!");
                exit();
            }
        }
        catch(Exception $e){
            header("location: subjects.php?error=".$conn->error."&type=ERROR: Cannot edit entry!");
            exit();
        }
    }

    //Delete db entry and refresh
    if(isset($_POST["delete"])){
        if($_COOKIE["login"] != "admin"){
            header("location: subjects.php?error=You are not authorized to delete this entry.<br>Please contact an admin&type=ERROR: Unable to delete entry!");
            exit();
        }
        $sql = "DELETE FROM subject WHERE subjectid = '".$_POST["idDelete"]."'";
        try{
            if ($conn->query($sql)) {
                header("Refresh:0");
                exit();
            } else {
                header("location: subjects.php?error=".$conn->error."&type=ERROR: Unable to delete entry!");
                exit();
            }
        }
        catch(Exception $e){
            header("location: subjects.php?error=".$conn->error."&type=ERROR: Unable to delete entry!");
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
                        <li><a href="schedule.php">Schedule</a></li>
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
            <table id="tableSubjects">
                <thead>
                    <tr>
                        <th>Subject ID</th>
                        <th>Subject Name</th>
                        <th>Lecturer ID</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $result = $conn->query("SELECT * FROM subject ORDER BY subjectid");

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                              echo makeRow($row["subjectid"], $row["subjectname"], $row["lecturerid"]);
                            }
                        }

                        function makeRow($SID, $subjectName, $LID){
                            $buttons = ($_COOKIE["login"] != "admin") ? "<td><button onclick=\"openEditPopup('".$SID."', '".$subjectName."', '".$LID."')\" style=\"background-color: var(--def-color); margin-left: 30%;\"><i class=\"fa fa-pencil\" aria-hidden=\"true\"></i></button></td>" : "<td><button onclick=\"openEditPopup('".$SID."', '".$subjectName."', '".$LID."')\"><i class=\"fa fa-pencil\" aria-hidden=\"true\"></i></button><button onclick=\"openDeletePopup('".$SID."', '".$subjectName."')\"><i class=\"fa fa-trash-o\" aria-hidden=\"true\"></i></button></td>";
                            $htmlstr = "<tr><td>".$SID."</td><td>".$subjectName."</td><td>".$LID."</td>".$buttons."</tr>";
                            return $htmlstr;        
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="popup addPopup" id="addPopupSubjects" onclick="closeAddPopup();">    
        <div>
            <div class="addPopupHeader t2" onclick="event.stopPropagation();">
                <span><strong>CREATE NEW</strong></span>
                <button type="button" class="closeButton" onclick="closeAddPopup()">X</button>
            </div>
            <div class="addPopupBody t4" onclick="event.stopPropagation();">
                <form action="" method="post">
                    <label for="DID">Subject ID:</label>
                    <input type="text" name="SID" id="SID">
                    <label for="subjectName">Subject Name:</label>
                    <input type="text" name="subjectName" id="subjectName">
                    <label for="LID">Lecturer ID:</label>
                    <select name="LID" id="LID">
                        <?php
                            $sql = "SELECT UID FROM login ORDER BY UID";
                            $result = $conn->query($sql);
                            while($row = $result->fetch_assoc()){
                                echo "<option value=\"".$row["UID"]."\">".$row["UID"]."</option>";
                            }               
                        ?>
                    </select>
                    <button type="submit">ADD +</button>
                </form>
            </div>
        </div>
    </div>
    <div class="popup editPopup" id="editPopupSubjects" onclick="closeEditPopup();">    
        <div>
            <div class="editPopupHeader t2" onclick="event.stopPropagation();">
                <span><strong>EDIT ENTRY</strong></span>
                <button type="button" class="closeButton" onclick="closeEditPopup()">X</button>
            </div>
            <div class="editPopupBody t4" onclick="event.stopPropagation();">
                <form action="" method="post">
                    <label for="SID_e">Subject ID:</label>
                    <input type="text" name="SID_e" id="SID_e">
                    <label for="subjectName_e">Subject Name:</label>
                    <input type="text" name="subjectName_e" id="subjectName_e">
                    <label for="LID_e">Lecturer ID:</label>
                    <select name="LID_e" id="LID_e">
                        <?php
                            $sql = "SELECT UID FROM login ORDER BY UID";
                            $result = $conn->query($sql);
                            while($row = $result->fetch_assoc()){
                                echo "<option value=\"".$row["UID"]."\">".$row["UID"]."</option>";
                            }               
                        ?>
                    </select>
                    <input type="text" name="idEdit" id="idEdit" style="display: none;">
                    <button type="submit">EDIT</button>
                </form>
            </div>
        </div>
    </div>
    <div class="popup deletePopup" id="removePopupSubjects" onclick="closeAddPopup();">    
        <div>
            <div class="deletePopupHeader t2" onclick="event.stopPropagation();">
                <span><strong>Are you sure?</strong></span>
                <button type="button" class="closeButton"onclick="closeDeletePopup()">X</button>
            </div>
            <div class="deletePopupBody t4" id="deletePopupHeader" onclick="event.stopPropagation();">
                <form method="post">
                    <span id="deletePopupBody" class="t3"></span>
                    <input type="text" name="idDelete" id="idDelete" style="display: none;">
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
            history.pushState({}, "", "subjects.php");
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
            document.getElementById("addPopupSubjects").style.display = "block";
        }

        function closeAddPopup(){
            document.getElementById("addPopupSubjects").style.display = "none";
        }

        function openEditPopup(SID, subjectName, LID){
            closeAllPopup();
            document.getElementById("editPopupSubjects").style.display = "block";
            document.getElementById("SID_e").value = SID;
            document.getElementById("subjectName_e").value = subjectName;
            document.getElementById("LID_e").value = LID;
            document.getElementById("idEdit").value = SID;
        }

        function closeEditPopup(){
            document.getElementById("editPopupSubjects").style.display = "none";
        }

        function openDeletePopup(id, name){
            closeAllPopup();
            document.getElementById("removePopupSubjects").style.display = "block";
            document.getElementById("deletePopupBody").innerHTML = "Delete " + id + " - " + name + "?";
            document.getElementById("idDelete").value = id;
        }

        function closeDeletePopup(){
            document.getElementById("removePopupSubjects").style.display = "none";
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
