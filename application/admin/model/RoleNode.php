<?php
namespace app\admin\model;
use think\Model;
class RoleNode extends Model{
        protected $table='shop_role_node';

        //定义时间戳字段名;
        protected $createTime=false;
        protected $updateTime=false;
}