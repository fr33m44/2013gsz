<?php 
	//连接数据库
	require_once 'conn.php';
	//单条记录
	isset($_GET['id']) && is_numeric($_GET['id'])?$id=$_GET['id']:$id=1;
    $sql=mysql_query("select * from status where id=".$id);
    $row = mysql_fetch_array($sql, MYSQL_ASSOC)
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/base.css" rel="stylesheet" />
<link href="css/jquery.lightbox-0.5.css" rel="stylesheet" />
<script language="javascript" type="text/javascript" src="js/jquery.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery.lightbox-0.5.js"></script>
<script language="javascript" type="text/javascript">
	$(function(){
		$('a.lightbox').lightBox();
	});
</script><title>干啥子</title>
</head>

<body>
<div id="wrapper">
	<div id="header"><a href="index.php"><img src="images/logo_ganshazi.png"></img></a></div>
	<div id="status-detail">
			<?php 
				echo $row['post_content'];
			?>
		<hr />
	
	</div>
	<div style:margin-top:20px;color:#555555;>
		Date:<?php echo $row['post_date']?> <?php /*echo $row['source']*/ ?>
	</div>
	
	<div id="footer">copyright© 2010  <a href="http://techguru.cn">tunpishuang</a>. All rights reserved.</div>
	<script src="http://s14.cnzz.com/stat.php?id=2250487&web_id=2250487&show=pic" language="JavaScript"></script>
</div>
</body>
</html>
