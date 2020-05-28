<?php
return [
    // 默认控制器名
    'default_controller'     => 'Login',
    // 默认操作名
    'default_action'         => 'login',
    'template'  =>  [
        'layout_on'     =>  true,
        'layout_name'   =>  'layout',
    ],
    //输出替换
    'view_replace_str'  =>  [
        '__PUBLIC__'=>'/uploads',

    ],
    'IMG_PATH' => 'http://static.shop.com/',

];