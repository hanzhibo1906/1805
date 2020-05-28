<?php
namespace  app\admin\controller;
use think\Controller;

class Goods extends Common
{
    public function goodsAdd()
    {
        if (check()) {
            $data=input('post.');

            $goods_model = model('Goods');

            $goods_model -> startTrans();

            try{

                $now = time();

                # 验证参数是否正确
                $validate=validate('Goods');

                if(!$validate->check($data)){
                    $error = $validate -> getError();
                    throw new \Exception( $error );
                }

                $data['goods_content'] = str_replace( config('IMG_PATH') , '%__IMG__%' , $data['goods_content'] );

                $goods_model ->allowField(true)->save($data);

                $goods_id = $goods_model -> getLastInsID();
//                var_dump($goods_id);exit;
                if( $goods_id < 1 ){
                    throw new \Exception( '商品数据写入失败' );
                }
                ###################### 写入商品的基本属性 ###############
                $goods_basic_model = model('GoodsBasicAttr');

                $basic = $data['basic'];
                if( !empty( $basic ) ){
                    $basic_insert = [];
                    $i = 0;
                    foreach( $basic as $key => $value ){
                        $basic_insert[$i]['goods_id'] = $goods_id;
                        $basic_insert[$i]['basic_attr_id'] = $key;
                        $basic_insert[$i]['basic_value'] = $value;
                        $basic_insert[$i]['status'] = 1;
                        $basic_insert[$i]['ctime'] = $now;
                        $i ++;
                    }
                    $number = $goods_basic_model -> insertAll(  $basic_insert );

                    if( $number < 1  ){
                        throw new \Exception( '商品基本属性写入失败' );
                    }
                }
                ################## 销售属性 ######################
                $sku = $data['sku'];

                if( !empty( $sku )){

                    $sku_model = model('GoodsSku');
                    $goods_sale_attr_model = model('GoodsSaleAttr');

                    foreach( $sku['sku'] as $k => $v  ){

                        $attr = explode(  ',' , trim( $v , ',') );

                        # 遍历写入商品的销售属性表
                        $goods_sale = [];
                        $sale_attr_id = '';
                        $value_id = '';
                        foreach( $attr as $kk => $vv ){
                            $attr_arr = explode( '|' , $vv );
                            $goods_sale['goods_id'] = $goods_id;
                            $goods_sale['sale_attr_id'] = array_shift($attr_arr);
//                            $value_id .= array_shift($attr_arr);
                            $goods_sale['sale_value_id'] = array_shift($attr_arr);
                            $value_id .= $goods_sale['sale_value_id'] . ',';
                            $goods_sale['status'] = 1;
                            $goods_sale['ctime'] = $now;
                            $goods_sale_attr_model -> insert( $goods_sale );
                            $id = $goods_sale_attr_model -> getLastInsID();
                            if( $id < 1 ){
                                throw new \Exception( '商品销售属性写入失败' );
                            }
                            $sale_attr_id .= $id.',';
                        }

                        # 插入货品表
                        $sku_insert = [];

                        # 货品的编号  商品的id  + 数量
                        $sku_insert['sku_no'] = $goods_id . $k;
                        $sku_insert['sku_name'] = $sku['sku_name'][$k];
                        $sku_insert['sku_price'] = $sku['goods_price'][$k];
                        $sku_insert['sku_stock'] = $sku['goods_stock'][$k];
                        $sku_insert['sku_img'] = '';
                        $sku_insert['sku_slider_img'] = '';
                        $sku_insert['sku_attr'] = rtrim($sale_attr_id,',');
                        $sku_insert['sku_value_id'] = rtrim($value_id,',');
                        $sku_insert['goods_id'] = $goods_id;
                        $sku_insert['status'] = 1;
                        $sku_insert['ctime'] = $now;

                        $sku_model -> insert( $sku_insert );

                        $id = $sku_model -> getLastInsID();

                        if( $id < 1 ){
                            throw new \Exception( '货品表写入失败' );
                        }
                    }
                }

                $goods_model -> commit();

                $this -> success();

            }catch ( \Exception $e) {

                $goods_model -> rollback();
                $this -> fail( $e -> getMessage() );

            }

        } else {
            $where = [
                'cate_show' => 1
            ];
            $cateInfo = model('Category')->where($where)->select();
            $brandInfo = model('Brand')->select();
            $this->assign('brand', $brandInfo);
            $data = getInfo($cateInfo);
            $this->assign('data', $data);
            return view();
        }

    }

    public function goodsList()
    {
        $where = [
            'cate_show' => 1
        ];
        $cateInfo = model('Category')->where($where)->select();
        $brandInfo = model('Brand')->select();
        $this->assign('brand', $brandInfo);
        $data = getInfo($cateInfo);
        $this->assign('data', $data);
        return view();
    }

    public function goodsDel(){
        $goods_id=input('post.goods_id');
        $res=model('Goods')->destroy($goods_id);
        if($res){
            win('删除成功');
        }else{
            fail('删除失败');
        }
    }

