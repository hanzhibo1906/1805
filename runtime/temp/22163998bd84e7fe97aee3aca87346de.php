<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:78:"/data/wwwroot/default/tp5/public/../application/index/view/login/register.html";i:1537521374;}*/ ?>
<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="UTF-8">
	<title>注册</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<meta name="format-detection" content="telephone=no">
	<meta name="renderer" content="webkit">
	<meta http-equiv="Cache-Control" content="no-siteapp" />

	<link rel="stylesheet" href="__STATIC__/index/login/amazeui.css" />
	<link href="__STATIC__/index/login/dlstyle.css" rel="stylesheet" type="text/css">
	<script src="__STATIC__/layui.js"></script>
	<link rel="stylesheet" href="__STATIC__/css/layui.css">
	<script src="__STATIC__/index/login/jquery-3.2.1.min.js"></script>
	<script src="__STATIC__/index/login/amazeui.min.js"></script>

</head>

<body>

<div class="login-boxtitle">
	<a href="home/demo.html"><img alt="" src="__STATIC__/index/login/logobig.png" /></a>
</div>

<div class="res-banner">
	<div class="res-main">
		<div class="login-banner-bg"><span></span><img src="__STATIC__/index/login/big.jpg" /></div>
		<div class="login-box">

			<div class="am-tabs" id="doc-my-tabs">
				<ul class="am-tabs-nav am-nav am-nav-tabs am-nav-justify">
					<li class="am-active"><a href="">邮箱注册</a></li>
					<li><a href="">手机号注册</a></li>
				</ul>

				<div class="am-tabs-bd">

					<!--邮箱-->
					<div class="am-tab-panel am-active">
						<form  class="layui-form">
							<!--邮箱-->
							<div class="user-email">
								<label for="email"><i class="am-icon-envelope-o"></i></label>
								<input type="email" name="user_email" lay-verify="required" id="email" placeholder="请输入邮箱账号">
							</div>
							<div class="verification">
								<label for="code"><i class="am-icon-code-fork"></i></label>
								<input type="tel" name="user_code" lay-verify="required" id="code" placeholder="请输入验证码">
								<a class="btn" href="javascript:void(0);" id="sendEmailCode">
									<span class="dyButton" id="span_email">获取</span></a>
							</div>
							<div class="user-pass">
								<label for="email_pwd"><i class="am-icon-lock"></i></label>
								<input type="password" name="user_pwd" lay-verify="checkpwd" id="email_pwd" placeholder="设置密码">
							</div>
							<div class="user-pass">
								<label for="email_pwd1"><i class="am-icon-lock"></i></label>
								<input type="password" name="user_pwd1" lay-verify="checkpwd1" id="email_pwd1" placeholder="确认密码">
							</div>
							<div class="am-cf">
								<input type="submit" name="" value="注册" lay-submit lay-filter="Demo" class="am-btn am-btn-primary am-btn-sm am-fl">
							</div>
						</form>


					</div>
					<!--手机号-->
					<div class="am-tab-panel">
						<form  class="layui-form">
							<div class="user-phone">
								<label for="phone"><i class="am-icon-mobile-phone am-icon-md"></i></label>
								<input type="tel" name="user_tel" lay-verify="required" id="phone" placeholder="请输入手机号">
							</div>
							<div class="verification">
								<label for="code"><i class="am-icon-code-fork"></i></label>
								<input type="tel" name="user_code" lay-verify="required" id="tel_code" placeholder="请输入验证码">
								<a class="btn" href="javascript:void(0);" id="sendTelCode">
									<span class="dyButton" id="span_tel">获取</span>
								</a>
							</div>
							<div class="user-pass">
								<label for="tel_pwd"><i class="am-icon-lock"></i></label>
								<input type="password" name="user_pwd" lay-verify="checkpwd" id="tel_pwd" placeholder="设置密码">
							</div>
							<div class="user-pass">
								<label for="tel_pwd1"><i class="am-icon-lock"></i></label>
								<input type="password" name="user_pwd1" lay-verify="checkpwd1" id="tel_pwd1" placeholder="确认密码">
							</div>
							<div class="am-cf">
								<input type="button"  value="注册"  lay-submit lay-filter="Demo" class="am-btn am-btn-primary am-btn-sm am-fl">
							</div>
						</form>
						<hr>
					</div>
					<script>
						$(function() {
							$('#doc-my-tabs').tabs();
						})
					</script>
				</div>
			</div>
		</div>
	</div>
	<div class="footer ">
		<div class="footer-hd ">
			<p>
				<a href="# ">恒望科技</a>
				<b>|</b>
				<a href="# ">商城首页</a>
				<b>|</b>
				<a href="# ">支付宝</a>
				<b>|</b>
				<a href="# ">物流</a>
			</p>
		</div>
		<div class="footer-bd ">
			<p>
				<a href="# ">关于恒望</a>
				<a href="# ">合作伙伴</a>
				<a href="# ">联系我们</a>
				<a href="# ">网站地图</a>
				<em>© 2015-2025 Hengwang.com 版权所有. 更多模板 <a href="http://www.cssmoban.com/" target="_blank" title="模板之家">模板之家</a> - Collect from <a href="http://www.cssmoban.com/" title="网页模板" target="_blank">网页模板</a></em>
			</p>
		</div>
	</div>
