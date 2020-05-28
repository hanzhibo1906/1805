<?php
namespace app\index\controller;

use page\AjaxPage;

class Product extends Common
{
    /**
     * 商品列表
     * @return mixed
     */
    public function ProductList(){

        $category_id = request() -> param('cid');

        $goods_model = model('Goods');

        if(  $category_id != 0 ){

            # 查询三级分类
            $three = $this -> getCategoryInfoByCid( $category_id );
            $two = $this -> getCategoryInfoByCid( $three['pid'] );
            $one = $this -> getCategoryInfoByCid( $two['pid'] );

            # 查询分类下对应的品牌
            $brand_where = [
                'cate_id' => $category_id
            ];

        }else{
            $brand_where = [];
            $three = [];
            $two = [];
            $one = [];
        }

        # 查询分类下对应的品牌数据
        $brand_info = $goods_model
            -> table('shop_goods g')
            -> field('distinct(g.brand_id),brand_name')
            -> join( 'shop_brand b' , '`g`.brand_id=`b`.brand_id')
            -> where( $brand_where )
            ->select();


        $brand_info_arr = collection( $brand_info ) -> toArray();

        # 查询商品数据，分页 【每页】
        $page = request() -> param('page', 1 , 'intval');

        $page_size = 8;
        # 查询商品的总条数

        $goods_where = [
            'gs.status' => 4
        ];


        ###################商品品牌的筛选###############
        $brand_id = request() -> param('brand_id' , '' , 'intval');

        if( !empty( $brand_id) ){
            $goods_where['brand_id'] = $brand_id;
        }
        ###################商品品牌的筛选###############

        ##################价格的筛选###################
        $price = request() -> param('price' , '' );
        $old_price = $price;
        if( !empty( $price ) ){
            $price = str_replace( ',' , '' , $price );
            if( strstr( $price , '-' ) ){
                $price_arr = explode( '-' , $price );
                $goods_where['sku_price'] = [ 'between' , $price_arr ];
            }else{
                $goods_where['sku_price'] = [ '>=' , intval( $price ) ];
            }
        }
        ##################价格的筛选###################

        ##################排序功能#####################
        $order_field = request() -> param('order_field' , 1 , 'intval');
        $order_type =  request() -> param('order_type' , 0 , 'intval');
        $order = [];
        switch( $order_field ){
            # 默认
            case 1:
                $order = ['sku_sale_number' => 'desc' , 'goods_new' => 'asc' ];
                break;
            # 销量
            case 2:
                if( $order_type == 1 ){
                    $order = ['sku_sale_number' => 'asc'];
                }else{
                    $order = ['sku_sale_number' => 'desc'];
                }
                break;
            # 价格
            case 3:
                if( $order_type == 1 ){
                    $order = ['sku_price' => 'asc' ];
                }else{
                    $order = ['sku_price' => 'desc' ];
                }
                break;
            # 新品
            case 4:
                $order = ['goods_new' => 'asc' , 'ctime' => 'desc' ];
                break;
            # 默认
            default:
                $order = ['sale_number' => 'desc' , 'goods_new' => 'asc' ];
                break;
        }


        ##################排序功能#####################

        if( !empty( $category_id ) ){
            $goods_where['cate_id'] = $category_id;
        }

//        $msg_count = $goods_model -> where( $goods_where ) -> count('goods_id');
//var_dump($goods_where);exit;
        $msg_count =  $goods_model
            -> where( $goods_where )
            -> table('shop_goods g')
            -> join('shop_goods_sku gs','g.goods_id=gs.goods_id','left')
            -> count('gs.goods_id');

        $page_str = AjaxPage::ajaxpager(
            $page,
            $msg_count,
            $page_size,
            url('Product/productList',
                [
                    'cid' => $category_id ,
                    'brand_id' => $brand_id ,
                    'price' =>$old_price ,
                    'order_field'=>$order_field,
                    'order_type' => $order_type
                ]
            ),
            'productlist'
        );

        $product_obj =  $goods_model
            -> where( $goods_where )
            -> table('shop_goods g')
            -> order( $order )
            -> join('shop_goods_sku gs','g.goods_id=gs.goods_id','left')
            -> paginate( $page_size  );
//        echo $goods_model -> getLastSql();exit;
//echo $goods_model -> getLastSql();exit;

        $this -> assign( 'product_list' , $product_obj );

        $this -> assign( 'page_str' ,$page_str);
        # 获取商品的最高价格
//        $max_price = $goods_model -> where( $goods_where ) -> max('goods_selfprice');
        $max_price = $goods_model
            -> where( $goods_where )
            -> table('shop_goods g')
            -> join('shop_goods_sku gs','g.goods_id=gs.goods_id','left')
            -> max('gs.sku_price');


        # 获取价格区间
        $money = $this -> _showMoneySelect( $max_price );

        $this -> assign('money' , $money );

        $this -> assign( 'brand' , $brand_info_arr );

        $this -> assign(  'three' , $three  );
        $this -> assign(  'two' , $two  );
        $this -> assign(  'one' , $one  );

        # 查询左侧分类数据
        $this -> getCategoryInfo();

        if( request() ->isAjax() ){
            $this->view->engine->layout(false);
            return $this -> fetch('product');
        }else{
            return $this -> fetch('list');
        }
    }


