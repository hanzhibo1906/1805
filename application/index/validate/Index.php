<?php
namespace app\index\validate;
use think\Validate;
class Index extends Validate{
    //定义规则
    protected $rule=[
        'user_tel'=>'require|checkTel|unique:shop_user',
        'user_pwd'=>'require|checkPwd',
        'user_pwd1'=>'require|confirm:user_pwd',
        'user_email'=>'require|email|unique:shop_user'
    ];

    //提示句子
    protected $message=[
        'user_tel.require'=>'手机号必填',
        'user_tel.unique'=>'手机号已被注册,请登录',
        'user_pwd.require'=>'密码必填',
        'user_pwd1.require'=>'确认密码必填',
        'user_pwd1.confirm'=>'确认密码必须和密码一致',
        'user_email.email'=>'邮箱格式错误',
        'user_email.unique'=>'邮箱已存在,请登录'
    ];

    //自定义验证
    //验证手机号
    public function checkTel($value,$rule,$data){
        $reg='/^1[1-9]\d{9}$/';
        if(!preg_match($reg,$value)){
            return '请输入正确号码';
        }else{
           return true;
        }
    }

    //验证密码
    public function checkPwd($value,$rule,$data){
        $reg='/^[a-z0-9_]{6,}$/';
        if(!preg_match($reg,$value)){
            return '密码数字字母下划线';
        }else{
            return true;
        }
    }

    //定义环境
    protected $scene=[
        'registerTel'=>['user_tel','user_pwd','user_pwd1'],
        'registerEmail'=>['user_email','user_pwd','user_pwd1']
    ];


}
