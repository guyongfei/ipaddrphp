<?php 
Class IPQ{
	static $content; //存储数据文件的第二部分
	static $count_ip=array(); //存储ip头字段对应的ip段长度，累加方式存储
	//加载数据文件，读取头1024字节，取出每个头ip对应的ip段数量，存放到$count_ip数组
	//$content变量存放每个ip段对应的MVGeoID数据
	static function load($datfile){
		$handle = fopen($datfile, "rb");
		$head = fread($handle, 1024);
		fseek($handle, 1024);
		$tmpcontent = fread($handle, filesize($datfile)-1024);
		self::$content = unpack("N*", $tmpcontent);
		fclose($handle);

		$arr = unpack("I256", $head);
		$i = 0;
		array_push(self::$count_ip, 0);
		foreach($arr as $item){
			array_push(self::$count_ip, self::$count_ip[$i]+$item);
			$i++;
		}
	}

	//根据输入IP找出对应的MVGeoId
	static function find($ip){
		$nip = ip2long($ip);
		$iparr = explode('.', $ip);
		$firstip = (int)$iparr[0];
		if ($firstip < 0 or $firstip > 255 or count($iparr) != 4){
			return False;
		} 
		$count_ip_first = self::$count_ip[$firstip];
		$count_ip_next = self::$count_ip[$firstip+1];
		$start_index = $count_ip_first * 2+1;
		$end_index = $count_ip_next * 2+1;
		while ($start_index < $end_index){
			if(self::$content[$start_index]>=$nip){
				$val1 = self::$content[$start_index+1];
				$val2 = self::chbo($val1);
				return $val2;
			}
			$start_index = $start_index + 2;
		}
		return False;
	}

	//反序Integer
	static function chbo($num){
		$data = dechex($num);
		if (strlen($data) == 6){
			$data = "00" . $data;
		}
		$u = unpack("H*", strrev(pack("H*", $data)));
		$f = hexdec($u[1]);
		return $f;

	}
}
?>