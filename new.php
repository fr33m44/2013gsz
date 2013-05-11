<?php 
	//连接数据库
	require_once 'conn.php';
	//start session
	session_start();
	if($_SESSION['userid'] == "")
		header('location:login.php');
	
	
	if(isset($_POST['title']))
	{
		$title 		= $_POST['title'];
		$date		= $_POST['date'];
		$content	= $_POST['content'];
		$result = mysql_query("insert into diary(title,date,content,post_date,post_modified) value('$title','$date', '$content', now(), now()) ");
		var_dump($result);
		echo "<script>保存成功！</script>";
		header('location:index.php');
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
<script language="javascript" src="lib/kindeditor/kindeditor.js"></script>
<script language="javascript" src="lib/kindeditor/lang/zh_CN.js"></script>
<script>
	KindEditor.ready(function(K) {
		var editor1 = K.create('textarea[name="content"]', {
			allowFileManager : false
		});
	});
</script>
<script language="javascript" type="text/javascript">
	$(function(){
		$('a.lightbox').lightBox();
	});
</script>
<title>写日记 - 干啥子日记[ganshazi diary]</title>
</head>

<body>
<div id="wrapper">
		
	<form name="form1" id="form1" action="new.php" method="post">
	<div id="title">
			<h2>Title:<input type="text" name="title" size="80" style="padding:3px 8px"/></h2>
	</div>
	<div id="date">
			<h2>Date:<input type="text" name="date" size="80" style="padding:3px 8px"/></h2>
	</div>
	<div id="panel">
		<span class="save"><input type="submit" value="保存" /></span>	
	</div>
	<div id="status-detail">
			<textarea name="content" id="content" style="width:600px;height:400px;"></textarea>
	</div>
	
	
	</form>
		
	<div style="margin-top:20px;color:#555555;">
		Date:
	</div>
	
	<div id="footer">copyright&copy 2010  <a href="http://tunps.com">tunpishuang</a>. All rights reserved.</div>
</div>
</body>
</html>
