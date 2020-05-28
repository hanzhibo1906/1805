<?php
   namespace app\admin\model;
   use think\Model;
   class GoodsBasicAttr extends Model{
       protected $table='shop_goods_basic_attr';
       //定义时间戳字段名;
       protected $createTime=false;
       protected $updateTime=false;

   }