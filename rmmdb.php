<?php
header("Content-Type: text/html; charset=UTF-8");

$fpath = "H:\\xampp\\htdocs\\";
//环境安装目录
$site = glob($fpath . "*");
//print_r($site);
foreach ($site as $key => $value) {
    $file = $value . "\\SpiderResult.mdb";
    echo $file."\n\r";

    if(file_exists($file)) {

        $result = @unlink($file);
        if ($result == false) {
            echo "unlink sucess";
        } else {
            echo "unlink fail";
        }
    }else{
        echo "file not exists";
    }

}

?>