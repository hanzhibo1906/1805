<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:75:"/data/wwwroot/default/tp5/public/../application/index/view/login/login.html";i:1539917524;}*/ ?>
<!DOCTYPE html>
<html>

<head lang="en">
    <meta charset="UTF-8">
    <title>登录</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <script src="__STATIC__/index/login/jquery-3.2.1.min.js"></script>
    <link rel="stylesheet" href="__STATIC__/css/layui.css">
    <script src="__STATIC__/layui.js"></script>
    <link rel="stylesheet" href="__STATIC__/index/login/amazeui.css" />
    <link href="__STATIC__/index/login/dlstyle.css" rel="stylesheet" type="text/css">
</head>

<body>

<div class="login-boxtitle">
    <a href="home.html"><img alt="logo" src="__STATIC__/index/login/logobig.png" /></a>
</div>

<div class="login-banner">
    <div class="login-main">
        <div class="login-banner-bg"><span></span><img src="__STATIC__/index/login/big.jpg" /></div>
        <div class="login-box">

            <h3 class="title">登录商城</h3>

            <div class="clear"></div>

            <div class="login-form">
                <form>
                    <div class="user-name">
                        <label for="account"><i class="am-icon-user"></i></label>
                        <input type="text" name="user" id="account" value="<?php echo isset($userInfo['account']) ? $userInfo['account'] :  ''; ?>" placeholder="邮箱/手机号">
                    </div>
                    <div class="user-pass">
                        <label for="user_pwd"><i class="am-icon-lock"></i></label>
                        <input type="password" name="user_pwd" id="user_pwd"  value="<?php echo isset($userInfo['user_pwd']) ? $userInfo['user_pwd'] :  ''; ?>"  placeholder="请输入密码">
                    </div>
                </form>
            </div>

            <div class="login-links">
                <label for="rememberPwd"><input id="rememberPwd" type="checkbox">记住密码10天</label>
                <a href="<?php echo url('Login/register'); ?>" class="zcnext am-fr am-btn-default">注册</a>
                <br />
            </div>
            <div class="am-cf">
                <input type="button" id="btn" value="登 录" class="am-btn am-btn-primary am-btn-sm">
            </div>
            <div class="partner">
                <h3>合作账号</h3>
                <div class="am-btn-group">
                    <li><a href="#"><i class="am-icon-qq am-icon-sm"></i><span>QQ登录</span></a></li>
                    <li><a href="#"><i class="am-icon-weibo am-icon-sm"></i><span>微博登录</span> </a></li>
                    <li><a href="#"><i class="am-icon-weixin am-icon-sm"></i><span>微信登录</span> </a></li>
                </div>
            </div>

        </div>
    </div>
</div>
<script>
    $(function(){
        layui.use(['layer'],function(){
                layer=layui.layer;
            $('#btn').click(function(){
                var account=$('#account').val();
                var user_pwd=$('#user_pwd').val();
                var rememberPwd=$('#rememberPwd').prop('checked');
                if(account==""){
                    layer.msg('手机号或邮箱必填',{icon:2});
                    return false;
                }

                if(user_pwd==""){
                    layer.msg('密码必填',{icon:2});
                    return false;
                }

                var callback = "<?php echo urldecode(request() -> param('callback'));?>";

                $.post(
                        '<?php echo url("Login/login"); ?>',
                        {account:account,user_pwd:user_pwd,rememberPwd:rememberPwd},
                        function(msg){
                            layer.msg(msg.font,{icon:msg.code});
                            if(msg.code==1){
                                if( callback == '' ){
                                    location.href="<?php echo url('Index/index'); ?>";
                                }else{
                                    location.href= callback;
                                }
                            }
                        },'json'
                )
            })
        })
    })
</script>