<?php
/**
 * @author ��Ȼ��һ(Sorata) <admin@shipengliang.com>
 * @version v1.2015.11.09-12.11
 * ���ܽ���:���ȡ��ͷ��������ı�
*/
/*˵����php.ini��Ҫ����extension=php_pdo_odbc.dll
*/
header("Content-type: text/html; charset=gb2312");
$eachParagraphContainSentence=5;#ÿ�ΰ�������
$showParagraphs=5;#������ٶ���
$maxLen=300;#�����
$minLen=10;#�������
$explodeStr=".";#��ַָ��
$titleExplode="|";#������ӵķָ���
$turns=$eachParagraphContainSentence-1;
$totalNeedsLine=$showParagraphs*$eachParagraphContainSentence;#һ���ļ���Ҫ���ܾ���
$dbName="SpiderResult.mdb";#php�ļ�ͬĿ¼��access���ݿ��ļ���
$db = new PDO("odbc:driver={microsoft access driver (*.mdb)};dbq=".realpath($dbName))or die("Connect Error");
if(!empty($_GET["id"])){
	$contentArray=array();
	$id=$_GET["id"];
	$preg = "/<\/?[^>]+>/i";#ȥ��html������
	$rs = $db->query('select ����,���� from Content where Id='.$id);
	$result=$rs->fetchAll();
	foreach($result as $k => $v){
		$title=$v[1];
		$content=preg_replace($preg,'',$v[0]);
		$tmp=explode($explodeStr,$content);#�ָ�����
		$tmp=array_filter($tmp);
		foreach($tmp as $k=>$v){
			$len=mb_strlen($v);
			if($len<$minLen || $len>$maxLen){
				unset($tmp[$k]);
			}
		}
		$contentArray=array_merge($contentArray, $tmp);#�ϲ���������
	}
	$outputContent="";
	if(count($contentArray)>1){
		shuffle($contentArray);
		if(count($contentArray)>=$totalNeedsLine){#�������������ȡָ����
			$contentArray=array_splice($contentArray,0,$totalNeedsLine);
		}
		$tmp="";
		$randIn=mt_rand(1,$eachParagraphContainSentence)-1;#�������λ��
		foreach ($contentArray as $key=>$value){
			$turn=$key%$eachParagraphContainSentence;
			if($turn==$randIn){
				$value=$title.$titleExplode.$value;
			}
			if($turn==$turns){#�ָ����
				$tmp.=$value;
				$outputContent.="<p>{$tmp}��</p>";
				$tmp="";
				$randIn=mt_rand(1,$eachParagraphContainSentence)-1;#�������λ��
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
	$rs = $db->query('select Id,���� from Content');
	$result=$rs->fetchAll();
	foreach($result as $k => $v){
		echo "<a href='?id=".$v[0]."'>".$v[1]."</a><br/>";   
	}
}
