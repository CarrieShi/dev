<?php
require 'Parsedown.class.php';
/**
* Markdown 文件基本操作
* 用于操作Markdown 文件
*
* @package api
* @access public
* @version $Id$
* @author $Author$
*/
class MdFile {
	private $_dir = 'posts';
	private $_md;

	public function __construct($pattern = '/*/*.md') {
		$this->_md = glob($this->_dir . $pattern);
		$this->is_file_exists();
	}
	
	private function is_file_exists() {
		if(empty($this->_md)) {
			exit('Emtpy!');
		}
	}

	/**
    * 获取文件列表
	* md文件
	*
	* @param
	*
	* @request GET
	* @success array ['dir' => ['dir' => 'xxx', 'name' => 'xxx', 'time' => 'xxx']]
	* @error string Empty
	* @data Empty
	*
	* @type GET,POST
	* @author Carrie hichunlishi@gmail.com
	* @edit Carrie 2016-04-25
    */
	public function listFiles() {
		foreach ($this->_md as $key => $val) {			
			$path = pathinfo($val);
			$dir = str_replace($this->_dir .'/', '', $path['dirname']);
			$files[$dir][] = array(
				'dir' => $dir,
				'name' => str_replace('.md', '', $path['basename']),
				'time' => filemtime($val),
				);
		}
		return $files;
	}

	/**
    * 获取文件标题
	* 读文件内容
	*
	* @param file string 文件路径
	*
	* @request GET $file='./posts/DEV/PHPDepoly.md'
	* @success string filetitle
	* @error string warning
	* @data warning【请检查标题】
	*
	* @type GET,POST
	* @author Carrie hichunlishi@gmail.com
	* @edit Carrie 2016-04-25
    */
	public function getTitle($file) {
		$main = file_get_contents($file);
		preg_match("|\# .*|",$main,$content);		
		$title = str_replace('# ','',$content[0]);
		return empty($title) ? '【请检查标题】' : $title;
	}

	/**
    * 获取文件内容
	* 读文件内容
	*
	* @param
	*
	* @request
	* @success string filecontent
	* @error 
	* @data 
	*
	* @type GET,POST
	* @author Carrie hichunlishi@gmail.com
	* @edit Carrie 2016-04-25
    */
	public function getContent() {
		$file = $this->_md[0];
		$content = file_get_contents($file);
		$parse = new Parsedown();
		$markdown = $parse->text($content);
		return $markdown;
	}
}
