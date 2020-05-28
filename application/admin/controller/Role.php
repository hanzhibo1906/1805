<?php
namespace app\admin\controller;
use think\image\Exception;

/**
 * 角色管理
 * Class Role
 * @package app\admin\controller
 */
class Role extends Common{

    public function roleAdd(){

        # 判断是否是post提交数据
        if( request() -> isPost() ){

            # 检测请求是否合法
            $this -> checkRequest();

            $role_model = model('Role');

            $role_model -> startTrans();

            try{
                # 添加
                # 写入角色表
                $now = time();
                $insert = [];
                $insert['role_name'] = request() ->param('role_name');
                $insert['status'] = request() ->param('status');
                $insert['ctime'] = $now;

                $role_model -> insert( $insert );
                $role_id = $role_model -> getLastInsID();

                if( $role_id < 1 ){
                    throw new Exception('写入角色表失败');
                }

                # 写角色关联的数据
                $post = request() -> param();
                $power = $post['power'];
                $i = 0 ;
                $new = [];
                foreach( $power as $k => $v ){
                    $new[$i]['role_id'] = $role_id;
                    $new[$i]['node_id'] = $v;
                    $i ++;
                }
                $role_node = model('RoleNode');
                $number = $role_node -> insertAll( $new );

                if( $number < 1 ){
                    throw new Exception('写入关联表失败');
                }

                $role_model -> commit();

                $this -> success();

            }catch ( \Exception $e ){
                $msg = $e -> getMessage();

                $role_model -> rollback();

                $this -> fail( $msg );

            }


        }else{
            return $this -> fetch();
        }

    }



    public function roleList(){

        return $this -> fetch();
    }

}
