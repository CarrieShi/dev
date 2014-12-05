<?php
/**
 * 注册与登录控制器
 */
Class LoginAction extends Action {

	/**
	 * 登录页面
	 */
	public function index() {
		$this->display();
	}

	/**
	 * 登录表单处理
	 */
	public function login() {
		if(!$this->isPOST()) {
			halt('页面不存在');
		}
		
		//提取表单内容
		$account = $this->_post('account');
		$pwd = $this->_post('pwd', 'md5');

		$where = array('account' => $account);

		//M方法是用来实例化空模型的，也就是说用户并没有在Model文件夹中新建一个自定义的模型
		$user = M('user')->where($where)->find();//find:一维数组;selecct:二维数组

		if(!$user || $user['password'] != $pwd) {
			$this->error('用户名或密码不正确');//error()中自带die或者exit，所以不需要写else部分
		}

		if($user['lock']) {
			$this->error('用户被锁定');
		}

		
		if(isset($_POST['auto'])) {
			$account = $user['account'];
			$ip = get_client_ip();
			$value = $account . '|' . $ip;
			$value = encryption($value);
			@setcookie('auto', $value, C('AUTO_LOGIN_TIME'), '/');
		} 

		//登录成功，写入SESSION并跳转到首页
		session('uid', $user['id']);

		header('Content-Type:text/html;Charset=UTF-8');
		redirect(__APP__, 3, '登录成功，正在为您跳转...');
	}
	/**
	 * 注册页面
	 */
	public function register() {
		$this->display();
	}

	/**
	 * 注册表单处理
	 */
	public function runRegis() {
		if(!$this->isPOST()) {
			halt('页面不存在');
		}
		if($_SESSION['verify'] != md5($_POST['verify'])) {
			$this->error('验证码错误');
		}
		if($_POST['pwd'] != $_POST['pwded']) {
			$this->error('两次密码不一致');
		}

		//提取POST数据
		$data = array(
			'account' => $this->_post('account'),
			'password' => $this->_post('pwd', 'md5'),
			'registime'	=> $_SERVER['REQUEST_TIME'],
			'userinfo' => array(
				'username' => $this->_post('uname')
				)		
			);
		$id = D('UserRelation')->insert($data);
		// 定义了一个具体的模型类 UserRelationModel.class.php，那么就必须用D方法来实例化这个模型，D('UserRelation'),否则会报错。
		//eq below
		// $db = D('UserRelation');//当前对象
		// $db->relation(true)->data($data)->add();
		if($id) {
			//插入数据成功后把用户ID写入SESSION
			session('uid', $id);

			//跳转至首页
			header('Content-Type:text/html;Charset=UTF-8');
			redirect(__APP__, 3, '注册成功，正在为您跳转...');
		} else {
			$this->error('注册失败,请重试...');
		}
	}

	/**
	 * 获取验证码
	 */
	public function verify() {
		import('ORG.Util.Image');
    	Image::buildImageVerify(4, 1, 'png');
	}

	/**
	 * 异步验证账号是否已存在
	 */
	public function checkAccount() {
		 if(!$this->isAjax()) {//如果不是异步提交的话，就返回错误
		 	halt('页面不存在');
		 }
		 $account = $this->_post('account');//相当于下面这一句
		 // $account = htmlspecialchars($_POST['account']);		 
		 $where = array('account' => $account);
		 if(M('user')->where($where)->getField('id')) {
		 	echo 'false';//传给jQuery
		 } else {
		 	echo 'true';
		 }
	}

	/**
	 * 异步验证昵称是否已存在
	 */
	public function checkUname() {
		if(!$this->isAjax()) {
			halt('页面不存在');
		}
		$username = $this->_post('uname');
		$where = array('username' => $username);
		if(M('userinfo')->where($where)->getField('id')) {
			echo 'false';//传给jQuery
		 } else {
		 	echo 'true';
		 }
	}

	/**
	 * 异步验证验证码
	 */
	public function checkVerify () {
		if(!$this->isAjax()) {
			halt('页面不存在');
		}
		$verify = $this->_post('verify');
		if ($_SESSION['verify'] != md5($_POST['verify'])) {
			echo 'false';
		} else {
		 	echo 'true';
		 }
	}
}