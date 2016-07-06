<?php

/**
* Log PHP
* @author <dinhtoan1905@gmail.com>
*/

/**
 * Khai bao cac bien const can thiet cho he thong luu log
 */
define("CURL_SLOW_LOG_TIME", 0.0000000001); // Thoi gian de check log khi su dung curl
define('mysqli_SLOW_LOG_TIME', 0.000000005);
define("CURL_TIMEOUT_CONNECT",1); //don vi tinh bang giay
if(!defined('DEBUG_BACKTRACE_IGNORE_ARGS')) define('DEBUG_BACKTRACE_IGNORE_ARGS',2);

/**
 * Class luu log ra dang file json
 */
class MyLog{
	
	/**
	 * Ham lay moc thoi gian de do toc do xu ly
	 */
	static function microtime_float(){
	   list($usec, $sec) = explode(" ", microtime());
	   return ((float)$usec + (float)$sec);
	}
	
	/**
	 * Ham ghi ra log file
	 */
 	static function checkLogSlow($time_execute, $typeLogSave, $debugInfo){
 		//gan du lieu ra dang array de ghi ra file log
	 	if(!isset($debugInfo[0])){
 			$debugInfo = is_array($debugInfo) ? $debugInfo : array();
 		}else{
 			$debugInfo	= $debugInfo[0];
 		}
 		
 		//gan cac tham so them vao du lieu luu log
 		$debugInfo["timeexecute"] 	= $time_execute;
 		$debugInfo["typelog"] 		= "slowlog";
 		$debugInfo["typeobject"]	= $typeLogSave;
 		$debugInfo["time"] 			= time();
 		
 		switch($typeLogSave){
 			case "curl":
 				if($time_execute > CURL_SLOW_LOG_TIME){
 					self::saveLog("curl_slow.cfn", $debugInfo);
 				}
 			break;
 			case "mysql":
 				if($time_execute > mysqli_SLOW_LOG_TIME){
 					self::saveLog("mysqli_slow.cfn", $debugInfo);
 				}
 			break;
 		}
 		
 	}//end functon checkSlow
 	
 	/**
	 * Ham ghi ra log file
	 */
 	static function checkLogError($msg_error, $typeLogSave, $debugInfo){
 		//gan du lieu ra dang array de ghi ra file log
	 	$debugInfo = self::filterDebug($debugInfo);
	 	
 		//gan cac tham so them vao du lieu luu log
 		$debugInfo["typelog"] 		= "errorlog";
 		$debugInfo["typeobject"]	= $typeLogSave;
 		$debugInfo["msg"]				= $msg_error;
 		$debugInfo["time"] 			= time();
 		if(isset($debugInfo["code"])) $debugInfo["code"] = intval($debugInfo["code"]);
 		self::saveLog("error_log.cfn", $debugInfo);
 		return json_encode($debugInfo);
 		
 	}//end functon checkSlow
 	
 	static function saveLog($fileName, $arrayData){
 		$dirname = dirname(__FILE__);
 		$dirname	= 		str_replace('classes\core',"", $dirname);
 		$dirname	= 		str_replace('classes/core',"", $dirname);
 		$dirname	= 		str_replace('classes',"", $dirname);
 		$dirname .=		"ipstore/";
 		@file_put_contents($dirname . $fileName,json_encode($arrayData) . "\n", FILE_APPEND);
 	}
 	
 	static function cacheFile($fileName, $arrayData){
 		$dirname = dirname(__FILE__);
 		$dirname	= 		str_replace('classes\core',"", $dirname);
 		$dirname	= 		str_replace('classes/core',"", $dirname);
 		$dirname	= 		str_replace('classes',"", $dirname);
 		$dirname .=		"ipstore/";
 		@file_put_contents($dirname . $fileName,json_encode($arrayData));
 	}
 	
 	static function readCache($fileName, $time_cache = 3600){
 		$dirname = dirname(__FILE__);
 		$dirname	= 		str_replace('classes\core',"", $dirname);
 		$dirname	= 		str_replace('classes/core',"", $dirname);
 		$dirname	= 		str_replace('classes',"", $dirname);
 		$dirname .=		"ipstore/";
 		$time_save = filectime($dirname);
 		if(time() > ($time_cache+$time_save)){
			return '';
		}
 		return @file_get_contents($dirname . $fileName);
 	}
 	
 	static function filterDebug($array){
 		$debugInfo = array();
 		$debugInfo["file"] = isset($array["file"]) ? $array["file"] : '';
 		$debugInfo["line"] = isset($array["line"]) ? $array["line"] : '';
 		$debugInfo["query"] = isset($array["query"]) ? $array["query"] : '';
 		$debugInfo["code"] = isset($array["code"]) ? $array["code"] : '';
 		return $debugInfo;
 	}
	
}