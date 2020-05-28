<?php
namespace app\index\controller;

use think\Request;

class Cart extends Common
{

    /**
     * 添加购物车数据
     */
    public function CartAdd(){

        # 判断请求是否合法
        if( !check() ){
            $this -> fail('非法请求');
        }

        # 接收商品的id
        $goods_id = request()->param('goods_id' , '' , 'intval');

        if( empty($goods_id) ){
            $this -> fail('要购买的商品没有找到');
        }

        # 查询商品是否存在
        $goods_model = model('Goods');

        $where = [
            'gs.sku_id' => $goods_id
        ];

        $goods_obj = $goods_model
            -> table('shop_goods g')
            -> join('shop_goods_sku gs','gs.goods_id=g.goods_id')
            -> where( $where )
            -> find();

        if( !empty($goods_obj) ){
            $goods_info = $goods_obj -> toArray();
        }else{
            $this -> fail('要购买的商品没有找到!');
        }

        # 接收购买数量
        $buy_number = request() -> param('buy_number' , 0 , 'intval');
        if( empty( $buy_number ) ){
            $this -> fail('请输入你要购买的数量');
        }

        # 判断是否登录
        if( $this -> checkUserLogin() ){
            return $this -> _addCartByDb( $goods_info , $buy_number );
        }else{
            return $this -> _addCartByCookie( $goods_info , $buy_number );
        }
    }

    /**
     * 登陆之后购物车数据存入DB
     */
    private function _addCartByDb( $goods_info , $buy_number  ){

        # 判断购物车是否有该商品
        $where = [
            'goods_id' => $goods_info['sku_id'],
            'user_id' => $this -> getUid(),
            'status' => 1
        ];

        $cart_model = model('Cart');

        if( $obj = $cart_model -> where( $where ) -> find() ){
            $cart_info = $obj -> toArray();
        }else{
            $cart_info =[];
        }

        $now = time();

//        var_dump($cart_info);exit;
        # 查询到数据，说明之前添加过该商品，需要修改数量
        if( !empty( $cart_info ) ){

            # 检查商品的库存
            $this -> checkGoodsStock( $goods_info , $buy_number , $cart_info['buy_number'] );

            $save = [];
            $save['buy_number'] = $cart_info['buy_number'] + $buy_number;
            $save['utime'] = $now;

            if( $cart_model -> where( $where ) -> update( $save ) ){
                $this -> success();
            }else{
                $this -> fail('添加失败');
            }
        }else{

            # 检查商品的库存
            $this -> checkGoodsStock( $goods_info , $buy_number , 0 );

            $insert = [];
            $insert['goods_id'] = $goods_info['sku_id'];
            $insert['user_id'] = $this -> getUid();
            $insert['add_price'] = $goods_info['sku_price'];
            $insert['buy_number'] = $buy_number;
            #  购物车的状态 1、正常 2 删除
            $insert['status'] = 1;
            $insert['ctime'] = $now;

            if( $cart_model -> insert( $insert) ){
                $this -> success();
            }else{
                $this -> fail('添加失败');
            }
        }

    }

    /**
     * 没有登陆购物车数据存入cookie
     */
    private function _addCartByCookie( $goods_info , $buy_number ){

        # 从cookie取出购物车数据
        $cart_str = cookie('cart');

        if( $cart_str ){
            $cookie_cart = json_decode(base64_decode( $cart_str ) , true );
        }else{
            $cookie_cart = [];
        }

        $now = time();

        # 判断是否购买过该商品
        if( isset( $cookie_cart[$goods_info['goods_id']] ) ){

            # 积累（多次）购买
            $this -> checkGoodsStock(
                $goods_info ,
                $buy_number ,
                $cookie_cart[$goods_info['goods_id']]['buy_number']
            );

            $cookie_cart[$goods_info['goods_id']]['buy_number'] += $buy_number;
            $cookie_cart[$goods_info['goods_id']]['utime'] = $now;
            $all_cart = $cookie_cart;
        }else{
            # （单次）购买
            $this -> checkGoodsStock(
                $goods_info ,
                $buy_number ,
                0
            );


            # 写入本次购买的数据
            $this_cart = [
                $goods_info['goods_id'] =>
                [
                    'goods_id' => $goods_info['goods_id'],
                    'buy_number' => $buy_number,
                    'ctime' => $now,
                    'utime'=> $now,
                    'add_price'=>$goods_info['goods_selfprice']
                ]
            ];
            $all_cart = $this_cart  +  $cookie_cart;
        }

        cookie( 'cart' , base64_encode(json_encode( $all_cart ) )  );

        $this -> success();

    }


