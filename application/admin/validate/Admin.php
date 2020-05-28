<?php
namespace app\admin\validate;
use think\Validate;
class Admin extends Validate
{
    //定义规则
    protected $rule=[
        'admin_name'=>'require|checkName',
        'admin_pwd'=>'require|checkPwd',
        'admin_email'=>'require|email',
        'admin_tel'=>'require',
    ];


    //提示文字
    protected $message=[
        'admin_name.require'=>'名字必填',
        'admin_pwd.require'=>'密码必填',
        'admin_email.require'=>'邮箱必填',
        'admin_tel.require'=>'电话必填',
        'admin_email.email'=>'请输入正确的邮箱',
    ];


    protected $scene=[
        'edit'=>['admin_name','admin_email','admin_tel']
    ];
    //验证用户名
    public function checkName($value,$rule,$data){
        $reg='/^[a-z_]\w{3,11}$/i';

        if(!preg_match($reg,$value)){
            return '用户名为字母数字下划线4-12位非数字开头';
        }else{
            if(empty($data['admin_id'])){
                $where=[
                    'admin_name'=>$value,
                ];
            }else{
                $where=[
                    'admin_id'=>['NEQ',$data['admin_id']],
                    'admin_name'=>$value,
                ];
            }
            $admin=model('Admin');

            $admin_info=$admin->where($where)->find();
            if(!empty($admin_info)){
                return '用户名已存在';
            }else{
                return true;
            }
        }
    }

    //验证密码
    public function checkPwd($value,$rule,$data){
        $reg='/^.{6,}$/i';
        if(!preg_match($reg,$value)){
            return '密码必须6位及以上';
        }else{
            return true;
        }
    }
}