</body>
</html>
<script>
	$(function(){
		layui.use(['layer','form'],function(){
			var layer=layui.layer;
			var form=layui.form;
			var second=60;
			var _time;
			//给获取的按钮来绑定点击事件
			$('.btn').click(function(){
				var _this=$(this);
				//获取手机号或者邮箱的值
				var _value=_this.parent('div').prev('div').find('input').val();
				if(_value==""){
					layer.msg('手机号或邮箱不能为空',{icon:2});
				}else{
					$.post(
							'<?php echo url("Login/send"); ?>',
							{value:_value},
							function(msg){
								console.log(msg);
								layer.msg(msg.font,{icon:msg.code});
							},'json'
					)
					//获取60秒
					//console.log(11111);
					_this.find('span').text(second+'s');
					if(_value.indexOf('@')==-1){
						_time=setInterval(secondTelTime,1000);
					}else{
						_time=setInterval(secondEmailTime,1000);
					}
				}
				//ajax传给php来做验证
			});

			//秒数倒计时
			function secondTelTime(){
				//获取span_tel
				var second=parseInt($('#span_tel').text());
				if(second==0){
					$('#span_tel').text('获取');
					clearInterval(_time);
					$('#sendTelCode').css('pointer-events','auto');

				}else{
					second=second-1;
					$('#span_tel').text(second+'s');
					$('#sendTelCode').css('pointer-events','none');
				}

			}
			function secondEmailTime(){
				//获取span_tel
				var second=parseInt($('#span_email').text());
				if(second==0){
					$('#span_email').text('获取');
					clearInterval(_time);
					$('#sendEmailCode').css('pointer-events','auto');

				}else{
					second=second-1;
					$('#span_email').text(second+'s');
					$('#sendEmailCode').css('pointer-events','none');
				}

			}
			form.verify({
				checkpwd:function(value,item){
					if(value==""){
						return '密码必填'
					}else if(value.length<6){
						return '密码必须6位以上'
					}
				},
				checkpwd1:function(value,item){
					var pwd=$('#tel_pwd').val();
					var pwd1=$('#email_pwd').val();
					if(value==""){
						return '确认密码必填';
					}else if(value!=pwd&&value!=pwd1){
						return '确认密码必须和密码一致';
					}
				}
			});
			form.on('submit(Demo)',function(data){
				$.post(
						'<?php echo url("Login/register"); ?>',
						data.field,
						function(msg){
							console.log(msg);
							layer.msg(msg.font,{icon:msg.code});
							if(msg.code==1){
								location.href="<?php echo url('Login/login'); ?>";
							}
						},'json'
				);
				return false;
			});
		})

	})
</script>