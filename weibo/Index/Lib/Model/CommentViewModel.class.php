<?php
/**
 * 评论视图模型
 */
Class CommentViewModel extends ViewModel {
	protected $viewFields = array(
		'comment' => array(
			'id', 'content', 'time', 'wid',
			'_type' => 'LEFT'
			),
		'userinfo' => array(
			'username', 'face50' => 'face', 'uid',
			'_on' => 'comment.uid = userinfo.uid'
			)
		);
}