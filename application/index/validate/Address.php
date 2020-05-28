<?php
namespace app\index\validate;
use think\Validate;
class Address extends Validate{
        protected $rule=[
            'province'=>'require',
            'city'=>'require',
            'district'=>'require',
            'address_man'=>'require',
            'adress_tel'=>'require',
            'address_detail'=>'require',
        ];

    protected $message=[
        'province.require'=>'省,市,区必填',
        'city.require'=>'省,市,区必填',
        'district.require'=>'省,市,区必填',
        'address_man.require'=>'收件人必填',
        'adress_tel.require'=>'收件人手机号必填',
        'address_detail.require'=>'收件人详细地址必填'
    ];

}