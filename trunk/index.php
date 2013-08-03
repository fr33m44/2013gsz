<?php 
	//连接数据库
	require_once 'conn.php';
	//start session
	session_start();
	if($_SESSION['userid'] == "")
		header('location:login.php');
	//new edit del data
	if(isset($_POST['a']))
	{
	
		$a  	= $_POST['a'];
		if($a == 'edit'){
			$id 	= $_POST['id'];
			$title  = $_POST['title'];
			$date 	= $_POST['date'];
			$content= $_POST['content'];
			$result = mysql_query($q = sprintf("update diary set date='%s', title='%s', content='%s' where id=%s",$date, $title, $content, $id));
			
			if($result)
			{
				echo '保存成功';
			}
			else
			{
				echo '保存失败';
			}
		}
		elseif($a == 'new'){
			
		}
		elseif($a =='search'){
			$page_size = 10;
			$key = $_POST['key'];
			$p = $_POST['p'];
			$query = sprintf("select count(*) as total from diary where title like '%%%s%%' or content like '%%%s%%'", $key, $key);
			//print_r($query);
			$result_count = mysql_query($query);
			$count = mysql_result($result_count,0, "total");
			$page_count = ceil($count/$page_size);
			$offset = ($p-1)*$page_size;
			//返回搜索结果
			$query = sprintf("select * from diary where title like '%%%s%%' or content like '%%%s%%' order by date desc limit $offset,$page_size", $key, $key);
			$result_search = mysql_query($query);
			$i=0;
			while($row_search = mysql_fetch_assoc($result_search))
			{
				$rows_search[$i++] = $row_search;
			}
			//print_r($rows_search);
			echo json_encode($rows_search);
			
			die;
		}
		else{
			
		}
		
		die;
	}
	//date filter
 	isset($_GET['y'])?$y=$_GET['y']:$y=0;
	isset($_GET['m'])?$m=$_GET['m']:$m=0;
	isset($_GET['d'])?$d=$_GET['d']:$d=0;
	//action
	//isset($_GET['a'])?$d=$_GET['a']:$a=0;// edit del add
 	//search
 	//isset($_GET['s'])?$s=$_GET['s']:$s=null;
 	
	/*
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
	}
	*/
	$yearList = mysql_query("select extract( year from date) as yearlist from diary group by extract( year from date) order by yearlist desc");
	if($y)
		$monthList = mysql_query("select extract( month from date) as monthlist from diary where date<'$y-12-31' && date>'$y-0-0' group by extract( month from date) order by monthlist desc");
	if($y && $m)
		$dayList = mysql_query("select extract( day from date) as daylist, title from diary where date<'$y-$m-31' && date>'$y-$m-0' group by extract( day from date) order by daylist desc");
	if($y && $m && $d)
		$content = mysql_query("select * from diary where date='$y-$m-$d'");
	
	
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" media="all" href="static/reset.css" />
<link rel="stylesheet" type="text/css" media="all" href="static/text.css" />
<link rel="stylesheet" type="text/css" media="all" href="static/960.css" />
<!--<link rel="stylesheet" type="text/css" media="all" href="static/demo.css" />-->
<link rel="stylesheet" type="text/css" media="all" href="static/base.css" />
<link href="static/jquery.lightbox-0.5.css" rel="stylesheet" />
<script language="javascript" type="text/javascript" src="static/jquery.js"></script>
<script language="javascript" type="text/javascript" src="static/jquery.lightbox-0.5.js"></script>
<script language="javascript" src="lib/kindeditor/kindeditor.js"></script>
<script language="javascript" src="lib/kindeditor/lang/zh_CN.js"></script>

<title>干啥子日记[ganshazi diary]</title>
</head>

<body>
	<div class="container_12" id="list">
		<!--head-->
		<div class="grid_12"><h1><a href="index.php">干啥子日记[ganshazi diary]</a></h1></div>
		<div class="clear"></div>
		
		<!--operation-->
		<div class="grid_6">&nbsp;</div>
		
		<div class="grid_3"><input type="text" id="search_key" placeholder="Search Diary Here..." size="20" /><input type="button" id="link_search" value="Search" /></div>
		
		<div class="grid_1"><a class="linkbtn" href="new.php" id="link_new">New Diary</a></div>
