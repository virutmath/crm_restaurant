<?
require_once 'config_db.php';

class db_init
{
	var $server;
	var $username;
	var $password;
	var $database;

	function __construct()
	{
		// Khai bao Server o day
		$this->server = DB_HOST;
		$this->username = DB_USER;
		$this->password = DB_PASS;
		$this->database = DB_NAME;
	}

	function __destruct()
	{
		unset($this->server);
		unset($this->username);
		unset($this->password);
		unset($this->database);
	}
}

?>
<?

class db_query
{
	var $result;
	var $links;
	var $time_slow_log = 0.5;

	function __construct($query, $file_include_name = "")
	{
		$generate_time = microtime_float();
		$dbinit = new db_init();
		//Khai bao connect
		$this->links = @mysqli_connect($dbinit->server, $dbinit->username, $dbinit->password);
		if (!$this->links) {
			echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
			echo '<meta name="revisit-after" content="1 days">';
			echo "<center>";
			echo "Chào bạn, trang web bạn yêu cầu hiện chưa thể thực hiện được. <br>";
			echo "Xin bạn vui lòng đợi vài giây rồi ấn <b>F5 để Refresh</b> lại trang web <br>";
			echo "</center>";
			exit();
		}
		// chọn cơ sở dữ liệu mặc định cho các truy vấn cơ sở dữ liệu
		$db_select = mysqli_select_db($this->links, $dbinit->database);
		//echo $query;
		$time_start = $this->microtime_float();
		// thực hiện 1 truy vấn trên cơ sở dữ liệu
		mysqli_query($this->links, "SET NAMES 'utf8'");
		// $query: thực hiện 1 câu SQL

		$this->result = mysqli_query($this->links, $query);

		$time_end = $this->microtime_float();
		$time = $time_end - $time_start;

		if ($time >= $this->time_slow_log) {

			//Ghi log o file
			$path = $_SERVER['DOCUMENT_ROOT'] . "/log/slow/";
			$filename = date("Y_m_d_H") . "h.txt";

			//Ghi log o file
			$url = $file_include_name;
			if (file_exists($path . $filename)) {

				$str = file_get_contents($path . $filename);
				$str = number_format($time, 10, ".", ",") . " :  " . $query . chr(13) . chr(13) . $str;
				file_put_contents($path . $filename, "Thoi gian : " . date("H:i") . " : " . $url . "--------------------------------------------->" . chr(13) . number_format($time, 10, ".", ",") . " :  " . $str);

			} else {

				file_put_contents($path . $filename, "Thoi gian : " . date("H:i") . " : " . $url . "--------------------------------------------->" . chr(13) . number_format($time, 10, ".", ",") . " :  " . $query);
				@chmod($path . $filename, 0644);
			}
		}
		if (!$this->result) {
			//ghi ra log loi query
			$path = $_SERVER['DOCUMENT_ROOT'] . "/log/error/";
			$filename = date("Y_m_d_H") . "h.txt";
			$str_error = '';
			$str = '';
			$error = "(" . mysqli_errno($this->links) . ") " . mysqli_error($this->links);

			mysqli_close($this->links);
			if (file_exists($path . $filename)) {
				$str = file_get_contents($path . $filename);

			}
			//khai bao ip vao
			$str_error .= "IP:" . $_SERVER['REMOTE_ADDR'] . "Thoi gian : " . date("H:i") . " " . $_SERVER['REQUEST_URI'] . chr(13);
			//khai bao loi file nao
			$str_error .= "Loi o file : " . $file_include_name . chr(13);
			//khai bao loi gi
			$str_error .= "Loi query : " . $error . chr(13);
			//query loi
			$str_error .= "Database : " . $dbinit->database . chr(13);

			//query loi
			$str_error .= "Query : " . $query . chr(13);

			$str_error .= "//------------------------------------------------------------------------------------------------->";

			$str_error = $str_error . chr(13) . $str;

			@file_put_contents($path . $filename, $str_error);
			@chmod($path . $filename, 0644);
			if ($_SERVER["SERVER_NAME"] == "localhost") die('<p style="font-size:13px;margin:30px auto; width : 900px;
			                font-family:Tahoma;color:#333;padding:24px 15px;background : #f9d386;text-align:center;">
			                <span style="color:#d60c0c;font-weight:bold;text-transform:uppercase;display:block">
			                Error in query string : </span>
			                <br><b style="color:#d60c0c;">ERROR</b> :  ' . $error
				. '<br><b style="color:#d60c0c;">QUERY</b> :' . $query . '</p>');
			error_404_document();
			die();
		}
		unset($dbinit);

	}

	//trả về array
	function resultArray()
	{

		$arrayReturn = array();
		while ($row = mysqli_fetch_assoc($this->result)) {
			$arrayReturn[] = $row;
		}

		return $arrayReturn;
	}

	function close()
	{
		mysqli_free_result($this->result);
		if ($this->links) {
			mysqli_close($this->links);
		}
	}

	//Hàm tính time
	function microtime_float()
	{
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}
}

?>
<?

class db_execute
{
	var $links;
	var $total = 0;

	function __construct($query, $utf8 = 1)
	{
		$dbinit = new db_init();

		$this->links = mysqli_connect($dbinit->server, $dbinit->username, $dbinit->password);
		mysqli_select_db($this->links, $dbinit->database);

		unset($dbinit);
		mysqli_query($this->links, "SET NAMES 'utf8'");
		mysqli_query($this->links, $query);

		//kiểm tra thành công hay chưa
		$str = strtolower(substr(trim($query), 0, 6));
		$this->total = mysqli_affected_rows($this->links);
		mysqli_close($this->links);
	}
}

class db_count
{
	var $total;

	function __construct($sql)
	{
		$db_ex = new db_query($sql);
		if ($row = mysqli_fetch_assoc($db_ex->result)) {
			$this->total = intval($row["count"]);
		} else {
			$this->total = 0;
		}
		$db_ex->close();
		unset($db_ex);
		return $this->total;
	}
}

?>
<?

class db_execute_return
{
	var $links;
	var $result;
	var $total = 0;

	function db_execute($query)
	{
		$dbinit = new db_init();
		$this->links = mysqli_connect($dbinit->server, $dbinit->username, $dbinit->password);
		mysqli_select_db($this->links, $dbinit->database);

		unset($dbinit);
		mysqli_query($this->links, "SET NAMES 'utf8'");
		mysqli_query($this->links, $query);
		$this->total = mysqli_affected_rows($this->links);
		$last_id = 0;
		$this->result = mysqli_query($this->links, "select LAST_INSERT_ID() as last_id");

		if ($row = mysqli_fetch_array($this->result)) {
			$last_id = $row["last_id"];
		}

		mysqli_close($this->links);
		return $last_id;
	}
}

?>