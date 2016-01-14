<?php
/**
 * 微博关联所有相关数据关联模型
 */
Class WeiboRelationModel extends RelationModel {

	protected $tableName = 'weibo';

	protected $_link = array(
		'picture' => array(
			'mapping_type' => HAS_ONE,
			'foreign_key' => 'wid'
			),
		'comment' => array(
			'mapping_type' => HAS_MANY,
			'foreign_key' => 'wid'
			),
		'keep' => array(
			'mapping_type' => HAS_MANY,
			'foreign_key' => 'wid'
			),
		'atme' => array(
			'mapping_type' => HAS_MANY,
			'foreign_key' => 'wid'
			)
		);
}