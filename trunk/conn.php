<?php
	mysql_connect("localhost", "tunpscom_tun", "leihuang2MYSQL!!") or
		die("Could not connect: " . mysql_error());
	mysql_select_db("tunpscom_wp");
	mysql_query("set names utf8");