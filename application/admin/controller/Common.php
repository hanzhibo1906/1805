<?php
namespace app\admin\controller;
use think\Controller;
class Common extends Controller{

    # 不需要检查权限的控制器 【都需要小写】
    private $no_power_check = [
        '/login/login',
        '/index/index',
        '/login/logout'
    ];


    function _initialize(){

        if(!session("?admin")){
            $this->jumpError('请先登录',url('Login/login'));
        }

        ################## 根据管理员的类型 ，获取管理员的权限
        $admin_info = session('admin');

        if( $admin_info['admin_type'] == 1 ){
            $new = $this -> _getAllMenu();
        }else{
            $new = $this -> _getAdminMenu( $admin_info );
        }
        ################## 根据管理员的类型 ，获取管理员的权限


        $this -> assign('AllMenu' , $new['menu'] );

        ##### admin_type 等于1 表示是超级管理员，超级管理员不收权限控制
        if( $admin_info['admin_type'] != 1 ){

            # 判断用户是否有权限访问当前的控制器和方法
            $power_list = $new['power_list'];

            #获取当前的控制器和方法
            $controller = request() -> controller();
            $action = request() -> action();

            $access = strtolower( '/'  . $controller . '/'.$action);


            # 判断是否 是 不需要检查权限的控制器和方法
            if(  !in_array( $access , $this -> no_power_check ) ){

                if( !in_array( $access , $power_list ) ){
                    echo $this -> fetch('public/nopower');
                    exit;
                }
            }
        }

    }

    /**
     * h获取管理员对应的权限数据
     * @param $admin_info
     */
    public function _getAdminMenu( $admin_info ){

        # 为了防止每次都查询数据库，把后台权限放到session，下一次使用的时候直接从session读取
        if( !session('?power') ) {
//            echo 'sql';

            # 根据管理员的id 获取管理员对应的角色id
            # 根据角色id查询 角色和权限节点的管理表、
            # 根据权限id查询对应的权限
            $sql = 'SELECT
                admin_id,
                role_name,
                pn.*
            FROM
                shop_admin_role ar
            LEFT JOIN shop_role r ON r.role_id = ar.role_id
            LEFT JOIN shop_role_node rn ON rn.role_id = ar.role_id
            LEFT JOIN shop_power_node pn ON pn.node_id = rn.node_id
            WHERE
                admin_id = ' . $admin_info['admin_id'];

            $menu = model('admin')->query($sql);

            if (!empty($menu)) {
                $new = [];
                $power_list = [];
                foreach ($menu as $key => $value) {
                    $power_list[] = strtolower($value['node_url']);
                    if ($value['level'] == 1) {
                        $new[$value['node_id']] = $value;
                    } else {
                        $new[$value['pid']]['son'][] = $value;
                    }
                }
            } else {
                $new = [];
            }

            $return = [
                'menu' => $new,
                'power_list' => $power_list
            ];


            # 把用户的左侧菜单和权限列表存入session
            session( 'power' , $return );

            return $return;
        }else{
//            echo 'session';
            return session('power');
        }

    }

    /**
     * 获取所有的权限节点
     * @return array
     */
    private function _getAllMenu(){
        ################################### 获取系统所有的权限节点######################


        # 为了防止每次都查询数据库，把后台权限放到session，下一次使用的时候直接从session读取
        if( !session('?power') ){

//            echo 'sql';

            # 获取左侧菜单列表
            $menu_model = model('PowerNode');

            $where = [
                'status' => 1
            ];


            $menu_obj = $menu_model -> where( $where ) -> select();

            $menu = collection( $menu_obj ) -> toArray();

            if( !empty( $menu) ){
                $new = [];
                $power_list = [];
                foreach( $menu as $key => $value ){
                    $power_list[] =  strtolower($value['node_url']);
                    if( $value['level'] == 1 ){
                        $new[$value['node_id']] = $value;
                    }else{
                        $new[$value['pid']]['son'][] = $value;
                    }
                }
            }else{
                $new = [];
            }

            $return = [
                'menu' => $new ,
                'power_list' => $power_list
            ];

            # 把用户的左侧菜单和权限列表存入session
            session( 'power' , $return );

            return $return;
        }else{
//            echo 'session';
            return session('power');
        }
        ################################### 获取系统所有的权限节点######################
    }


    /**
     * 失败时候的返回
     */
    protected function fail( $msg = 'fail' , $status = 1 , $data = [] ){

        $arr = [
            'status' => $status,
            'msg' =>$msg,
            'data' => $data
        ];

        echo json_encode( $arr );
        exit;

    }

    /**
     * 失败时候的返回
     */
    protected function success( $data = [] , $status = 1000 , $msg = 'success' ){

        $arr = [
            'status' => $status,
            'msg' =>$msg,
            'data' => $data
        ];

        echo json_encode( $arr );
        exit;

    }

    public function checkRequest(){
        if(  ! request() -> isAjax() && !request() ->isPost() ){
            $this -> fail('非法请求');
        }
    }
}
