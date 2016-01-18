<?php
/**
 * 评论视图模型
 */
Class CommentViewModel extends ViewModel {

	protected $viewFields = array(
		'comment' => array(
			'id', 'content', 'time', 'uid',
			'_type' => 'LEFT'
			),
		'userinfo' => array(
			'username',
			'_on' => 'comment.uid = userinfo.uid'
			)
		);
}