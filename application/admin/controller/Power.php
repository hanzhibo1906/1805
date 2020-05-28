<?php
namespace  app\admin\controller;
use think\Controller;
use app\admin\model\Admin as AdminModel;
class Power extends Common{


    public function powerAdd(){

        $model = model('PowerNode');

        if( request() -> isPost() ){


            # 接受数据，写入数据库
//            $insert['node_name'] = request() -> param();
            $insert = request() -> param();
            $insert['ctime'] = time();
            if( $insert['pid'] == '' ){
                $insert['level'] = 1;
            }else{
                $insert['level'] = 2;
            }

            if( $model -> insert( $insert ) ){
                $this -> success();
            }else{
                $this -> fail('添加失败');
            }
        }else{

            # 查询系统现在有的一级菜单
            $where = [
                'pid' => 0 ,
                'level' => 1
            ];


            $menu = $model -> where( $where ) -> select();

            $this -> assign( 'menu' , $menu );

            return view();
        }
    }
    public function powerList(){
        return view();
    }
}