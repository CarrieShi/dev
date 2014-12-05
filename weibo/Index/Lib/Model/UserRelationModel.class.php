<?php
/**
 * 用户与用户信息表关联模型
 */
class UserRelationModel extends RelationModel {

	//定义主表名称
	protected $tableName = 'user';

	//定义用户与用户信息表关联关系属性
	protected $_link = array(
		'userinfo' => array(
			'mapping_type' => HAS_ONE,
			'foreign_key' => 'uid'
			)
		);

	/**
	 * 自动插入方法
	 */
	public function insert ($data=NULL) {
		$data = is_null($data) ? $_POST : $data;
		return $this->relation(true)->data($data)->add();//当前对象$this
	}
}