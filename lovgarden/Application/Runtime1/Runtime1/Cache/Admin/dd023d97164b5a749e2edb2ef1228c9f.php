<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<title>后台登录</title>
<meta name="author" content="DeathGhost" />
<link rel="stylesheet" type="text/css" href="/Public/Admin/css/style.css" />
<style>
body{height:100%;background:#16a085;overflow:hidden;}
canvas{z-index:-1;position:absolute;}
</style>
<script src="/Public/Admin/js/jquery.js"></script>
<script src="/Public/Admin/js/verificationNumbers.js"></script>
<script src="/Public/Admin/js/Particleground.js"></script>
<script>
$(document).ready(function() {
  //粒子背景特效
  $('body').particleground({
    dotColor: '#5cbdaa',
    lineColor: '#5cbdaa'
  });
  //验证码
  createCode();
  //测试提交，对接程序删除即可
  $(".submit_btn").click(function(){
	  location.href="index.html";
	  });
});
</script>
</head>
<body>
<?php if(!empty($error_message)): ?>
<p style="color:red;position: absolute; top: 20%;width:100%;text-align: center;font-size: 20px;"><?php print $error_message; ?></p>
<?php endif; ?>
<form action="/admin/user/login" method="POST" class="login-form">
<dl class="admin_login">
 <dt>
  <strong>丽de花苑后台管理系统</strong>
  <em>Management System</em>
 </dt>
 <dd class="user_icon">
     <input type="text" name="user_name" placeholder="账号" class="login_txtbx" value="<?php print $user_name; ?>"/>
 </dd>
 <dd class="pwd_icon">
     <input type="password" name="user_password" placeholder="密码" class="login_txtbx"/>
 </dd>
 <dd class="val_icon">
  <div class="lovgarden-checkcode">
      <input type="text" id="J_codetext" name="user_active_code" placeholder="验证码" maxlength="4" class="login_txtbx" style="width:42%;display: inline-block;">
    <img src="/Admin/User/chkcode" onclick="this.src='<?php echo U('chkcode'); ?>#'+Math.random();" style="height:43px; display: inline-block;margin-bottom: 2px;"/>
  </div>
 </dd>
 <dd>
  <input type="submit" value="立即登录" class="submit_btn"/>
 </dd>
 <dd>
  <p>丽de花苑版权所有</p>
 </dd>
</dl>
</form>
</body>
</html>