<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:83:"D:\phpstudy_pro\WWW\aliyun_1805\public/../application/admin\view\attr\saleattr.html";i:1542001616;s:76:"D:\phpstudy_pro\WWW\aliyun_1805\public/../application/admin\view\layout.html";i:1538028866;s:81:"D:\phpstudy_pro\WWW\aliyun_1805\public/../application/admin\view\public\head.html";i:1541725994;s:81:"D:\phpstudy_pro\WWW\aliyun_1805\public/../application/admin\view\public\left.html";i:1540871306;}*/ ?>
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
    .left{
        margin-left: 30px;
    }
    .valueleft{
        margin-left: 50px;
    }
</style>
<fieldset class="layui-elem-field layui-field-title">
    <legend>商品的销售属性</legend>
</fieldset>
<form class="layui-form">
    <div class="layui-form-item">
        <label class="layui-form-label">请选择分类</label>
        <div class="layui-input-inline">
            <select name="category_id"  lay-verify="required" >
                <option value="">请选择</option>
                <?php if(is_array($category_list) || $category_list instanceof \think\Collection || $category_list instanceof \think\Paginator): $i = 0; $__LIST__ = $category_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                    <option value="<?php echo $v['cate_id']; ?>"
                    <?php if($v['level'] != 2): ?>disabled <?php endif; ?>
                    ><?php echo str_repeat( '&nbsp;-&nbsp;' , ($v['level'] +1) ); ?><?php echo $v['cate_name']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
        <div class="layui-btn-group">
            <button class="layui-btn attradd"  type="button"> <i class="layui-icon"></i></button>
        </div>
    </div>

    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit lay-filter="*">立即提交</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </div>
</form>
<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
<script>

    var attr = '<div class="layui-form-item left">'+
            '<label class="layui-form-label">属性名</label>'+
            '<div class="layui-input-inline">'+
            '<input type="text" name="attr[]" len="0" lay-verify="required"'+
            'placeholder="请输入属性名"   autocomplete="off" class="layui-input">'+
            '</div><div class="layui-btn-group">' +
            '<button class="layui-btn layui-btn-danger attrdel" type="button">' +
            '<i class="layui-icon"></i></button>'+
            '<button class="layui-btn valueadd"  type="button"> ' +
            '<i class="layui-icon"></i></button>'+
            '</div></div>';

    var value = '<div class="layui-form-item valueleft">'+
            '<label class="layui-form-label">属性值</label>'+
            '<div class="layui-input-inline">'+
            '<input type="text" name="value[]" lay-verify="required"'+
            'placeholder="请输入属性值"   autocomplete="off" class="layui-input">'+
            '</div><div class="layui-btn-group">' +
            '<button class="layui-btn layui-btn-danger valuedel" type="button">' +
            '<i class="layui-icon"></i></button>'+
            '</div></div>';
    $(function(){
        layui.use(['form', 'layer'], function(){
            var form = layui.form;
            var  layer = layui.layer;

            $('.attradd').click(function(){
                $(this).parents('.layui-form-item').after(attr);
                var len = $(this).parents('.layui-form').find('.left').length;
                $(this).parents('.layui-form').find('.left').first().find('input').first().attr('name' , 'attr['+len+']' );
                $(this).parents('.layui-form').find('.left').first().find('input').first().attr('len' , len );
            });


            $(document).on(  'click' , '.attrdel' , function(){
                // 找到对应的属性值value 删除
                while( $(this).parents('.layui-form-item').next().attr('class') == 'layui-form-item valueleft' ){
                    $(this).parents('.layui-form-item').next().remove();
                }
                $(this).parents('.layui-form-item').remove();
            });



            $(document).on(  'click' , '.valuedel' , function(){
//                $(this).parents('.layui-form-item').after(value);
                $(this).parents('.layui-form-item').remove();
            });


            $(document).on(  'click' , '.valueadd' , function(){
                $(this).parents('.layui-form-item').after(value);
                var this_len = $(this).parents('.layui-form-item').find('input').attr('len');
                $(this).parents('.layui-form-item').next().find('input').attr('name' , 'value['+this_len+'][]' );
            });

            form.on('submit(*)',function(data){
                $.post(
                        "<?php echo url('Attr/saleAttr'); ?>",
                        data.field,
                        function( json_info ){
                            if(json_info.status == 1000){
                                alert('ok');
//                                location.href="<?php echo url('Category/cateList'); ?>";
                            }else{
                                alert(json_info.msg);
                            }
                        },'json'
                )
                return false;
            })
        });
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