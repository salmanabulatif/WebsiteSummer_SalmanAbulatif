<?php
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
    session_start();
    require "private/function.php";
    if(!isset($_SESSION['UserId'])&&!isset($_SESSION['AccountId'])){
        die(header("Location: index.php"));
    }
    if (isset($_POST['no'])) {
    	header("Location: profile.php");
    	die("Redirect to profile");
    }
	$id = htmlspecialchars($_GET['id']);
    if (isset($_POST['yes'])) {
	    $query = "delete from appointments where id=?;";
	    $stmt = mysqli_stmt_init($con);
	    if(!mysqli_stmt_prepare($stmt, $query)){
	        header("Location: index.php");
	        die("Something Went Wrong! Try Again.");
	    }
	    mysqli_stmt_bind_param($stmt, "i", $id);
	    mysqli_stmt_execute($stmt);
	    header("Location: profile.php");
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
	<title>Delete</title>
</head>
<body>
	<div>
		<form method="POST">
			<p>This Action Cannot be Reversed! Are You Sure You Want to Delete This Booking?</p>
			<input type="submit" name="yes" value="Yes">
			<input type="submit" name="no" value="No">
		</form>
	</div>
</body>
</html>