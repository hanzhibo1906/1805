<?php
namespace app\index\controller;
use think\image\Exception;

class Order extends Common
{
    public function order()
    {

        # 取出用户所有的订单
        if( !$this -> checkUserLogin() ){
            $this -> jumpError('请先登录' , 'Login/login');
        }

        $where = [
            'o.user_id' => $this -> getUid()
        ];

        $order_model = model('Order');

        $order_list = $order_model
            -> table('shop_order o')
            -> field('o.*,GROUP_CONCAT( CONCAT( goods_name ,
                    \':\' ,goods_price, \'*\' , buy_number , \'<br/>\') ) as buy')
            -> join('shop_order_detail od','o.order_id=od.order_id')
            -> group('o.order_id')
            -> where( $where ) ->paginate( 1 );

//        echo $order_model -> getLastSql();exit;

//        var_dump($order_list);exit;
//        $order_list = collection( $obj ) -> toArray();

        $this -> assign('order' , $order_list );
        $this -> view -> engine -> layout(false);
        return view();
    }

    public function detail()
    {

        if( !$this -> checkUserLogin()){
            $this -> jumpError('请先登陆' , 'Login/login');
        }
        # 接口地址
        $url  = 'http://route.showapi.com/64-19';

        #快递接口需要的参数
        #密钥
        $secret = '7642cc928dd94c13ad11a5259f00c836';

        $param = [
            'showapi_appid' => '78506',
            'com' => 'auto',
            'nu'=>'3381528943947'
        ];

        ksort($param );

        $str = '';
        foreach( $param as $key => $value){
            $str .= $key . $value;
        }

        $str = $str . $secret ;
        $sign = md5( $str );

        $param['showapi_sign'] = $sign;


        $info =  json_decode(file_get_contents( $url .'?' . http_build_query( $param ) ) , true );

//var_dump($info);exit;

        $this -> assign( 'express' , $info  );
        $this -> view -> engine -> layout(false);

        return view();
    }

    public function confirmOrder(){

        # 没有登录不能访问
        if( !$this->checkUserLogin() ){
            $this -> jumpError(
                '还没有登录呢，请先登录'  ,
                url('login/login' , ['callback'=> urlencode(urlencode( request() -> url(true))) ] )
            );
        }

        # 取出购物车对应的数据
        $cart_id_str = request() -> param('cart_id' , '' );

        # 没有购物车数据，不能提交订单，跳转购物车列表页面
        if( !$cart_id_str ){
            $this -> jumpError(
                '还没有登录呢，请先登录'  ,
                url('Cart/cartList' )
            );
        }

        $cart_id_arr = array_filter(array_map( 'intval' , explode( ',' , $cart_id_str  )));

        if( empty( $cart_id_str ) ){
            $this -> jumpError(
                '购物车数据未找到'  ,
                url('Cart/cartList' )
            );
        }

        $where = [
            'cart_id' => [ 'in' , $cart_id_arr ],
            'user_id' => $this -> getUid()
        ];

        $cart_model  = model('Cart');

        $obj = $cart_model
            -> table('shop_cart c')
            -> join('shop_goods_sku gs','gs.sku_id=c.goods_id')
            -> join( 'shop_goods g','g.goods_id=gs.goods_id')
            -> where( $where )
            -> select();




        if( $obj ){
            $cart_info = collection( $obj ) -> toArray();
        }else{
            $this -> jumpError(
                '购物车数据未找到'  ,
                url('Cart/cartList' )
            );
        }


        # 取出用户对应的收货地址信息
        $addr_where = [
            'user_id' => $this -> getUid(),
            'status' => 1
        ];

        $addr_model = model('Address');

        if( $obj = $addr_model -> where( $addr_where ) -> order('address_default desc') -> select()){
            $user_address = collection( $obj ) -> toArray();
        }else{
            $user_address = [];
        }

        $this -> assign( 'user_address' , $user_address );
        $this -> assign('cart' , $cart_info);
        return $this -> fetch();
    }

