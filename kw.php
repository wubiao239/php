<?php
header("content-type:text/html; charset=utf-8");
echo "开始读取sitemap";
$spdir = "http://www.rewitec.asia/";
//大sitemap路径
$urldir = "http://www.rewitec.asia/\d+/";
//小sitemap路径结构自行修改
$file = file_get_contents($spdir . "sitemap.xml");

preg_match_all("~<sitemap><loc>(.*)</loc></sitemap>~i", $file, $smurl);

$content = "";
$url = "";
$i = 1;
foreach ($smurl[1] as $key => $value) {
    # code...

    $content = file_get_contents($value);
    $url .= $content;
    echo "获取sitemap" . $i . "成功<br>";
    $i++;
}
preg_match_all("~<url><loc>" . $urldir . "(.*).html/</loc></url>~i", $url, $urlword);
echo "打印关键词也可以直接从word.txt获取<br>";
echo "========================================<br>";
foreach ($urlword[1] as $k => $v) {
    $print = str_ireplace("-", " ", $v);

    echo $print . "<br>";
    @$word .= $print . "\n\r";
}
//echo $word;
$fp = fopen("word.txt", 'w');
fwrite($fp, $word);
echo "sitemap下载完成";
?>