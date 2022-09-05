<?php
	$con = mysqli_connect("localhost", "root", "", "dentistry");
	if(!$con){
		die("Database backend connection error");
	}