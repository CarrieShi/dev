<?php
/**
 * 私信视图模型
 */
class LetterViewModel extends ViewModel {

	protected $viewFields = array(
		'letter' => array(
			'id', 'content', 'time',
			'_type' => 'LEFT'
			),
		'userinfo' => array(
			'username', 'face50' => 'face', 'uid',
			'_on' => 'letter.from = userinfo.uid'
			)
		);
}