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
}