<?php
namespace app\admin\validate;
use think\Validate;
class Friend extends Validate{
        //定义规则
    protected $rule=[
        'name'=>'require|checkName',
        'url'=>'require',
        'logo'=>'require',
    ];

    //提示文字
    protected $message=[
        'name.require'=>'名字必填',
        'url.require'=>'链接地址必填',
        'logo.require'=>'logo必选'
    ];

    //验证链接名
    public function checkName($value,$rule,$data){
        if(empty($data['id'])){
            $where=[
                'name'=>$value
            ];
        }else{
            $where=[
                'id'=>['NEQ',$data['id']],
                'name'=>$data['name']
            ];
        }

        $friend_data=model('Friend')->where($where)->find();

        if(!empty($friend_data)){
            return '链接名已存在';
        }else{
            return true;
        }
    }
}