<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:86:"C:\wamp64\www\1805\aliyun_1805\public/../application/admin\view\attr\saleattrshow.html";i:1542260883;}*/ ?>
<style>
    tr{
        width: 100%;
    }
    td{
        width: 13%;
        text-align: center;
    }
</style>
<form class="layui-form">
    <?php if(is_array($sale) || $sale instanceof \think\Collection || $sale instanceof \think\Paginator): $i = 0; $__LIST__ = $sale;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
        <div class="layui-form-item" >
            <div class="layui-input-block" style="margin-left: 2%">
                <input type="checkbox" lay-filter="parent" name="parent[]"
                       title="<?php echo $v['attr_name']; ?>"  value="<?php echo $v['attr_id']; ?>" lay-skin="primary" >
            </div>
            <div class="layui-input-block" style="margin-left: 4%">
                <?php if(is_array($v['son']) || $v['son'] instanceof \think\Collection || $v['son'] instanceof \think\Paginator): $i = 0; $__LIST__ = $v['son'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($i % 2 );++$i;?>
                    <input type="checkbox" lay-filter="son" name="son[]" title="<?php echo $vv; ?>"
                           value="<?php echo $key; ?>" lay-skin="primary"  parent_id="<?php echo $v['attr_id']; ?>">
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
            <hr/>
        </div>
    <?php endforeach; endif; else: echo "" ;endif; ?>

    <div>
        <table class="layui-table" lay-filter="test">
            <thead>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</form>

<script>
    layui.use(['form', 'layer','table'], function(){
        var form = layui.form;
        var  layer = layui.layer;
        var table = layui.table;

        form.on('checkbox(parent)', function(data){
            if( data.elem.checked == true ){
                data.othis.parents('.layui-input-block').next().find('input').prop('checked',true);
                form.render();
            }else{
                data.othis.parents('.layui-input-block').next().find('input').prop('checked',false);
                form.render();
            }
            showSku();
        });

        form.on('checkbox(son)', function(data){
            if( data.elem.checked == true ){
                data.othis.parents('.layui-input-block').prev().find('input').prop('checked',true);
                form.render();
            }else{

                var mark = 0;
                //获取同级的所有二级菜单是否有选中的，有选中的化，让父级还是选中的状态
                data.othis.parent('.layui-input-block').find('input').each(function(){
                    if( $(this).prop('checked') == true ){
                        mark = 1;
                    }
                });
                if( mark == 1 ){
                    data.othis.parents('.layui-input-block').prev().find('input').prop('checked',true);
                    form.render();
                }else{
                    data.othis.parents('.layui-input-block').prev().find('input').prop('checked',false);
                    form.render();
                }
            }
            showSku();
        });
    });

    // 笛卡尔积算法
    function descartes(  list )
    {
        //parent上一级索引;count指针计数
        var point  = {};

        var result = [];
        var pIndex = null;
        var tempCount = 0;
        var temp   = [];

        //根据参数列生成指针对象
        for(var index in list)
        {
            if(typeof list[index] == 'object')
            {
                point[index] = {'parent':pIndex,'count':0}
                pIndex = index;
            }
        }

        //单维度数据结构直接返回
        if(pIndex == null)
        {
            return list;
        }

        //动态生成笛卡尔积
        while(true)
        {
            for(var index in list)
            {
                tempCount = point[index]['count'];
                temp.push(list[index][tempCount]);
            }

            //压入结果数组
            result.push(temp);
            temp = [];

            //检查指针最大值问题
            while(true)
            {
                if(point[index]['count']+1 >= list[index].length)
                {
                    point[index]['count'] = 0;
                    pIndex = point[index]['parent'];
                    if(pIndex == null)
                    {
                        return result;
                    }

                    //赋值parent进行再次检查
                    index = pIndex;
                }
                else
                {
                    point[index]['count']++;
                    break;
                }
            }
        }
    }

    // 组合货品
    function showSku(){

        // 先获取表头,拼装表头
        var table_head = "<tr><td>商品名称</td>";
        var head_arr = [];
        var i =0;
        $('[name^=parent]').each(function(){
//            console.log($(this).prop('checked'));
            if( $(this).prop('checked') == true ){
                head_arr[i] = $(this).attr('title');
                table_head +='<td>'+$(this).attr('title')+'</td>' ;
            }
            i++;
        });
        table_head += "<td>库存</td>";
        table_head += "<td>价格</td>";
        table_head += "<td>操作</td>";
        table_head += "</tr>";
        $('.layui-table thead').html(table_head);

        // 拼装货品的tr和td
        var body_arr = [];
        var j = 0;
        $('[name^=parent]').each(function(){
            if( $(this).prop('checked') == true ){
                body_arr[j] = new Array();
                var k = 0;
                $(this).parents('.layui-input-block').next().find('input').each(function(){
                    if( $(this).prop('checked') == true ){
                        body_arr[j][k] = $(this).attr('parent_id')+'|'+
                                $(this).attr('title')
                                + '|' +$(this).val();
                        k++;
                    }
                })
                j++;
            }
        });
        var result = descartes(body_arr);
        var product_name = $('[name=goods_name]').val();
//        var product_name = '三星S9+';

        console.log( result );

        // z组合货品的名称
        var table_body = '';
        for( var i in result ){
            var sku_name = product_name;
            table_body += '<tr>';
            var attr_value='';
            for( var j in result[i]){
                var attr = result[i][j].split( '|' );
                if( sku_name == product_name ){
                    sku_name +=  ' -' + attr[1];
                }else{
                    sku_name +=  '-' + attr[1];
                }
                attr_value += attr[0]+'|'+attr[2]+',';
            }
            // 货品的名称
            table_body += '<td>'+sku_name+
                    '<input type="hidden" name="sku[sku][]" value="'+attr_value+'">' +
                    '<input type="hidden" name="sku[sku_name][]" value="'+sku_name+'"></td>';

            // 货品的属性
            for( var j in result[i]){
                var attr = result[i][j].split( '|' );
                table_body += '<td>'+attr[1]+'</td>';
            }

            table_body += '<td><input type="text" name="sku[goods_stock][]" ' +
                    'lay-verify="required" value="10" autocomplete="off" placeholder="请输入库存" ' +
                    'class="layui-input"></td>';
            table_body += '<td><input type="text" name="sku[goods_price][]" ' +
                    'lay-verify="required"  value="2999" autocomplete="off" placeholder="请输入价格" ' +
                    'class="layui-input"></td>';
            table_body += '<td>操作</td>';
            table_body +='</tr>';
        }

        $('.layui-table tbody').html(table_body)

    }

//    var arr = new Array();
//    arr[0] = ['金色','黑色'];
//    arr[1] = ['32','64'];
//    arr[2] = ['联通','移动'];
//    arr[3] = ['双卡双待','双卡单待'];
//    var result = descartes( arr );
//    console.log( result );

    $('')


</script>
