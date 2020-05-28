<?php
   namespace app\admin\model;
   use think\Model;
   class Admin extends Model{
       protected $table='shop_admin';
       //定义时间戳字段名;
       protected $createTime='admin_time';
       protected $updateTime=false;
        public $salt;


       //自动完成
       protected $insert=['salt'];
       //密码修改器
       public function setAdminPwdAttr($value){
           $salt=createSalt();
           $this->salt=$salt;
           $pwd=createPwd($value,$salt);
           return $pwd;
            //return md5($value);
       }

       //自动完成
       public function setSaltAttr(){
           return $this->salt;
       }
   }