<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</html>
<?php
	require_once(dirname(__FILE__) . '/../HTML_To_Markdown.php');
	//连接数据库
	require_once '../../conn.php';
	$query = sprintf("select * from diary  ");
	$r = mysql_query($query);
	$i=0;
	while($row = mysql_fetch_assoc($r))
	{
		//$html = null;
		//if (get_magic_quotes_gpc())

		$markdown = new HTML_To_Markdown($row['content_html']);
		$markdown = addslashes($markdown);
		$result = mysql_query($q = sprintf("update diary set content_text='%s' where id=%s",$markdown, $row["id"]));
		//echo $q;

		if($result)
		{
			echo '保存成功'.$row["id"];
		}
		else
		{
			echo '保存失败'.$row["id"];
		}
	}