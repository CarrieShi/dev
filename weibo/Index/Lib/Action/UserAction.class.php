<?php
/**
 * 用户个人页控制器
 */
Class UserAction extends CommonAction {


	/**
	 * 用户个人页视图
	 */
	public function index () {
		$id = $this->_get('id', 'intval');

		//读取用户个人信息
		$where = array('uid'=>$id);
		$userinfo = M('userinfo')->where($where)->field('truename,face50,face80,style', true)->find();
		
		if(!$userinfo) {
			header('Content-Type:text/html;Charset=UTF-8');
			redirect(__ROOT__, 3, '用户不存在，正在为您跳转到首页');
			exit();
		}

		//实例化微博视图模型
		$db = D('WeiboView');
		import('ORG.Util.Page');// 导入分页类

		//统计总条数，用于分页
		$count = $db->where($where)->count();
		$page = new Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数
		$limit = $page->firstRow.','.$page->listRows;

		//读取所有微博
		$result = $db->getAll($where, $limit);

		$this->userinfo = $userinfo;
		$this->weibo = $result;
		$this->page = $page->show();// 分页显示输出
		
		$this->display();
	}

	/**
	 * 空操作
	 */
	public function _empty($name) {
		// echo $name;//调用了没有定义的方法，默认输出的名字
		$this->_getUrl($name);
	}

	/**
	 * 处理用户名空操作，获得用户ID，跳转至用户个人页
	 */
	private function _getUrl ($name) {
		$name = htmlspecialchars($name);
		$where = array('username' => $name);
		$uid = M('userinfo')->where($where)->getField('uid');
		
		if(!$uid) {
			redirect(U('Index/index'));
		} else {
			//redirect(U('index', array('id' => $uid)));
			redirect(U('/' . $uid));	//配置了URL功能
		}
	}
}