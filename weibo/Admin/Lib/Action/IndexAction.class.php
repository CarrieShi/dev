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
		$db = M('user');
		$this->user = $db->count();
		$this->lock = $db->where(array('lock' => 1))->count();

		$db = M('weibo');
		$this->weibo = $db->where(array('isturn' => 0))->count();
		$this->turn = $db->where(array('isturn' => array('GT', 0)))->count();
		$this->comment = M('comment')->count();
		//echo $db->getLastSql();die;
		$config = include './Index/Conf/system.php';
		$this->copy = $config['COPY'];
		$this->author = $config['AUTHOR'];

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