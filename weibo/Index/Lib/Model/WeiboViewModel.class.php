<?php
/**
 * 读取微博试图模型
 */
Class WeiboViewModel extends ViewModel {

	//定义视图表关联关系
	public $viewFields = array(
		'weibo' => array(
			'id','content','isturn','time','turn','keep','comment','uid',
			'_type' => 'LEFT'
			),
		'userinfo' => array(
			'username', 'face50' => 'face',
			'_on' => 'weibo.uid = userinfo.uid',
			'_type' => 'LEFT'//1）上面任意表都可以与下面的表以left join的方法关联，在本例中是weibo与picture左关联
			),
		'picture' => array(
			'mini', 'medium', 'max',
			'_on' => 'weibo.id = picture.wid'//1)结合面的left join， 添加关联的字段
			)
		);

	/**
	 * 返回查询所有记录
	 * @param array $where 查询条件
	 * @return
	 */
	public function getAll($where) {
		$result = $this->where($where)->order('time DESC')->select();
		//echo $this->getLastSql(); //查看sql
		
		//重组结果集数组，得到转发微博
		if($result) {
			foreach ($result as $k => $v) {
				if($v['isturn']) {
					//$where = array('id' => $v['isturn']);
					//$result[$k]['isturn'] = $this->where($where)->find();
					$result[$k]['isturn'] = $this->find($v['isturn']);//相当于上面的两行
				}
			}
		}
		return $result;
	}
}