    public function submitOrder(){

        # 检测是不是非法请求
        $this -> checkRequest();

        # 接受cartid
        $cart_id_str = request() -> param('cart_id' , '');
        if( !$cart_id_str ){
            $this -> jumpError(
                '购物车数据未找到'  ,
                url('Cart/cartList' )
            );
        }

        # 接收支付方式

        # 订单支付方式
        $order_pay_type = request() ->param('pay_type' , 1 ,  'intval');

        # 收货地址的id
        $addr_id = request() ->param('addr_id' , 0 ,  'intval');
        if(  !$addr_id ){
            $this -> fail('请选择您的收货地址');
        }

        $url =  request() -> domain() .url( 'Order/confirmOrder' , [ 'cart_id' =>  $cart_id_str ] );

        # 接受数据
        if( !$this -> checkUserLogin() ){
            $this -> jumpError(
                '还没有登录呢，请先登录',
                url('Login/login' , [ 'callback' => urlencode(urlencode( $url ))])
            );
        }

        # 查询购物车的数据
        $cart_id_arr = array_filter(array_map( 'intval' , explode( ',' , $cart_id_str  )));

        if( empty( $cart_id_str ) ){
            $this -> jumpError(
                '购物车数据未找到'  ,
                url('Cart/cartList' )
            );
        }

        $cart_where = [
            'cart_id' => [ 'in' , $cart_id_arr ],
            'user_id' => $this -> getUid(),
            'shop_cart.status' => 1
        ];

        $cart_model  = model('Cart');

        $obj = $cart_model
            -> table('shop_cart c')
            -> join('shop_goods_sku gs','gs.sku_id=c.goods_id')
            -> join( 'shop_goods g','g.goods_id=gs.goods_id')
            -> where( $cart_where )
            -> select();

//        var_dump($)
        # 查询多条之后返回一个对象，需要过来一下空
//        $obj = array_filter( (array) $obj );

        if( $obj ){
            $cart_info = collection( $obj ) -> toArray();
        }else{
            $cart_info = [];
            $this -> jumpError(
                '购物车数据未找到'  ,
                url('Cart/cartList' )
            );
        }
//        var_dump($cart_info);exit;

        # 实例化订单模型
        $order_model = model('Order');

        # 开启事务
        $order_model -> startTrans();

        try{

            $now = time();
            $user_id = $this -> getUid();

            # 1、写入订单表数据
            $order_no = $this -> _createOrderNo();
            $order_insert = [];
            $order_insert['user_id'] = $user_id;
            $order_insert['order_no'] = $order_no;

            # 计算订单的金额
            $order_amount = 0.00;

            # 遍历购物车数据，计算总金额
            foreach ( $cart_info as $key => $value ){
                $order_amount += $value['buy_number'] * $value['goods_selfprice'];
            }

            $order_insert['order_amount'] = $order_amount;
            $order_insert['order_paytype'] = $order_pay_type;
            $order_insert['order_note'] = request() -> param('note');
            # 1、待支付
            if( $order_pay_type == 2 ){
                # 货到付款的订单需要商家先确认，确认之后 直接就是发货
                $order_insert['order_status'] = 4;
            }else{
                $order_insert['order_status'] = 1;
            }

            $order_insert['ctime'] =$now ;

            $order_model -> insert( $order_insert );

            $order_id =  $order_model -> getLastInsID();

            if(  $order_id  < 0 ){
                throw new \Exception('订单表写入失败，请重试' , 100 );
            }


            # 2、写入订单商品表
            $order_detail = [];
            foreach( $cart_info as $k => $v ){
                $order_detail[$k]['order_id'] = $order_id;
                $order_detail[$k]['user_id'] = $user_id;
                $order_detail[$k]['goods_id'] = $v['goods_id'];
                $order_detail[$k]['buy_number'] = $v['buy_number'];
                $order_detail[$k]['goods_name'] = $v['goods_name'];
                $order_detail[$k]['goods_price'] = $v['goods_selfprice'];
                $order_detail[$k]['goods_img'] = $v['goods_goods_img'];
                $order_detail[$k]['status'] = 1;
                $order_detail[$k]['ctime'] = $now;
            }

            $order_detail_model = model('OrderDetail');

            $number = $order_detail_model -> insertAll( $order_detail );

            if( $number < 1 ){
                throw new \Exception('详情表写入失败，请重试' , 100 );
            }

            # 3、写入订单的收货地址表
            $order_address = [];

            $address_model = model('Address');

//            $where = [];
            $address_where = [
                'address_id' => $addr_id,
                'user_id' => $user_id,
                'status' => 1
            ];
            if( $obj = $address_model -> where( $address_where ) -> find() ){
                $addr_info = $obj -> toArray();
            }else{
                throw new \Exception( '没有找到对应的收货地址',100 );
            }


            $order_address['order_id'] = $order_id;
            $order_address['user_id'] = $user_id;
            $order_address['receive_name'] = $addr_info['address_man'];
            $order_address['receive_phone'] = $addr_info['address_tel'];
            $order_address['address_detail'] = $addr_info['address_detail'];
            $order_address['post_code'] = '000000';
            $order_address['ctime'] = $now;
            $order_address['status'] = 1;

            $order_address_model = model('OrderAddress');

            if( !$order_address_model -> insert( $order_address ) ){
                throw new \Exception('订单收货地址写入失败', 100);
            }

            # 4、减商品的库存
            $goods_model = model('Goods');
            $sku_model = model('GoodsSku');

            foreach( $cart_info as $k => $v ){

//                var_dump($v);exit;
                $goods_where = [
                    'sku_id' => $v['sku_id'],
                    'status' => 4
                ];

                # 检查库存
                $check = $this -> checkGoodsStock( $v , $v['buy_number'] , 0 , 0 );
                if( !$check ){
                    $order_model -> rollback();
                    if(  $v['goods_stock'] > 200 ){
                        $v['goods_stock'] = 200;
                    }
                    $this -> fail( $v['goods_name'].'只能购买' .$v['goods_stock'] . '件');
                }

                $goods_save = [
                    'sku_stock' => $v['sku_stock'] - $v['buy_number']
                ];
//                var_dump($goods_save);exit;

                if( ! $sku_model -> where( $goods_where ) -> update( $goods_save ) ){
//                    var_dump($v);exit;
                    echo $sku_model->getLastSql();
                    throw new \Exception('商品库存修改失败',100);
                }

            }

            # 5、删除购物车的数据
            $cart_save = [
                'status' => 2,
                'utime' => $now
            ];


            if( !$cart_model -> where( $cart_where  ) -> update( $cart_save ) ){
                throw new \Exception('购物车数据删除失败',100);
            }

            # t提交事务
            $order_model -> commit();

            # 返回成功
            $this -> success( ['order_no' => $order_no ] );

        }catch ( \Exception $e ){

            $order_model -> rollback();

            $this -> fail( $e -> getMessage() );
        }
    }

