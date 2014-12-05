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

		echo $id;
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