<?php
	session_start();
	unset($_SESSION['Username']);
	unset($_SESSION['UserId']);
	unset($_SESSION['AccountId']);
	die(header("Location: index.php"));