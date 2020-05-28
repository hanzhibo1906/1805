<?php
namespace app\admin\model;
use think\Model;
class Brand extends Model
{
    protected $table = 'shop_brand';
    //定义时间戳字段名;
    protected $createTime = 'brand_time';
    protected $updateTime = false;
}