    /**
     *  订单创建成功页面
     */
    public function createSuccess(){

        $order_no = request() -> param('order_no' , '' );

        if( !$order_no ){
            $this -> jumpError('没有找到你要查看的订单');
        }

        $order_model = model('order');

        $order_where = [
            'order_no' => $order_no,
            'user_id' => $this -> getUid()
        ];

        if( $obj = $order_model -> where( $order_where ) -> find() ){
            $order_info = $obj -> toArray();
        }else{
            $this -> jumpError('没有找到你要查看的订单!');
        }


//        var_dump($order_info);
//        exit;
        $this -> assign( 'order' ,$order_info );
        return $this -> fetch();



    }

    /**
     * 支付宝支付
     */
    public function alipay(){

        $this -> checkUserLogin();

        $order_info = $this -> getOrderInfo();

        ##############支付宝支付##############

        $config = config('ali_pay_config');

        require_once EXTEND_PATH . 'alipay/pagepay/service/AlipayTradeService.php';

        require_once EXTEND_PATH.'alipay/pagepay/buildermodel/AlipayTradePagePayContentBuilder.php';


        //商户订单号，商户网站订单系统中唯一订单号，必填
        $out_trade_no = $order_info['order_no'];

        //订单名称，必填
        $subject = "1805--电子商城--支付宝支付";

        //付款金额，必填
        $total_amount = $order_info['order_amount'];

        //商品描述，可空
        $body = '这是我购买的商品';

        //构造参数
        $payRequestBuilder = new \AlipayTradePagePayContentBuilder();
        $payRequestBuilder->setBody($body);
        $payRequestBuilder->setSubject($subject);
        $payRequestBuilder->setTotalAmount($total_amount);
        $payRequestBuilder->setOutTradeNo($out_trade_no);

        $aop = new \AlipayTradeService($config);

        /**
         * pagePay 电脑网站支付请求
         * @param $builder 业务参数，使用buildmodel中的对象生成。
         * @param $return_url 同步跳转地址，公网可以访问
         * @param $notify_url 异步通知地址，公网可以访问
         * @return $response 支付宝返回的信息
         */
        $response = $aop->pagePay($payRequestBuilder,$config['return_url'],$config['notify_url']);

    }


    /**
     * 支付宝同步通知
     */
    public function paySuccess(){

        if( !$this -> checkUserLogin()){
            $this -> jumpError('请先登陆' , 'Login/login');
        }

        $param = request() -> param( );
        $order_no = $param['out_trade_no'];

        $order_info = $this ->getOrderInfo( $order_no );

        # 验证订单号和订单金额是否正确
        if( $order_info['order_no'] !=  $param['out_trade_no'] ){
            $this -> jumpError('订单号未找到','Index/index');
        }

        if( $order_info['order_amount'] !=  $param['total_amount'] ){
            $this -> jumpError('订单号金额不正确','Index/index');
        }


        ####################### 支付宝同步通知之后，需要验证签名是否正确  【防止在传输的过程被修改】
        $config = config('ali_pay_config');
        require_once EXTEND_PATH . 'alipay/pagepay/service/AlipayTradeService.php';


        $alipaySevice = new \AlipayTradeService($config);
        $result = $alipaySevice->check( $param );

        if( $result ){

            $this -> assign('order' , $order_info);
            return $this -> fetch();
        }else{
            $this -> jumpError('订单数据未找到','Index/index');
        }
    }


