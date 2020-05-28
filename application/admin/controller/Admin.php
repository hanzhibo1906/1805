<?php
namespace app\admin\controller;

use think\Controller;
use app\admin\model\Admin as AdminModel;
use think\image\Exception;

/**
 * 管理员管理
 * Class Admin
 * @package app\admin\controller
 */
class Admin extends Common
{
    /**
     * 添加管理员
     * @return \think\response\View
     */
    public function adminAdd()
    {
        if (request()->isPost() && request()->isAjax()) {
            $data = input('post.');
            if (empty($data)) {
                exit('非法操作此页面');
            }
            $validate = validate('Admin');

            if (!$validate->check($data)) {
                $font = $validate->getError();
                echo json_encode(['font' => $font, 'code' => 2]);
                exit;
            }

            $model = model('Admin');

            # 开启事务
            $model -> startTrans();

            # 接受角色id的数据
            $role = $data['role'];
            unset( $data['role'] );

            try{


                # 写入管理员表
                $model->save($data);

                $admin_id = $model -> getLastInsID();

                if( $admin_id < 1 ){
                    throw new \Exception('管理员表写入失败');
                }

                #写入管理员和角色的关联表
                $admin_role_model = model('AdminRole');
                $insert = [];

                $i = 0;
                foreach( $role as $key => $value ){
                    $insert[$i]['admin_id'] = $admin_id;
                    $insert[$i]['role_id'] = $value;
                    $i ++;
                }

                $number = $admin_role_model -> insertAll( $insert );

                if( $number < 1 ){
                    throw new \Exception('管理员表写入失败');
                }

                $model -> commit();

                $this -> success();

            }catch ( \Exception $e ){

                $model -> rollback();

                $this -> fail( $e -> getMessage() );
            }

        } else {
            # 查询已有的角色
            $role_model = model('Role');

            $where = [
                'r.status' => 1
            ];

            $role_list = $role_model
                -> table('shop_role r')
                -> field('*,group_concat(`node_name`) as node_all')
                -> join('shop_role_node rn','rn.role_id=r.role_id')
                -> join('shop_power_node pn' , 'pn.node_id=rn.node_id')
                -> where( $where )
                -> group('r.role_id')
                ->select();

//            print_r($role_list);exit;

            $this -> assign('role' , $role_list );

            return view();
        }
    }

    public function checkName()
    {
        //接收用户名
        $admin_name = input('post.admin_name');
        if (empty($admin_name)) {
            exit('非法操作此页面');
        }
        $admin_id = input('post.admin_id');
        if (empty($admin_id)) {
            $where = ["admin_name" => $admin_name];
        } else {
            $where = [
                'admin_id' => ['NEQ', $admin_id],
                "admin_name" => $admin_name
            ];
        };
        $arr = model('Admin')->where($where)->find();
        if ($arr) {
            echo 'no';
        } else {
            echo 'ok';
        }
    }

    public function adminList()
    {
        return view();
    }

    public function adminInfo()
    {
        $p = input('get.page');
        if (empty($p)) {
            exit('非法操作此页面');
        }
        $page_num = input('get.limit');
        if (empty($page_num)) {
            exit('非法操作此页面');
        }
        $admin_info = model('Admin')->page($p, $page_num)->select();
        $count = model('Admin')->count();
        $info = ['code' => 0, 'msg' => '', 'count' => $count, 'data' => $admin_info];
        echo json_encode($info);
    }

    public function adminUpdate()
    {
        $data = input('post.');
        if (empty($data)) {
            exit('非法操作此页面');
        }
        $where = [
            'admin_id' => $data['admin_id']
        ];
        $field = [
            $data['field'] => $data['value']
        ];
        $res = model('Admin')->where($where)->update($field);
        if ($res) {
            echo json_encode(['font' => 'ok', 'code' => 1]);
        } else {
            echo json_encode(['font' => 'no', 'code' => 2]);
        }
    }

    public function adminDel()
    {
        $admin_id = input('post.admin_id');
        if (empty($admin_id)) {
            exit('非法操作此页面');
        }
        $where = ['admin_id' => $admin_id];
        $res = model('Admin')->where($where)->delete();
        if ($res) {
            echo json_encode(['font' => '删除成功', 'code' => 1]);
        } else {
            echo json_encode(['font' => '删除失败', 'code' => 2]);
        }
    }

    public function adminUpdateInfo()
    {
        //接受id
        $admin_id = input('get.admin_id');
        if (empty($admin_id)) {
            exit('非法操作此页面');
        }
        $where = [
            'admin_id' => $admin_id
        ];
        //查询修改的数据
        $data = model('Admin')->where($where)->find();
        $this->assign('data', $data);
        return $this->fetch();
    }

    public function adminUp()
    {
        if (request()->isPost() && request()->isAjax()) {

            $data = input('post.');
            if (empty($data)) {
                exit('非法操作此页面');
            }
            $validate = validate('Admin');
            if (!$validate->scene('edit')->check($data)) {
                $font = $validate->getError();
                echo json_encode(['font' => $font, 'code' => 2]);
                exit;
            }
            $model = model('Admin');
            $where = [
                'admin_id' => $data['admin_id']
            ];
            $arr = [
                'admin_name' => $data['admin_name'],
                'admin_email' => $data['admin_email'],
                'admin_tel' => $data['admin_tel']
            ];
            $res = $model->where($where)->update($arr);
            if ($res) {
                echo json_encode(['font' => '修改成功', 'code' => 1]);
            } else {
                echo json_encode(['font' => '修改失败', 'code' => 2]);
            }
        }
    }

    public function pwdChange()
    {
        if (check()) {
            $pwd1 = input('post.pwd1');

            $admin_id = input('post.admin_id');
            $where = [
                'admin_id' => $admin_id
            ];
            $admin = model('Admin')->where($where)->find();
            $salt = $admin['salt'];
            //填写的密码
            $res = createPwd($pwd1, $salt);
            $admin_pwd = $admin['admin_pwd'];
            //echo $admin_pwd;
            // echo '<br>';
            //echo $res;die;
            //echo $res;die;
            //print_r($res);exit;

            /*echo $admin_pwd;
            echo $res;exit;*/
            if ($res != $admin_pwd) {
                fail('旧密码错误');
            }
        } else {
            return view();
        }
    }

    public function pwdChangeDo()
    {
        $pwd2 = input('post.');
        $id = $pwd2['admin_id'];
        $n_pwd = $pwd2['admin_pwd2'];
        $where = [
            'admin_id' => $id
        ];
        $admin = model('Admin')->where($where)->find();
        $salt = $admin['salt'];
        //填写的密码
        $new_pwd = createPwd($n_pwd, $salt);
        $arr = [
            'admin_pwd' => $new_pwd
        ];
        $res = model('Admin')->where($where)->update($arr);
        if ($res) {
            session('admin', null);
            $this->view->engine->layout(false);
            win('修改成功');
        } else {
            fail('修改失败');
            exit;
        }
    }

    public function skip()
    {
        $this->success('修改成功', 'Login/login');
    }
}