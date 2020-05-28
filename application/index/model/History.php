<?php
namespace app\index\model;
use think\Model;
class History extends Model{
    protected $table='shop_history';

    protected $createTime=false;
    protected $updateTime=false;
}