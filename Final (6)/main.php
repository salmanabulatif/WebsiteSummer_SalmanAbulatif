<?php
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	session_start();
	require "private/function.php";
	if(!isset($_SESSION['UserId'])&&!isset($_SESSION['AccountId'])){
		die(header("Location: index.php"));
	}
	//add a date processor
	if($_SERVER['REQUEST_METHOD']==='POST'&&isset($_POST['submit'])){
		if (!empty($_POST['Patient_Name'])&&!empty($_POST['Patient_Age'])&&!empty($_POST['Treatments'])&&!empty($_POST['date'])&&!empty($_POST['time'])&&empty($_POST['dataEdit'])&&!empty($_POST['user_address'])) {

			$name = htmlspecialchars($_POST['Patient_Name']);
			$age = htmlspecialchars($_POST['Patient_Age']);
			$treatment = htmlspecialchars($_POST['Treatments']);
			$date = htmlspecialchars($_POST['date']);
			$time = htmlspecialchars($_POST['time']);
			$user_id = htmlspecialchars($_POST['user_address']);
			$time_f = date('H:i:s', strtotime($time. ' + 30 minutes'));
			// $time_b = date('H:i:s', strtotime($time. ' - 30 minutes'));
			$query = "select * from appointments where date=? and time=?;";
			$stmt = mysqli_stmt_init($con);
			if(!mysqli_stmt_prepare($stmt, $query)){
			    header("Location: index.php");
			    die("Something Went Wrong! Try Again.");
			}
			mysqli_stmt_bind_param($stmt, "ss", $date, $time);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_store_result($stmt);
			if (mysqli_stmt_num_rows($stmt)==0) {
				$query = "insert into appointments(username, treatment, user_age, time, date, time_finish, user_address) values(?,?,?,?,?,?,?);";
				$stmt = mysqli_stmt_init($con);
				if (!mysqli_stmt_prepare($stmt, $query)) {
					echo "error ocurred";
				}
				mysqli_stmt_bind_param($stmt, "sssssss", $name, $treatment, $age, $time, $date, $time_f,$user_id);
				mysqli_stmt_execute($stmt);
				$error1 = "Appointment Done Successfully!";
			}else{
				$error_time = "Time already exists";
			}
		}elseif (!empty($_POST['Patient_Name'])&&!empty($_POST['Patient_Age'])&&!empty($_POST['Treatments'])&&!empty($_POST['date'])&&!empty($_POST['time'])&&!empty($_POST['dataEdit'])&&!empty($_POST['user_address'])) {
			$id = htmlspecialchars($_POST['dataEdit']);
			$name = htmlspecialchars($_POST['Patient_Name']);
			$age = htmlspecialchars($_POST['Patient_Age']);
			$treatment = htmlspecialchars($_POST['Treatments']);
			$date = htmlspecialchars($_POST['date']);
			$time = htmlspecialchars($_POST['time']);

		    $query = "update appointments set username=?,treatment=?,user_age=?,date=?,time=? where id=?;";
		    $stmt = mysqli_stmt_init($con);
		    if(!mysqli_stmt_prepare($stmt, $query)){
		        header("Location: index.php");
		        die("Something Went Wrong! Try Again.");
		    }
		    mysqli_stmt_bind_param($stmt, "ssissi", $name,$treatment,$age,$date,$time,$id);
		    mysqli_stmt_execute($stmt);
		    header("Location: main.php");
		}
		else{
			$error = "All fields should be filled";
		}

	}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="description" content="This is a dentistry clinic website that provides many services to keep the customer healthy and give the cutomer the best methods to follow in order to keep his/her teeth healthy">
    <meta name="keywords" content="Booking, Dentistry, Dentist, Clinic, teeth, medical, medicine">
    <meta name="author" content="Salman Abulatif">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments</title>
    <style type="text/css">
        *{
            padding: 0;
            margin: 0;
            overflow: hidden;
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
        .appointment-container{
            width: 100%;
            height: 100%;
            position: absolute;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        .appointment-content{
            height: 70%;
            width: 30%;
            background: #fbfbf8;
            padding: 20px;
            border-radius: 5px;
            position: relative;
        }
        .appointment-container input{
            margin: 20px auto;
            display: block;
            width: 70%;
            padding: 8px;
            border: 1px solid gray;
        }
        .appointment-container select{
            margin: 20px auto;
            display: block;
            width: 74%;
            padding: 8px;
            border: 1px solid gray;
            color: rgba(0, 0, 0, 0.6);
        }
        .appointment-container input[type="submit"]{
            background-color: black;
            padding: 8px;
            border-radius: 1px;
            width: 40%;
            color: white;
            box-shadow: 3px 3px 15px -4px rgba(0, 0, 0, 0.5);
            margin-bottom: 15px;
        }
        .error{
        	color: red;
        }
        .success{
        	color: green;
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
	        .appointment-content{
	            height: 70%;
	            width: 30%;
	            background: #fbfbf8;
	            padding: 20px;
	            border-radius: 5px;
	            position: relative;
	        }
	        .appointment-container{
	            width: 100%;
	            height: 100%;
	            position: absolute;
	            display: flex;
	            justify-content: center;
	            align-items: center;
	            text-align: center;
	            margin-top: -40px;
	        }
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
	        .appointment-content{
	            height: 70%;
	            width: 50%;
	            background: #fbfbf8;
	            padding: 20px;
	            border-radius: 5px;
	            position: relative;
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
	        .appointment-content{
	            height: 70%;
	            width: 60%;
	            background: #fbfbf8;
	            padding: 20px;
	            border-radius: 5px;
	            position: relative;
	        }
	        .appointment-container{
	            width: 100%;
	            height: 100%;
	            position: absolute;
	            display: flex;
	            justify-content: center;
	            align-items: center;
	            text-align: center;
	            margin-top: -40px;
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
	            width: 19%;
	            text-align: center;
	            float: right;
	            box-shadow: 6px 6px 29px -4px rgba(0, 0, 0, 0.7);
	        }
	        .appointment-content{
	            height: 70%;
	            width: 80%;
	            background: #fbfbf8;
	            padding: 20px;
	            border-radius: 5px;
	            position: relative;
	        }
	        .appointment-container{
	            width: 100%;
	            height: 100%;
	            position: absolute;
	            display: flex;
	            justify-content: center;
	            align-items: center;
	            text-align: center;
	            margin-top: -40px;
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
                <li class="login"><a href="profile.php">Profile</a></li>
                
            </ul>
        </nav>
        <hr>
    </header>

    <div class="appointment-container">
	    <div class="appointment-content">
	    	<h1>Appointment<br>Booking</h1>
	    	<form method="POST">
	    		<input type="text" name="Patient_Name" placeholder="Patient Name" value="<?php if(isset($_GET['username'])){echo $_GET['username'];}?>">
	    		<input type="text" name="Patient_Age" placeholder="Patient Age" value="<?php if(isset($_GET['age'])){echo $_GET['age'];}?>">
	    		<select name="Treatments" value="<?php if(isset($_GET['treatment'])){echo $_GET['treatment'];}?>">
	    			<option value="Fellings">Fellings</option>
	    			<option value="Bridge">Bridge</option>
	    			<option value="Crowns">Crowns</option>
	    			<option value="Root Canal Treatment">Root Canal Treatment</option>
	    			<option value="Braces">Braces</option>
	    		</select>
	    		<input type="date" name="date" value="<?php if(isset($_GET['date'])){echo $_GET['date'];}?>">
	    		<input type="time" name="time" id="time" value="<?php if(isset($_GET['time'])){echo $_GET['time'];}?>">
	    		<?php if(isset($error)){echo "<script>alert('".$error."')</script>";}?>
	    		<?php if(isset($error1)){echo "<script>alert('".$error1."')</script>";}?>
	    		<?php if(isset($error_time)){echo "<script>alert('".$error_time."')</script>";}?>
	    		<input type="hidden" name="dataEdit" value="<?php if(isset($_GET['id'])){echo $_GET['id'];}?>">
	    		<input type="hidden" name="user_address" value="<?php echo $_SESSION['UserId']?>">
	    		<input type="submit" name="submit" value="Book">
	    		
	    	</form>
	    </div>	
    </div>
    <script type="text/javascript">
    	document.getElementById('time').step = "1800";
    	//document.getElementById('time').setCustomValidity("There should be half an hour between appointments. Ex: 22:00 or 22:30");
    </script>
</body>
</html>