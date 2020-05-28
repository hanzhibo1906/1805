<?php
namespace  app\admin\controller;
use think\Controller;
class Classes extends Common{
        public function index(){
            if(check()){
               $data=input('post.');
                $info=collection($data)->toArray();
                foreach($info as $k=>$v){
                    $info['class_name'][0]=$info['student_name'];
                    foreach($v as $kk=>$vv){

                    }

                }
                print_r($info);
            }else{
                return view();
            }
        }

    public function classList(){
        $where=[
            'pid'=>0
        ];
        $classInfo=model('Classes')->where($where)->select();
        $this->assign('data',$classInfo);
        return view();
    }

    public function students(){
        $id=input('post.id');
        if($id===""){
            fail('');
        }
        $where=[
            'pid'=>$id
        ];
        $data=model('Classes')->where($where)->select();
        return json(['info'=>$data,'code'=>1]);
    }
}