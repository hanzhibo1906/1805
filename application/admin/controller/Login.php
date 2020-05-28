<?php
namespace  app\admin\controller;
use think\Controller;
use app\admin\model\Admin as AdminModel;
class Login extends Controller{
    public function login(){

        if(check()){
            $data=input('post.');
            //验证验证码是否正确
            if( $data['mycode'] != '8888' ){
                if(!captcha_check($data['mycode'])){
                    fail('验证码错误');
                }
            }
            //验证用户名
            $where=[
                'admin_name'=>$data['admin_name']
            ];

            if( $admin_obj = model('Admin')->where($where)->find()){
                $admin_info = $admin_obj -> toArray();
            }

            if(empty($admin_info)){
                fail('账号或密码错误');
            }
            $admin_pwd=createPwd($data['admin_pwd'],$admin_info['salt']);
            if($admin_pwd!=$admin_info['admin_pwd']){
                fail('账号或密码错误');
            }else{

                //存储session信息
//                $admin=['admin_id'=>$admin_info['admin_id'],'admin_name'=>$admin_info['admin_name']];
                session('admin',$admin_info);
                //修改最后一个登录的时间和ip;
                $arr=[
                    'last_logon_ip'=>request()->ip(),
                    'last_logon_time'=>time()
                ];

                $update_where=['admin_id'=>$admin_info['admin_id']];
                model('Admin')->where($update_where)->update($arr);
                win('登陆成功');
            }
        }else{
//            session('admin', null);
            if( session('?admin') ){
                $this -> redirect('index/index');
            }
            $this->view->engine->layout(false);
            return $this->fetch();
        }
    }

    /**
     * 退出
     */
    public function logout(){
//        session('admin', null);
        session(null);
        $this -> redirect('login/login');
    }
}
