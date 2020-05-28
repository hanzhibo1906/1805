<?php
namespace app\index\model;
use think\Model;
class OrderAddress extends Model{
    protected $table='shop_order_address';
    //定义时间戳
    protected $createTime=false;
    protected $updateTime=false;

}