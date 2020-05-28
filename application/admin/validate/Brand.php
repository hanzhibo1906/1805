<?php
namespace app\admin\validate;
use think\Validate;
class Brand extends Validate{
    //定义规则
    protected $rule=[
        'brand_name'=>'require|checkName',
        'brand_url'=>'require',
        'brand_logo'=>'require',
        'brand_describe'=>'require'
    ];

    //提示文字
    protected $message=[
        'brand_name.require'=>'品牌名称必填',
        'brand_url.require'=>'品牌地址必填',
        'brand_logo.require'=>'品牌Logo不得为空',
        'brand_describe.require'=>'品牌介绍必填'
    ];

    //自定义
    public function checkName($value,$rule,$data){
        if(empty($data['brand_id'])){
            $where=[
                'brand_name'=>$value,
            ];
        }else{
            $where=[
                'brand_id'=>['NEQ',$data['brand_id']],
                'brand_name'=>$value,
            ];
        }
        $info=model('Brand')->where($where)->find();
        if(!empty($info)){
            return '品牌名称已存在';
        }else{
            return true;
        }
    }
}