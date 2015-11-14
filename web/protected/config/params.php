<?php
return array(
    //网站所用图片路径
    'image_url' => '/images',
    //网站所用js文件路径
    'js_url' => '/js',
    //网站所用css文件路径
    'css_url' => '/css',
    //上传图片路径
    'upload_img_url' => '/upload/images/',
    //上传文件路径
    'upload_file_url' => '/upload/files/',
    'gov_user_type' => array(
        '1' => '上传问题人员',
        '2' => '处理问题单位',
        '3' => '管理员', 
        '4' => '超级管理员',
    ),
    'gov_type_role' => array(
        '1' => 'finder',
        '2' => 'unit',
        '3' => 'admin', //
        '4' => 'superAdmin',
    ),
    //the type of user operation
    'operation_type' => array(
        '1' => '新建管理员',
        '2' => '删除管理员',
        '3' => '新建用户',
        '4' => '删除用户',
        '5' => '修改密码',
        '6' => '上传问题图片',
        '7' => '上传处理图片',
        '8' => '标记图片状态',
        '9' => '用户登录',
        '10' => '用户登出',
    ),
    // all kinds of page size in the site
    'page_size' => array(
        'common' => 1, 
    ), 
);