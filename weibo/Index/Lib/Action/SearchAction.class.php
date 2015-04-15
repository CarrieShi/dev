<?php
/**
 * 搜索控制器
 */
class SearchAction extends CommonAction {

	/**
	 * 搜索找人
	 */
	public function sechUser () {
		$keyword = $this->_getKeyword();
		
		if ($keyword) {
			//检索出除自己外昵称含有关键字的用户
			$where = array(
				'username' => array('LIKE', '%' . $keyword . '%'), 
				'uid' => array('NEQ', session('uid'))
				);
			$field = array('username', 'sex', 'location', 'intro', 'face80', 'follow', 'fans', 'weibo', 'uid');
			$db = M('userinfo');
			import('ORG.Util.Page');// 导入分页类
			$count      = $db->where($where)->count();// 查询满足要求的总记录数
			$Page       = new Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数
			$limit 		= $Page->firstRow.','.$Page->listRows;
			$show       = $Page->show();// 分页显示输出
			// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
			$result = $db->where($where)->field($field)->order('id  DESC')->limit($limit)->select();			

			//重新组合结果集，得到是否互相关注
			$result = $this->_getMutual($result);

			$this->result = $result ? $result : false;

			$this->assign('page',$show);// 赋值分页输出
		}

		$this->keyword = $keyword;
		$this->count = $count;
		$this->display();
	}

	/**
	 * 搜索微博
	 */
	public function sechWeibo () {
		$keyword = $this->_getKeyword();
		
		if ($keyword) {
			//检索出含有关键字的微博
			$where = array('content' => array('LIKE', '%' . $keyword . '%'));
			$db = D('WeiboView');
			import('ORG.Util.Page');// 导入分页类
			$count      = M('weibo')->where($where)->count();// 查询满足要求的总记录数
			$Page       = new Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数
			$limit 		= $Page->firstRow.','.$Page->listRows;
			$show       = $Page->show();// 分页显示输出
			// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
			$weibo = $db->getAll($where, $limit);

			$this->weibo = $weibo ? $weibo : false;

			$this->assign('page',$show);// 赋值分页输出
		}

		$this->keyword = $keyword;
		$this->count = $count;
		$this->display();
	}

	/**
	 * 返回搜索关键字
	 */
	private function _getKeyword () {
		return $_GET['keyword'] == '搜索微博、找人' ? NULL : $_GET['keyword'];
	}

	/**
	 * 重组结果集得到是否互相关注与是否已关注
	 * @param [Array] $result [需要处理的结果集]
	 * @return [Array]		[处理完成后的结果集]
	 */
	private function _getMutual ($result) {
		if (!$result) return false;

		$db = M('follow');

		foreach ($result as $k => $v) {
			//是否互相关注
			$sql = '(SELECT `follow` FROM `hd_follow` WHERE `follow` = ' . $v['uid'] . ' AND `fans` = ' . session('uid') . ')
				UNION (SELECT `follow` FROM `hd_follow` WHERE `follow` = ' . session('uid') . ' AND `fans` = ' . $v['uid'] . ')';
			$mutual = $db->query($sql);

			if (count($mutual) == 2) {
				$result[$k]['mutual'] = 1;
				$result[$k]['followed'] = 1;
			} else {
				$result[$k]['mutual'] = 0;
				//如果我有关注他，则返回1，否则返回0
				$result[$k]['followed'] = $mutual[0]['follow'] == $v['uid'] ? 1 : 0; 
			}
		}
		return $result;
	}
}