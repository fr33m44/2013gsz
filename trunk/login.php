<?php 
	//连接数据库
	require_once 'conn.php';
	//start session
	session_start();
	
	if(isset($_POST['username']))
	{
		$username = $_POST['username'];
		$password = $_POST['password'];
		$result = mysql_query("select name,pass from `user` where id=1");
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		if($row['name'] == $username && $row['pass'] == md5($password))
		{
			$_SESSION['userid'] = 1;
			header('location:index.php');
		}
		else
		{
			unset($_SESSION['userid']);
			echo '<script>登录失败!</script>';
			header('location:login.php');
		}
		
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="static/base.css" rel="stylesheet" />
<link href="static/jquery.lightbox-0.5.css" rel="stylesheet" />
<script language="javascript" type="text/javascript" src="static/jquery.js"></script>
<script language="javascript" type="text/javascript" src="static/jquery.lightbox-0.5.js"></script>

<title>登录 - 干啥子日记[ganshazi diary]</title>
</head>

<body>
	<div class="loginform">
		<form id="login" action="login.php" method="post">
			用户名：<input type="text" name="username" />
			密码：<input type="password" name="password" />
			<input type="submit" value="登录" />
		</form>
	</div>
</body>
</html>