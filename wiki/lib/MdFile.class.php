<?php
require 'Parsedown.class.php';

class MdFile {
	private $_dir = 'posts';
	private $_md;

	public function __construct($pattern = '/*/*.md') {
		$this->_md = glob(iconv('UTF-8','gb2312',$this->_dir . $pattern));
		$this->is_file_exists();
	}

	public function is_file_exists() {
		if(empty($this->_md)) {
			exit('Emtpy!');
		}
	}

	public function listFiles() {
		foreach ($this->_md as $key => $val) {			
			$path = pathinfo($val);
			$dir = str_replace($this->_dir .'/', '', $path['dirname']);

			$files[$dir][] = array(
				'dir' => $dir,
				'name' => str_replace('.md', '',  iconv('gb2312','UTF-8',$path['basename'])),
				'time' => filemtime($val)
				);
		}
		return $files;
	}

	public function getTitle($file) {
		$main = file_get_contents($file);
		preg_match('|\# .*|',$main,$content);		
		$title = str_replace('# ','',$content[0]);
		return empty($title) ? '【请检查标题】' : $title;
	}

	public function getContent() {
		$file = $this->_md[0];
		$content = file_get_contents($file);
		$parse = new Parsedown();
		$markdown = $parse->text($content);
		return $markdown;
	}
}