    /**
     * 根据价格展示价格区间
     * @return array
     */
    private function _showMoneySelect( $max_price ){

        # 每一份价格
        $one_price = ceil( $max_price / 7 );

        # 价格筛选的数组
        $money_arr = [];

        # 循环 出现价格区间
        for( $i =0 ; $i < 6 ; $i ++ ){
            $start = $one_price * $i ;
            $end = $one_price * ($i + 1)-0.01;
            $money_arr[] = number_format($start , 2 , '.' , ','). '-' . number_format($end , 2 , '.' , ',');
        }

        $money_arr[] = number_format($end + 0.01 , 2, '.' , ','). '以上';

        return $money_arr;

    }


    public function productDetail(){

        # 接收商品的id
        $goods_id = request() -> param('id', 0 , 'intval');

        if( empty( $goods_id ) ){
            $this -> jumpError( '要查看的商品不存在');
        }

        $goods_model  = model('Goods');

        $where = [
            'gs.sku_id' => $goods_id
        ];

        $obj = $goods_model
            -> table('shop_goods g')
            -> join('shop_goods_sku gs','gs.goods_id=g.goods_id')
            -> where($where)
            -> find() ;

//        echo $goods_model -> getLastSql();exit;
        if( empty( $obj ) ){
            $this ->jumpError( '要查看的商品找不到');
        }

        $goods_info = $obj -> toArray();
//        var_dump($goods_info);exit;

        # 查询货品对应的商品的属性
        $sku_goods_id = $goods_info['goods_id'];

        $model = model('GoodsSaleAttr');

        $where = [
            'gsa.goods_id' => $sku_goods_id,
            'gsa.status' => 1
        ];
        $obj = $model
            -> table('shop_goods_sale_attr gsa')
            -> join('shop_sale_attr sa','sa.sale_id=gsa.sale_attr_id')
            -> join('shop_sale_attr_value sav','gsa.sale_value_id=sav.sale_value_id')
            -> where( $where )
            -> select();

        $info =  collection( $obj ) -> toArray();


        if( !empty( $info ) ){
            $attr = [];
            foreach( $info as $key => $value ){
                $attr[$value['sale_attr_id']]['sale_attr_id'] = $value['sale_attr_id'];
                $attr[$value['sale_attr_id']]['sale_attr_name'] = $value['attr_name'];
                $attr[$value['sale_attr_id']]['son'][$value['sale_value_id']] = $value['attr_value'];
            }

        }

        # 取出商品的对应sku的id，根据id去查对应的属性值
        $sale_where = [
            'id'=> [ 'in', $goods_info['sku_attr']]
        ];

        $obj = $model -> field('sale_value_id') -> where( $sale_where ) -> select();

        $check_sale_value = collection( $obj ) -> toArray();
        $check = [];
        foreach( $check_sale_value as $k => $v ){
            $check[] = $v['sale_value_id'];
        }

        # 去除商品对应的基本属性
        $basic_model = model('GoodsBasicAttr');

        $basic_where = [
            'goods_id' =>$sku_goods_id
        ];

        $basic_list =$basic_model
            -> table('shop_goods_basic_attr gba')
            -> where( $basic_where)
            -> join('shop_basic_attr ba' ,'ba.basic_id=gba.basic_attr_id' ,'left')
            -> select();

        $this -> assign( 'basic' , $basic_list );

        # 取出货品对应的属性
        $this -> assign( 'check' , $check );

        $this -> assign( 'attr' , $attr );

        # 获取用户的浏览记录
//        $history = $this -> _getHistory();
        $history = [];

        $this -> assign( 'history' , $history );

        # 用户在进入详情页的时候，把浏览记录的信息存入cookie中
//        $this -> _addHistory( $goods_id );

        # 商品的库存
        $goods_info['goods_stock'] = 200;

        $this -> assign( 'goods' ,$goods_info );

        return $this -> fetch('detail');
    }

    /**
     * 记录浏览记录
     */
    private function _addHistory( $goods_id ){
        # 首先判断用户是否登陆
        if( $this -> checkUserLogin()  ){
            # 用户是登陆状态，浏览记录存到数据库
            $this -> _dbHistory( $goods_id );
        }else{
            # 没有登陆的状态，存入cookie
            $this -> _cookieHistory( $goods_id );
        }
    }

