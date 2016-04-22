<?php
class Index {
	private $_dir = 'posts';
	private $_md;

	public function __construct($pattern = '/*/*.md') {
		$this->_md = glob($this->_dir . $pattern);
	}

	public function listFiles() {
		if(empty($this->_md)) {
			exit('Emtpy!');
		}

		foreach ($this->_md as $key => $val) {
			$file_info = $this->getFileInfo($val);
			$files[$file_info['dir']][] = $file_info;
		}

		return $files;
	}

	public function getFileInfo($file) {
		$main = file_get_contents($file);
		preg_match("|\# .*|",$main,$content);
		$path = pathinfo($file);
		$title = str_replace('# ','',$content[0]);
		if(empty($title)) $title = $path['basename'];
		return array(
			'title' => $title,
			'dir' => str_replace($this->_dir . '/', '', $path['dirname']),
			'name' => $path['basename'],
			'time' => filemtime($file)
			);
	}
}

$obj = new Index();
$files = $obj->listFiles();
require 'theme/default/index.html';