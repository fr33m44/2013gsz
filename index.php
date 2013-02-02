<?php 
	//连接数据库
	require_once 'conn.php';
	//分页
 	isset($_GET['page'])?$page=$_GET['page']:$page=1;
 	//搜索
 	isset($_GET['s'])?$s=$_GET['s']:$s=null;
 	
	
    if(is_numeric($page))
    {
    	$page_size=10;
    	isset($_GET['s'])?$query="select count(*) as total from status where  post_type = 'post' and post_status = 'publish' and post_content like '%".$s."%'":$query="select count(*) as total from status where  post_type = 'post' and post_status = 'publish' ";
    	$result_p=mysql_query($query);
    	$status_count=mysql_result($result_p,0,"total");
    	$page_count=ceil($status_count/$page_size);
    	$offset=($page-1)*$page_size;
    	isset($_GET['s'])?$sql=mysql_query("select * from status where post_type = 'post' and post_status = 'publish' and post_content like '%".$s."%' order by post_date desc limit $offset,$page_size"):$sql=mysql_query("select * from status where post_type = 'post' and post_status = 'publish'  order by post_date desc limit $offset,$page_size");
    	$sql_user=mysql_query("select * from user");
    	$row_user=mysql_fetch_assoc($sql_user);
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
		/*
		$(".item").mouseover(function(){
			$(this).css("background","#eee");
			});
		$(".item").mouseout(function(){
			$(this).css("background","#fff");
			});
		*/
	});
</script>
<title>干啥子</title>
</head>

<body>
<div id="wrapper">
	<div id="header">
		<div id="logo">
			<a href="."><img src="images/logo_ganshazi.png"></img></a>
		</div>
		<div id="search-bar">
			<form action="index.php" method="get">
				<input name="s" type="text" value="输入关键字..." onclick="this.value='';" />
				<input type="submit" value="搜索" />
			</form>
		</div>
		<div id="account-binding">
			<a href="accounts.php" title="帐号绑定">帐号绑定</a>
		</div>
	</div>
	<div id="status">
	<textarea id="postform" rows="3" cols="65"></textarea>
	
	<input type="button" id="btnPost" value="发送" />
	<ol id="status-list">
	<?php 
		while (($row = mysql_fetch_array($sql, MYSQL_ASSOC))==true) {
    			if(!$row)
    			{
    				echo "暂无任何数据。";
    				break;
    			}
	?>
		<li class="item" id="<?php echo $row['ID']?>">
			<div class="avatar">
				<span class="b">
						<img src="images/tun.jpg" width="48" height="48" title="tun的头像"/>
					
				</span>
			</div>
			<div class="info">
				<a href="#" class="username"><?php echo $row_user['screen_name']?></a> 在 
				<span class="meta">
					<a class="time-stamp" title="<?php echo $row['post_date']?>" href="detail.php?id=<?php echo $row['ID']?>"><?php echo $row['post_date']?></a> 使用 网页 发布
				</span>
			</div>
			<div class="content">
			<?php 
				echo $row['post_content'];
				/*
				if($row['picPath']!="")
				{
					echo '<a class="lightbox" href="img/'.str_replace("100x75","640x480",substr($row['picPath'],-43,43)).'"> <img src=img/'.substr($row['picPath'],-43,43).' /></a>';
				}
				*/
			?>
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
	</div>
	<!--
	<div id="sidebar">
		<div id="icon">
		<img src="images/tun.jpg" title="头像"/>
		<ul id="stat">
			<li>帐号 <?php echo $row_user['name']?> </li>
			<li>所在地 <?php echo $row_user['screen_name']?></li>
			<li>网站 ??</li>
			<li>兴趣爱好 ?? </li>
			<li>个人简介 <?php echo $row_user['description']?></li>
			<li><?php echo $row_user['followers_count']?> 个我更随的人</li>
			<li><?php echo $row_user['following_count']?> 个跟随我的人</li>
			<li><?php echo $row_user['statuses_count']?> 个状态</li>
		</ul>
		</div>
	</div>
	-->
	<div id="footer">copyright© 2010  <a href="http://tunps.com">tunpishuang</a>. All rights reserved.</div>
	<script src="http://s14.cnzz.com/stat.php?id=2250487&web_id=2250487&show=pic" language="JavaScript"></script>
</div>
</body>
</html>
