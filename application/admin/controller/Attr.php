<?php
namespace app\admin\controller;

use think\Controller;
use app\admin\model\Admin as AdminModel;
use think\image\Exception;

/**
 * 商品属性管理
 * Class Attr
 * @package app\admin\controller
 */
class Attr extends Common
{
    public function basicAttr(){



        if( request() -> isPost() ){

            $post = request()->param();

            $basic_model = model('Basic');


            $basic_model -> startTrans();

            try{

                $basic_insert = [];
                $now = time();
                foreach( $post['attr']  as $k => $v ){
                    $basic_insert['category_id'] = $post['category_id'];
                    $basic_insert['attr_name'] = $v;
                    $basic_insert['status'] = 1;
                    $basic_insert['ctime'] = $now;

                    if( !$basic_model -> insert( $basic_insert ) ){
                        throw new \Exception('插入属性表失败');
                    }

                    $basic_id = $basic_model -> getLastInsID();
//                    var_dump($basic_id);

                    # 判断有没有属性值
                    $basic_value_model = model('BasicValue');
                    if( isset( $post['value'][$k] ) ){

                        # 写入属性值表
                        $value_insert = [];
                        foreach( $post['value'][$k] as $kk => $vv ){
                            $value_insert[$kk]['category_id'] = $post['category_id'];
                            $value_insert[$kk]['basic_id'] = $basic_id;
                            $value_insert[$kk]['attr_value'] = $vv;
                            $value_insert[$kk]['status'] = 1;
                            $value_insert[$kk]['ctime'] = $now;
                        }

                        $number = $basic_value_model -> insertAll( $value_insert );
                        if( $number < 1 ){
                            throw new \Exception('插入属性值失败');
                        }
                    }
                }

                $basic_model -> commit();

                $this -> success();

            }catch ( \Exception $e ){
                $basic_model -> rollback();
                $this -> fail( $e -> getMessage() );
            }

        }else{
            # 获取系统的分类
            $category_model = model('Category');

            $c_where = [
                'status' => 1
            ];

            $category_obj = $category_model -> where( $c_where ) -> select();

            $category_list =  collection( $category_obj ) -> toArray();

            $category_list = getInfo( $category_list );

            $this -> assign( 'category_list' , $category_list );

            return $this -> fetch();
        }
    }


    /**
     * 销售属性
     * @return mixed
     */
    public function saleAttr(){

        if( request() -> isPost() ){

            $post = request()->param();

            $sale_model = model('Sale');


            $sale_model -> startTrans();

            try{

                $basic_insert = [];
                $now = time();
                foreach( $post['attr']  as $k => $v ){
                    $basic_insert['category_id'] = $post['category_id'];
                    $basic_insert['attr_name'] = $v;
                    $basic_insert['status'] = 1;
                    $basic_insert['ctime'] = $now;

                    if( !$sale_model -> insert( $basic_insert ) ){
                        throw new \Exception('插入属性表失败');
                    }

                    $sale_id = $sale_model -> getLastInsID();
//                    var_dump($basic_id);

                    # 判断有没有属性值
                    $sale_value_model = model('SaleValue');
                    if( isset( $post['value'][$k] ) ){

                        # 写入属性值表
                        $value_insert = [];
                        foreach( $post['value'][$k] as $kk => $vv ){
                            $value_insert[$kk]['category_id'] = $post['category_id'];
                            $value_insert[$kk]['sale_id'] = $sale_id;
                            $value_insert[$kk]['attr_value'] = $vv;
                            $value_insert[$kk]['status'] = 1;
                            $value_insert[$kk]['ctime'] = $now;
                        }

                        $number = $sale_value_model -> insertAll( $value_insert );
                        if( $number < 1 ){
                            throw new \Exception('插入属性值失败');
                        }
                    }
                }

                $sale_model -> commit();

                $this -> success();

            }catch ( \Exception $e ){
                $sale_model -> rollback();
                $this -> fail( $e -> getMessage() );
            }

        }else {

            # 获取系统的分类
            $category_model = model('Category');

            $c_where = [
                'status' => 1
            ];

            $category_obj = $category_model->where($c_where)->select();

            $category_list = collection($category_obj)->toArray();

            $category_list = getInfo($category_list);

            $this->assign('category_list', $category_list);

            return $this->fetch();
        }
    }


    /**
     * 基本属性的展示
     */
    public function basicAttrShow(){

        $this -> checkRequest();
//        $category_id = 48;
//
        $category_id = request() -> param('category_id');

        # 获取分类对应属性信息
        $basic_model = model('Basic');

        #查询启用的属性
        $where = [
            'a.status' => 1,
            'a.category_id'=> $category_id
        ];

        $basic_obj = $basic_model
            -> field('a.*,v.attr_value,v.basic_value_id')
            -> table('shop_basic_attr a')
            -> join('shop_basic_attr_value v' , 'a.basic_id=v.basic_id','left')
            -> where( $where )
            -> select();
//        echo $basic_model->getLastSql();

        $basic_arr = collection( $basic_obj ) -> toArray();

        $new = [];
        foreach( $basic_arr as $key => $value ){
            $new[$value['basic_id']]['attr_id'] = $value['basic_id'];
            $new[$value['basic_id']]['attr_name'] = $value['attr_name'];
            if($value['basic_value_id']){
                $new[$value['basic_id']]['has_son'] = 1;
                $new[$value['basic_id']]['son'][$value['basic_value_id']] = $value['attr_value'];
            }else{
                $new[$value['basic_id']]['has_son'] = 0;
            }

        }
//        var_dump($new);exit;

        $this -> view -> engine -> layout(false);

        $this -> assign( 'basic' , $new );

        return $this -> fetch();
    }


    /**
     * 销售属性的展示
     */
    public function saleAttrShow(){

        $this -> checkRequest();

//        $category_id = 48;
        $category_id = request() -> param('category_id');

        # 获取分类对应属性信息
        $sale_model = model('Sale');

        #查询启用的属性
        $where = [
            's.status' => 1,
            's.category_id'=> $category_id
        ];

        $sale_obj = $sale_model
            -> field('s.*,v.attr_value,v.sale_value_id')
            -> table('shop_sale_attr s')
            -> join('shop_sale_attr_value v' , 's.sale_id=v.sale_id','left')
            -> where( $where )
            -> select();
//        echo $basic_model->getLastSql();

        $sale_arr = collection( $sale_obj ) -> toArray();

        $new = [];
        foreach( $sale_arr as $key => $value ){
            $new[$value['sale_id']]['attr_id'] = $value['sale_id'];
            $new[$value['sale_id']]['attr_name'] = $value['attr_name'];
            if($value['sale_value_id']){
                $new[$value['sale_id']]['has_son'] = 1;
                $new[$value['sale_id']]['son'][$value['sale_value_id']] = $value['attr_value'];
            }else{
                $new[$value['sale_id']]['has_son'] = 0;
            }
        }


        $this -> view -> engine -> layout(false);

        $this -> assign( 'sale' , $new );

        return $this -> fetch();
    }

    public function test(){
        print_r(request() -> param());
        exit;
    }

}























