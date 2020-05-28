<?php
   namespace app\admin\model;
   use think\Model;
   class Admin extends Model{
       protected $table='shop_friend';
       //定义时间戳字段名;
       protected $createTime=false;
       protected $updateTime=false;

   }