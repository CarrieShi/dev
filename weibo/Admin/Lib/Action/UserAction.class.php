<?php
/**
 * 用户管理控制器
 */
Class UserAction extends CommonAction {

	/**
	 * 微博用户列表
	 */
	public function index () {

		$users = D('UserView')->select();
		print_r($users);
		$this->display();
	}
}