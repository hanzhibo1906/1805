<?php
namespace app\index\controller;
use think\Controller;
class Common extends Controller
{
    function _initialize(){
    }


    /**
     * 查询左侧公共的分类信息
     */
    protected function getCategoryInfo(){

        $where=[
            'cate_show'=>1
        ];
        $res=model('Category')->where($where)->select();

        $newRes=collection($res)->toArray();

        $data=getIndexCateInfo($newRes);

        $this->assign('data',$data);

    }

    protected function checkUserLogin(){
        if(!session("?sessionInfo")){
            return [];
            //$this->error('请先登录',url('Login/login'));
        }else{
            return session('sessionInfo');
        }
    }

    /**
     * 获取单个分类的信息
     */
    protected function getCategoryInfoByCid( $category_id ){

//        echo $category_id , '<br/>';
        # 查询二级分类
        $where = [
            'cate_id' => $category_id
        ];
        $category_model = model('Category');

        $category_info  = $category_model -> where( $where )
            ->find()
            ->toArray();

        return $category_info;

    }
    /**
     * 从cookie中获取用户的浏览记录
     */
    protected function _getHistoryByCookie( $show_all = 0 ){

        $cookie_history = [];
        if( cookie('?history') ){
            $cookie_history_str = cookie('history');
            $cookie_history = json_decode(base64_decode($cookie_history_str ) , true );
        }

        # 如果等于1说明去所有的
        if( $show_all == 1 ){
            return $cookie_history;
        }

        # 处理浏览记录，会存在一个商品浏览了多次
        if( count($cookie_history)  > 1 ){
            $new = [];
            foreach( $cookie_history as $k => $v ){
                $new[$v['goods_id']] = $v;
            }
            $cookie_history = $new;
        }

        return $cookie_history;

    }

    /**
     * 返回用户id
     */
    protected function getUid(){

        return session('sessionInfo.user_id');

    }

    /**
     * 同步浏览记录
     */
    protected function asyncHistory(){

        # 获取cookie中的浏览记录
        $cookie = $this -> _getHistoryByCookie( 1 );

        # 批量写入mysql数据库
        $history_model = model('History');

        # 遍历cookie的数据
        if( !empty( $cookie ) ){
            foreach( $cookie as $key => $value ){
                $cookie[$key]['user_id'] = $this -> getUid();
            }

            if( $history_model -> saveAll( $cookie ) ){
                cookie('history' , null );
            }
        }
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

    /**
     * 检查商品的库存
     * @param $goods_info  商品的基本信息
     * @param $buy_number  本次购买数量
     * @param $old_buy_number 之前购买数量
     */
    protected function checkGoodsStock( $goods_info , $buy_number  , $old_buy_number , $show_error = 1 ){

        if( $goods_info['goods_stock'] > 200 ){
            $goods_info['goods_stock'] = 200;
        }

        # 单次购买不能超过库存
        if( $buy_number > $goods_info['goods_stock'] ){
            if( $show_error ){

                $this -> fail(
                    '商品'.$goods_info['goods_name'] .
                    '最多只能购买' . $goods_info['goods_stock'] .'件'
                );
            }else{
                return false;
            }
        }

        # 累计购买不能超过库存
        if( ( $old_buy_number + $buy_number )  > $goods_info['goods_stock'] ){
            $can_buy_number = $goods_info['goods_stock'] - $old_buy_number;
            if( $can_buy_number > 0 ){
                if( $show_error ){
                    $this -> fail(
                        '商品'.$goods_info['goods_name'] .
                        '最多只能购买' . $goods_info['goods_stock'] .'件，你已经购买了'.$old_buy_number.'件，'.
                        '还可以买'. ($goods_info['goods_stock'] - $old_buy_number) . '件。'
                    );
                }else{
                    return false;
                }

            }else{

                if( $show_error ){
                    $this -> fail(
                        '商品'.$goods_info['goods_name'] .
                        '最多只能购买' . $goods_info['goods_stock'] .'件，你已经购买了'
                        . $goods_info['goods_stock'] .'不能继续购买了。'
                    );
                }else{
                    return false;
                }
            }
        }
        return true;
    }



    /**
     * 同步购物车的数据
     */
    protected function asyncCart(){

        # 从cookie取出购物车数据
        $cart_str = cookie('cart');

        if( $cart_str ){
            $cookie_cart = json_decode(base64_decode( $cart_str ) , true );
        }else{
            $cookie_cart = [];
        }


        # 如果cookie中不存在购物车数据就不同步
        if( !empty( $cookie_cart ) ){

            $cart_model = model( 'Cart' );
            $goods_model = model('Goods');
            foreach( $cookie_cart as $k => $v ){

                # 判断数据库有没有这条数据
                $cart_where = [
                    'user_id' => $this -> getUid(),
                    'goods_id' => $v['goods_id'],
                    'status' => 1
                ];

                # 如果查询到，说明之前数据库存在这条记录
                if( $obj = $cart_model -> where( $cart_where ) -> find() ){

                    $cart_info = $obj -> toArray();

                    # 检查是否超过库存
                    $goods_where = [
                        'gs.sku_id' => $v['goods_id']
                    ];
                    $goods_info = $goods_model
                        -> table('shop_goods g')
                        -> join('shop_goods_sku gs','g.goods_id=gs.goods_id')
                        -> where( $goods_where )
                        -> find()
                        -> toArray();

                    # 检查库存
                    $check = $this -> checkGoodsStock(
                        $goods_info ,
                        $v['buy_number'] ,
                        $cart_info['buy_number'] ,
                        0
                    );
                    if( $check ){
                        # 修改数量和修改时间
                        $save = [];
                        $save['utime'] = time();
                        $save['buy_number'] = $cart_info['buy_number'] + $v['buy_number'];
                    }else{
                        $save = [];
                        $save['utime'] = time();
                        # cookie中的数据 和 购物车数据  加起来超过200  就给最大的库存
                        if( $goods_info['goods_stock'] < 200 ){
                            $all = $goods_info['goods_stock'];
                        }else{
                            $all = 200;
                        }
                        $save['buy_number'] = $all;
                    }
                    $cart_model -> where( $cart_where ) -> update(  $save );

                # 不存在修改商品，需要在数据库新建一个数据
                }else{
                    $v['user_id'] = $this -> getUid();

                    $cart_model -> insert($v);
                }


            }
            # 清空购物车数据
            cookie('cart',null);
            return true;

        }
    }


    public function checkRequest(){
        if(  ! request() -> isAjax() && !request() ->isPost() ){
            $this -> fail('非法请求');
        }
    }




    /**
     * 读取cookie中的购物车数据
     */
    protected function getCookieCart(){
        # 从cookie取出购物车数据
        $cart_str = cookie('cart');

        if( $cart_str ){
            $cookie_cart = json_decode(base64_decode( $cart_str ) , true );
        }else{
            $cookie_cart = [];
        }

        return $cookie_cart;

    }

    /**
     * 读取cookie中的购物车数据
     */
    protected function getGoodsInfo( int $goods_id ){

        $where = [
            'goods_id' => $goods_id
        ];

        $goods_model = model('Goods');

        if( $obj = $goods_model -> where( $where ) -> find()) {
            return $obj -> toArray();
        }else{
            return [];
        }

    }

}
























