<?php
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    session_start();
    require "private/function.php";
    if(isset($_POST['submit-signup'])&&$_SERVER['REQUEST_METHOD']==="POST"){
        if(isset($_POST['username-signup'])&&isset($_POST['password-signup'])&&isset($_SESSION['csrfToken'])&&$_SESSION['csrfToken']==htmlspecialchars($_POST['csrftoken'])&&!empty($_POST['username-signup'])&&!empty($_POST['password-signup'])){
            $username = htmlspecialchars($_POST['username-signup']);
            $password = md5(htmlspecialchars($_POST['password-signup']));
            $accounttype = htmlspecialchars($_POST['accounttype']);
            if($accounttype=="family"||$accounttype=="individual"){
                if($accounttype=="family"){
                    $accounttype = 0;
                }else if($accounttype=="individual"){
                    $accounttype = 1;
                }
                $query = "select username from users where username=?;";
                $stmt = mysqli_stmt_init($con);
                if(!mysqli_stmt_prepare($stmt, $query)){
                    header("Location: index.php");
                    die("Something Went Wrong! Try Again.");
                }
                mysqli_stmt_bind_param($stmt, "s", $username);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt)<1) {
                    $query = "insert into users(username, password, account_type, account_address,img_link) values(?,?,?,?,?);";
                    $stmt = mysqli_stmt_init($con);
                    if(!mysqli_stmt_prepare($stmt, $query)){
                        header("Location: index.php");
                        die("Something Went Wrong! Try Again.");
                    }
                    $userid = random_id_gen();
                    $img = 'default.jpg';
                    mysqli_stmt_bind_param($stmt, "ssiss", $username, $password,$accounttype,$userid, $img);
                    mysqli_stmt_execute($stmt);
                }else{
                    $error1 = "User already exists";
                }


            }else{
                header("Location: index.php");
                die("Something Went Wrong! Try Again.");
                
            }
            
        }else{
            header("Location: index.php");
            die("Something Went Wrong! Try Again.");
            
        }
    }

    if(isset($_POST['submit'])&&$_SERVER['REQUEST_METHOD']==="POST"){
        if(isset($_POST['username'])&&isset($_POST['password'])&&isset($_SESSION['csrfToken'])&&$_SESSION['csrfToken']==htmlspecialchars($_POST['csrftoken'])&&!empty($_POST['username'])&&!empty($_POST['password'])){
            $username = htmlspecialchars($_POST['username']);
            $password = md5(htmlspecialchars($_POST['password']));
            $query = "select username, password, account_type,account_address from users where username=? and password=? limit 1;";
            $stmt = mysqli_stmt_init($con);
            if(!mysqli_stmt_prepare($stmt, $query)){
                header("Location: index.php");
                die("Something Went Wrong! Try Again.");
            }
            mysqli_stmt_bind_param($stmt, "ss", $username, $password);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt)==1) {
                mysqli_stmt_bind_result($stmt,$username, $password, $account_type,$account_address);
                mysqli_stmt_fetch($stmt);
                $_SESSION['Username']=$username;
                $_SESSION['UserId']=$account_address;
                $_SESSION['AccountId']=$account_type;
                header("Location: main.php");
            }else{
                $error1 = "User does not exist, or incorrect Username or password";
            }
        }
    }
    $_SESSION['csrfToken'] = md5(rand());
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="This is a dentistry clinic website that provides many services to keep the customer healthy and give the cutomer the best methods to follow in order to keep his/her teeth healthy">
    <meta name="keywords" content="Booking, Dentistry, Dentist, Clinic, teeth, medical, medicine">
    <meta name="author" content="Salman Abulatif">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dentistry Website</title>
    <style>
        *{
            padding: 0;
            margin: 0;
        }
        body{
            background-image: url("background.jpeg");
            background-size: cover;
            background-repeat: no-repeat;
        }

        nav{
            background-color: #fbfbf8;
        }
        nav ul{
            width: auto;
            padding: 15px;
        }
        nav ul li{
            display: inline-block;
        }
        .login{
            background-color: black;
            padding: 5px;
            border-radius: 1px;
            width: 5%;
            text-align: center;
            float: right;
            box-shadow: 6px 6px 29px -4px rgba(0, 0, 0, 0.7);
        }
        .Title-header{
            font-family: serif;
        }
        nav li{
            margin: 5px;
        }
        nav ul li a{
            text-decoration: none;
            color: white;
        }
        nav ul li a:hover{
            text-decoration: none;
            color: white;
            cursor: pointer;
        }
        .popup{
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            position: absolute;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            display: none;
        }
        .popup-content{
            height: 50%;
            width: 30%;
            background: #fbfbf8;
            padding: 20px;
            border-radius: 5px;
            position: relative;
        }
        .popup input[type="text"]{
            margin: 20px auto;
            display: block;
            width: 50%;
            padding: 8px;
            border: 1px solid gray;
        }
        .popup input[type="password"]{
            margin: 20px auto;
            display: block;
            width: 50%;
            padding: 8px;
            border: 1px solid gray;
        }
        .popup input[type="submit"]{
            background-color: black;
            padding: 5px;
            border-radius: 1px;
            width: 20%;
            color: white;
            box-shadow: 3px 3px 15px -4px rgba(0, 0, 0, 0.5);
            margin-bottom: 15px;
        }
        .popup-content svg{
            float: right;
            display: block;
        } 
        .popup-content svg:hover{
            float: right;
            display: block;
            opacity: 0.5;
            cursor: pointer;
        }
        .popup-content a{
            text-decoration: none;
            color: black;
        }
        .popup-content a:hover{
            text-decoration: none;
            color: black;
            cursor: pointer;
        }
        .popup-content p{
            word-wrap: none;
        }


        /****************************/
        .popup-signup{
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            position: absolute;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            display: none;
            
        }
        .popup-content-signup{
            height: 50%;
            width: 30%;
            background: #fbfbf8;
            padding: 20px;
            border-radius: 5px;
            position: relative;
        }
        .popup-signup input[type="text"]{
            margin: 20px auto;
            display: block;
            width: 50%;
            padding: 8px;
            border: 1px solid gray;
        }
        .popup-signup input[type="password"]{
            margin: 20px auto;
            display: block;
            width: 50%;
            padding: 8px;
            border: 1px solid gray;
        }
        .popup-signup input[type="submit"]{
            background-color: black;
            padding: 5px;
            border-radius: 1px;
            width: 20%;
            color: white;
            box-shadow: 3px 3px 15px -4px rgba(0, 0, 0, 0.5);
            margin-bottom: 15px;
            margin-top: 15px;
        }
        .popup-content-signup svg{
            float: right;
            display: block;
        } 
        .popup-content-signup svg:hover{
            float: right;
            display: block;
            opacity: 0.5;
            cursor: pointer;
        }
        .popup-content-signup a{
            text-decoration: none;
            color: black;
        }
        .popup-content-signup a:hover{
            text-decoration: none;
            color: black;
            cursor: pointer;
        }
        .popup-content-signup p{
            word-wrap: none;
        }

        .body-container{
            display: inline;
            width: 100%;
        }
        .body-content{
            border-radius: 5px;
            width: 50%;
            background-color: rgba(255,255,255,0.5);
            white-space: pre-line;
            margin: 5px;
        }
        .body-content img{
            margin: 10px;
            float: left;
            width: 20%;
            height: 80px;
        }
        .location{
            width: 25px;
            float: right;
            right: 0;
            bottom: 0;
            margin: 10px;
            border: solid black 1px; 
            border-radius: 90px 90px;
            padding: 10px;
            position: fixed;
            background-color: #fbfbf8;
        }
        .location:hover{
            width: 30px;
            transition: 0.5s;
            float: right;
            margin: 10px;
            border: solid black 1px; 
            border-radius: 90px 90px;
            padding: 10px;
            background-color: #fbfbf8;
        }
        .popup-location{
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            position: absolute;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            display: none;
            
        }
        .popup-content-location{
            height: 50%;
            width: 30%;
            background: #fbfbf8;
            padding: 20px;
            border-radius: 5px;
            position: relative;
        }
        .popup-content-location iframe{
            width: 90%;
            height: 80%;
        }
        .popup-content-location svg{
            float: right;
            display: block;
            opacity: 0.5;
            cursor: pointer;
        }

        @media only screen and (max-width: 600px){
            .body-content{
                width: 90%;
                display: flex;
                flex-direction: column;
                white-space: pre-line;
                margin: 10px;
                background-color: white;
                padding: 10px;
            }
            .body-content img{
                margin: auto;
                width: 80%;
                height: 10%;
                border-radius: 10px;
                margin-bottom: 10px;
                margin-top: 10px;
            }
            body{
                background-image: none;
                background-color: #a6cbd3;
            }
            nav li{
                margin-right: 20px ;
            }
            .popup-content-location{
                height: 50%;
                width: 80%;
                background: #fbfbf8;
                padding: 20px;
                border-radius: 5px;
                position: relative;
            }
            .popup-content-signup{
                height: 50%;
                width: 80%;
                background: #fbfbf8;
                padding: 20px;
                border-radius: 5px;
                position: relative;
            }

            .popup-signup input[type="text"]{
                margin: 20px auto;
                display: block;
                width: 70%;
                padding: 8px;
                border: 1px solid gray;
            }
            .popup-signup input[type="password"]{
                margin: 20px auto;
                display: block;
                width: 70%;
                padding: 8px;
                border: 1px solid gray;
            }
            .popup-signup input[type="submit"]{
                background-color: black;
                padding: 5px;
                border-radius: 1px;
                width: 40%;
                color: white;
                box-shadow: 3px 3px 15px -4px rgba(0, 0, 0, 0.5);
                margin-bottom: 15px;
                margin-top: 15px;
            }

            .popup-content{
                height: 50%;
                width: 80%;
                background: #fbfbf8;
                padding: 20px;
                border-radius: 5px;
                position: relative;
            }

            .popup input[type="text"]{
                margin: 20px auto;
                display: block;
                width: 70%;
                padding: 8px;
                border: 1px solid gray;
            }
            .popup input[type="password"]{
                margin: 20px auto;
                display: block;
                width: 70%;
                padding: 8px;
                border: 1px solid gray;
            }
            .popup input[type="submit"]{
                background-color: black;
                padding: 5px;
                border-radius: 1px;
                width: 40%;
                color: white;
                box-shadow: 3px 3px 15px -4px rgba(0, 0, 0, 0.5);
                margin-bottom: 15px;
            }

            nav ul{
                width: auto;
                padding: 15px;
            }
            .login{
                background-color: black;
                padding: 5px;
                border-radius: 1px;
                width: 15%;
                text-align: center;
                float: right;
                box-shadow: 6px 6px 29px -4px rgba(0, 0, 0, 0.7);
            }
            footer{
                height: 80px;
            }
            .location{
                width: 30px;
                margin: 10px;
                border: solid black 1px; 
                border-radius: 90px 90px;
                padding: 10px;
                position: fixed;
                bottom: 0;
                right:0;
                background-color: #fbfbf8;
            }

        }
        @media only screen and (max-width: 300px){
            nav ul li{
                display: inline;
            }
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><h1 class="Title-header">Dentistry Website</h1></li>
                <!-- <li><a href="">Profile</a></li> this should be added if the used has registered -->
                <li class="login"><a onclick="login()">Login</a></li>
            </ul>
        </nav>
        <hr>
    </header>

    
    <div class="body-container" style="background-color: white;">
        <h1>Our Services</h1>
        <div class="body-content">
            <img src="fellings.jpg" alt="Fellings">
            <h3>Fellings</h3>
            <p>Fellings are used to repair a hole in a tooth caused by decay.</p>
        </div>
        <div class="body-content">
            <img src="bridge.jpg" alt="Bridge">
            <h3>Bridge</h3>
            <p>A bridge is a fixed replacement for a missing tooth or teeth.</p>
        </div>
        <div class="body-content">
            <img src="crowns.jpg" alt="Crowns">
            <h3>Crowns</h3>
            <p>A crown is a type of cap that completely covers a real tooth.</p>
        </div>
        <div class="body-content">
            <img src="Root canal treatment.jpg" alt="Root Canal Treatment">
            <h3>Root Canal Treatment</h3>
            <p>Root canal treatment tackles infection at the center of a tooth (the root canal system).</p>
        </div>
        <div class="body-content">
            <img src="braces.jpg" alt="Braces">
            <h3>Braces</h3>
            <p>Braces stengthen or move teeth to improve the appearance of the teeth and how they work.</p>
        </div>
    </div>
    <div class="popup">
        <div class="popup-content">
            <svg onclick="closelogin()" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.2253 4.81108C5.83477 4.42056 5.20161 4.42056 4.81108 4.81108C4.42056 5.20161 4.42056 5.83477 4.81108 6.2253L10.5858 12L4.81114 17.7747C4.42062 18.1652 4.42062 18.7984 4.81114 19.1889C5.20167 19.5794 5.83483 19.5794 6.22535 19.1889L12 13.4142L17.7747 19.1889C18.1652 19.5794 18.7984 19.5794 19.1889 19.1889C19.5794 18.7984 19.5794 18.1652 19.1889 17.7747L13.4142 12L19.189 6.2253C19.5795 5.83477 19.5795 5.20161 19.189 4.81108C18.7985 4.42056 18.1653 4.42056 17.7748 4.81108L12 10.5858L6.2253 4.81108Z" fill="currentColor" /></svg>
            <br>
            <h1>Login</h1>
            <form method="POST">
                <input type="text" name="username" placeholder="Username">
                <input type="password" name="password" placeholder="Password">
                <input type="hidden" name="csrftoken" value="<?php echo $_SESSION['csrfToken'];?>">
                <input type="submit" name="submit" value="Login">
            </form>
            <p>You Don't Have an Account? <a onclick="signup()">Signup</a></p>
                     
        </div>
    </div>


    <div class="popup-signup" id="signup-form">
        <div class="popup-content-signup">
            <svg onclick="signupclose()" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.2253 4.81108C5.83477 4.42056 5.20161 4.42056 4.81108 4.81108C4.42056 5.20161 4.42056 5.83477 4.81108 6.2253L10.5858 12L4.81114 17.7747C4.42062 18.1652 4.42062 18.7984 4.81114 19.1889C5.20167 19.5794 5.83483 19.5794 6.22535 19.1889L12 13.4142L17.7747 19.1889C18.1652 19.5794 18.7984 19.5794 19.1889 19.1889C19.5794 18.7984 19.5794 18.1652 19.1889 17.7747L13.4142 12L19.189 6.2253C19.5795 5.83477 19.5795 5.20161 19.189 4.81108C18.7985 4.42056 18.1653 4.42056 17.7748 4.81108L12 10.5858L6.2253 4.81108Z" fill="currentColor" /></svg>
            <br>
            <h1>Signup</h1>
            <form method="POST">
                <input type="text" name="username-signup" id="username-signup" placeholder="Username">
                <input type="password" name="password-signup" id="password-signup" placeholder="Password" onchange="CheckPassword(this)">
                <label>Family</label>
                <input type="radio" name="accounttype" value="family">  
                <label>Indevidual</label>
                <input type="radio" name="accounttype" value="individual" checked><br>
                <input type="hidden" name="csrftoken" value="<?php echo $_SESSION['csrfToken'];?>">
                <input type="submit" name="submit-signup" id="submit-signup" value="Signup" onclick="update()">
            </form><br> 
            <?php if(isset($error1)):?>
                <p id="error"><?php echo $error1;?></p>
            <?php endif?>
            <p>Already Have an Account? <a onclick="login()">Login</a></p>
                     
        </div>
    </div>

    <div class="popup-location" id="popup-location">
        <div class="popup-content-location">
            <svg onclick="locationclose()" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.2253 4.81108C5.83477 4.42056 5.20161 4.42056 4.81108 4.81108C4.42056 5.20161 4.42056 5.83477 4.81108 6.2253L10.5858 12L4.81114 17.7747C4.42062 18.1652 4.42062 18.7984 4.81114 19.1889C5.20167 19.5794 5.83483 19.5794 6.22535 19.1889L12 13.4142L17.7747 19.1889C18.1652 19.5794 18.7984 19.5794 19.1889 19.1889C19.5794 18.7984 19.5794 18.1652 19.1889 17.7747L13.4142 12L19.189 6.2253C19.5795 5.83477 19.5795 5.20161 19.189 4.81108C18.7985 4.42056 18.1653 4.42056 17.7748 4.81108L12 10.5858L6.2253 4.81108Z" fill="currentColor" /></svg>
            <h1>Location</h1>
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3384.5748749676845!2d35.83031251516187!3d31.97242808122232!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x151ca17f274178b1%3A0xe04bf74531579108!2sAlHussein%20Technical%20University!5e0!3m2!1sen!2sjo!4v1660497621921!5m2!1sen!2sjo" style="border:0;" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>

    <footer>
        <div class="location">
            <svg version="1.1" onclick="location1()" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 349.661 349.661" xmlns:xlink="http://www.w3.org/1999/xlink" enable-background="new 0 0 349.661 349.661">
                <g>
                    <path d="M174.831,0C102.056,0,42.849,59.207,42.849,131.981c0,30.083,21.156,74.658,62.881,132.485   c30.46,42.215,61.363,76.607,61.671,76.95l7.429,8.245l7.429-8.245c0.309-0.342,31.211-34.734,61.671-76.95   c41.725-57.828,62.881-102.402,62.881-132.485C306.812,59.207,247.605,0,174.831,0z M174.83,319.617   c-37.058-42.692-111.98-139.048-111.98-187.636C62.849,70.235,113.084,20,174.831,20s111.981,50.235,111.981,111.981   C286.812,180.54,211.888,276.915,174.83,319.617z"/>
                    <circle cx="174.831" cy="131.982" r="49.696"/>
                </g>
            </svg>

        </div>
    </footer>
    <script>
        document.getElementById("submit-signup").disabled = true;
        if(document.getElementById("error").textContent){
            document.querySelector(".popup-signup").style.display = "flex";
            document.querySelector(".body-container").style.display = "none";
        }
        function location1(){
            document.querySelector(".location").style.display = "none";
            document.querySelector(".popup-location").style.display = "flex";
            document.querySelector(".body-container").style.display = "none";
        }
        function locationclose(){
            document.querySelector(".location").style.display = "block";
            document.querySelector(".popup-location").style.display = "none";
            document.querySelector(".body-container").style.display = "inline";
        }
        function login(){
            document.querySelector(".popup-signup").style.display = "none";
            document.querySelector(".popup").style.display = "flex";
            document.querySelector(".body-container").style.display = "none";
            document.querySelector(".location").style.display = "none";
            document.querySelector(".popup-location").style.display = "none";


        }
        function closelogin(){
            document.querySelector(".popup").style.display = "none";
            document.querySelector(".body-container").style.display = "inline";
            document.querySelector(".location").style.display = "block";
        }
        function signup(){
            document.querySelector(".popup").style.display = "none";
            document.querySelector(".popup-signup").style.display = "flex";
            document.querySelector(".body-container").style.display = "none";
            document.querySelector(".location").style.display = "none";
            document.querySelector(".popup-location").style.display = "none";

        }
        function signupclose(){
            document.querySelector(".location").style.display = "block";
            document.querySelector(".popup-signup").style.display = "none";
            document.querySelector(".body-container").style.display = "inline";
        }
        function CheckPassword(inputtxt){ 
            var pass = document.getElementById("submit-signup")
            var passw=  /^[A-Za-z]\w{7,14}$/;
            if(inputtxt.value.match(passw)) {
                document.getElementById("submit-signup").disabled = false;
                return true;
            }else{ 
                alert('Wrong! Password should contain 7 to 14 letters of Uppercase and Lowercase.');
                return false;
            }
        }
    </script>
</body>
</html>
