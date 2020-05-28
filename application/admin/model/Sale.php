<?php
namespace app\admin\model;
use think\Model;
class Sale extends Model{
   protected $table='shop_sale_attr';
   //定义时间戳字段名;
   protected $createTime=false;
   protected $updateTime=false;


}