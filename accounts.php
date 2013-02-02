<?php 
	
	function authAccount($User,$Pswd,$Network)
	{
		if($Network == 'twitter')
		{
			$curl=curl_init();
			$opt=array(
				CURLOPT_POST=>1,
				CURLOPT_URL=>'http://api.twitter.com/1/statuses/update.xml',
				CURLOPT_POSTFIELDS=>"status=playing with cURL and the Twitter API",
				CURLOPT_USERPWD=>$User.":".$Pswd
			);
			curl_setopt_array($curl,$opt);
			$res=curl_exec($curl);
			curl_close($curl);
			return $res;
		}
	}
	
	isset($_POST['user'])?$user=$_POST['user']:$user=null;
	isset($_POST['pswd'])?$pswd=$_POST['pswd']:$pswd=null;
	isset($_POST['network'])?$network=$_POST['network']:$network=null;
	
	if($user!=null && $pswd!=null)
	{ 
			authAccount($user,$pswd,$network);
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/base.css" rel="stylesheet" />
<script language="javascript" type="text/javascript" src="js/jquery.js"></script>
<script>
	$(document).ready(function(){
		//初始化
		$("#input-info").hide();
		$("#pswd").hide();

		//点击添加
		$("#add").click(function(){
			$("#input-info").fadeIn("slow");
		});

		//帐号
		$("#user").focus(function(){
			if($("#user").val()=="输入帐号...")
			{
				$("#user").val("");
			}
		});
		$("#user").blur(function(){
			if($("#user").val()=="")
			{
				$("#user").val("输入帐号...");
			}
		});
		//密码
		$("#fakepswd").focus(function(){
			$("#fakepswd").hide();
			$("#pswd").show();
			$("#pswd").focus();
		});
		$("#pswd").blur(function(){
			if($("#pswd").val()=="")
			{
				$("#fakepswd").show();
				$("#pswd").hide();
			}
		});

		//ok
		$("#ok").click(function(){
			switch($("#network")[0].options[$("#network")[0].selectedIndex].value)
			{
				case "twitter" :
					break;
				case "digu"		:
					break;
				case "renren"	:
					break;
				case "qq"		:
					break;
			}
		});
	});
</script> 

<title>干啥子 - 帐号绑定</title>
</head>

<body>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']?>">
		<div id="wrapper">
			<fieldset>
				<legend>选择网络</legend>
				<select id="network" name="network">
					<option value="twitter">推特</option>
					<option value="digu">嘀咕</option>
					<option value="renren">人人</option>
					<option value="qq">QQ</option>
				</select>
				<input type="button" id="add" value="添加" />
			</fieldset>
			<fieldset id="input-info">
				<legend>输入信息</legend>
				<input id="user" name="user" type="text" value="输入帐号..." />
				<input id="fakepswd" type="text" value="输入密码..." />
				<input id="pswd" name="pswd" type="password" />
				<input id="ok" name="ok" type="submit" value="好" />
			</fieldset> 
		</div>	
	</form>
</body>
</html>