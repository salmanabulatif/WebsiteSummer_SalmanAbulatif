<?php
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    session_start();
    require "private/function.php";
    if(!isset($_SESSION['UserId'])&&!isset($_SESSION['AccountId'])){
        die(header("Location: index.php"));
    }
    //aapointment data
    $query = "select id,username,treatment,user_age,date,time from appointments where user_address=?;";
    $stmt = mysqli_stmt_init($con);
    if(!mysqli_stmt_prepare($stmt, $query)){
        header("Location: index.php");
        die("Something Went Wrong! Try Again.");
    }
    $username = $_SESSION['UserId'];
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    mysqli_stmt_bind_result($stmt, $id,$username, $treatment,$user_age, $date,$time);
    $user_elements = array(array(),array(),array(),array(),array(),array());
    while(mysqli_stmt_fetch($stmt)){
        array_push($user_elements[0],$id);
        array_push($user_elements[1],$username);
        array_push($user_elements[2],$treatment);
        array_push($user_elements[3],$user_age);
        array_push($user_elements[4],$date);
        array_push($user_elements[5],$time);
    }
    //img data
    $query = "select img_link from users where account_address=?;";
    $stmt = mysqli_stmt_init($con);
    if(!mysqli_stmt_prepare($stmt, $query)){
        header("Location: index.php");
        die("Something Went Wrong! Try Again.");
    }
    $username = $_SESSION['UserId'];
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    mysqli_stmt_bind_result($stmt, $img_link);
    while(mysqli_stmt_fetch($stmt)){
        $user_img = $img_link;
    }
    if (isset($_POST['img_change'])&&$_SERVER['REQUEST_METHOD']==="POST") {
        $file = $_FILES['prfl_img'];

        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileError = $file['error'];
        $fileType = $file['type'];

        $fileExtention = explode(".",$fileName);

        $fileActualExtention = strtolower(end($fileExtention));

        $fileAllow = array("jpg","png","jpeg");

        if (in_array($fileActualExtention, $fileAllow)) {
            if ($fileError===0) {
                if($fileSize<1000000){
                    $fileNameNew = $_SESSION['UserId'].".".$fileActualExtention;
                    $fileDestination = "userimages/$fileNameNew";
                    move_uploaded_file($fileTmpName, $fileDestination);
                    $user_id =$_SESSION['UserId'];
                    $query = "UPDATE users set img_link=? where account_address=?;";
                    $stmt = mysqli_stmt_init($con);
                    if(!mysqli_stmt_prepare($stmt, $query)){
                        header("Location: index.php");
                        die("Something Went Wrong! Try Again.");
                    }
                    mysqli_stmt_bind_param($stmt, "ss",$fileNameNew,$user_id);
                    mysqli_stmt_execute($stmt);
                    header("Location: profile.php");
                }else{
                    $error = "The File is too big";
                }
            }else{
                $error = "An error ocurred while uploading the image";
            }
        }else{
            $error = "files of this type cannot be uploaded. Only jpeg, jpg, png";
        }

    }
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta name="description" content="This is a dentistry clinic website that provides many services to keep the customer healthy and give the cutomer the best methods to follow in order to keep his/her teeth healthy">
    <meta name="keywords" content="Booking, Dentistry, Dentist, Clinic, teeth, medical, medicine">
    <meta name="author" content="Salman Abulatif">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Profile</title>
	<style type="text/css">
        *{
            padding: 0;
            margin: 0;
            overflow: hidden;
            overflow-x: visible;
        }
        body{
            background-image: url("background.jpeg");
            background-size: cover;
            background-repeat: no-repeat;
            background-color: #a7ccd4;
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
        .container{
            margin: 2.5%;
            background-color: white;
            width: 95%;
            text-align: center;
            height: 100vh;
        }
        .container table{
            margin: auto;
            width: 80%;
        }
        .container a{
            text-decoration: none;
            color: black;
            cursor: pointer;
        }
        .container a:hover{
            text-decoration: underline;
            color: black;
            cursor: pointer;

        }
        .container table, td, tr{
            border: 0.5px solid black;
        }
        .userdata{
            margin-top: 10px;
            margin-bottom: 10px;
            display: block;
            width: 100%;
        }
        .userdata img{
            height: 80px;
            border-radius: 50%;
        }
        .userdata form input{
            width: 15%;
        }
        .Username{
            font-size: 20px;
        }
        @media only screen and (max-width: 1200px){
            body{
                background: none;
                background-size: cover;
                background-repeat: no-repeat;
                background-color: #a7ccd4;
            }
            .login{
                background-color: black;
                padding: 5px;
                border-radius: 1px;
                width: 9%;
                text-align: center;
                float: right;
                box-shadow: 6px 6px 29px -4px rgba(0, 0, 0, 0.7);
            }

        }
        @media only screen and (max-width: 600px){
            body{
                background: none;
                background-size: cover;
                background-repeat: no-repeat;
                background-color: #a7ccd4;
            }
            .login{
                background-color: black;
                padding: 5px;
                border-radius: 1px;
                width: 12%;
                text-align: center;
                float: right;
                box-shadow: 6px 6px 29px -4px rgba(0, 0, 0, 0.7);
            }
            .container table{
                margin: auto;
                width: 90%;
            }
            .userdata form input{
                width: 20%;
            }
        }
        @media only screen and (max-width: 400px){
            body{
                background: none;
                background-size: cover;
                background-repeat: no-repeat;
                background-color: #a7ccd4;
            }
            .login{
                background-color: black;
                padding: 5px;
                border-radius: 1px;
                width: 27%;
                text-align: center;
                float: right;
                box-shadow: 6px 6px 29px -4px rgba(0, 0, 0, 0.7);
            }
            .container table{
                margin: auto;
                width: 100%;
            }
            .userdata form input{
                width: 50%;
            }
        }
        @media only screen and (max-width: 150px){
            body{
                background: none;
                background-size: cover;
                background-repeat: no-repeat;
                background-color: #a7ccd4;
            }
            .login{
                background-color: black;
                padding: 5px;
                border-radius: 1px;
                width: 50%;
                text-align: center;
                float: right;
                box-shadow: 6px 6px 29px -4px rgba(0, 0, 0, 0.7);
            }
            .container table{
                margin: auto;
                width: 100%;
            }
            .userdata form input{
                width: 50%;
            }
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><h1 class="Title-header">Dentistry Website</h1></li>
                <li class="login"><a href="logout.php">Logout</a></li>
                <li class="login"><a href="main.php">Main</a></li>
            </ul>
        </nav>
        <hr>
    </header>
    <div class="container">
        <div class="userdata">
            <img src="userimages/<?php echo $user_img;?>" alt="UserImg"><br>
            <label class="Username">Username: <?php echo $_SESSION['Username']?></label>
            <form method="POST" enctype="multipart/form-data">
                <input type="file" name="prfl_img" value="Choose file">
                <input type="submit" name="img_change" value="Upload Photo">
            </form>
            
        </div>
        <div class="appointments">
            <h2>Bookings</h2>
            <table>
                <tr>
                    <th>Appointment name</th>
                    <th>Treatment</th>
                    <th>Age</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Operations</th>
                </tr>
                <?php for($i=0;$i<count($user_elements[0]);$i++):?>
                    <tr>
                        <td><?php echo $user_elements[1][$i];?></td>
                        <td><?php echo $user_elements[2][$i];?></td>
                        <td><?php echo $user_elements[3][$i];?></td>
                        <td><?php echo $user_elements[4][$i];?></td>
                        <td><?php echo $user_elements[5][$i];?></td>
                        <td><a href="delete.php?id=<?php echo $user_elements[0][$i];?>">Delete</a> <a href="main.php?id=<?php echo $user_elements[0][$i];?>&username=<?php echo $user_elements[1][$i];?>&treatment=<?php echo $user_elements[2][$i];?>&age=<?php echo $user_elements[3][$i];?>&date=<?php echo $user_elements[4][$i];?>&time=<?php echo $user_elements[5][$i];?>">Edit</a></td>
                    </tr>
                <?php endfor;?>
            </table>
        </div>   
    </div>

</body>
</html>