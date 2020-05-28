<?php
namespace  app\admin\controller;
use think\Controller;
class Category extends Common{
    public function categoryAdd(){
        if(check()){
            $data=input('post.');
            $validate = validate('Category');
            if(!$validate->check($data)){
                $font=$validate->getError();
                win($font);
            }
            $res=model('Category')->save($data);
            if($res){
                win('添加成功');
            }else{
                fail('添加失败');
            }
        }else {
            $where=[
                'cate_show'=>1
            ];
            $cateInfo=model('Category')->where($where)->select();
            $data=getInfo($cateInfo);
            $this->assign('data',$data);
            return view();
        }
    }

    public function checkName(){
        $cate_name=input('post.cate_name');
        $cate_id=input('post.cate_id');
        if(empty($cate_id)){
            $where=[
                'cate_name'=>$cate_name
            ];
        }else{
            $where=[
                'cate_id'=>['NEQ',$cate_id],
                'cate_name'=>$cate_name
            ];
        }
       $res=model('Category')->where($where)->find();
        if(!empty($res)){
            fail('用户名已存在');
        }
    }

    public function categoryList(){
        //查询所有分类信息
        $where=[
            'cate_show'=>1
        ];
        $cateInfo=model('Category')->where($where)->select();
        $data=getInfo($cateInfo);
        $this->assign('data',$data);
        /*foreach($data as $k=>$v){
            echo $v['cate_name'];
            echo '<br>';
        }*/
        return view();
    }

    //删除
    public function cateDel(){
        $cate_id=input('post.cate_id');
        //验证此分类下是否有子类
        $cate_where=[
            'pid'=>$cate_id
        ];
        $cateInfo=model('Category')->where($cate_where)->find();
        if(!empty($cateInfo)){
            fail('此分类下有子类或商品');
        }

        //验证此分类下是否有商品
        $where=[
            'cate_id'=>$cate_id
        ];
        $goodsInfo=model('Goods')->where($where)->find();
        if(!empty($goodsInfo)){
            fail('此分类下有子类或商品');
        }

        $res=model('Category')->where($where)->delete();
        if($res){
            win('删除成功');
        }else{
            fail('删除失败');
        }
    }
    //既点及改
    public function cateChange(){
        $data=input('post.');
        $where=[
            'cate_id'=>$data['cate_id']
        ];
        $arr=[
            $data['column']=>$data['val']
        ];
        $res=model('Category')->save($arr,$where);
        if($res){
            win('修改成功');
        }else{
            fail('修改失败');
        }
    }

    //修改
    public function cateUpdate(){
        $cate_id=input('get.cate_id');
        $where=[
            'cate_show'=>1
        ];
        $cateInfo=model('Category')->where($where)->select();
        $data=getInfo($cateInfo);
        $this->assign('sel',$data);
        $where=[
            'cate_id'=>$cate_id
        ];
        $info=model('Category')->where($where)->find();
        $this->assign('data',$info);
        return view();
    }

    //修改执行
    public function cateUpdateDo(){
        if(check()){
            $data = input('post.');
            $validate = validate('Category');
            if (!$validate->check($data)) {
                $font = $validate->getError();
                fail($font);
            }
            $where=[
                'cate_id'=>$data['cate_id']
            ];
            $arr=[
                'cate_name'=>$data['cate_name'],
                'cate_show'=>$data['cate_show'],
                'cate_navshow'=>$data['cate_navshow'],
                'pid'=>$data['pid'],
            ];
            $res=model('Category')->where($where)->update($arr);
            if($res){
                win('修改成功');
            }else{
                fail('修改失败');
            }
        }
    }

}