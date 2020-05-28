<?php
namespace app\index\controller;
use think\Controller;
use think\Session;
class Login extends Common
{
    public function login()
    {
        if(check()){
                 $data=input('post.');
                //验证账号密码
                if(empty($data['account'])){
                    fail('手机号或邮箱必填');
                }

                if(empty($data['user_pwd'])){
                    fail('密码必填');
                }
                $account=$data['account'];
                //根据账号查询 $data;
                if(substr_count($account,'@')) {
                    $where = [
                        'user_email' => $account
                    ];
                }else{
                    $where = [
                        'user_tel' => $account
                    ];
                }
                $info=model('User')->where($where)->find();
                 $time=time();
                $last_error_time=$info['last_error_time'];

                if(!empty($info)){
                    $user_pwd=$data['user_pwd'];
                    $new_pwd=md5($user_pwd);
                    $pwdInfo=$info['user_pwd'];
                    if($new_pwd==$pwdInfo){
                        if($info['error_num']>=5&&$time-$last_error_time<3600){
                            $openTime=60-(ceil($time-$last_error_time/60));
                            fail('当前账号锁定,还有'.$openTime.'解封');
                        }

                            $where=[
                                'user_id'=>$info['user_id']
                            ];
                            $arr=[
                                'error_num'=>0,
                                 'last_error_time'=>null
                            ];
                            model('User')->where($where)->update($arr);

                        if($data['rememberPwd']==true) {

                            $cookieInfo = [
                                'account' =>$account,
                                'user_pwd' => $data['user_pwd']
                            ];

                            setcookie('cookieInfo',serialize($cookieInfo) , $time+60*60*24*10);
                        }
                        $sessionInfo=[
                            'user_id'=>$info['user_id'],
                            'account'=>$account
                        ];
                        session('sessionInfo',$sessionInfo);

                        # 同步cookie中的浏览记录
                        $this -> asyncHistory();

                        # 同步cookie中的购物车数据 到 数据库
                        $this -> asyncCart();

                        win('登陆成功');
                    }else{
                        if($time-$info['last_error_time']>3600){

                            $where=[
                                'user_id'=>$info['user_id']
                            ];
                            $arr=[
                                'error_num'=>1,
                                'last_error_time'=>$time
                            ];
                            model('User')->where($where)->update($arr);
                        }else{
                            if($info['error_num']>=5){
                                fail('账号已锁定');
                            }else{
                                $where=[
                                    'user_id'=>$info['user_id']
                                ];
                                $arr=[
                                   'error_num'=> $info['error_num']+1,
                                    'last_error_time'=>$time
                                ];
                                model('User')->where($where)->update($arr);
                                $num=5-$info['error_num'];
                                fail('你还可以输入'.$num.'次');
                            }
                        }
                    }
                }else{
                    fail('账号或密码错误');
                }


            //查询没有账号
        }else{
            $str=cookie('cookieInfo');
            $userInfo=unserialize($str);
            $this->view->engine->layout(false);//关闭布局
            $this->assign('userInfo',$userInfo);
            return view();
        }
    }

    public function register(){
        if(check()){
                 $data=input('post.');
                //验证验证码是否正确
                //存session里边的验证码
                $sendCode=session("Info.sendCode");
                $sendTime=session('Info.sendTime');
                if(empty($data['user_code'])){
                    fail('验证码不能为空');
                }else if($data['user_code']!=$sendCode){
                    fail('验证码有误');
                }else if(time()-$sendTime>300){
                    fail('验证码失效,5分钟内输入有效');
                }
                //验证手机号，密码，确认密码
                 $validate=validate('Index');
                 if(empty($data['user_email'])) {
                     $res = $validate->scene('registerTel')->check($data);
                 }else {
                     $res=$validate->scene('registerEmail')->check($data);
                 }

                if(!$res){
                    fail($validate->getError());
                }
                //把信息入库
                $model=model('User');
                $result=$model->allowField(true)->save($data);
                if($result){
                    win('注册成功');
                }else{
                    fail('注册失败');
                }
        }else{
            $this->view->engine->layout(false);//关闭布局
            return view();
        }
    }

    public function send(){
        $value=input('post.value');
        if(substr_count($value,'@')){
            $sendCode=createCode();
            $res=sendEM($value,$sendCode);
           if($res=='ok'){
               $codeInfo=[
                   'sendCode'=>$sendCode,
                   'sendTime'=>time()
               ];
               session("Info",$codeInfo);
               win('发送成功');
           }else{
               fail('发送失败');
           }
        }else{
           $sendCode=createCode();
            $res=sendSms($value,$sendCode);
           $result=$res->Message;
           if($result=='OK'){
                $codeInfo=[
                    'sendCode'=>$sendCode,
                    'sendTime'=>time()
                ];
                session("Info",$codeInfo);
                win('发送成功');
            }else{
               fail('发送失败');
            }
        }
    }
}