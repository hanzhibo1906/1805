<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:87:"C:\wamp64\www\1805\aliyun_1805\public/../application/admin\view\attr\basicattrshow.html";i:1542157333;}*/ ?>
<?php if(is_array($basic) || $basic instanceof \think\Collection || $basic instanceof \think\Paginator): $i = 0; $__LIST__ = $basic;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
    <div class="layui-form-item">
        <label class="layui-form-label"><?php echo $v['attr_name']; ?></label>
        <div class="layui-input-inline">
            <?php if($v['has_son'] == 1): ?>
                <select name="basic[<?php echo $v['attr_id']; ?>]"  lay-verify="required" >
                    <option value="">请选择</option>
                    <?php if(is_array($v['son']) || $v['son'] instanceof \think\Collection || $v['son'] instanceof \think\Paginator): $i = 0; $__LIST__ = $v['son'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($i % 2 );++$i;?>
                        <option value="<?php echo $vv; ?>"><?php echo $vv; ?></option>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            <?php else: ?>
                <input type="text" name="basic[<?php echo $v['attr_id']; ?>]" lay-verify="required" autocomplete="off"
                       placeholder="请输入标题" class="layui-input">
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; endif; else: echo "" ;endif; ?>

