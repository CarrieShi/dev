<?php
require 'lib/MdFile.class.php';

$obj = new MdFile();
$files = $obj->listFiles();
require 'theme/default/index.html';