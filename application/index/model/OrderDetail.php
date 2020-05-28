<?php
namespace app\index\model;
use think\Model;
class OrderDetail extends Model{
    protected $table='shop_order_detail';
    //定义时间戳
    protected $createTime=false;
    protected $updateTime=false;

}