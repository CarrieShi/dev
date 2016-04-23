<?php
require 'lib/MdFile.class.php';

if(empty($_GET['read'])){
	exit('参数错误');
}

$obj = new MdFile($_GET['read']);
$markdown = $obj->getContent();
$title = str_replace('.md', '', basename($_GET['read']));

require 'theme/default/wiki.html';
