<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:81:"D:\phpstudy_pro\WWW\aliyun_1805\public/../application/admin\view\login\login.html";i:1541746348;}*/ ?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>系统登录</title>
<link href="__STATIC__/admin/css/login.css" rel="stylesheet" rev="stylesheet" type="text/css" media="all" />
<link href="__STATIC__/admin/css/demo.css" rel="stylesheet" rev="stylesheet" type="text/css" media="all" />
<link rel="stylesheet" href="__STATIC__/css/layui.css">
<script src="__STATIC__/layui.js"></script>
<script type="text/javascript" src="__STATIC__/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="__STATIC__/admin/js/jquery.SuperSlide.js"></script>
<script type="text/javascript" src="__STATIC__/admin/js/Validform_v5.3.2_min.js"></script>

<div class="header">
  <h1 class="headerLogo"><a title="后台管理系统" target="_blank" href="#"><img alt="logo" src="__STATIC__/admin/images/logo.gif"></a></h1>
</div>

<div class="banner">

<div class="login-aside">
  <div id="o-box-up"></div>
  <div id="o-box-down"  style="table-layout:fixed;">
   <div class="error-box"></div>

   <form class="layui-form" action="">
   <div class="fm-item">
	   <label for="logonId" class="form-label">shop系统登陆：</label>
	   <input type="text" placeholder="输入账号" name="admin_name"
              value='likang' lay-verify="required|username" class="i-text">
       <div class="ui-form-explain"></div>
  </div>

  <div class="fm-item">
	   <label for="logonId" class="form-label">登陆密码：</label>
	   <input type="password"
             value="123456" maxlength="100" name="admin_pwd"
              class="i-text" lay-verify="required" placeholder="请设置密码！">
       <div class="ui-form-explain"></div>
  </div>

  <div class="fm-item pos-r">
	   <label for="logonId" class="form-label">验证码</label>
	   <input type="text" placeholder="输入验证码"
              value="8888" lay-verify="required" name="mycode" class="i-text yzm">
        <div><img src="<?php echo captcha_src(); ?>"  id='code' class="yzm-img" alt="captcha" /></div>
  </div>
  <div class="fm-item">
	   <label for="logonId" class="form-label"></label>
	   <input type="submit" value="" lay-submit lay-filter="*" tabindex="4" id="send-btn" class="btn-login">
       <div class="ui-form-explain"></div>
  </div>
  </form>
  </div>
</div>
	<div class="bd">
		<ul>
			<li style="background:url(__STATIC__/admin/themes/theme-pic1.jpg) #CCE1F3 center 0 no-repeat;"></li>
			<li style="background:url(__STATIC__/admin/themes/theme-pic2.jpg) #BCE0FF center 0 no-repeat;"></li>
		</ul>
	</div>
</div>
</body>
</html>
<Script>
    $(function() {
        layui.use(['form', 'layer'], function () {
            var form = layui.form;
            //验证码切换
            $('#code').click(function(){
                $('#code').attr('src','<?php echo captcha_src(); ?>?+num='+Math.random());
            });
            form.on('submit(*)',function(data){

                $.post(
                        "<?php echo url('Login/login'); ?>",
                        data.field,
                        function(msg){
                            layer.msg(msg.font,{icon:msg.code});
                            if(msg.code==1){
                                location.href="<?php echo url('Index/index'); ?>";
                                $('#code').attr('src','<?php echo captcha_src(); ?>?+num='+Math.random());
                            }
                        },
                    'json'
                );
                return false;
            })
        })
    })

</Script>
