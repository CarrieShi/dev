<?php
require 'lib/MdFile.class.php';

if(empty($_GET['read'])){
	exit('参数错误');
}

$obj = new MdFile($_GET['read']);
$markdown = $obj->getContent();

require 'theme/default/wiki.html';