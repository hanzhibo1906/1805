<?php
namespace app\admin\validate;
use think\Validate;
class Goods extends Validate{
    //定义规则
    protected $rule=[
        'goods_name'=>'require',
        'goods_selfprice'=>'require',
        'goods_marketprice'=>'require',
        'goods_stock'=>'require',
        'goods_score'=>'require'
    ];

    protected $message=[
        'goods_name.require'=>'商品名称必填',
        'goods_selfprice'=>'本店价格必填',
        'goods_marketprice'=>'市场价格必填',
        'goods_stock'=>'库存必填',
        'goods_score'=>'积分必填',
    ];
}