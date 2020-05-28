<?php
return [
    // 默认控制器名
    'default_controller'     => 'Index',
    // 默认操作名
    'default_action'         => 'index',
    'template'  =>  [
        'layout_on'     =>  true,
        'layout_name'   =>  'layout',
    ],
    /*//输出替换
    'view_replace_str'  =>  [
        '__PUBLIC__'=>'/uploads',

    ]*/

    session([
        'prefix'     => 'module',
        'type'       => '',
        'auto_start' => true
    ]),
    //输出替换
    'view_replace_str'  =>  [
        '__PUBLIC__'=>'http://39.107.239.7/',
        '__NORMAL__'=>'/public',
    ],

    'IMG_PATH' => 'http://39.107.239.7/',


    'ali_pay_config' => array (
        //应用ID,您的APPID。
        'app_id' => "2016072800107762",

        //商户私钥
        'merchant_private_key' => "MIIEpAIBAAKCAQEA9Rtu/eZgz+KzNhBHLkswkg+uAQHTlMrnYivSdfawRR5u89QlmP59Y/hBEOJLgfx7xEwmf7hft8LfPXhVtPCgzfgICrjGnEA3F2jp70QjrbZFM0xliykClwCvisu8QpAMTNngunRZvIsN1IhdWGjUZcM1FvJU/yz42fQpo0agoTe4rL5W6WSI3QkTqqND7Oy6sP4GsRH9lfRU3vtHXnRMZY7o08Mxnl2ErN/rFVh85EL2oG6sZpnS73hkgZjwGw1hJUv4jSDn/MD4hkTsAXoZ3oEe/IkKq7d8j0vDpaMCre7165eLaz6bk0lMdgegjOXViBECI5KtgbYZPSItfeEUYQIDAQABAoIBAQCI5urGm+/FDW1lrA/l9o9JdcKNw3RnLjGw7qMdykzIPmhgfkUFwFdPCDFnec4M63ZBvPrf6Z5k6E6Caf0NFD3s7TWor24XhfJ/e9T2FEwNUbozHd+1q5FLwFFMJ+GeGBEWt7dCzYv1uIFgDU59/AduU2sKxw3cfUT9j9fV37QGdx4q25IBrTiskl6377dYaZKw60yf+XNM8J97orAxR2c7Gz2p6CUwn6dHUBrSx0j/T1d80IQ6AHKD7BR69+srPwOw2YrnxiAXaifgBZZbUUM+zjgYk/f7ceEVpn43AbnUZvF4qVogV73n8tggAJkK7Bf6333JsrVOAaJzG9bJsy9FAoGBAP8WvdjFf891W7G3Q0xN5GW1uUiquVFCkID2t9BrlaEv6exyB1nuLkqRxoxUQh2aKXQOnsTFkwDxoARu3UXThxV/jtwYAm85tZAT9v7hZsbuGIAUMcbSC5zhmKOb50XEgcsmmmZSxDPt6gtDxQyAXk+wxXZsaWbCgVo4hVruEfaDAoGBAPX7kIT8NdVU8PBGXyPazqe54rdp+Cq0OT2rXLvQkbGiEdQcmLhYOGc6iiBto6Tkc9yMQlq9Xe2/G8/Co8nBn80wwWZiaTaK+3e0i1rwbIQjcc+cvy+Lhq2dYTB2YtKcrGkwoNaPUitHkaZfn1OtKC/gSuQHca5NhpwdZx6ZY/RLAoGBAOrPXUx4aLFoX1KU1r8biF3TzM3GUbYqZug/s26ISgFJILSFNp0isbv0umacfQvQx83MU3vgPvSEdv79qnPHRjaU58VT2JHX9ni82AVBKSF/NFBZxoQ0/3mW9LHOJjLDs5J0Y3ZDQ9Zlb6aU6IMcezQyDWJ4YwGzk+yf0KmmMiTPAoGAL3nbXd9z4HXhfu6GJFxx8GtvN6lLTaq1NZLidhl+VUOLn13ZmBLm5/2jdTjUEdpKBnJHyE2uWFm/W20cwa8OUcMsQL+TwQQ4HtWI5AdtWl+g8so1GE8csluiy4C9+BpnKHJLSL4mxNWuZeck0Dimff8TVUmehl7OMUl3gq9mXFsCgYBkdLeJYnD7LsStLs65zoNjlD6g5ngdW64enbGEv8vdyllMjWtBt+5nX9GZGIofC0moa36WzuUyo3/CBtXLzCjmaAkMtqwlxW8iPUMFeevGhFDUb3OOdh1j6B67qjPjNmxDGIlOstjBSCHI9a4/maK/8e3ce+y8CxTrkVI3rppokQ==",

        //异步通知地址
        'notify_url' => "http://39.107.239.7/Order/notify",

        //同步跳转
        'return_url' => "http://39.107.239.7/Order/paySuccess",

        //编码格式
        'charset' => "UTF-8",

        //签名方式
        'sign_type'=>"RSA2",

        //支付宝网关
        'gatewayUrl' => "https://openapi.alipaydev.com/gateway.do",

        //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
        'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA1gqpl1Nr/9elHouS7FqT1Woh4b3ly7/Qupt9LxCQFEAmunggg52uWU4G+lN5wh378GktBADDSu4V727SytvQgQw3jssxCFGlp3he7DuYwTLz6BDmJPEWUj3u2u0YaXgTBRhMLtpHM8j2adENY7vsFggTLAenchLjZZwlQ7usV5g8wHPboYKM4jeDFkTiQ+cLQXbGpYttEid4JN68e6tJYo0dlJO6ViEj6o+UieLbuhaaL5LlcC6aYBkGVQS/nPnzQLqpsUt9NYu6WzLdNoNUor9jyRq7TSBdp+HWXx5vtcaaZPBvvImkFNgHaxWdJj4b7/2zG7wokzEkRnVW+zNwiwIDAQAB",
    )


];
