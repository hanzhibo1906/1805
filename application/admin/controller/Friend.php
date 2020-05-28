<?php
namespace  app\admin\controller;
use think\Controller;
class Friend extends Controller{
    public function friendAdd(){
        if($this->check()){
            $data=input('post.');
            if(empty($data)){
                exit('非法操作此页面');
            }
            $validate=validate('Friend');
            if(!$validate->check($data)){
               $font=$validate->getError();
               $this->fail($font);
            }
            $model=model('Friend');
            $res=$model->allowField(true)->save($data);
            if($res){
                echo json_encode(['font'=>'添加成功','code'=>1]);
            }else{
                echo json_encode(['font'=>'添加失败','code'=>2]);
            }
        }else{
            return view();
        }
    }

    public function checkName(){
        $nam=input('post.f_name');
        if(empty($nam)){
            exit('非法操作此页面');
        }
        $id=input('post.f_id');
        if(empty($id)){
            $where=[
                'name'=>$nam
            ];
        }else{
            $where=[
                'id'=>['NEQ',$id],
                "name"=>$nam
            ];
        }

        $data=model('Friend')->where($where)->find();


    }
    public function friendList(){
        return view();
    }

    public function friendInfo(){
        $page=input('get.page');
        if(empty($page)){
            exit('非法操作此页面');
        }
        $limit=input('get.limit');
        if(empty($limit)){
            exit('非法操作此页面');
        }
        $admin_info=model('Friend')->page($page,$limit)->select();
        $count=model('Friend')->count();
        $info=['code'=>0,'msg'=>'','count'=>$count,'data'=>$admin_info];
        echo json_encode($info);
    }

    public function friendUpdate(){
        $data=input('post.');
        if(empty($data)){
            exit('非法操作此页面');
        }
        //拼接条件
        $where=[
            'id'=>$data['f_id']
        ];
        //设置数据
        $arr=[
            $data['field']=>$data['value']
        ];
        //执行修改
        $res=model('Friend')->where($where)->update($arr);
        if($res){
            $this->win('修改成功');
        }else{
            $this->fail('修改失败');
        }
    }

    public function friendDel(){
        $id=input('post.f_id');
        if(empty($id)){
            exit('非法操作此页面');
        }
        $where=['id'=>$id];
        $res=model('Friend')->where($where)->delete();
        if($res){
            $this->win('删除成功');
        }else{
           $this->fail('删除失败');
        }
    }

    //修改展示页面
    public function friendUpdateInfo(){
        //接受id
        $id=input('get.id');
        if(empty($id)){
            exit('非法操作此页面');
        }
        $where=[
            'id'=>$id
        ];
        //查询修改的数据
        $data=model('Friend')->where($where)->find();
        $this->assign('data',$data);
        return $this->fetch('Friend/friendUpdateInfo');
    }
    public function friendUp(){
        if($this->check()){
            $data=input('post.');
            if(empty($data)){
                exit('非法操作此页面');
            }
            $validate=validate('Friend');
            if(!$validate->check($data)){
                $font=$validate->getError();
                $this->fail($font);
            }
            $model=model('Friend');
            $where=[
                'id'=>$data['id']
            ];
            $arr=[
                'name'=>$data['name'],
                'url'=>$data['url'],
                'logo'=>$data['logo']
            ];
            $res=$model->allowField(true)->save($arr,$where);
            if($res){
                $this->win('修改成功');
            }else{
                $this->fail('修改失败');
            }
        }else{
            return view();
        }
    }
    //文件上传
    public function friendLoad(){
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('file');
        if(empty($file)){
            exit('非法操作此页面');
        }
        // 移动到框架应用根目录/public/uploads/ 目录下
//        var_dump(DS);exit;
        $info=$file->move(ROOT_PATH . 'public/uploads/friend');

        $path = $info -> getPathname() ;

        $path = str_replace( ROOT_PATH .'public' , '' ,$path );
//        var_dump($path);exit;
        if($info){
            // 成功上传后 获取上传信息
            echo json_encode(['font'=>'上传成功','code'=>1,'src'=>$path]);
        }else{
            // 上传失败获取错误信息
            $this->fail($file->getError());
        }
    }
    //检验是否ajax和post上传
    public function check(){
        if(request()->isPost()&&request()->isAjax()){
            return true;
        }
    }

    //错误信息
    public function fail($font){
        echo json_encode(['font'=>$font,'code'=>2]);
        exit;
    }

    //正确
    public function win($font){
        echo json_encode(['font'=>$font,'code'=>1]);
        exit;
    }
}