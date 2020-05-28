<?php
namespace app\index\model;
use think\Model;
class Order extends Model{
    protected $table='shop_order';
    //定义时间戳
    protected $createTime=false;
    protected $updateTime=false;

}