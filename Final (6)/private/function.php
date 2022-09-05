<?php
	require "connect.php";
	function random_id_gen(){
		$array = array(0,1,2,3,4,5,6,7,8,9,'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
		$user_addr = "";
		for ($i=0; $i < 49; $i++) {
			$random = rand(0, 16);
			$user_addr .= $array[$random];
		}
		return $user_addr;
	}