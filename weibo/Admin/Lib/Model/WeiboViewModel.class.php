<?php
/**
 * 读取微博试图模型
 */
Class WeiboViewModel extends ViewModel {

	//定义视图表关联关系
	public $viewFields = array(
		'weibo' => array(
			'id','content','isturn','time','turn','keep','comment','uid',
			'_type' => 'LEFT'
			),
		'userinfo' => array(
			'username',
			'_on' => 'weibo.uid = userinfo.uid',
			'_type' => 'LEFT'
			),
		'picture' => array(
			'max' => 'pic',
			'_on' => 'weibo.id = picture.wid'
			)
		);
}
