<?php
/**
 * 用户管理控制器
 */
Class UserAction extends CommonAction {

	/**
	 * 微博用户列表
	 */
	public function index () {
		import('ORG.Util.Page');
		$count = M('user')->count();
		$page = new Page($count, 20);
		$limit = $page->firstRow . ',' . $page->listRows;


		$this->users = D('UserView')->limit($limit)->select();
		$this->page = $page->show();
		$this->display();
	}

	/**
	 * 锁定用户
	 */
	public function lockUser() {
		$data = array(
			'id' => $this->_get('id', 'intval'),
			'lock' => $this->_get('lock', 'intval')
			);

		$msg = $data['lock'] ? '锁定' : '解锁';
		if(M('user')->save($data)) {
			$this->success($msg . '成功', $_SERVER['HTTP_REFFER']);
		} else {
			$this->error($msg . '失败，请重试...');
		}
	}

	/**
	 * 微博用户检索
	 */
	public function sechUser() {
		if(isset($_GET['sech']) && isset($_GET['type'])) {
			$where = $_GET['type'] ? array('id' => $this->_get('sech', 'intval')) : array('username' => array('LIKE', '%' . $this->_get('sech') . '%'));
			$user = D('UserView')->where($where)->select();
			// echo D('UserView')->getLastSql();
			$this->user = $user ? $user : false;
		}
		$this->display();
	}

	/**
	 * 后台管理员列表
	 */
	public function admin() {
		$this->admin = D('admin')->select();
		$this->display();
	}

	/**
	 * 锁定后台管理员
	 */
	public function lockAdmin() {		
		$data = array(
			'id' => $this->_get('id', 'intval'),
			'lock' => $this->_get('lock', 'intval')
			);

		$msg = $data['lock'] ? '锁定' : '解锁';
		if(M('admin')->save($data)) {
			$this->success($msg . '成功', U('admin'));
		} else {
			$this->error($msg . '失败，请重试...');
		}
	}

	/**
	 * 删除后台管理员
	 */
	public function delAdmin() {
		$id = $this->_get('id', 'intval');

		if(M('admin')->delete($id)) {
			$this->success('删除成功', U('admin'));
		} else {
			$this->error('删除失败，请重试...');
		}
	}

	/**
	 * 添加管理员
	 */
	public function runAddAdmin() {
		if($_POST['pwd'] != $_POST['pwded']) {
			$this->error('两次密码不一致');
		}

		$data = array(
			'username' => $this->_post('username'),
			'password' => $this->_post('pwd', 'md5'),
			'logintime' => time(),
			'loginip' => get_client_ip(),
			'admin' => $this->_post('admin', 'intval')
			);

		if(M('admin')->data($data)->add()) {
			$this->success('添加成功', U('admin'));
		} else {
			$this->error('添加失败，请重试...');
		}
	}

	/**
	 * 修改密码
	 */
	public function runEditPwd() {
		$db = M('admin');
		$old = $db->where(array('id' => session('uid')))->getField('password');
		if($old != $this->_post('old', 'md5'))	{
			$this->error('旧密码错误');
		}

		if($_POST['pwd'] != $_POST['pwded']) {
			$this->error('两次密码不一致');
		}

		$data = array(
			'id' => session('uid'),
			'password' => $this->_post('pwd', 'md5')
			);

		if($db->save($data)) {
			$this->success('修改成功', U('Index/copy'));
		} else {
			$this->error('修改失败，请重试...');
		}
	}
}