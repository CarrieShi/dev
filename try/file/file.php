<?php
header('Content-type:text/html; Charset=UTF-8');
include 'FileUtil.class.php';

// FileUtil::createDir('test/create/dir/');
// FileUtil::createFile('test/create/dir/dir_1.php'); 
// FileUtil::createFile('test/create/create_1.php');
// FileUtil::createFile('test/test_1.php');
// FileUtil::createFile('test/create/dir/dir_2.php'); 
// FileUtil::createFile('test/create/create_2.php');

// FileUtil::createDir('test_copy/create/dir/');
// FileUtil::createFile('test_copy/create/dir/dir_2.php'); 
// FileUtil::createFile('test_copy/create/create_2.php');
// FileUtil::createFile('test_copy/create/dir/dir_3.php'); 
// FileUtil::createFile('test_copy/create/create_3.php');

// 将test_copy下的create覆盖test下的create
// 不变 test/create/dir/dir_1.php test/create/create_1.php
// 覆盖 test/create/dir/dir_2.php test/create/create_2.php
// 新建 test_copy/create/dir/dir_3.php test_copy/create/create_3.php
// FileUtil::copyDir('test_copy/create','test/create', true);

// 遍历目录文件
$file_list = FileUtil::scanFile_1('./test_copy');
echo '<pre>';
echo '要更新的文件: <br>';
print_r($file_list);


// $file_list = FileUtil::globFile('./test/', '*');
// print_r($file_list);