<?php
namespace app\index\controller;
use think\Db;
class Adress extends Common
{
    public function address(){
        if(check()){
            $data=input('post.');
            $vali= validate('Address');
            $res=$vali ->check($data);
            if(!$res){
                fail($vali->getError());
            }
            //判断是否设置默认收货地址
            if(!empty($data['adress_default'])){
                $where=[
                    'user_id'=>session('sessionInfo.user_id')
                ];
                $arr=[
                    'adress_default'=>0
                ];
                model('Address')->where($where)->update($arr);
            }
            //添加
            $result=model('Address')->save($data);
            if($result){
                win('添加成功');
            }else{
                fail('添加失败');
            }
        }else{
            //查询所有省份
            $where=[
                'PARENT_ID'=>1
            ];

            $data=model('Region')->where($where)->select();

            //查询当前用户所有的收货地址
            $address_where=[
                'user_id'=>session('sessionInfo.user_id')
            ];
            $addressInfo=model('Address')->getAddressInfo($address_where);
            $this->assign('data',$data);
            $this->assign('addressInfo',$addressInfo);
            return view();
        }
    }

    public function city(){
        $id=input('post.id');
        if($id===""){
            fail('');
        }
        $where=[
            'PARENT_ID'=>$id
        ];
        $data=model('Region')->where($where)->select();
        return json(['info'=>$data,'code'=>1]);
    }

    public function setDefault(){
        $address_id=input('post.address_id');
        //开启事务
        Db::startTrans();
        $Address=model('Address');
        //把此用户的所有的default改为0
        $where=[
            'user_id'=>session('sessionInfo.user_id')
        ];
        $res1=$Address->where($where)->update(['adress_default'=>0]);

        $update_where=[
            'address_id'=>$address_id,
            'user_id'=>session('sessionInfo.user_id')
        ];
        $res2=$Address->where($update_where)->update(['adress_default'=>1]);
        if($res1&&$res2){
            Db::commit();
            win('设置成功');
        }else{
            Db::rollback();
            fail('设置失败');
        }
    }

    public function addressUpdateInfo(){
        if(check()){

        }else{
            //查询所有省份
            $where=[
                'PARENT_ID'=>1
            ];
            $address=model('Address');
            $address_id=input('get.address_id');
            $address_where=[
                'address_id'=>$address_id
            ];
            $addressInfo=$address->where($address_where)->find();
            //查询当前省份下的所有城市
            $province=model('Region')->where(['PARENT_ID'=>$addressInfo['province']])->select();
            //查询当前城市下的县区
            $city=model('Region')->where(['PARENT_ID'=>$addressInfo['city']])->select();

            $data=model('Region')->where($where)->select();
            $this->assign('province',$province);
            $this->assign('city',$city);
            $this->assign('data',$data);
            $this->assign('addressInfo',$addressInfo);
            return view();
        }
    }

    public function addressUpdate(){
        $data=input('post.');
        //判断是否设置默认收货地址
        if(!empty($data['adress_default'])){
            $where=[
                'user_id'=>session('sessionInfo.user_id')
            ];
            $arr=[
                'adress_default'=>0
            ];
            model('Address')->where($where)->update($arr);
        }

        //执行修改
        $update_where=[
            'address_id'=>$data['address_id']
        ];

        $arr=[
            'province'=>$data['province'],
            'city'=>$data['city'],
            'district'=>$data['district'],
            'address_man'=>$data['address_man'],
            'adress_tel'=>$data['adress_tel'],
            'address_detail'=>$data['address_detail'],
            'adress_default'=>$data['adress_default']
        ];
        $res=model('Address')->where($update_where)->update($arr);
        if($res){
            win('修改成功');
        }else{
            fail('修改失败');
        }
    }

    public function addressDel(){
            $address_id=input('post.address_id');
            $where=[
                'address_id'=>$address_id
            ];

                 $res=model('Address')->where($where)->delete();
               if($res){
                   win('删除成功');
               }else{
                   fail('删除失败');
               }
    }
}