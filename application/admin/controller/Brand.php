<?php
namespace  app\admin\controller;
use think\Controller;
class Brand extends Common{
    public function brandAdd(){
        if(check()){
            # 检查名字是否重复
            $name=input('post.brand_name');
            if(empty($name)){
                exit('非法操作此页面');
            }
            $brand_id=input('post.brand_id');
            if(empty($brand_id)){
                $where=[
                    'brand_name'=>$name
                ];
            }else{
                $where=[
                    'brand_id'=>['NEQ',$brand_id],
                    'brand_name'=>$name
                ];
            }
            $data=model('Brand')->where($where)->find();
            if(!empty($data)){
                fail('品牌名称已存在');
            }


            $data=input('post.');
            if(empty($data)){
                exit('非法操作此页面');
            }
            $validate=validate('Brand');
            if(!$validate->check($data)){
                $font=$validate->getError();
                fail($font);
            }
            $info=model('Brand')->allowField(true)->save($data);
            if($info){
                win('添加成功');
            }else{
                fail('添加失败');
            }
        }else{
            return view();
        }
    }
//
//    public function checkName(){
//
//    }
    public function brandList(){
        if( request() -> isAjax() ){

            $page=input('get.page');
            if(empty($page)){
                exit('非法操作此页面');
            }
            $limit=input('get.limit');
            if(empty($limit)){
                exit('非法操作此页面');
            }
            $brand_info=model('Brand')->order('brand_sort','asc')->page($page,$limit)->select();
            $count=model('Brand')->count();
            $info=['code'=>0,'msg'=>'','count'=>$count,'data'=>$brand_info];
            echo json_encode($info);
            exit;

        }else{
            return view();
        }
    }

    public function brandUpload(){
        // 获取表单上传文件 例如上传了001.jpg
          $file = request()->file('file');
            if(empty($file)){
                exit('非法操作此页面');
            }
        //动到框架应用根目录/public/uploads/ 目录下
         $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
        if($info){
            // 成功上传后 获取上传信息
               echo json_encode(['font'=>'上传成功','code'=>1,'src'=>$info->getSaveName()]);

        }else{
            // 上传失败获取错误信息
              fail($file->getError());
        }

    }

    public function brandDel(){
        $brand_id=input('post.brand_id');
        if(empty($brand_id)){
            exit('非法操作此页面');
        }
        $where=[
            'brand_id'=>$brand_id
        ];
        $goodsInfo=model('Goods')->where($where)->find();
        if($goodsInfo){
            fail('品牌下有商品');
        }
        //删除
        $res=model('Brand')->where($where)->delete();
        if($res){
            win('删除成功');
        }else{
            fail('删除失败');
        }
    }

    public function brandUpdate(){
        $data=input('post.');
        if(empty($data)){
            exit('非法操作此页面');
        }
        $where=[
            'brand_id'=>$data['brand_id']
        ];
        $field=[
            $data['field']=>$data['value']
        ];
        $res=model('Brand')->where($where)->update($field);
        if($res){
            win('Ok');
        }else{
            fail('No');
        }
    }

    public function brandUpdateInfo(){
        $brand_id=input('get.brand_id');
        if(empty($brand_id)){
            exit('非法操作此页面');
        }
        $where=[
            'brand_id'=>$brand_id
        ];
        $data=model('Brand')->where($where)->find();
        $this->assign('data',$data);
        return view();
    }

    public function brandUp(){
        if(check()){
            $data=input('post.');
            if(empty($data)){
                exit('非法操作此页面');
            }
            $validate=validate('Brand');
            if(!$validate->check($data)){
                $font=$validate->getError();
                fail($font);
            }
            $where=[
                'brand_id'=>$data['brand_id']
            ];
            $arr=[
                'brand_name'=>$data['brand_name'],
                'brand_url'=>$data['brand_url'],
                'brand_logo'=>$data['brand_logo'],
                'brand_describe'=>$data['brand_describe'],
                'brand_show'=>$data['brand_show']
            ];
            $info=model('Brand')->allowField(true)->save($arr,$where);
            if($info){
                win('修改成功');
            }else{
                fail('修改失败');
            }
        }
    }
}