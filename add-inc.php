<?php 
	// domain-inc自动添加
	error_reporting(0);
	set_time_limit(3600);
	header("Conten-Type:text/html;charset=utf-8");
	date_default_timezone_set("Asia/Shanghai");
	$start_time = "2016-04-19 23:58:00";	//设置程序开始添加时间
	$end_time = date("Y-m-d H:i:s",strtotime($start_time)+(600));	//自动开始执行10分钟结束
	$reg_date = date("Y-m-d",strtotime($start_time)+(86400*2));

	//***********个人信息配置*************//
	$user_email = "";
	$user_pw = "";
	$domain_name = array("canadiancollege.co.in","gayathrifoundation.in");
	$customer_id = "";
	$contact_id = "";
	//***********************************//

	$url = "http://tools.crusherexporters.com/post-domain.php";

	while (true) {
		if(strtotime($end_time)>=time() && strtotime($start_time)<= time()) {
			foreach ($domain_name as $domain) {
				$data = "user_email={$user_email}&user_pw={$user_pw}&domain_name={$domain}&reg_years=1&reg_date={$reg_date}&customer_id={$customer_id}&contact_id={$contact_id}&contact_submit=立即加入";
				$str = "Adding domain {$domain}".auto_post($url,$data);
				echo $str;
				write_log($str);
			}
			sleep(1);
		}else if(time()>strtotime($end_time)) {
			echo "program has stopped\n";
			write_log("program has stopped");
			break;
		}else {
			echo "Program is running,pending add at ".$start_time."\n";
			write_log("Program is running,pending add");
			sleep(60);
		}
	}

	function auto_post($url,$data) {

		$options = array(
			'http' => array(
				"method" => "POST",
				"content" => $data
			)
		);

		$result = file_get_contents($url,false,stream_context_create($options));

		return $result;
	}

	function write_log($log) {
		$log_name = dirname(__FILE__)."/".date("Y-m-d",time()+3600*2)."_log.log";
		$file = fopen($log_name, "a+");
		fwrite($file, date("Y-m-d H:i:s",time())."\t".$log."\r\n");
		fclose($file);
	}

 ?>