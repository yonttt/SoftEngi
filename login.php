<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scame=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <script src="https://kit.fontawesome.com/e21d3ce84f.js" crossorigin="anonymous"></script>
    <title>Login | PUIS</title>
</head>
<body>
    <div id="bodyleft">
        <div id="leftshading">
            <div class="text">
                <strong class="t1 cam">PRESIDENT UNIVERSITY</strong><br>
                <strong><i class="t2 cam">Where tomorrow leader come together</i></strong><br><br>
                <i class="t5 cam">Passion · Responsibility · Entrepreneurial spirit · Sincerity · 
                Inclusiveness · Dedication · Excellence · Nationalism · Trendsetter</i><br><br><br>
                <p class="t3 cam" id="loginp">President University (PresUniv) is one of the best private universities 
                in Indonesia (accredited A). President University offers strong international learning and 
                research environment. The lectures at President University are carried out in English. The 
                number of international students at President University is one of the highest among all 
                universities across Indonesia. President University is located in one of the largest industrial 
                estates in Southeast Asia (Jababeka Industrial Estate) where various companies from many countries 
                establish and run their business.</p><br>
                <a href="https://president.ac.id/" target="_blank" class="links t4 cam"><i class="fa-solid fa-globe"></i> Website</a>
            </div>
        </div>
    </div>
    <div id="bodyright">
        <div id=loginbody>
            <div id="loginlogo">
                <img id="imglogo" src="images/logo-presuniv.png" title="President University" alt="logo">
            </div><br>
            <span class="cam t2">PUIS</span><br><br>
            <form action="authenticate.php" method="post" id="loginform">
                <label for="uid" class="cam f3">User ID</label><br>
                <input type="text" name="uid" id="uid" placeholder="Email" class="cam f3" required><br>
                <label for="password" class="cam f3">Password</label><br>
                <input type="password" name="password" id="password" placeholder="Password" class="cam f5" required><br>
                <span id="spanparent">
                    <input type="checkbox" name="parent" id="parent">
                    <div class="checkbox" onclick="document.getElementById('parent').checked = !document.getElementById('parent').checked;">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <label for="parent" class="cam f3">Login as Parent?</label><br>
                </span><br>
                <span id=moreform>
                    <a href="documents\20200527-PUIS-troubleshooting-login.pdf" class="bl t5">Can't Login?</a>
                    <span id="forgot" class="f5" onclick="alert('Uh Oh! Good Luck!')">Forgot Password?</span>
                </span><br>
                <button type="submit" id="loginbutton" class="cam">Login</button>
            </form>
        </div>
        <div id="loginfooter">
            <ul id="socs">
                <li>
                    <a href="https://www.facebook.com/president.university" target="_blank">
                        <button type="button" class="social" id="fb"><i class="fab fa-facebook-f"></i></button>
                    </a>
                </li>
                <li>
                    <a href="https://www.instagram.com/president_university" target="_blank">
                        <button type="button" class="social" id="ig"><i class="fa-brands fa-instagram"></i></button>
                    </a>
                </li>
                <li>
                    <a href="https://www.youtube.com/channel/UCpLRrcBN_1UAfrJgIFpM2WA" target="_blank">
                        <button type="button" class="social" id="yt"><i class="fa-brands fa-youtube"></i></button>
                    </a>
                </li>
                <li>    
                    <a href="https://www.linkedin.com/school/president-university/" target="_blank">
                        <button type="button" class="social" id="li"><i class="fa-brands fa-linkedin-in"></i></button>
                    </a>
                </li>
            </ul>
            <div id="loginsignature" class="t4">Software Development Team</div>
        </div>
    </div>
    <div class="popup" id="loginPopup">
        <div class="popupheader t3">
            <span><strong>ERROR! Login Failed</strong></span>
        </div>
        <div class="popupbody t4">
            <p>Unable to login! <br><br> Please ensure you have entered the correct username and password!</p>
            <button type="button" id="loginFailedAcc" class="t3" onclick="hidepopup()">OK</button>
        </div>
    </div>
    <script>
        const searchParams = new URLSearchParams(window.location.search);
        if(searchParams.has('status') && searchParams.get("status") == "f"){
            document.getElementById("loginPopup").style.display = "block";
        }

        function hidepopup(){
            document.getElementById("loginPopup").style.display = "none";
        }
    </script>
</body>
</html>