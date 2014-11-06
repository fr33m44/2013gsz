<?php 
	//连接数据库
	require_once 'conn.php';
	//start session
	session_start();
	if($_SESSION['userid'] == "")
		header('location:login.php');
	
	isset($_GET['a']) ? $a = $_GET['a'] : $a = null;
	//new edit del data
	if(isset($_POST['a']))
	{
		$a 	= $_POST['a'];
		if($a == 'edit')
		{
			$id 	= $_POST['id'];
			$title  = $_POST['title'];
			$date 	= $_POST['date'];
			$content_text = $_POST['content_text'];
			$content_html = $_POST['content_html'];
			$result = mysql_query($q = sprintf("update diary set date='%s', title='%s', content_text='%s', content_html='%s' where id=%s",$date, $title, $content_text, $content_html, $id));
			
			if($result)
			{
				echo '保存成功';
			}
			else
			{
				echo '保存失败';
			}
		}
		elseif($a == 'new')
		{
			$title  = $_POST['title'];
			$date 	= $_POST['date'];
			$content_text = $_POST['content_text'];
			$content_html = $_POST['content_html'];
			$result = mysql_query($q = sprintf("insert into diary set date='%s', title='%s', content_text='%s', content_html='%s' ",$date, $title, $content_text, $content_html));
			
			if($result)
			{
				echo '保存成功';
			}
			else
			{
				echo '保存失败';
			}
		}
		elseif($a =='search'){
			$page_size = 10;
			$key = $_POST['key'];
			$p = $_POST['p'];
			$query = sprintf("select count(1) as total from diary where title like '%%%s%%' or content like '%%%s%%'", $key, $key);
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
		$monthList = mysql_query("select lpad(extract( month from date),2,0) as monthlist from diary where date <= '$y-12-31' && date >= '$y-0-0' group by extract( month from date) order by monthlist desc");
	if($y && $m)
		$dayList = mysql_query("select lpad(extract( day from date),2,0) as daylist, DAYOFWEEK(DATE) AS weeknum, title from diary where date <= '$y-$m-31' && date >= '$y-$m-0' group by extract( day from date) order by daylist desc");
	if($y && $m && $d)
		$content = mysql_query("select *,DAYOFWEEK(DATE) AS weeknum from diary where date = '$y-$m-$d'");
	
	
	
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
<link rel="stylesheet" type="text/css" media="all" href="static/pagedown.css" />
<link href="static/jquery.lightbox-0.5.css" rel="stylesheet" />
<script language="javascript" type="text/javascript" src="static/jquery.js"></script>
<script language="javascript" type="text/javascript" src="static/jquery.lightbox-0.5.js"></script>
<!--<script language="javascript" src="lib/kindeditor/kindeditor.js"></script>-->
<!--<script language="javascript" src="lib/kindeditor/lang/zh_CN.js"></script>-->
<script language="javascript" type="text/javascript" src="static/Markdown.Converter.js"></script>
<script language="javascript" type="text/javascript" src="static/Markdown.Sanitizer.js"></script>
<script language="javascript" type="text/javascript" src="static/Markdown.Editor.js"></script>


<title>干啥子日记[ganshazi diary]</title>
</head>

<body>
	<div class="container_12" id="list">
		<!--head-->
		<div class="grid_12"><h1><a href="index.php">干啥子日记[ganshazi diary]</a></h1></div>
		<div class="clear"></div>
		
		<!--operation-->
		<div class="grid_6">&nbsp;</div>
		
		<div class="grid_3"><input type="text" id="search_key" placeholder="输入关键字搜索..." size="20" /><input type="button" id="link_search" value="Search" /></div>
		
		<div class="grid_3">
			<a class="linkbtn" href="index.php?a=new" id="link_new">New</a>
<?php if($y && $m && $d){?>
			<a class="linkbtn" href="javascript:;" id="link_edit">Edit</a>
			<a class="linkbtn" href="javascript:;" id="link_save">Save</a>
<?php } elseif($a == 'new'){?>
			<a class="linkbtn" href="javascript:;" id="link_save">Save</a>
<?php } ?>
		</div>
		<div class="clear"></div>
		<!--list-->
		<div class="grid_1" id="year">
			<ul class="list">
			<?php
				while($yearListArr = mysql_fetch_assoc($yearList))
				{
					if($y == $yearListArr['yearlist'])
						echo '<li style="font-weight:bold"><a href="index.php?y='.$yearListArr['yearlist'].'">'.$yearListArr['yearlist']."年</a></li>\n";
					else
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
						if($m == $monthListArr['monthlist'])
							echo '<li style="font-weight:bold"><a href="index.php?y='.$y.'&m='.$monthListArr['monthlist'].'">'.$monthListArr['monthlist']."月</a></li>\n";
						else
							echo '<li><a href="index.php?y='.$y.'&m='.$monthListArr['monthlist'].'">'.$monthListArr['monthlist']."月</a></li>\n";
					}
				}
			?>
			</ul>
		</div>
		<div class="grid_3" id="day">
			<ul class="list">
			<?php
				if($y && $m)
				{
					while($dayListArr = mysql_fetch_assoc($dayList))
					{
						$bEmphasizeWeekend = false;
						
						if($dayListArr["weeknum"] == 1)
						{
							$dayListArr["week_name"] = "日";
							$bEmphasizeWeekend = true;
						}
						elseif($dayListArr["weeknum"] == 2)
							$dayListArr["week_name"] = "一";
						elseif($dayListArr["weeknum"] == 3)
							$dayListArr["week_name"] = "二";
						elseif($dayListArr["weeknum"] == 4)
							$dayListArr["week_name"] = "三";
						elseif($dayListArr["weeknum"] == 5)
							$dayListArr["week_name"] = "四";
						elseif($dayListArr["weeknum"] == 6)
							$dayListArr["week_name"] = "五";
						elseif($dayListArr["weeknum"] == 7)
						{
							$dayListArr["week_name"] = "六";
							$bEmphasizeWeekend = true;
						}
						if($bEmphasizeWeekend)
							if($d == $dayListArr['daylist'])
								echo sprintf("<li style=\"font-weight:bold\"><a href=\"index.php?y=%s&m=%s&d=%s\">%s日<span style=\"color:red\">[%s]</span> %s</a></li>\n", $y, $m, $dayListArr['daylist'], $dayListArr['daylist'], $dayListArr['week_name'], $dayListArr['title']);
							else
								echo sprintf("<li><a href=\"index.php?y=%s&m=%s&d=%s\">%s日<span style=\"color:red\">[%s]</span> %s</a></li>\n", $y, $m, $dayListArr['daylist'], $dayListArr['daylist'], $dayListArr['week_name'], $dayListArr['title']);
							
						else
							if($d == $dayListArr['daylist'])
								echo sprintf("<li style=\"font-weight:bold\"><a href=\"index.php?y=%s&m=%s&d=%s\">%s日[%s] %s</a></li>\n", $y, $m, $dayListArr['daylist'], $dayListArr['daylist'], $dayListArr['week_name'], $dayListArr['title']);
							else
								echo sprintf("<li><a href=\"index.php?y=%s&m=%s&d=%s\">%s日[%s] %s</a></li>\n", $y, $m, $dayListArr['daylist'], $dayListArr['daylist'], $dayListArr['week_name'], $dayListArr['title']);
					}
				}
			?>
			</ul>
		</div>
<?php
//show diary content or show new diary editor UI
if($y && $m && $d)
	{
		$content = mysql_fetch_assoc($content);
?>
		<div class="grid_7" id="content" name="content">
			<div id="title"><h2 id="titleHead"><?php echo $content['title']?></h2></div>
			<div id="dates"><em>Date:<span id="date"><?php echo $content['date']?></span> | Post Date:<?php echo $content['post_date']?> | Last Modified:<?php echo $content['post_modified']?></em></div>
			<div id="editor" class="wmd-panel">
				<div id="wmd-button-bar"></div>
				<textarea class="wmd-input content-md" id="wmd-input"><?php echo $content['content_text']?></textarea>
				<input type="hidden" name="id" value="<?php echo $content['id']?>"/>
			</div>
			<div id="wmd-preview" class="wmd-panel wmd-preview"></div>
		</div>
<?php }elseif($a == 'new'){?>		
		<div class="grid_7" id="content" name="content">
			<div id="title"><h2><input name="title" id="title" type="text" size="80" style="padding:3px 8px" /></h2></div>
			<div id="dates"><em>Date:<input type="text" name="date" /></em></div>
			<div id="editor" class="wmd-panel">
				<div id="wmd-button-bar"></div>
				<textarea class="wmd-input content-md" id="wmd-input"></textarea>
				<input type="hidden" name="id" />
			</div>
			<div id="wmd-preview" class="wmd-panel wmd-preview"></div>
		</div>
<?php }else {?>
		<div class="grid_7" id="content" name="content">
		</div>
<?php } ?>

		<div class="clear"></div>
		<!--copyright-->
		<div class="grid_12" id="horizon"><hr /></div>
		<div class="clear"></div>
		<div class="grid_12" id="footer">copyright© 2010-  <a href="http://tunps.com">tunpishuang</a>. All rights reserved.</div>
		<div class="clear"></div>
	</div>
</div>
<script language="javascript" type="text/javascript">
$(function(){
<?php 
	if($y && $m && $d){
?>
	var id=<?php echo $content['id']?>;
	$('#link_save').click(function(){
		$.ajax({
			type:"POST",
			contentType: "application/x-www-form-urlencoded;charset=utf-8",
			url:location.href,
			data:{
					'a':'edit',
					'id':id,
					'date':$('input[name="date"]').val(),
					'title':$('input[name="title"]').val(), 
					'content_text':$('.content-md').val(),
					'content_html':$('#wmd-preview').html()
				},
			success: function(msg){
				alert(msg);
				location.replace(location.href);
			}
		}); 
	});
	$('#editor').hide();//隐藏编辑界面
	$('#wmd-preview').show();//显示html
<?php } ?>

<?php 
	if($a == 'new'){
?>
	$('#link_save').click(function(){
		$.ajax({
			type:"POST",
			contentType: "application/x-www-form-urlencoded;charset=utf-8",
			url:location.href,
			data:{
					'a':'new',
					'date':$('input[name="date"]').val(),
					'title':$('input[name="title"]').val(), 
					'content_text':$('.content-md').val(),
					'content_html':$('#wmd-preview').html()
				},
			success: function(msg){
				alert(msg);
				location.replace(location.href);
			}
		}); 
	});
	$('#editor').hide();//隐藏编辑界面
	$('#wmd-preview').show();//显示html
<?php } ?>

<?php 
	if(($y && $m && $d)|| ($a == 'new')) {
?>
	var converter1 = Markdown.getSanitizingConverter();
			converter1.hooks.chain("preBlockGamut", function (text, rbg) {
				return text.replace(/^ {0,3}""" *\n((?:.*?\n)+?) {0,3}""" *$/gm, function (whole, inner) {
					return "<blockquote>" + rbg(inner) + "</blockquote>\n";
				});
			});
	var editor1 = new Markdown.Editor(converter1);
	editor1.run();
<?php } ?>

<?php 
	if($a == 'new') {
?>
	$('#editor').show();//隐藏编辑界面
	$('#wmd-preview').show();//显示html
<?php } ?>

	$('#link_edit').click(function(){
		if(typeof $('input[id="title"]').attr('id') == 'undefined')
		{
			var title = $("#titleHead").html();
			var date = $("#date").html();
			$("#title").html('<h2><input name="title" id="title" type="text" size="80" value="'+title+'"+ style="padding:3px 8px" /></h2>');
			$("#date").html('<input type="text" name="date" value="'+date+'"/>');
			$('#editor').show();//隐藏编辑界面
		}
	}); 
	

	
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