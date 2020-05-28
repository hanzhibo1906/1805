<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:77:"C:\wamp64\www\1805\shop\tp5\public/../application/admin\view\index\index.html";i:1538013579;s:72:"C:\wamp64\www\1805\shop\tp5\public/../application/admin\view\layout.html";i:1538028864;s:77:"C:\wamp64\www\1805\shop\tp5\public/../application/admin\view\public\head.html";i:1538013665;s:77:"C:\wamp64\www\1805\shop\tp5\public/../application/admin\view\public\left.html";i:1538125900;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>layout 后台大布局 - Layui</title>
    <link rel="stylesheet" href="__STATIC__/css/layui.css">
    <script src="__STATIC__/jquery-3.2.1.min.js"></script>
    <script src="__STATIC__/layui.js"></script>
</head>
<style type="text/css">
    .layui-table img {
        max-width: 150px;
        min-height: 100%;
    }
    .layui-table-cell{
        height: auto!important;
        white-space: normal;
    }
</style>

<body class="layui-layout-body">
<div class="layui-layout layui-layout-admin">
    <!--头部布局-->
    <div class="layui-header">
    <div class="layui-logo">layui 后台布局</div>
    <!-- 头部区域（可配合layui已有的水平导航） -->
    <ul class="layui-nav layui-layout-left">
        <li class="layui-nav-item"><a href="">邮件管理</a></li>
        <li class="layui-nav-item"><a href="">消息管理</a></li>
        <li class="layui-nav-item"><a href="">授权管理</a></li>
    </ul>
    <ul class="layui-nav layui-layout-right">
        <li class="layui-nav-item">
            <a href="javascript:;">
                <img src="__STATIC__/images/d833c895d143ad4b6eba587980025aafa50f06f6.jpg" class="layui-nav-img">
                欢迎<?php echo \think\Session::get('admin.admin_name'); ?>登录
            </a>
            <dl class="layui-nav-child">
                <dd><a href="">基本资料</a></dd>
                <dd><a href="">安全设置</a></dd>
            </dl>
        </li>
        <li class="layui-nav-item"><a href="<?php echo url('Login/login'); ?>">退了</a></li>
    </ul>
</div>
    <!--左边布局-->
    <div class="layui-side layui-bg-black">
    <div class="layui-side-scroll">
        <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
        <ul class="layui-nav layui-nav-tree"  lay-filter="test">
            <li class="layui-nav-item layui-nav-itemed">
                <a class="" href="javascript:;">分类管理</a>
                <dl class="layui-nav-child">
                    <dd><a href="<?php echo url('Category/cateAdd'); ?>">分类添加</a></dd>
                    <dd><a href="<?php echo url('Category/cateList'); ?>">分类列表展示</a></dd>
                </dl>
            </li>
            <li class="layui-nav-item layui-nav-itemed">
                <a href="javascript:;">管理员管理</a>
                <dl class="layui-nav-child">
                    <dd><a href="<?php echo url('Admin/adminAdd'); ?>">管理员添加</a></dd>
                    <dd><a href="<?php echo url('Admin/adminList'); ?>">管理员列表展示</a></dd>
                </dl>
            </li>
            <li class="layui-nav-item layui-nav-itemed">
                <a href="javascript:;">用户管理</a>
                <dl class="layui-nav-child">
                    <dd><a href="<?php echo url('User/UserShow'); ?>">用户列表展示</a></dd>
                </dl>
            </li>
            <li class="layui-nav-item layui-nav-itemed">
                <a href="javascript:;">友联管理</a>
                <dl class="layui-nav-child">
                    <dd><a href="<?php echo url('Friend/friendAdd'); ?>">友联添加</a></dd>
                    <dd><a href="<?php echo url('Friend/friendList'); ?>">友联列表展示</a></dd>
                </dl>
            </li>
            <li class="layui-nav-item layui-nav-itemed">
                <a href="javascript:;">商品管理</a>
                <dl class="layui-nav-child">
                    <dd><a href="<?php echo url('Goods/goodsAdd'); ?>">商品添加</a></dd>
                    <dd><a href="<?php echo url('Goods/goodsList'); ?>">商品列表展示</a></dd>
                </dl>
            </li>
            <li class="layui-nav-item layui-nav-itemed">
                <a href="javascript:;">品牌管理</a>
                <dl class="layui-nav-child">
                    <dd><a href="<?php echo url('Brand/brandAdd'); ?>">添加品牌</a></dd>
                    <dd><a href="<?php echo url('Brand/brandList'); ?>">品牌列表展示</a></dd>
                </dl>
            </li>
            <li class="layui-nav-item layui-nav-itemed">
                <a href="javascript:;">系统设置</a>
                <dl class="layui-nav-child">
                    <dd><a href="<?php echo url('Admin/pwdChange'); ?>">修改密码</a></dd>
                    <dd><a href="<?php echo url('Website/webAdd'); ?>">系统设置</a></dd>
                </dl>
            </li>
            <li class="layui-nav-item layui-nav-itemed">
                <a href="javascript:;">班级管理</a>
                <dl class="layui-nav-child">
                    <dd><a href="<?php echo url('Classes/index'); ?>">班级添加</a></dd>
                    <dd><a href="<?php echo url('Classes/classList'); ?>">班级展示</a></dd>
                </dl>
            </li>
        </ul>
    </div>
</div>
<script>
        $(function(){
            $(document).on('click','.layui-nav-child dd a',function(){
                $(this).prop('checked',true);
            })
        })
</script>
    <div class="layui-body" style="width:1200px;">
        <!-- 内容主体区域 -->
        <div style="padding: 15px;">
            <h2><font color="red">欢迎浏览后台系统!!谢谢！</font></h2>

        </div>
    </div>
</div>
<script>
    //JavaScript代码区域
    layui.use('element', function(){
        var element = layui.element;

    });
</script>
</body>
</html>