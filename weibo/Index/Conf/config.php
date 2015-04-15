<?php
return array(
	//数据库配置
	'DB_TYPE'               => 'mysql',     // 数据库类型
    'DB_HOST'               => 'localhost', // 服务器地址
    'DB_NAME'               => 'weibo',          // 数据库名
    'DB_USER'               => 'root',      // 用户名
    'DB_PWD'                => '123456',          // 密码
    'DB_PREFIX'             => 'hd_',    // 数据库表前缀
    'DB_CHARSET'            => 'utf8',      // 数据库编码默认采用utf8

    'DEFAULT_THEME'			=> 'default',//默认模板主题

    'URL_HTML_SUFFIX'		=> '',
    'URL_MODEL'             => 1,
    //'TOKEN_ON'              => false;//当前版本不会自动生成表单令牌，故不需要设置
    //'SHOW_PAGE_TRACE' => true,

    //用于异位或加码的KEY
    'ENCRYPTION_KEY'        => 'weibothinkphp',
    //自动登录保存时间
    'AUTO_LOGIN_TIME'       => time() + 3600 * 24 * 7,

    //图片上传
    'UPLOAD_MAX_SIZE' => 2000000,   //最大上传大小
    'UPLOAD_PATH' => './Uploads/',  //文件上传保存路径
    'UPLOAD_EXTS' => array('jpg', 'jpeg', 'gif', 'png'),    //允许上传文件的后缀

    //URL路由配置
    'URL_ROUTER_ON' => true,        //开启URL路由功能
    'URL_ROUTE_RULES' => array(    //定义路由规则
        ':id\d' => 'User/index',
        'follow/:uid\d' => array('User/followList', 'type=1'),
        'fans/:uid\d' => array('User/followList', 'type=0')
        ),

    //自定义标签
    'TAGLIB_LOAD' => true, //加载自定义标签库
    'APP_AUTOLOAD_PATH' =>'@.Taglib', //自动加载 @.会映射到weibo/Index/Lib
    'TAGLIB_BUILD_IN' => 'Cx,Hdtags', //加入系统标签库 

    //缓存设置
    'DATA_CACHE_SUBDIR' => true, //开启以哈希等形式生成缓存目录
    'DATA_PATH_LEVEL'  => 2, //目录层次
);
?>