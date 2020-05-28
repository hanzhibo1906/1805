<?php
namespace app\admin\model;
use think\Model;
class Goods extends Model
{
    protected $table = 'shop_goods';
    protected $updateTime = false;
    protected $createTime = false;

    public function goodsInfo($where,$page,$limit){
        $data=$this->field('shop_goods.*,cate_name,brand_name')->alias('g')->join('shop_category c','g.cate_id=c.cate_id')->join('shop_brand b','g.brand_id=b.brand_id')->where($where)->page($page,$limit)->select();

        foreach($data as $k=>$v){
            if($v['goods_up']==1){
                $data[$k]['goods_up']='√';
            }else{
                $data[$k]['goods_up']='×';
            }

            if($v['goods_new']==1){
                $data[$k]['goods_new']='√';
            }else{
                $data[$k]['goods_new']='×';
            }

            if($v['goods_best']==1){
                $data[$k]['goods_best']='√';
            }else{
                $data[$k]['goods_best']='×';
            }

            if($v['goods_hot']==1){
                $data[$k]['goods_hot']='√';
            }else{
                $data[$k]['goods_hot']='×';
            }
        }
        return $data;
    }


    public function goodsCount($where){
       return $this->alias('g')->join('shop_category c','g.cate_id=c.cate_id')->join('shop_brand b','g.brand_id=b.brand_id')->where($where)->count();
    }
}