    private function _getHistory(){

        # 首先判断用户是否登陆
        if( $this -> checkUserLogin()  ){
            # 用户是登陆状态，浏览记录存到数据库
            return $this -> _getHistoryByMysql(  );
        }else{
            # 没有登陆的状态，存入cookie
            $cookie = $this -> _getHistoryByCookie( );
            $goods_id = [];
            if( !empty($cookie) ){
                foreach( $cookie as $k => $v ){
                    $goods_id[] = $v['goods_id'];
                }
            }
            if( empty($goods_id) ){
                return [];
            }
            # 查询商品表返回商品的基本信息
            $goods_model = model('Goods');
            $where =[
                'goods_id' => [ 'in', $goods_id ]
            ];

            $all = $goods_model -> where( $where )->select();

            $arr = collection( $all) -> toArray();
            # 以goods_id作为key
            $goods_all = [];
            foreach( $arr as $key => $value ){
                $goods_all[$value['goods_id']] = $value;
            }

//            var_dump($goods_all);exit;
            foreach( $cookie as $k => $v ){
                $cookie[$k] = array_merge( $v , $goods_all[$v['goods_id']] );
            }

            return $cookie;

        }
    }


    /**
     * 登陆状态下，从数据库去浏览记录
     */
    private  function _getHistoryByMysql(){

        $history_model = model('History');

        $where =[
            'user_id' => $this -> getUid()
        ];

        $history_object = $history_model -> table('shop_history h')
            -> join( 'shop_goods g ','h.goods_id=g.goods_id')
            -> where( $where )
            -> order('h.ctime desc')
            -> select();

        $history_list = collection( $history_object ) -> toArray();

        $history = [];
        foreach( $history_list as $key => $v ){
            $history[$v['goods_id']] = $v;
        }

        return $history;

    }


    /**
     * 没有登陆，浏览记录存入cookie中
     */
    private function _cookieHistory( $goods_id )
    {

//        # base64_encode 编码 对字符串进行base64编码
//        $str = 'abcd十大爱上大哥阿萨大asda';
//        echo base64_decode( base64_encode( $str ));exit;

        # 先获取之前是否存在浏览记录，如果存在需要和本次的浏览记录合并
        $cookie_history = $this -> _getHistoryByCookie( 1 );
//        var_dump($cookie_history);exit;

        # 存入cookie中的数据 包含 商品id  + 当前的时间
        $this_history =[
            [
                'goods_id' => $goods_id,
                'ctime' => time()
            ]
        ];

        # 合并浏览记录
        $all_history = array_merge( $this_history , $cookie_history );

        # 把本次的浏览记录存入cookie
        cookie(  'history' , base64_encode( json_encode( $all_history ))  );

    }

    /**
     * 登陆状态下，浏览记录存入db
     */
    private function _dbHistory( $goods_id ){

        # 直接写入浏览记录
        $insert = [
            'goods_id' => $goods_id ,
            'user_id' => $this -> getUid(),
            'ctime' => time()
        ];

        $history_model = model('History');

        return $history_model -> save( $insert );
    }



    /**
     * 点击品牌的时候，价格缺件要跟着改变
     */
    public function showMoneySelect(){

        if( !request() -> isAjax() && request() -> isPost() ){
            return '非法请求';
        }

        # 根据品牌id和分类id查询最高价格
        $category_id = request() -> param('cid');
        $brand_id = request() -> param('brand_id');

        $where = [
            'gs.status' => 4
        ];
        if( !empty( $category_id )){
            $where['cate_id'] = $category_id;
        }
        if( !empty( $brand_id ) ){
            $where['brand_id'] = $brand_id;
        }

        $goods_model  = model('goods');

//        $max = $goods_model -> where( $where) -> max('goods_selfprice');
        $max_price = $goods_model
            -> where( $where )
            -> table('shop_goods g')
            -> join('shop_goods_sku gs','g.goods_id=gs.goods_id','left')
            -> max('gs.sku_price');

        $money_arr = $this -> _showMoneySelect( $max_price );


        $this->view->engine->layout(false);

        $this -> assign( 'money' , $money_arr );

        return $this -> fetch('money');
    }

    /**
     * 切换货品
     */
    public function checkSku(){
        $this -> checkRequest();

        $goods_id = $this -> request -> param('goods_id' , 0 , 'intval');

        $value_id = $this -> request -> param('value_id' , '');

        if( $value_id == '' || $goods_id == 0 ){
            $this -> fail('没有你要找的货品');
        }else{
            # 查询sku表
            $goods_sku_model = model('GoodsSku');
            $where = [
                'goods_id' => $goods_id,
                'sku_value_id' => rtrim($value_id, ',')
            ];
//            var_dump($where);exit;

            if( $obj =$goods_sku_model -> where( $where ) -> find()){

                $goods_info = $obj -> toArray();

                $this -> success( ['sku_id' => $goods_info['sku_id']]);

            }else{
                $this ->fail('没有找到你要购买的货品');
            }
        }
    }


    public  function test(){

        $this->view->engine->layout(false);

        return $this -> fetch();

    }


    public  function testDo(){
        $post = request() -> param();

        foreach( $post['class'] as $k => $v ){
            //插入 班级表
            $id = 1;
            if( $student = $post['student'][$k] ){
                $insert = [];
                foreach( $student as $kk => $vv ){
                    $insert[$kk]['student'] =$vv;
                }

                # 写入学生表
            }
        }

        exit;

    }

}

