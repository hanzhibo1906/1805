<?php
namespace app\admin\model;
use think\Model;
class Role extends Model{
        protected $table='shop_role';

    //定义时间戳字段名;
    protected $createTime=false;
    protected $updateTime=false;
}