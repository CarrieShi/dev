<?php
/**
 * 首页控制器
 */
class IndexAction extends CommonAction {

	/**
	 * 首页视图
	 */
	public function index () {		
		//实例化微博视图模型
		$db = D('WeiboView');
		
		//获取当前用自身ID于当前用户所有关注好友的ID
		$uid = array(session('uid'));
		$where = array('fans' => session('uid'));
		$result = M('follow')->field('follow')->where($where)->select();
		if($result) {
			foreach ($result as $v) {
				$uid[] = $v['follow'];
			}
		}
		//组合WHERE条件，条件为当前用自身ID于当前用户所有关注好友的ID
		$where = array('uid' => array('IN', implode(',',$uid)));

		//读取所有微博
		$result = $db->getAll($where);

		$this->weibo = $result;
		$this->display();
	}

	/**
	 * 微博发布处理
	 */
	public function sendWeibo () {
		if(!$this->isPost()) {
			halt('页面不存在！');
		}
		$data = array(
			'content' => $this->_post('content'),//htmlspecialchars($_POST['content'])
			'time' => time(),
			'uid' => session('uid')
			);
		if($wid = M('weibo')->data($data)->add()) {
			if(!empty($_POST['max'])) {
				$img = array(
					'mini' => $this->_post('mini'),
					'medium' => $this->_post('medium'),
					'max' => $this->_post('max'),
					'wid' => $wid
					);
				M('picture')->data($img)->add();
			}
			M('userinfo')->where(array('uid' => session('uid')))->setInc('weibo');
			$this->success('发布成功', U('index'));
		} else {
			$this->error('发布失败，请重试...');
		}
	}

	/**
	 * 转发微博
	 */
	public function turn() {
		if(!$this->isPost()) {
			halt('页面不存在！');
		}
		//原微博ID
		$id = $this->_post('id', 'intval');
		$tid = $this->_post('tid', 'intval');
		//转发内容
		$content = $this->_post('content');

		//提取插入数据
		$data = array(
			'content' => $content,
			'isturn' => $tid ? $tid : $id,
			'time' => time(),
			'uid' => session('uid')
			);

		//插入数据到微博表
		$db = M('weibo');
		if($db->data($data)->add()) {
			//原微博转发数+1
			$db->where(array('id' => $id))->setInc('turn');
			if($tid) {
				$db->where(array('id' => $tid))->setInc('turn');
			}

			//用户发布微博数+1
			M('userinfo')->where(array('uid' => session('uid')))->setInc('weibo');

			//如果点击了同时评论，插入内容到评论表
			if(isset($_POST['becomment'])) {
				$data = array(
					'content' => $content,
					'time' => time(),
					'uid' => session('uid'),
					'wid' => $id
					);
				//插入评论数据后，给原微博评论数+1
				if(M('comment')->data($data)->add()) {
					$db->where(array('id' => $id))->setInc('comment');
				}
			}
			$this->success('转发成功', U('index'));
		} else {
			$this->error('转发失败，请重试...');
		}

	}

	/**
	 * 退出登录
	 */
	public function loginOut () {		
		//卸载SESSION
		session_unset();
		session_destroy();

		//删除用于自动登录的COOKIE
		@setcookie('auto', '', time() - 3600, '/');

		header('Content-Type:text/html;Charset=UTF-8');
		redirect(__APP__, 3, '退出成功，正在为您跳转...');
	}
}