<?php if($y && $m && $d){?>
		<div class="grid_1"><a class="linkbtn" href="javascript:;" id="link_edit">Edit Diary</a></div>
		
		<div class="grid_1"><a class="linkbtn hide" href="javascript:;" id="link_save">Save Diary</a></div>
<?php } ?>
		<div class="clear"></div>
		<!--list-->
		<div class="grid_1" id="year">
			<ul class="list">
			<?php
				while($yearListArr = mysql_fetch_assoc($yearList))
				{
					echo '<li><a href="index.php?y='.$yearListArr['yearlist'].'">'.$yearListArr['yearlist']."年</a></li>\n";
				}
			?>
			</ul>
		</div>
		<div class="grid_1" id="month">
			<ul class="list">
			<?php
				if($y)
				{
					while($monthListArr = mysql_fetch_assoc($monthList))
					{
						echo '<li><a href="index.php?y='.$y.'&m='.$monthListArr['monthlist'].'">'.$monthListArr['monthlist']."月</a></li>\n";
					}
				}
			?>
			</ul>
		</div>
		<div class="grid_2" id="day">
			<ul class="list">
			<?php
				if($y && $m)
				{
					while($dayListArr = mysql_fetch_assoc($dayList))
					{
						echo sprintf("<li><a href=\"index.php?y=%s&m=%s&d=%s\">%s日 %s</a></li>\n", $y, $m, $dayListArr['daylist'], $dayListArr['daylist'], $dayListArr['title']);
					}
				}
			?>
			</ul>
		</div>
<?php 
if($y && $m && $d)
	{
		$content = mysql_fetch_assoc($content);
?>
		<div class="grid_8" id="content" name="content">
			<div id="title"><h2 id="titleHead"><?php echo $content['title']?></h2></div>
			<div id="dates"><em>Date:<span id="date"><?php echo $content['date']?></span> | Post Date:<?php echo $content['post_date']?> | Last Modified:<?php echo $content['post_modified']?></em></div>
			<div id="contentText"><?php echo $content['content']?></div>
			<input type="hidden" name="id" value="<?php echo $content['id']?>"/>
		</div>
<?php } ?>
		<div class="clear"></div>
		<!--copyright-->
		<div class="grid_12" id="footer">copyright© 2010  <a href="http://tunps.com">tunpishuang</a>. All rights reserved.</div>
		<div class="clear"></div>
	</div>
</div>
<script language="javascript" type="text/javascript">
$(function(){
<?php 
	if($y && $m && $d){
?>
	var id=<?php echo $content['id']?>;
	
	$('#link_edit').click(function(){
		if(typeof $('input[id="title"]').attr('id') == 'undefined')
		{
			$('#link_save').removeClass('hide');
			$.getScript('lib/kindeditor/kindeditor-min.js', function(){
				KindEditor.basePath = 'lib/kindeditor/';
				KindEditor.create('div[id="contentText"]');
			});
			var title = $("#titleHead").html();
			var date = $("#date").html();
			$("#title").html('<h2><input name="title" id="title" type="text" size="80" value="'+title+'"+ style="padding:3px 8px" /></h2>');
			$("#date").html('<input type="text" name="date" value="'+date+'"/>');
		}
	}); 
	
	$('#link_save').click(function(){
		KindEditor.remove('div[id="contentText"]');
		$.ajax({
			type:"POST",
			contentType: "application/x-www-form-urlencoded;charset=utf-8",
			url:location.href,
			data:{	
					'a':'edit',
					'id':id,
					'date':$('input[name="date"]').val(),
					'title':$('input[name="title"]').val(), 
					'content':$('#contentText').html()
				},
			success: function(msg){
				alert(msg);
				location.replace(location.href);
			}
		}); 
	});
<?php } ?>
	
	$('#link_search').click(function(){
		$.ajax({
			type:"POST",
			data:{'a':'search','key':$('#search_key').val(),'p':1},
			url:"index.php?a=search&key="+$('#search_key').val(),
			success: function(msg){
				//alert(msg);
				//location.replace(location.href);
				//parse result into 
			}
		}); 
	});
});
</script>
</body>
</html>