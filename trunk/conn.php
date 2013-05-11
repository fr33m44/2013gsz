<?php
	//mysql_connect("localhost", "tunpscom_tun", "leihuang2MYSQL!!") or
	mysql_connect("localhost", "root", "") or
		die("Could not connect: " . mysql_error());
	mysql_select_db("gsz");
	mysql_query("set names utf8");
	
	if(!get_magic_quotes_gpc()) 
	{
		foreach($_POST as $k=>$v) 
			$_POST[$k] = addslashes($v);
		foreach($_GET as $k=>$v) 
			$_GET[$k] = addslashes($v);			

	}
	
