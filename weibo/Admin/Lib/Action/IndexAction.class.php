<?php
/**
 * 后台首页
 */
class IndexAction extends CommonAction {

	/**
	 * 后台首页视图
	 */
	public function index() {
		$this->display();
	}

	/**
	 * 后台信息页
	 */
	public function copy() {
		$this->display();
	}

	/**
	 * 退出登陆
	 */
	public function loginOut() {
		session_unset();
		session_destroy();
		redirect(U('Login/index'));
	}


}