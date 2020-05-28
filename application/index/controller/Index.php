<?php
namespace app\index\controller;
use think\Controller;
class Index extends Controller
{
    public function index()
    {
        $this->view->engine->layout(false);//关闭布局
        if(check()){

        }else{
            $where=[
                'cate_show'=>1
            ];
            $navWhere=[
                'cate_navshow'=>1
            ];
            $res=model('Category')->where($where)->select();
            $navRes=model('Category')->where($navWhere)->select();
            $newNavRes=collection($navRes)->toArray();
            $newRes=collection($res)->toArray();
            $data=getIndexCateInfo($newRes);

            //处理楼层 1层
            $cate_id=5;
            $floorInfo=$this->getfloorInfo($cate_id,$newRes);
            $this->assign('floorInfo',$floorInfo);
            $this->assign('data',$data);
            $this->assign('nav',$newNavRes);

//            $this->view->engine->layout(false);//关闭布局
            return view();
        }
    }

    public function quit(){
        session('sessionInfo',null);
        return view('Login/login');
    }
    public function getfloorInfo($cate_id,$cateInfo){
        $floorInfo=[];
        //分类id为1的分类信息
        foreach($cateInfo as $k=>$v){
            //print_r($v);
            if($v['cate_id']==$cate_id){
                $floorInfo=$v;
            }

        }
        //分类id为1的子类  二级分类
        foreach($cateInfo as $k=>$v){
            if($v['pid']==$cate_id){
                //因为把$v赋给一位数组的话，后边的数据会把前边的数据覆盖，所以要把它赋给2维数组，才不会被覆盖
                $floorInfo['cateList'][]=$v;
            }
        }
        //当前分类下的每一级的所有分类id
        $idInfo=getAllCateId($cate_id,$cateInfo);
        //print_r($data);
        $where=[
            'goods_up'=>1,
            'cate_id'=>['in',$idInfo]
        ];
        //根据分类id查询商品信息
        $floorInfo['goodsList']=collection(model('goods')->field('goods_id,goods_name,goods_selfprice,goods_goods_img')->where($where)->select())->toArray();
        return $floorInfo;
    }

}
