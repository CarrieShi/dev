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

	// 去掉url后的.html
	'URL_HTML_SUFFIX'		=> '',

	// 自定义模板替换
	'TMPL_PARSE_STRING' => array(
		'__PUBLIC__' => __ROOT__ . '/Admin/Tpl/Public',
		'__APP__' => __ROOT__ . '/Admin/index'
		)
);
?>