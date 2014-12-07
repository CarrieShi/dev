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
		import('ORG.Util.Page');// 导入分页类

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
		//统计总条数，用于分页
		$count = $db->where($where)->count();
		$page = new Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数
		$limit = $page->firstRow.','.$page->listRows;

		//读取所有微博
		$result = $db->getAll($where, $limit);

		$this->weibo = $result;
		$this->page = $page->show();// 分页显示输出
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
	 *
	 * isturn 保存的是原创微博的id
	 * tid存在，则id对应的微博不是原创微博
	 * 多重转发的信息，在页面端由js组合后再传给content
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
	 * 评论
	 *
	 * 1、评论插入comment表
	 * 2、同时转发，评论(如果为多次转发的微博，还需处理后)插入weibo表
	 *
	 * $_POST['content'] 评论（或同时转发）的内容
	 * $_POST['wid'] 被评论（或同时转发）的微博id
	 * $_POST['uid'] 被评论（或同时转发）的用户id
	 * $_POST['isturn'] 是否同时转发（1 ：0）
	 *
	 * $data['uid'] 评论（或同时转发）的用户id
	 * $weibo['isturn'] 非空时为最早被转发的微博
	 */
	public function comment () {
		if(!$this->isAjax()) {
			halt('页面不存在！');
		}

		//提取异步提交的评论数据
		$data = array(
			'content' => $this->_post('content'),
			'time' => time(),
			'uid' => session('uid'),
			'wid' => $this->_post('wid', 'intval')
			);
		if(M('comment')->data($data)->add()) {
			//读取评论用户信息
			$fields = array('username', 'face50' => 'face', 'uid');
			$where = array('uid' => $data['uid']);
			$user = M('userinfo')->where($where)->field($fields)->find();
			
			$db = M('weibo');
			//评论数+1
			$db->where(array('id' => $data['wid']))->setInc('comment');
			
			//同时转发
			if($_POST['isturn']) {
				$field = array('content', 'isturn');
				$weibo = $db->field($field)->find($data['wid']);
				//如果为多次转发的微博，还需处理 // @username : content
				if($weibo['isturn']) {
					$uid = $this->_post('uid', 'intval');
					$username = M('userinfo')->where(array('uid' => $uid))->getField('username');
				}
				$content = $weibo['isturn'] ? $data['content'] . ' // @' . $username . ' : ' . $weibo['content'] : $data['content'];
				
				$cons = array(
					'content' => $content,
					'isturn' => $weibo['isturn'] ? $weibo['isturn'] : $data['wid'],
					'time' => time(),
					'uid' => $data['uid']
					);
				if($db->data($cons)->add()) {
					//转发数+1
					$db->where(array('id' => $data['wid']))->setInc('turn');
				}
				exit(1);
			}

			//组合评论样式并以字符串返回
			$str = '';
			$str .= '<dl class="comment_content">';
			$str .= '<dt><a href="' . U('/' . $data['uid']) . '">';
			$str .= '<img src="';
			$str .= __ROOT__;
			if($user['face']) {
				$str .= '/Uploads/Face/' . $user['face']; 
			} else {
				$str .= '/Public/Images/noface.gif';
			}
			$str .= '" alt="' . $user['username'] . '" width="30" height="30"/>';
			$str .= '</a></dt><dd>';
			$str .= '<a href="' . U('/' . $data['uid']) . '" class="comment_name">';
			$str .= $user['username'] . '</a> : ' . replace_weibo($data['content']);
			$str .= '&nbsp;&nbsp;( ' . time_format($data['time']) . ' )';
			$str .= '<div class="reply">';
			$str .= '<a href="">回复</a>';
			$str .= '</div></dd></dl>';
			echo $str;	
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