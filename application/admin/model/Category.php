<?php
namespace app\admin\model;
use think\Model;
class Category extends Model
{
    protected $table = 'shop_category';
    //定义时间戳字段名;
    protected $createTime='cate_time';
    protected $updateTime=false;
}