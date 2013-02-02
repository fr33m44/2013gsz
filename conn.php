<?php
	mysql_connect("localhost", "root", "") or
		die("Could not connect: " . mysql_error());
	mysql_select_db("gsz");
	mysql_query("set names utf8");