    /**
     * 支付宝的异步通知
     */
    public function notify(){

        # 接受支付宝的异步通知的数据
        $ali_param = request() -> param();
        file_put_contents(
            '/data/wwwroot/default/tp5/alipay.log',
            print_r($ali_param , true ) ,
            FILE_APPEND
        );

        # 接受订单号
        $order_no = $ali_param['out_trade_no'];

        # 验证是否是支付的异步通知
        ####################### 支付宝同步通知之后，需要验证签名是否正确  【防止在传输的过程被修改】
        $config = config('ali_pay_config');
        require_once EXTEND_PATH . 'alipay/pagepay/service/AlipayTradeService.php';

        $alipaySevice = new \AlipayTradeService($config);
        $alipaySevice->writeLog(var_export($_POST,true));
        $result = $alipaySevice->check($ali_param);

        # 验签不成功，返回错误提示
        if( !$result ){
            file_put_contents(
                '/data/wwwroot/default/tp5/alipay.log',
                'check sign fail' ,
                FILE_APPEND
            );
            echo 'check sign fail';
            exit;
        }



        # 获取一下订单信息
        $order_info = $this -> getOrderInfo( $order_no , 1 );

        # 判断订单金额是否正确
        if( $order_info['order_amount'] != $ali_param['total_amount']  ){

            file_put_contents(
                '/data/wwwroot/default/tp5/alipay.log',
                '订单金额不正确' ,
                FILE_APPEND
            );
            echo '订单金额不正确';
            exit;
        }

        # 验证appid是否正确
        if( $ali_param['app_id'] != $config['app_id']  ) {

            file_put_contents(
                '/data/wwwroot/default/tp5/alipay.log',
                'appid is error' ,
                FILE_APPEND
            );
            echo 'appid is error';
            exit;
        }

        # 判断订单状态，是否是未支付，只有未支付的订单才需要需改订单状态
        if( $order_info['order_status'] > 1 ){
            file_put_contents(
                '/data/wwwroot/default/tp5/alipay.log',
                'success1' ,
                FILE_APPEND
            );
            echo 'success';
            exit;
        }

        if( $order_info['order_status'] == 1 ){
            # x修改数据库的订单状态为已支付 【2】
            $where = [
                'order_no' => $order_no
            ];
            # 修改数据
            $save = [
                'order_status' => 2,
                'pay_time' => time(),
                'utime' => time()
            ];

            $order_model = model('order');


            if( $order_model -> where( $where ) -> update($save) ){
                file_put_contents(
                    '/data/wwwroot/default/tp5/alipay.log',
                    'success2' ,
                    FILE_APPEND
                );
                echo 'success';
            }else{
                file_put_contents(
                    '/data/wwwroot/default/tp5/alipay.log',
                    'update fail' ,
                    FILE_APPEND
                );
                echo 'fail';
            }
            exit;

        }


    }

    /**
     * 查询
     * @param string $order_no_param
     * @return array
     */
    private function getOrderInfo( $order_no_param = ''  , $notify = 0 ){

        $order_no = request() -> param('order_no' , '' );

        if( $order_no_param ){
            $order_no = $order_no_param;
        }

        if( !$order_no ){
            $this -> jumpError('没有找到你要查看的订单');
        }

        $order_model = model('order');


        if( $notify ){
            $order_where = [
                'order_no' => $order_no
            ];
        }else{
            $order_where = [
                'order_no' => $order_no,
                'user_id' => $this -> getUid()
            ];
        }

        if( $obj = $order_model -> where( $order_where ) -> find() ){
            $order_info = $obj -> toArray();
        }else{
            $this -> jumpError('没有找到你要查看的订单!');
        }

        return $order_info;
    }


    /**
     * 生成订单编号
     */
    private function _createOrderNo(){

        # 订单号规则
        #  业务线（1位） + 时间（6位 181022 ） + 用户id （4位） + 4位随机数

        $uid = $this -> getUid();

        $uid = 100182;

        if(  $uid < 10000 ){
            $uid = str_repeat( 0 , 4 - strlen( $uid ) ) . $uid;
        }else{
            $uid = substr( $uid , -4 , 4 );
        }

        return  1 . date('ymd') . $uid . rand( 1000,9999);


    }

}




















