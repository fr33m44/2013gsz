<?php 
	//连接数据库
	require_once 'conn.php';
	//start session
	session_start();
	if($_SESSION['userid'] == "")
		header('location:login.php');
	
	//单条记录
	isset($_GET['id']) && is_numeric($_GET['id'])?$id=$_GET['id']:$id=1;
    $sql=mysql_query("select id,title,content,post_date,post_modified from diary where id=".$id);
    $row = mysql_fetch_array($sql, MYSQL_ASSOC);
	
	if(!isset($_GET['a']) || !in_array($_GET['a'],array('view','edit','del','save')))
	{
		$_GET['a'] = 'view';
	}
	if(isset($_POST['title']) )
	{
		$sql = "update `diary` set title='".$_POST['title']."',content='".$_POST['content']."',  post_modified=now() where id = ".$_POST['id'] ;
		//echo $sql;exit;
		mysql_query($sql);
		echo '<script>保存成功！</script>';
		header('location:detail.php?id='.$_POST["id"]);
	}
	
	if(isset($_GET['a']) && $_GET['a'] == 'del')
	{
	
		$id = $_GET['id'];
		$sql = "delete from `diary` where id = $id";
		mysql_query($sql);
		echo '<script>删除成功！</script>';
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
<title><?php echo $row['title']?> - 干啥子日记[ganshazi diary]</title>
</head>

<body>
<div id="wrapper">
	<?php if($_GET['a'] == 'view') { ?>
	<div id="title">
			<h1><?php echo $row['title'];?></h1>
	</div>
	<div id="panel">
		<span class="edit"><a href="detail.php?id=<?php echo $row['id']?>&a=edit">编辑</a></span>
		<span class="delete"><a href="detail.php?id=<?php echo $row['id']?>&a=del">删除</a></span>
	</div>
	<div id="status-detail">
			<?php
				echo $row['content'];
			?>
	</div>
	<?php } ?>
	
	<?php if($_GET['a'] == 'edit'){ ?>
	<form name="form1" id="form1" action="detail.php" method="post">
	<div id="title">
			<h1><input type="text" name="title" value="<?php echo $row['title'];?>" /></h1>
	</div>
	<input type="hidden" name="id" value="<?php echo $row['id']?>" />
	<div id="panel">
		<span class="save"><input type="submit" value="保存" /></span>	
	</div>
	<div id="status-detail">
			<textarea name="content" id="content" style="width:600px;height:400px;"><?php echo $row['content'];?></textarea>

	</div>
	
	
	</form>
	<?php } ?>
	
	<div style="margin-top:20px;color:#555555;">
		Post Date:<?php echo $row['post_date']?> <br />
		Last Modified:<?php echo $row['post_modified']?> <br />
	</div>
	
	<div id="footer">copyright&copy 2010  <a href="http://tunps.com">tunpishuang</a>. All rights reserved.</div>
</div>
</body>
</html>
