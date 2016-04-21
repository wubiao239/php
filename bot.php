<?php
error_reporting(0);
date_default_timezone_set("UTC");
function get_naps_bot() {
	$useragent = strtolower($_SERVER['HTTP_USER_AGENT']);
	if (strpos($useragent, 'googlebot') !== false) {
		return 'Googlebot';
	}
	if (strpos($useragent, 'msnbot') !== false) {
		return 'MSNbot';
	}
	if (strpos($useragent, 'slurp') !== false) {
		return 'Yahoobot';
	}
	if (strpos($useragent, 'baiduspider') !== false) {
		return 'Baiduspider';
	}
	if (strpos($useragent, 'sohu-search') !== false) {
		return 'Sohubot';
	}
	if (strpos($useragent, 'lycos') !== false) {
		return 'Lycos';
	}
	if (strpos($useragent, 'robozilla') !== false) {
		return 'Robozilla';
	}
	return false;
}

//添加蜘蛛的抓取记录
$searchbot = get_naps_bot();
$date=date("y-m-d h:i:s",time());
if ($searchbot) {
	$content=$searchbot.$date."\r\n";
	$handle = fopen("spider.txt", "a+");
	fwrite($handle, $content);
	fclose($handle);
}
?>