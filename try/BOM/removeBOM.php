<?php
$auto = 0;// 0：仅显示是否有BOM;1：显示是否有BOM，并将有BOM的文件改为无DOM
checkdir('.');//当前目录

function checkdir($basedir){
    if ($dh = opendir($basedir)) {
        while (($file = readdir($dh)) !== false) {
            if($file{0} == '.') {
              continue;
            }
            if ($file != '.' && $file != '..'){
                if (!is_dir($basedir."/".$file)) {
                    echo "filename: $basedir/$file ".checkBOM("$basedir/$file")." <br>";
                } else {     
                  $dirname = $basedir."/".$file;
                  checkdir($dirname);
                }
            }
        }
      closedir($dh);
    }
}

function checkBOM ($filename) {
    global $auto;
    $contents = file_get_contents($filename);
    $charset[1] = substr($contents, 0, 1);
    $charset[2] = substr($contents, 1, 1);
    $charset[3] = substr($contents, 2, 1);
    if (ord($charset[1]) == 239 && ord($charset[2]) == 187 && ord($charset[3]) == 191) {
        if ($auto == 1) {
           $rest = substr($contents, 3);
           //rewrite ($filename, $rest);
           file_put_contents($filename, $rest, LOCK_EX);
           return ("<font color=red>BOM found, automatically removed.</font>");
        } else {
          return ("<font color=red>BOM found.</font>");
        }
    }
    else return ("BOM Not Found.");
}