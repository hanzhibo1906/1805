<?php
namespace app\admin\model;
use think\Model;
class BasicValue extends Model{
   protected $table='shop_basic_attr_value';
   //定义时间戳字段名;
   protected $createTime=false;
   protected $updateTime=false;


}