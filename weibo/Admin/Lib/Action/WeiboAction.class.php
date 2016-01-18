<?php
/**
 * 微博管理
 */
Class WeiboAction extends CommonAction {

	/**
	 * 原创微博
	 * @return [type] [description]
	 */
	public function index () {
		import('ORG.Util.Page');
		$where = array('isturn' => 0);
		$count = M('weibo')->where($where)->count();
		$page = new Page($count, 20);
		$limit = $page->firstRow . ',' . $page->listRows;

		$weibo = D('WeiboView')->where($where)->limit($limit)->order('time DESC')->select();
		$this->weibo = $weibo;
		$this->page = $page->show();
		$this->display();
	}

	/**
	 * 删除微博
	 */
	public function delWeibo() {
		$id = $this->_get('id', 'intval');
		$uid = $this->_get('uid', 'intval');

		// 删除微博
		if(D('WeiboRelation')->relation(true)->delete($id)) {
			M('userinfo')->where(array('uid' => $uid))->setDec('weibo');
			$this->success('删除成功', U('index'));
		} else {
			$this->error('删除失败，请重试...');
		}
	}

	/**
	 * 转发微博列表
	 */
	public function turn () {
		import('ORG.Util.Page');
		$where = array('isturn' => array('GT',0));
		$count = M('weibo')->where($where)->count();
		$page = new Page($count, 20);
		$limit = $page->firstRow . ',' . $page->listRows;

		$db = D('WeiboView');
		unset($db->viewFiled['picture']);//不关联图片表
		$turn = $db->where($where)->limit($limit)->order('time DESC')->select();
		$this->turn = $turn;
		$this->page = $page->show();
		$this->display();
	}

	/**
	 * 微博检索
	 */
	public function sechWeibo() {
		if(isset($_GET['sech'])) {
			$where = array('content' => array('LIKE', '%' . $this->_get('sech') . '%'));
			$weibo = D('WeiboView')->where($where)->order('time DESC')->select();
			// echo D('UserView')->getLastSql();
			$this->weibo = $weibo ? $weibo : false;
		}
		$this->display();
	}

	/**
	 * 评论列表
	 */
	public function comment() {		
		import('ORG.Util.Page');
		$count = M('comment')->count();
		$page = new Page($count, 20);
		$limit = $page->firstRow . ',' . $page->listRows;

		$comment = D('CommentView')->limit($limit)->order('time DESC')->select();
		$this->comment = $comment;
		$this->page = $page->show();
		$this->display();
	}

	/**
	 * 评论列表
	 */
	public function sechComment() {
		if(isset($_GET['sech'])) {
			$where = array('content' => array('LIKE', '%' . $this->_get('sech') . '%'));
			$comment = D('CommentView')->where($where)->order('time DESC')->select();
			// echo D('UserView')->getLastSql();
			$this->comment = $comment ? $comment : false;
		}
		$this->display();
	}
}