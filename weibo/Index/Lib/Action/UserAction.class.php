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

		$this->userinfo = $userinfo;

		// 导入分页类
		import('ORG.Util.Page');

		//统计总条数，用于分页
		$count = M('weibo')->where($where)->count();
		$page = new Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数
		$limit = $page->firstRow.','.$page->listRows;

		//读取所有微博
		$this->weibo = D('WeiboView')->getAll($where, $limit);
		$this->page = $page->show();// 分页显示输出

		//我的关注
		if(S('follow_' . $id)) {
			//缓存已成功并且未过期
			$follow = S('follow_' . $id);
		} else {
			//生成缓存
			$where = array('fans' => $id);
			$follow = M('follow')->where($where)->field('follow')->select();
			foreach ($follow as $k => $v) {
				$follow[$k] = $v['follow'];
			}
			$where = array('uid' => array('IN', $follow));
			$field = array('username', 'face50' => 'face', 'uid');
			$follow = M('userinfo')->field($field)->where($where)->limit(8)->select();

			S('follow_' . $id, $follow, 3600);
		}
		$this->follow = $follow;

		//我的粉丝
		if(S('fans_' . $id)) {
			//缓存已成功并且未过期
			$fans = S('fans_' . $id);
		} else {
			//生成缓存
			$where = array('follow' => $id);
			$fans = M('follow')->where($where)->field('fans')->select();
			foreach ($fans as $k => $v) {
				$fans[$k] = $v['fans'];
			}
			$where = array('uid' => array('IN', $fans));
			$field = array('username', 'face50' => 'face', 'uid');
			$fans = M('userinfo')->field($field)->where($where)->limit(8)->select();

			S('fans_' . $id, $fans, 3600);
		}
		$this->fans = $fans;

		$this->display();
	}

	/**
	 * 用户关注和粉丝列表
	 */
	public function followList() {
		$uid = $this->_get('uid', 'intval');

		//区分关注与粉丝(1:关注；2:粉丝)
		$type = $this->_get('type', 'intval');

		// 导入分页类
		import('ORG.Util.Page');
		$db = M('follow');

		//根据type，读取用户关注与粉丝ID
		$where = $type ? array('fans' => $uid) : array('follow' => $uid);
		$field = $type ? 'follow' : 'fans';
		$count = $db->where($where)->count();
		$page = new Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数
		$limit = $page->firstRow.','.$page->listRows;

		//读取所有微博
		$uids = $db->field($field)->where($where)->limit($limit)->select();

		if($uids) {
			//把用户关注或者粉丝ID重组为一维数组
			foreach ($uids as $k => $v) {
				$uids[$k] = $v[$field];
			}

			//提取用户信息
			$where = array('uid' => array('IN', $uids));
			$field = array('face50' => 'face', 'username', 'sex', 'location', 'follow', 'fans', 'weibo', 'uid');
			$users = M('userinfo')->where($where)->field($field)->select();

			//分配用户信息到视图
			$this->users = $users;
		}


		//重复请求？查看登陆用户的好友的好友和登陆用户的关系
		$where = array('fans' => session('uid'));
		$follow = $db->field('follow')->where($where)->select();

		if($follow) {
			foreach ($follow as $k => $v) {
				$follow[$k] = $v['follow'];
			}
		}

		$where = array('follow' => session('uid'));
		$fans = $db->field('fans')->where($where)->select();

		if($fans) {
			foreach ($fans as $k => $v) {
				$fans[$k] = $v['fans'];
			}
		}

		$this->follow = $follow;
		$this->fans = $fans;
		
		$this->type = $type;
		$this->count = $count;
		$this->page = $page->show();// 分页显示输出
		$this->display();
	}

	/**
	 * 收藏列表
	 */
	public function keep () {
		import('ORG.Util.Page');
		$uid = session('uid');

		$count = M('keep')->where(array('uid' => $uid))->count();
		$page = new Page($count, 20);
		$limit = $page->firstRow . ',' . $page->listRows;

		$where = array('keep.uid' => $uid);
		$this->weibo = D('KeepView')->getAll($where, $limit);
		$this->page = $page->show();;
		$this->display('weiboList');
	}

	/**
	 * 取消收藏
	 */
	public function cancelKeep() {
		if( ! $this->isAjax()) {
			halt('页面不存在!');
		}

		$kid = $this->_post('kid', 'intval');
		$wid = $this->_post('wid', 'intval');

		if(M('keep')->delete($kid)) {
			M('weibo')->where(array('id' => $wid))->setDec('keep');

			echo 1;
		} else {
			echo 0;
		}
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