<?php
namespace app\index\model;
use think\Model;
class Cart extends Model{
    protected $table='shop_cart';
    protected $createTime=false;
    protected $updateTime=false;
}