    public function goodsUpdateInfo(){
        $goods_id=input('get.goods_id');
        $goods_where=[
            'goods_id'=>$goods_id
        ];
        $cate_where = [
            'cate_show' => 1
        ];
        $cateInfo = model('Category')->where($cate_where)->select();
        $goodsInfo = model('Goods')->where($goods_where)->find();
        $brandInfo = model('Brand')->select();
        $cate = getInfo($cateInfo);
        $this->assign('brand', $brandInfo);
        $this->assign('cate', $cate);
        $this->assign('good', $goodsInfo);
        return view();
    }
    //修改
    public function goodsUpdateDo(){
        $data=input('post.');
        $where=[
            'goods_id'=>$data['goods_id']
        ];
        $arr=[
            'goods_name'=>$data['goods_name'],
            'goods_selfprice'=>$data['goods_selfprice'],
            'goods_marketprice'=>$data['goods_marketprice'],
            'goods_up'=>$data['goods_up'],
            'goods_new'=>$data['goods_new'],
            'goods_best'=>$data['goods_best'],
            'goods_hot'=>$data['goods_hot'],
            'goods_num'=>$data['goods_num'],
            'goods_score'=>$data['goods_score'],
            'goods_goods_img'=>$data['goods_goods_img'],
            'goods_big_imgs'=>$data['goods_big_imgs'],
            'goods_mid_imgs'=>$data['goods_mid_imgs'],
            'goods_small_imgs'=>$data['goods_small_imgs'],
            'brand_id'=>$data['brand_id'],
            'cate_id'=>$data['cate_id']
        ];
        $res=model('Goods')->allowField(true)->save($arr,$where);
        if($res){
            win('修改成功');
        }else{
            fail('修改失败');
        }
    }
    public function goodsUpdate(){
        $data=input('post.');
        $arr=[
            $data['field']=>$data['value']
        ];
        $where=[
            'goods_id'=>$data['goods_id']
        ];
        $res=model('Goods')->where($where)->update($arr);
        if($res){
            win('修改成功');
        }else{
            fail('修改失败');
        }
    }
    public function goodsInfo(){
        $page=input('get.page');
        $limit=input('get.limit');
        $cate_name=input('get.cate_name');
        $brand_name=input('get.brand_name');
        $goods_name=input('get.goods_name');

        $where=[
            'goods_up'=>1
        ];
        if(!empty($cate_name)){
            $where['cate_name']=$cate_name;
        }
        if(!empty($brand_name)){
            $where['brand_name']=$brand_name;
        }
        if(!empty($goods_name)){
            $where['goods_name']=['LIKE',"%$goods_name%"];
        }
        $goods_info=model('Goods')->goodsInfo($where,$page,$limit);
        $goods_count=model('Goods')->goodsCount($where);
        $info=['code'=>0,'msg'=>'','count'=>$goods_count,'data'=>$goods_info];
        echo json_encode($info);
    }
    //商品文件上传
    public function goodsUpLoad()
    {
//        $type = input('get.type');
        $type = request() -> param('type');
        //文件上传
        $fileInfo = $this->upload();
        //生成缩略图
        if ($type == 1) {
            //生成单张缩略图
            $tmp = [
                'path' => $fileInfo['path'],
                'filename' => $fileInfo['filename'],
                'dir' => 'goods_thumb',
                'width' => 210,
                'height' => 185
            ];
        } elseif( $type == 3  ){
            # 富文本编辑器的上传
            /**
             * {
            "code": 0 //0表示成功，其它失败
            ,"msg": "" //提示信息 //一般上传失败后返回
            ,"data": {
            "src": "图片路径"
            ,"title": "图片名称" //可选
            }
            }
             */
            $arr = [
                'code' => 0 ,
                'msg' => 'success',
                'data' => [
                    'src'=> config('IMG_PATH') .$fileInfo['path'],
                    'title' => $fileInfo['filename']
                ]
            ];
            echo json_encode( $arr );
            exit;
        }else {
            //生成多张缩略图
            $tmp = [
                [
                    'path' => $fileInfo['path'],
                    'filename' => $fileInfo['filename'],
                    'dir' => 'goods_big',
                    'width' => 320,
                    'height' => 320
                ],
                [
                    'path' => $fileInfo['path'],
                    'filename' => $fileInfo['filename'],
                    'dir' => 'goods_mid',
                    'width' => 210,
                    'height' => 210
                ],
                [
                    'path' => $fileInfo['path'],
                    'filename' => $fileInfo['filename'],
                    'dir' => 'goods_small',
                    'width' => 79,
                    'height' => 79
                ]
            ];
        }
        $this->thumb($tmp);
    }

    //文件上传
    function upLoad()
    {
        //文件上传
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads' . DS . 'goods');
        $path = './uploads/goods/' . $info->getSaveName();//图片路径
        $filename = $info->getFilename();
        return ['path' => $path, 'filename' => $filename];
    }

    //生成缩略图
    function thumb($tmp)
    {
        if (empty($tmp[0])) {
            //生成单张缩略图
            $image = \think\Image::open($tmp['path']);
            $dir = './uploads/goods/' . $tmp['dir'] . '/' . date('Ymd') . '/';//设置目录
            is_dir($dir) or mkdir($dir, 0777, true);//检测目是否存在
            $thumb_path = $dir . $tmp['filename'];//生成缩略图路径
            $res = $image->thumb($tmp['width'], $tmp['height'])->save($thumb_path);
            if ($res) {
                $info = [
                    'font' => '上传成功',
                    'code' => 1,
                    'src' => $thumb_path,
                ];
            }
        } else {
            //生成多张缩略图
            foreach ($tmp as $k => $v) {
                $image = \think\Image::open($v['path']);
                $dir = './uploads/goods/' . $v['dir'] . '/' . date('Ymd') . '/';//设置目录
                is_dir($dir) or mkdir($dir, 0777, true);//检测目是否存在
                $thumb_path = $dir . $v['filename'];//生成缩略图路径
                $res = $image->thumb($v['width'], $v['height'])->save($thumb_path);
                if ($res) {
                   $info['font']='操作成功';
                   $info['code']=1;
                    $info['src'][$v['dir']]=$thumb_path;
                }
            }
        }
        echo json_encode($info);
        exit;

    }
}