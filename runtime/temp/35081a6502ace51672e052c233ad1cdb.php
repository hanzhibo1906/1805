<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:85:"D:\phpstudy_pro\WWW\aliyun_1805\public/../application/admin\view\goods\goodslist.html";i:1536923970;s:76:"D:\phpstudy_pro\WWW\aliyun_1805\public/../application/admin\view\layout.html";i:1538028866;s:81:"D:\phpstudy_pro\WWW\aliyun_1805\public/../application/admin\view\public\head.html";i:1541725994;s:81:"D:\phpstudy_pro\WWW\aliyun_1805\public/../application/admin\view\public\left.html";i:1540871306;}*/ ?>
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
            <div>
    <form class="layui-form">
        品牌:
        <div class="layui-input-inline">
            <select name="brand_name" lay-verify="required">
                <option value="0">--请选择--</option>
                <?php if(is_array($brand) || $brand instanceof \think\Collection || $brand instanceof \think\Paginator): $i = 0; $__LIST__ = $brand;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                <option value="<?php echo $v['brand_name']; ?>"><?php echo $v['brand_name']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
        分类:
        <div class="layui-input-inline">
            <select name="cate_name" lay-verify="required">
                <option value="0">--请选择--</option>
                <?php if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                <option value="<?php echo $v['cate_name']; ?>"><?php echo str_repeat('&nbsp;&nbsp;',$v['level']*2); ?><?php echo $v['cate_name']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
        <div class="layui-input-inline">
            <input type="text" name="goods_name" placeholder="商品名称" class="layui-input">
        </div>
            <button class="layui-btn" lay-submit lay-filter="*">搜索</button>
    </form>
</div>
<table class="layui-hide" id="test"  lay-filter="test"></table>
<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>
<script>
    layui.use(['table','form','layer'],function(){
        var table = layui.table;
        var form = layui.form;
        var layer = layui.layer;
        var tableIns=table.render({
            elem: '#test'
            ,url:'<?php echo url("Goods/goodsInfo"); ?>'
            ,cellMinWidth: 80 //全局定义常规单元格的最小宽度，layui 2.2.1 新增
            ,limit:3
            ,cols: [[
                {field:'goods_id', width:50, title: 'ID', sort: true}
                ,{field:'goods_name', width:80, title: '商品名称'}
                ,{field:'goods_selfprice', width:80, title: '商品本店价格',edit: 'text'}
                ,{field:'goods_marketprice', width:80, title: '商品市场价格'}
                ,{field:'goods_up', title: '是否上架', width: 80, edit: 'text'} //minWidth：局部定义当前单元格的最小宽度，layui 2.2.1 新增
                ,{field:'goods_new', title: '是否为新品',width: 80, edit: 'text'}
                ,{field:'goods_best', title: '是否为精品',width: 80, edit: 'text'}
                ,{field:'goods_hot', title: '是否为热卖品',width:80, edit: 'text'}
                ,{field:'goods_num', title: '商品库存',width:80, edit: 'text'}
                ,{field:'goods_score', title: '商品积分',width:80, edit: 'text'}
                ,{field:'cate_name', title: '分类',width:100}
                ,{field:'brand_name', title: '品牌',width:85}
                ,{fixed: 'right', title:'操作', toolbar: '#barDemo', width:150}
            ]]
            ,page:true
        });

        var where;
        form.on('submit(*)',function(data){
            tableIns.reload({
                where:data.field
            });
            return false;
        });
        //监听单元格编辑
        table.on('edit(test)', function(obj){
            var value = obj.value //得到修改后的值
                    ,data = obj.data //得到所在行所有键值
                    ,field = obj.field; //得到字段
            var res;
            if(value=='×'){
                res=0;
            }else if(value=='√'){
                res=1;
            }else{
                res=value;
            }
            $.post(
                    '<?php echo url("Goods/goodsUpdate"); ?>',
                    {value:res,goods_id:data.goods_id,field:field},
                    function(msg){
                        layer.msg(msg.font,{icon: msg.code});
                    },'json'
            )
        });
        //监听工具条
        table.on('tool(test)', function(obj){ //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"
            var data = obj.data; //获得当前行数据
            var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
            var tr = obj.tr; //获得当前行 tr 的DOM对象

            if(layEvent === 'edit'){ //查看
                location.href="<?php echo url('Goods/goodsUpdateInfo'); ?>?goods_id="+data.goods_id
            } else if(layEvent === 'del') { //删除
                layer.confirm('真的删除行么', function (index) {
                    $.post(
                            '<?php echo url("Goods/goodsDel"); ?>',
                            {goods_id:data.goods_id},
                            function(msg){
                                layer.msg(msg.font,{icon:msg.code});
                                if(msg.code==1){
                                    tableIns.reload({
                                        where:where
                                    });
                                }
                            },'json'
                    )

                });
            }
        });
    });
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