    /**
     * 购物车列表页面
     */
    public function cartList(){

        if( $is_login = $this -> checkUserLogin() ){
            # 登陆状态，从数据库读取数据
            $goods_list = $this -> _getDbCart();
        }else{

            # 从cookie中读取购物车的数据
            $cart = $this -> getCookieCart();

            # 查询购物车对应的商品的数据
            if( !empty( $cart ) ){
                # 不要在for循环中写查询语句
                foreach( $cart as $key => $value ){
                    $id_arr[] = $value['goods_id'];
                }
                $goods_where['gs.sku_id'] = [ 'in' , $id_arr];

                $goods_model = model('Goods');
                $obj = $goods_model
                    -> table('shop_goods g')
                    -> join('shop_goods_sku gs' , 'g.goods_id=gs.goods_id')
                    -> where( $goods_where)
                    -> select();
                if( $obj ){
                    $goods_list = collection( $obj ) -> toArray();
                }else{
                    $goods_list = [];
                }

                foreach( $goods_list as $k => &$v ){
                    $v = array_merge( $v , $cart[$v['goods_id']] );
                }
//                var_dump($goods_list);exit;
            }

        }

        if( $is_login == [] ){
            $login = 0;
        }else{
            $login = 1;
        }
//        var_dump( $login );exit;
        $this -> assign( 'url' , request()->url(true) );
        $this -> assign( 'login' , $login  );
        $this -> assign('cart' , $goods_list);
        return $this -> fetch( );
    }

    /**
     * 获取购物车的数据
     */
    private function _getDbCart(  ){

        $where = [
            'user_id' => $this->getUid(),
            'c.status' => 1
        ];

        $cart_model = model('Cart');

        $obj = $cart_model
            -> table('shop_cart c')
            -> join('shop_goods_sku gs','gs.sku_id=c.goods_id')
            -> join('shop_goods g' , 'g.goods_id=gs.goods_id')
            -> where( $where )
            -> select();

//        echo $cart_model -> getLastSql();exit;

        if( !empty( $obj )  ){
            return collection( $obj ) -> toArray();
        }else{
            return [];
        }

    }

    /**
     * 修改购物车数据
     */
    public function cartUpdate(){

        $this -> checkRequest();

        $goods_id = request() -> param('goods_id' , 0 , 'intval' );
        if( !$goods_id ){
            $this -> fail('商品没有找到');
        }
        $buy_number = request() -> param('buy_number' , 0 , 'intval');
        if( !$buy_number  ){
            $this ->fail('请输入你要购买的数量');
        }

        //判断用户是否登录
        if( $this -> checkUserLogin() ){
            $result = $this -> _updateCartByDb( $goods_id , $buy_number );
        }else{
            $result = $this -> _updateCartByCookie( $goods_id , $buy_number );
        }

        if( $result ){
            $this -> success();
        }else{
            $this -> fail('修改购物车失败，请重试');
        }
    }

    /**
     * 修改cookie中的购物车数据
     * # 参数前边加类型 ： 要求传入的参数 必须符合这个类型
     */
    private function _updateCartByCookie( int $goods_id , int $buy_number){

        # 判断这个用户数是否购买过这个商品
        $cart = $this -> getCookieCart( );

        # 没有购买过商品，直接返回提示信息
        if( !isset( $cart[$goods_id] ) ){
            $this -> fail('没有找到要修改的商品数据');
        }

        # 如果购买过该商品，需要修改购物车的数量

        $goods_info = $this -> getGoodsInfo( $goods_id  );

        if( empty( $goods_info ) ){
            $this -> fail('要修改的商品数据不正确!');
        }

        $this -> checkGoodsStock( $goods_info ,  $buy_number , 0 );

        # 修改购物车的数量
        $cart[$goods_id]['buy_number'] = $buy_number;
        $cart[$goods_id]['utime'] = time();

        cookie( 'cart' , base64_encode(json_encode( $cart ) )  );

        return true;

    }

    /**
     * 修改数据库的购物车购物车数据
     * @param int $goods_id
     * @param int $buy_number
     */
    private function _updateCartByDb( int $goods_id , int $buy_number ){

        # 判断这个用户数是否购买过这个商品
        $where = [
            'status' => 1 ,
            'goods_id' => $goods_id,
            'user_id' => $this -> getUid()
        ];

        $cart_model  = model('Cart');

        if( !$cart_model -> where( $where) -> find()){
            $this -> fail('没有找到要修改的购物车数据');
        }

        # 如果购买过该商品，需要修改购物车的数量
        $goods_info = $this -> getGoodsInfo( $goods_id  );
        if( empty( $goods_info ) ){
            $this -> fail('要修改的商品数据不正确!');
        }

        # 检查商品的库存
        $this -> checkGoodsStock( $goods_info ,  $buy_number , 0 );

        # 修改购物车的数量
        $save = [];
        $save['buy_number'] = $buy_number;
        $save['utime'] = time();

        if( $cart_model -> where( $where ) -> update($save) ){
            return true;
        }else{
            return false;
        }
    }
















}

