<?php
/**
 * @author 了然如一(Sorata) <admin@shipengliang.com>
 * @version v1.2015.11.09-12.11
 * 功能介绍:随机取火车头结果生成文本
*/
/*说明：php.ini需要开启extension=php_pdo_odbc.dll
*/
header("Content-type: text/html; charset=gb2312");
$eachParagraphContainSentence=5;#每段包含几句
$showParagraphs=5;#输出多少段落
$maxLen=300;#最长字数
$minLen=10;#最短字数
$explodeStr=".";#拆分分割符
$titleExplode="|";#标题添加的分隔符
$turns=$eachParagraphContainSentence-1;
$totalNeedsLine=$showParagraphs*$eachParagraphContainSentence;#一个文件需要的总句数
$dbName="SpiderResult.mdb";#php文件同目录的access数据库文件名
$db = new PDO("odbc:driver={microsoft access driver (*.mdb)};dbq=".realpath($dbName))or die("Connect Error");
if(!empty($_GET["id"])){
	$contentArray=array();
	$id=$_GET["id"];
	$preg = "/<\/?[^>]+>/i";#去除html的正则
	$rs = $db->query('select 内容,标题 from Content where Id='.$id);
	$result=$rs->fetchAll();
	foreach($result as $k => $v){
		$title=$v[1];
		$content=preg_replace($preg,'',$v[0]);
		$tmp=explode($explodeStr,$content);#分割数组
		$tmp=array_filter($tmp);
		foreach($tmp as $k=>$v){
			$len=mb_strlen($v);
			if($len<$minLen || $len>$maxLen){
				unset($tmp[$k]);
			}
		}
		$contentArray=array_merge($contentArray, $tmp);#合并到总数组
	}
	$outputContent="";
	if(count($contentArray)>1){
		shuffle($contentArray);
		if(count($contentArray)>=$totalNeedsLine){#总数超出则随机取指定句
			$contentArray=array_splice($contentArray,0,$totalNeedsLine);
		}
		$tmp="";
		$randIn=mt_rand(1,$eachParagraphContainSentence)-1;#随机插入位置
		foreach ($contentArray as $key=>$value){
			$turn=$key%$eachParagraphContainSentence;
			if($turn==$randIn){
				$value=$title.$titleExplode.$value;
			}
			if($turn==$turns){#分割段落
				$tmp.=$value;
				$outputContent.="<p>{$tmp}。</p>";
				$tmp="";
				$randIn=mt_rand(1,$eachParagraphContainSentence)-1;#随机插入位置
			}else{
				$tmp.=$value.$explodeStr;
			}
		}
		if(substr($outputContent,-4)!="</p>"){$outputContent.=$explodeStr."</p>";}
	}else{
		$outputContent="<p>{$content}{$explodeStr}</p>";
	}
	echo "<h1>".$title."</h1>";
	echo "<div>".$outputContent."</div>";
}else{
	$rs = $db->query('select Id,标题 from Content');
	$result=$rs->fetchAll();
	foreach($result as $k => $v){
		echo "<a href='?id=".$v[0]."'>".$v[1]."</a><br/>";   
	}
}
