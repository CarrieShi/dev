<?php

/**
 * 格式化打印数组
 */
function p($arr){
	echo '<pre>';
	print_r($arr);
	echo '</pre>';
}

/**
 * 异位或加密字符串
 * @param [String] $value [需要加密的字符串]
 * @param [integer] $type [加密解密（0:加密，1:解密）]
 * @return [String]       [加密或解密后的字符串]
 */
function encryption($value, $type=0) {
	$key = md5(C('ENCRYPTION_KEY'));

	if(!$type) {
		return str_replace('=', '', base64_encode($value ^ $key));
	}

	$value = base64_decode($value);
	return $value ^ $key;
}

/**
 * 格式化时间
 * @param [string] $time [格式化的时间戳]
 * @return
 */
function time_format ($time) {
	$now = time();
	$today = strtotime(date('Y-m-d'));
	$diff = $now - $time;
	$str = '';
	switch ($time) {
		case $diff < 60:
			$str = $diff . '秒前';	
			break;
		case $diff < 3600:
			$str = floor($diff / 60) . '分钟前';	
			break;
		case $diff < 28800:
			$str = floor($diff / 3600) . '小时前';	
			break;
		case $time > $today:
			$str = '今天' . date('H:i', $time);	
			break;
		default:
			$str = date('Y-m-d H:i:s', $time);
			break;
	}
	return $str;
}

/**
 * 替换微博内容的URL地址、@用户与表情
 * @param [String] $content [需要处理的微博字符串]
 * @return
 */
function replace_weibo ($content) {
	if(empty($content)) return;

	//$content = '中奖流水http://m_new14.yyport.com/admin/games/win_summary?uin=200031&level=1 百度www.baidu.com 我的项目 http://dev.md.com:8081/test/weibo/index.php';
	//$content = '我的项目 http://dev.md.com:8081/test/weibo/index.php @user01 [亲亲] hello [呵呵]';

	//TODO:其他的英文也会被改成链接形式 
	//给URL地址加上 <a> 链接 ()括号表示原子组 ?:表示不放到内存中 ?表示0个或1个
	$preg = '/(?:http:\/\/)?([\w.]+[\w\/]*\.[\w.]+[\w\/]*\??[\S=\&\+\%]*)/is';
	$content = preg_replace($preg, '<a href="http://\\1" target="_blank">\\1</a>', $content);
	
	//给用户加上 <a> 链接 
	//todo：如果以@user结尾，不加空格，会@不到
	$preg = '/@(\S+)\s/is';//\S 表示除空格以外的字符 \s 表示空格
	$content = preg_replace($preg, '<a href="' . __APP__ . '/User/\\1">\\1</a>', $content);

	//提取微博内容中所有表情文件
	//原模式'/\[(\S]+)\]/is'，在多个表情时会有些问题...
	//+ 贪婪性 引擎会匹配到最后，回溯，返回第一个和最后一个匹配到的之间的内容
	//? 懒惰性 比配最少的内容，如果后面还有符合模式的内容，也不会被返回
	$preg = '/\[(\S[^\]]+)\]/is';
	preg_match_all($preg, $content, $arr);
	
	//载入表情包数组文件
	$phiz = include './Public/data/phiz.php';

	if (!empty($arr[1])) {
		foreach ($arr[1] as $k => $v) {
			$name = array_search($v, $phiz);
			if($name) {
				$content = str_replace($arr[0][$k], '<img src="' . __ROOT__  .'/Public/Images/phiz/'. $name . '.gif" title="' . $v . '"/>', $content);
			}
		}
	}

	return $content;
}