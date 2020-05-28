<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:91:"D:\phpstudy_pro\WWW\aliyun_1805\public/../application/admin\view\category\categorylist.html";i:1538011784;s:76:"D:\phpstudy_pro\WWW\aliyun_1805\public/../application/admin\view\layout.html";i:1538028866;s:81:"D:\phpstudy_pro\WWW\aliyun_1805\public/../application/admin\view\public\head.html";i:1541725994;s:81:"D:\phpstudy_pro\WWW\aliyun_1805\public/../application/admin\view\public\left.html";i:1540871306;}*/ ?>
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
        <li class="layui-nav-item"><a href="<?php echo url('Login/logout'); ?>">注销</a></li>
    </ul>
</div>
    <!--左边布局-->
    <style>
    .leftchecked{
        color: #ff33eb!important;
        font-weight: bold!important;
    }
</style>
<div class="layui-side layui-bg-black">
    <div class="layui-side-scroll">
        <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
        <ul class="layui-nav layui-nav-tree"  lay-filter="test">

            <!--<li class="layui-nav-item layui-nav-itemed">-->
                <!--<a class="" href="javascript:;">权限节点管理</a>-->
                <!--<dl class="layui-nav-child">-->
                    <!--<dd><a href="<?php echo url('Power/poweradd'); ?>">节点添加</a></dd>-->
                    <!--<dd><a href="<?php echo url('Power/powerList'); ?>">节点列表展示</a></dd>-->
                <!--</dl>-->
            <!--</li>-->

            <?php if(is_array($AllMenu) || $AllMenu instanceof \think\Collection || $AllMenu instanceof \think\Paginator): $i = 0; $__LIST__ = $AllMenu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;
                if( strtolower(request()->controller()) == strtolower(ltrim( $v['node_url'] ,'/' )) ){
                    echo '<li class="layui-nav-item layui-nav-itemed">';
                }else{
                    echo '<li class="layui-nav-item">';
                }
                ?>
                <a class="" href="javascript:;"><?php echo $v['node_name']; ?></a>
                <dl class="layui-nav-child">

                    <?php
                    if( isset($v['son']) ){ if(is_array($v['son']) || $v['son'] instanceof \think\Collection || $v['son'] instanceof \think\Paginator): $i = 0; $__LIST__ = $v['son'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($i % 2 );++$i;
                        $action = explode( '/' , trim($vv['node_url'] , '/' )  );
                        $action_url = strtolower(array_pop( $action ));
                        if( strtolower(request()->action()) ==  $action_url ){
                            echo '<dd>
                            <a class="leftchecked" href="'.url($vv['node_url']).'">'.$vv['node_name'].'</a></dd>';
                        }else{
                            echo '<dd><a href="'.url($vv['node_url']).'">'.$vv['node_name'].'</a></dd>';
                        }
                    endforeach; endif; else: echo "" ;endif; } ?>
                </dl>
                </li>
            <?php endforeach; endif; else: echo "" ;endif; ?>


        </ul>
    </div>
</div>

    <div class="layui-body" style="width:1200px;">
        <!-- 内容主体区域 -->
        <div style="padding: 15px;">
            <style>
    .change{
        width:100px;
        height:25px;
        background:pink;
    }
</style>
<table class="layui-table">
    <thead>
        <tr>
            <td>分类id</td>
            <td>分类名称</td>
            <td>分类添加时间</td>
            <td>操作</td>
        </tr>
    </thead>
    <tbody>
            <?php if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
            <tr class="showHide" pid="<?php echo $v['pid']; ?>"  cate_id="<?php echo $v['cate_id']; ?>" style="display:none;">
                <td>
                    <?php echo str_repeat('&nbsp;&nbsp;',$v['level']*2); ?>
                    <a href="#" class="showCate">+</a>
                    <?php echo $v['cate_id']; ?></td>
                <td>
                    <?php echo str_repeat('&nbsp;&nbsp;',$v['level']*2); ?>
                    <span class="showInput"><?php echo $v['cate_name']; ?></span>
                    <input type="text" class="change" column="cate_name" cate_id="<?php echo $v['cate_id']; ?>" style="display:none;" value="<?php echo $v['cate_name']; ?>">
                </td>
                <td><?php echo $v['cate_time']; ?></td>
                <td><a class="del" href="#" cate_id="<?php echo $v['cate_id']; ?>">删除</a>&nbsp;&nbsp;||&nbsp;&nbsp;<a href="<?php echo url('Category/cateUpdate'); ?>?cate_id=<?php echo $v['cate_id']; ?>">修改</a></td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
    </tbody>
</table>
<script>
    $(function(){
        layui.use(['layer'],function(){
            var layer=layui.layer;
            //页面一加载，只显示pid=0的数据
            showTr(0);
            //展示
            function showTr(cate_id){
                $('.showHide').each(function(index){
                    var pid=$(this).attr('pid');
                    if(pid==cate_id){
                        $(this).show();
                    }
                });
            }
            //隐藏
            function hideTr(cate_id){
                $('.showHide').each(function(index){
                    var pid=$(this).attr('pid');
                    if(pid==cate_id){
                        $(this).hide();
                        $(this).find('.showCate').html('+');
                        var new_cate_id=$(this).attr('cate_id');
                        hideTr(new_cate_id);
                    }
                });
            }


            $(".showCate").click(function(){
                //获取当前对象文本值
                var sign=$(this).html();
                //获取自己id
                var cate_id=$(this).parents('tr').attr('cate_id');
                if(sign=="+"){
                    showTr(cate_id);
                    $(this).html('-');
                }else{
                    hideTr(cate_id);
                    $(this).html('+');
                }

            })

            $('.showInput').click(function(){
                $(this).next('input').show();
                $(this).hide();
            });

            $('.del').click(function(){
                _this=$(this);
                var cate_id=_this.attr('cate_id');
                $.post(
                        '<?php echo url("Category/cateDel"); ?>',
                        {cate_id:cate_id},
                        function(msg){
                            //console.log(msg);
                            layer.msg(msg.font,{icon:msg.code});
                            if(msg.code==1){
                                _this.parents('tr').remove();
                            }
                        },'json'
                )
            });

            //给文本框加失去焦点事件
            $('.change').blur(function(){
                var column=$(this).attr('column');
                var cate_id=$(this).attr('cate_id');
                var _this=$(this);
                var val=$(this).val();
                $.post(
                        '<?php echo url("Category/cateChange"); ?>',
                        {column:column,cate_id:cate_id,val:val},
                        function(msg){
                            layer.msg(msg.font,{icon:msg.code});
                            if(msg.code==1){
                                _this.hide();
                                _this.prev('span').html(val);
                                _this.prev('span').show();
                            }
                        },'json'
                )
            })
        })

    })
</script>
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