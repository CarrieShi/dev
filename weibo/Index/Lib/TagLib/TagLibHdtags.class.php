<?php
import('TagLib');

class TagLibHdtags extends Taglib {

	protected $tags = array(
		'userinfo' => array('attr' => 'id', 'close' => 1),// close 默认 1 非闭合标签
		'maybe' => array('attr' => 'uid', 'close' => 1)
		);

	/**
	 * 读取用户信息标签
	 * @param [array] $attr html属性
	 * @param [string] $content
	 */
	public function _userinfo ($attr, $content) {
		$attr = $this->parseXmlAttr($attr);
		$id = $attr['id'];		
		$str = '';
		$str .= '<?php ';
		$str .= '$where = array("uid" => '. $id .');';
		$str .= '$field = array("username", "face80" => "face", "follow", "fans", "weibo", "weibo", "uid");';
		$str .= '$userinfo = M("userinfo")->where($where)->field($field)->find();';
		$str .= 'extract($userinfo);';
		$str .= '?>';
		$str .= $content;
		return $str;
	}

	public function _maybe ($attr, $content) {
		$attr = $this->parseXmlAttr($attr);
		$uid = $attr['uid'];
		$str = '';
		$str .= '<?php ';
		$str .= '$uid = ' . $uid .';';
		$str .= '$db = M("follow");';
		$str .= '$where = array("fans" => session("uid"));';
		$str .= '$follow = $db->where($where)->field("follow")->select();';
		$str .= 'foreach ($follow as $k => $v) :';
		$str .= '$follow[$k] = $v["follow"];';
		$str .= 'endforeach;';
		$str .= '$sql = "SELECT uid, username, face50 AS face, COUNT(f.follow) AS `count`  
						FROM hd_follow f LEFT JOIN hd_userinfo u ON u.uid = f.follow 
						WHERE f.fans IN (". implode(\',\', $follow) .")  /*我关注的ID-->关注的用户*/
							AND f.follow NOT IN (". implode(\',\', $follow) .")  /*而不是关注 我关注的ID 的用户*/
							AND f.follow <> ". $uid ."  /*也不是我关注的其他用户*/
						GROUP BY uid 
						ORDER BY `count` DESC LIMIT 4";';
		$str .= '$friend = $db->query($sql);';
		$str .= 'foreach ($friend as $v) :';		
		$str .= 'extract($v);?>';
		$str .= $content;
		$str .= '<?php endforeach;?>';
		
		return $str;	
	}
}