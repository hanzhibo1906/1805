<?php
namespace app\index\model;
use think\Model;
class Address extends Model{
    protected $table='shop_address';
    //定义时间戳字段名;
    protected $createTime=false;
    protected $updateTime=false;

    //自动完成
    protected $insert=['user_id'];

    protected function setUserIdAttr(){
        return session('sessionInfo.user_id');
    }

    public function getAddressInfo($where){
        $addressInfo=collection($this->where($where)->select())->toArray();
        foreach($addressInfo as $k=>$v){
            $addressInfo[$k]['province']=model('Region')->where(['REGION_ID'=>$v['province']])->value('REGION_NAME');
            $addressInfo[$k]['city']=model('Region')->where(['REGION_ID'=>$v['city']])->value('REGION_NAME');
            $addressInfo[$k]['district']=model('Region')->where(['REGION_ID'=>$v['district']])->value('REGION_NAME');

        }
        return $addressInfo;

    }
}