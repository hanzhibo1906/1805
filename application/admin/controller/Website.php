<?php
namespace app\admin\controller;
use think\Controller;
class Website extends Common{
   public function webAdd(){
       if(check()){
           $data=input('post.');
           foreach($data as $k=>$v){
               $list[]=['name'=>$k,'value'=>$v];
           }
         /*  $list=[
               ['name'=>'WEB_NAME','value'=>$data['web_name']],
               ['name'=>'WEB_URL','value'=>$data['web_url']],
               ['name'=>'WEB_COPYRIGHT','value'=>$data['web_copyright']],
               ['name'=>'WEB_RECORD','value'=>$data['web_beian']]
           ];*/
           $webInfo=model('Website')->select();
           if(!empty($webInfo)){
                model('Website')->query('truncate table shop_website');
                $res=model('Website')->saveAll($list);
               if($res){
                    win('保存成功');
               }else{
                   fail('保存失败');
               }
           }
       }else{
           $info=[];
           $webInfo=model('Website')->select();
           foreach ($webInfo as $k=>$v) {
               $info[$v['name']]=$v['value'];
           }
           $this->assign('info',$info);
           return view();
       }
   }
}