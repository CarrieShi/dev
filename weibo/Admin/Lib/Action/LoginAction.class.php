<?php
/**
 * 登陆页面
 */
class LoginAction extends Action {
	
	/**
	 * 登陆页面视图
	 */
	public function index () {
		$this->display();
	}

	/**
	 * 登陆操作处理
	 */
	public function login() {
		if(! $this->isPOST()) {
			halt('页面不存在');
		}

		// 安全考虑
		if(! isset($_POST['submit'])) {
			return false;
		}

		//验证码对比
		if($_SESSION['verify'] != md5($_POST['verify'])) {
			$this->error('验证码错误');
		}

		$username = $this->_post('uname');
		$password = $this->_post('pwd', 'md5');
		$db = M('admin');
		$user = $db->where(array('username' => $username))->find();

		if(! $user || $user['password'] != $password) {
			$this->error('用户密码不正确');
		}

		if($user['lock']) {
			$this->error('用户被锁定');
		}

		$data = array(
			'id' => $user['id'],
			'logintime' => time(),
			'loginip' => get_client_ip()
			);
		$db->save($data);//操作数据中有主键，自动填充为where条件
		// 同：$db->where(array('id' => $user['id']))->save($data);

		//登录成功，写入SESSION并跳转到首页
		session('uid', $user['id']);
		session('username', $user['username']);
		session('logintime', date('Y-m-d H:i:s', $user['logintime']));
		session('now', date('Y-m-d H:i:s', time()));		
		session('loginip', $user['loginip']);

		$this->success('正在登陆...', __APP__);
		// header('Content-Type:text/html;Charset=UTF-8');
		// redirect(__APP__, 3, '登录成功，正在为您跳转...');
	}

	/**
	 * 验证码
	 */
	public function verify() {
		import('ORG.Util.Image');
		Image::buildImageVerify();
	}
}