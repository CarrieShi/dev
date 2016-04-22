<?php
require 'lib/Parsedown.class.php';

$file = "posts/{$_GET['read']}";
if(empty($_GET['read']) || !file_exists($file)){
	exit('404 Not Found');
}

$md = file_get_contents($file);
preg_match("|\# .*|",$md,$content);
$title = str_replace('# ','',$content[0]);
$parse = new Parsedown();
$markdown = $parse->text($md);
require 'theme/default/wiki.html';