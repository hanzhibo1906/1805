<?php
namespace app\admin\validate;
use think\Validate;
class Category extends Validate{
    //定义规则
    protected $rule=[
        'cate_name'=>'require|checkName'
    ];

    //提示文字
    protected $message=[
        'cate_name.require'=>'分类名必填'
    ];

    //自定义
    public function checkName($value,$rule,$data){
        if(empty($data['cate_id'])){
            $where=[
                'cate_name'=>$value
            ];
        }else{
            $where=[
                'cate_id'=>['NEQ',$data['cate_id']],
                'cate_name'=>$value
            ];
        }
        $res=model('Category')->where($where)->find();
        if(!empty($res)){
            return '用户名已存在';
        }else{
            return true;
        }
    }
}