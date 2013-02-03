<?php 
	//连接数据库
	require_once 'conn.php';
	//start session
	session_start();
	if($_SESSION['userid'] == "")
		header('location:login.php');
	
	
	//分页
 	isset($_GET['page'])?$page=$_GET['page']:$page=1;
 	//搜索
 	isset($_GET['s'])?$s=$_GET['s']:$s=null;
 	
	
    if(is_numeric($page))
    {
    	$page_size=10;
    	isset($_GET['s'])?$query="select count(*) as total from diary where content like '%".$s."%'":$query="select count(*) as total from diary ";
    	$result_p=mysql_query($query);
    	$status_count=mysql_result($result_p,0,"total");
    	$page_count=ceil($status_count/$page_size);
    	$offset=($page-1)*$page_size;
    	isset($_GET['s'])?$sql=mysql_query("select * from diary where content like '%".$s."%' order by title desc limit $offset,$page_size"):$sql=mysql_query("select * from diary order by title desc limit $offset,$page_size");
    	$sql_user=mysql_query("select * from user");
    	$row_user=mysql_fetch_assoc($sql_user);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="static/base.css" rel="stylesheet" />
<link href="static/jquery.lightbox-0.5.css" rel="stylesheet" />
<script language="javascript" type="text/javascript" src="static/jquery.js"></script>
<script language="javascript" type="text/javascript" src="static/jquery.lightbox-0.5.js"></script>
<script language="javascript" type="text/javascript">
	$(function(){
		$('a.lightbox').lightBox();
		
		$(".item").mouseover(function(){
			$(this).css("background","#eee");
			});
		$(".item").mouseout(function(){
			$(this).css("background","#fff");
			});
		
	});
</script>
<title>干啥子日记[ganshazi diary]</title>
</head>

<body>
<div id="wrapper">
	<div id="panel">
		<ol id="menu-list">
			<li><a href="new.php">写日记</a></li>
		</ol>
	</div>
	<ol id="status-list">
	<?php 
		while (($row = mysql_fetch_array($sql, MYSQL_ASSOC))==true) {
    			if(!$row)
    			{
    				echo "暂无任何数据。";
    				break;
    			}
	?>
		<li class="item" id="<?php echo $row['id']?>">
			<div class="info">
				<span class="item-right">
					<span class="post-time"> <?php echo $row['post_date']; ?></span>
					<span class="edit"><a href="detail.php?id=<?php echo $row['id']?>&a=edit">编辑</a></span>
					<span class="delete"><a href="detail.php?id=<?php echo $row['id']?>&a=del">删除</a></span>
				</span>
				<span class="meta">
					<a class="post-title" title="<?php 
							echo $row['title'];
							?>" href="detail.php?id=<?php echo $row['id']?>"><?php 
							echo $row['title'];
							?>
					</a>
				</span>
			</div>
		</li>
	<?php 
    	}
    }
    else
    {
    	die("<h1>HTTP 404,PAGE NOT FOUND.</h1>");
    }
	?>
	</ol>
	<div id="page-nav">
	<?php
		if($page!=1)
		{	
			if(isset($s))
			{
				echo '<a href="index.php?page=1&s='.$s.'">首页</a> | ';
				echo '<a href="index.php?page='.($page-1).'&s='.$s.'">上一页</a>';
			}
			else
			{
				echo '<a href="index.php?page=1">首页</a> | ';
				echo '<a href="index.php?page='.($page-1).'">上一页</a>';
			}
		}
		if($page<$page_count)
		{
			if(isset($s))
			{
				echo ' <a href="index.php?page='.($page+1).'&s='.$s.'">下一页</a> | ';
				echo ' <a href="index.php?page='.($page_count).'&s='.$s.'">尾页</a>';
			}
			else
			{
				echo ' <a href="index.php?page='.($page+1).'">下一页</a> | ';
				echo ' <a href="index.php?page='.($page_count).'">尾页</a>';
			}
		}
	?>
	</div>
	<div id="footer">copyright© 2010  <a href="http://tunps.com">tunpishuang</a>. All rights reserved.</div>
	<script src="http://s14.cnzz.com/stat.php?id=2250487&web_id=2250487&show=pic" language="JavaScript"></script>
</div>
</body>
</html>
