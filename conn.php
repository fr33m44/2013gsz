<?php

	$conn = new mysqli("localhost", "root", "", "gsz");
	$conn->query("set names utf8");
	
	if(!get_magic_quotes_gpc()) 
	{
		foreach($_POST as $k=>$v) 
			$_POST[$k] = addslashes($v);
		foreach($_GET as $k=>$v) 
			$_GET[$k] = addslashes($v);			

	}
	
