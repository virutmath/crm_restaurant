<?
/*
class generate form
Created by FinalStyle.com
*/
class generate_form{
	var $array_data_field = array();
	var $array_data_value;
	var $array_data_type;
	var $array_data_store;
	var $array_data_default_value;
	var $array_data_require;
	var $array_data_error_message;
	var $array_data_unique;
	var $array_data_error_message2;
	var $number_of_field			= -1;
	var $table_name				    = "";
	var $record_field				= "";
	var $record_value				= "";
	var $removeHTML				    = 1;
	var $form_name					= "";
	var $java_code_add_on		    = "";
	var $strJavascript			    = "";
	var $strErrorField			    = "";
	var $border_form_ok			    = "solid 1px #00FF00";
	var $border_form_not			= "solid 1px #FF0000";
	var $jAlert						= false;

	/**
	*1). $data_field			     : Ten truong
	*2). $data_value			     : Ten form
	*3). $data_type				 : Kieu du lieu , 0 : string , 1 : kieu int, 2 : kieu email, 3 : kieu double, 4 : kieu hash password
	*4). $data_store			     : Noi luu giu data  0 : post, 1 : variable
	*5). $data_default_value	     : Gia tri mac dinh, neu require thi phai lon hon hoac bang default
	*6). $data_require			 : Du lieu nay co can thiet hay khong
	*7). $data_error_message	     : Loi dua ra man hinh
	*8). $data_unique			 : Chi co duy nhat trong database
	*9). $data_error_message2     : Loi dua ra man hinh neu co duplicate
	*10). $type_form: kiểu form   : 1 text ; 2 textarea; 3 kiểu checkbook
	*/
	function add($data_field, $data_value, $data_type, $data_store, $data_default_value, $data_require=0, $data_error_message="", $data_unique=0, $data_error_message2="",$type_form = 0){
		/*
		kiểm tra xem nếu add 2 lần thì đưa ra thông báo luôn
		*/

		if(in_array($data_field,$this->array_data_field)) $this->strErrorField .= "&bull; add 2 lần trường <strong>" . $data_field . "</strong> <br>";

		$this->number_of_field++;
		$this->array_data_field[$this->number_of_field]				= $data_field;
		$this->array_data_value[$this->number_of_field]				= $data_value;
		$this->array_data_type[$this->number_of_field]				= $data_type;
		$this->array_data_store[$this->number_of_field]				= $data_store;
		$this->array_data_default_value[$this->number_of_field]	    = $data_default_value;
		$this->array_data_require[$data_field]						= $data_require;
		$this->array_data_error_message[$this->number_of_field]	    = $data_error_message;
		$this->array_data_unique[$this->number_of_field]			= $data_unique;
		$this->array_data_error_message2[$this->number_of_field] = $data_error_message2;
		if($data_require == 1){
			$this->strJavascript 									.= $this->checkTypeData($this->number_of_field);
		}
	}

	/*
	table_name : Ten bang
	*/
	function addTable($table_name){
		$this->table_name = $table_name;

		$arrayField = array_flip($this->array_data_field);
		$arrayFieldIntable = array();

		//kiem tra xem cac truong co ton tai trong bang chua
		$db_field = new db_query("SHOW FIELDS FROM " . $this->table_name);
			while($row = mysqli_fetch_array($db_field->result)){
				$arrayFieldIntable[$row["Field"]] = 0;
			}
		foreach($arrayField as $key=>$value){
			if(!array_key_exists($key,$arrayFieldIntable)) $this->strErrorField .= "&bull; không tồn tại trường <strong>" . $key . "</strong> trong bảng <strong>" . $this->table_name . "</strong> <br>";
		}
		unset($db_field);
		unset($row);
		unset($arrayField);
		unset($arrayFieldIntable);

	}

	/*
	form_name : Ten Form
	*/
	function addFormname($form_name){
		$this->form_name = $form_name;
	}

	/*
	Su dung khi update data
	record_field : Ten truong can edit
	record_value : Gia tri
	*/
	function addRecordID($record_field, $record_value){
		$this->record_field = $record_field;
		$this->record_value = $record_value;
	}

	/*
	Remove HTML truoc khi add vao database
	value  0: Not Remove, 1 : Remove
	*/
	function removeHTML($value){
		$this->removeHTML = $value;
	}

	/*
	Remove HTML truoc khi add vao database
	*/
	function htmlspecialbo($str){
		$arrDenied	= array('<', '>', '"');
		$arrReplace	= array('&lt;', '&gt;', '&quot;');
		$str = str_replace($arrDenied, $arrReplace, $str);
		return $str;
	}

	/*
	Generate Insert SQL
	*/
	function generate_insert_SQL($multi=0, $replace_into = false, $ignore = false){

		$str_field	= "(";
		$str_data	= "(";
		for($i=0; $i<=$this->number_of_field; $i++){
			$str_field .= $this->array_data_field[$i] . ",";
			//gan bien temp = gia tri mac dinh
			$temp = $this->array_data_default_value[$i];

			//Read from method POST
			if($this->array_data_store[$i] == 0){
				if (isset($_POST[$this->array_data_value[$i]])) $temp = $_POST[$this->array_data_value[$i]];
			}
			//Read from global variable
			else{
				$temp_var = $this->array_data_value[$i];
				global $$temp_var;
				if(isset($$temp_var)){
					$temp = $$temp_var;
				}
			}
			//remove quote;
			$temp = str_replace("\'", "'", $temp);
			$temp = str_replace("'", "''", $temp);

			//Lưu biến raw_temp để phục vụ những chỗ ko cần HTML specialbo
			$raw_temp = $temp;

			//Remove HTML tag if removeHTML = 1
			if($this->removeHTML == 1) $temp = $this->htmlspecialbo($temp);

			switch($this->array_data_type[$i]){
				case "0": $str_data .= "'" . $temp . "',"; break;
				case "1": $str_data .= intval($temp) . ","; break;
				case "2": $str_data .= "'" . $temp . "',"; break;
				case "3": $str_data .= doubleval($temp) . ","; break;
				case "4": $str_data .= "'" . md5($temp) . "',"; break;
				//Kiểu có new line -> chuyển xuống dòng thành <br>
				case "5": $str_data .= "'" . nl2br($temp) . "',"; break;
				//Kiểu ko remove HTML tag
				case "6": $str_data .= "'" . $raw_temp . "',"; break;
			}
		}

		//$str_field	= substr($str_field, 0, strlen($str_field)-1) . ")";
		//$str_data	= substr($str_data, 0, strlen($str_data)-1) . ")";
		//nếu tồn tại bến lang_id
		$str_field	= substr($str_field, 0, strlen($str_field)-1) . ")";
		$str_data	= substr($str_data, 0, strlen($str_data)-1) . ")";

		if($multi==1){
			return $str_data;
		}else{
			$querystr	= (($replace_into) ? "REPLACE " : "INSERT ") . (($ignore) ? "IGNORE" : "") . " INTO " . $this->table_name . $str_field . " VALUES " . $str_data;
			return $querystr;
		}

	}

	/*
	Generate Insert SQL nhiều record
	*/
	function generate_insert_multi($table,$sql){
		$str_field	= "(";
		for($i=0; $i<=$this->number_of_field; $i++){
			$str_field .= $this->array_data_field[$i] . (($i<$this->number_of_field) ? "," : '');
		}
		$str_field .= ')';
		$querystr	= "INSERT INTO " . $table . $str_field . " VALUES " . $sql;
		return $querystr;
	}
	/*
	Generate Update SQL
	update_field_name : Truong can update vi du : cat_id
	update_field_value : Gia tri can update vi du : 10
	*/
	function generate_update_SQL($update_field_name, $update_field_value){
		$str_field	= "(";
		$str_data	= "(";
		$querystr	= "";
		for($i=0; $i<=$this->number_of_field; $i++){
			$str_field = $this->array_data_field[$i] . "=";
			//gan bien temp = gia tri mac dinh
			$temp = $this->array_data_default_value[$i];

			//Read from method POST
			if($this->array_data_store[$i]==0){
				if(isset($_POST[$this->array_data_value[$i]])) $temp = $_POST[$this->array_data_value[$i]];
			}
			//Read from global variable
			else{
				$temp_var = $this->array_data_value[$i];
				global $$temp_var;
				$temp = $$temp_var;
			}
			//remove quote;
			$temp = str_replace("\'","'",$temp);
			$temp = str_replace("'","''",$temp);

			//Lưu biến raw_temp để phục vụ những chỗ ko cần HTML specialbo
			$raw_temp = $temp;

			//Remove HTML tag if removeHTML = 1
			if($this->removeHTML == 1) $temp = $this->htmlspecialbo($temp);

			switch ($this->array_data_type[$i]){
				case "0": $str_data = "'" . $temp . "',"; break;
				case "1": $str_data = intval($temp) . ","; break;
				case "2": $str_data = "'" . $temp . "',"; break;
				case "3": $str_data = doubleval($temp) . ","; break;
				case "4": $str_data = "'" . md5($temp) . "',"; break;
				//Kiểu có new line -> chuyển xuống dòng thành <br>
				case "5": $str_data = "'" . nl2br($temp) . "',"; break;
				//Kiểu ko remove HTML tag
				case "6": $str_data = "'" . $raw_temp . "',"; break;
			}
			$querystr .=  $str_field . $str_data;
		}

		$querystr = substr($querystr, 0, strlen($querystr)-1);
		$querystr = "UPDATE " . $this->table_name . " SET " . $querystr . " WHERE " . $update_field_name . " = " . $update_field_value . " LIMIT 1";

		return $querystr;
	}

	/*
	Add them ma Javascript vao check
	*/
	function addjavasrciptcode($java_code_add_on){
		$this->strJavascript  .= $java_code_add_on;
	}

	/*
	Kiem tra javascript
	*/
	function checkjavascript(){
		echo "<script language='javascript'>";
		echo "function trim(sString){
					while(sString.substring(0,1) == ' '){
						sString = sString.substring(1, sString.length);
					}
					while(sString.substring(sString.length-1, sString.length) == ' '){
						sString = sString.substring(0,sString.length-1);
					}
					return sString;
				}
				function checkblank(str){
					if(trim(str) == '') return true;
					else return false;
				}
				function isemail(email) {
					var re = /^(\w|[^_]\.[^_]|[\-])+(([^_])(\@){1}([^_]))(([a-z]|[\d]|[_]|[\-])+|([^_]\.[^_])*)+\.[a-z]{2,3}$/i
					return re.test(email);
				}
		      function validateForm(){
			   ";
					echo $this->strJavascript;
					echo "document." . $this->form_name . ".submit();
	    		}
		";
		echo "</script>";
	}

	/*
	Kiem  tra kiểu dữ liệu bằng javascript
	*/
	function checkTypeData($i){
		$strreturn = '';
		$str_alert	= "alert('" . htmlspecialchars($this->array_data_error_message[$i]) . "');";
		if($this->jAlert){
			$str_alert	= '$("#errMsg").html(""); $("#errMsg").jAlert("'. htmlspecialchars($this->array_data_error_message[$i]) . '", "fatal", "' . $this->array_data_value[$i] . '");';
		}

		switch($this->array_data_type[$i]){
			//String
			case 0:
			case 5:
			case 6:
				$strreturn = "if (checkblank(document.getElementById(\"" . $this->array_data_value[$i] . "\").value)) { " . $str_alert . " document.getElementById(\"" . $this->array_data_value[$i] . "\").style.border='" . $this->border_form_not . "'; document.getElementById(\"" . $this->array_data_value[$i] . "\").focus(); return false;}else{ document.getElementById(\"" . $this->array_data_value[$i] . "\").style.border='" . $this->border_form_ok . "'; }";
				break;
			//Integer
			case 1:
				$strreturn = "if (checkblank(document.getElementById(\"" . $this->array_data_value[$i] . "\").value)) { " . $str_alert . "  document.getElementById(\"" . $this->array_data_value[$i] . "\").style.border='" . $this->border_form_not . "'; document.getElementById(\"" . $this->array_data_value[$i] . "\").focus(); return false;}else{ document.getElementById(\"" . $this->array_data_value[$i] . "\").style.border='" . $this->border_form_ok . "'; }";
				$strreturn .= "if (isNaN(document.getElementById(\"" . $this->array_data_value[$i] . "\").value)) { " . $str_alert . "  document.getElementById(\"" . $this->array_data_value[$i] . "\").style.border='" . $this->border_form_not . "'; document.getElementById(\"" . $this->array_data_value[$i] . "\").focus(); return false;}else{ document.getElementById(\"" . $this->array_data_value[$i] . "\").style.border='" . $this->border_form_ok . "'; }";
				break;
			//Email
			case 2:
				$strreturn = "if (!isemail(document.getElementById(\"" . $this->array_data_value[$i] . "\").value)) { " . $str_alert . "  document.getElementById(\"" . $this->array_data_value[$i] . "\").style.border='" . $this->border_form_not . "'; document.getElementById(\"" . $this->array_data_value[$i] . "\").focus(); return false;}else{ document.getElementById(\"" . $this->array_data_value[$i] . "\").style.border='" . $this->border_form_ok . "'; }";
				break;
			//Double
			case 3:
				$strreturn = "if (checkblank(document.getElementById(\"" . $this->array_data_value[$i] . "\").value)) { " . $str_alert . "  document.getElementById(\"" . $this->array_data_value[$i] . "\").style.border='" . $this->border_form_not . "'; document.getElementById(\"" . $this->array_data_value[$i] . "\").focus(); return false;}else{ document.getElementById(\"" . $this->array_data_value[$i] . "\").style.border='" . $this->border_form_ok . "'; }";
				$strreturn .= "if (isNaN(document.getElementById(\"" . $this->array_data_value[$i] . "\").value)) { " . $str_alert . "  document.getElementById(\"" . $this->array_data_value[$i] . "\").style.border='" . $this->border_form_not . "'; document.getElementById(\"" . $this->array_data_value[$i] . "\").focus(); return false;}else{ document.getElementById(\"" . $this->array_data_value[$i] . "\").style.border='" . $this->border_form_ok . "'; }";
				break;
			//Password hash
			case 4:
				$strreturn = "if (checkblank(document.getElementById(\"" . $this->array_data_value[$i] . "\").value)) { " . $str_alert . "  document.getElementById(\"" . $this->array_data_value[$i] . "\").style.border='" . $this->border_form_not . "'; document.getElementById(\"" . $this->array_data_value[$i] . "\").focus(); return false;}else{ document.getElementById(\"" . $this->array_data_value[$i] . "\").style.border='" . $this->border_form_ok . "'; }";
				break;
		}
		return $strreturn;
	}

	function checkTypeform($id,$type,$message){
		$strreturn = '';
		$str_alert	= "alert('" . htmlspecialchars($message) . "');";
		if($this->jAlert){
			$str_alert	= '$("#errMsg").jAlert("'. htmlspecialchars($message) . '", "fatal", "' . $id . '");';
		}

		switch($type){
			//String
			case 0:
			case 5:
			case 6:
				$strreturn = "if (checkblank(document.getElementById(\"" . $id . "\").value)) {   document.getElementById(\"" . $id . "\").style.border='" . $this->border_form_not . "'; document.getElementById(\"" . $id . "\").focus(); return false;}else{ document.getElementById(\"" . $id . "\").style.border='" . $this->border_form_ok . "'; }";
				break;
			//Integer
			case 1:
				$strreturn = "if (checkblank(document.getElementById(\"" . $id . "\").value)) { " . $str_alert . "  document.getElementById(\"" . $id . "\").style.border='" . $this->border_form_not . "'; document.getElementById(\"" . $id . "\").focus(); return false;}else{ document.getElementById(\"" . $id . "\").style.border='" . $this->border_form_ok . "'; }";
				$strreturn .= "if (isNaN(document.getElementById(\"" . $id . "\").value)) { " . $str_alert . "  document.getElementById(\"" . $id . "\").style.border='" . $this->border_form_not . "'; document.getElementById(\"" . $id . "\").focus(); return false;}else{ document.getElementById(\"" . $id . "\").style.border='" . $this->border_form_ok . "'; }";
				break;
			//Email
			case 2:
				$strreturn = "if (!isemail(document.getElementById(\"" . $id . "\").value)) { " . $str_alert . "  document.getElementById(\"" . $id . "\").style.border='" . $this->border_form_not . "'; document.getElementById(\"" . $id . "\").focus(); return false;}else{ document.getElementById(\"" . $id . "\").style.border='" . $this->border_form_ok . "'; }";
				break;
			//Double
			case 3:
				$strreturn = "if (checkblank(document.getElementById(\"" . $id . "\").value)) { " . $str_alert . "  document.getElementById(\"" . $id . "\").style.border='" . $this->border_form_not . "'; document.getElementById(\"" . $id . "\").focus(); return false;}else{ document.getElementById(\"" . $id . "\").style.border='" . $this->border_form_ok . "'; }";
				$strreturn .= "if (isNaN(document.getElementById(\"" . $id . "\").value)) { " . $str_alert . "  document.getElementById(\"" . $id . "\").style.border='" . $this->border_form_not . "'; document.getElementById(\"" . $id . "\").focus(); return false;}else{ document.getElementById(\"" . $id . "\").style.border='" . $this->border_form_ok . "'; }";
				break;
			//Password hash
			case 4:
				$strreturn = "if (checkblank(document.getElementById(\"" . $id . "\").value)) { " . $str_alert . "  document.getElementById(\"" . $id . "\").style.border='" . $this->border_form_not . "'; document.getElementById(\"" . $id . "\").focus(); return false;}else{ document.getElementById(\"" . $id . "\").style.border='" . $this->border_form_ok . "'; }";
				break;
		}
		$this->strJavascript  .= $strreturn;
		return $strreturn;
	}
		/*
	Kiem tra data
	*/
	function checkdata($id_field="", $id_value=0){
		$errormsg = "";
		for($i=0; $i<=$this->number_of_field; $i++){
			//lay gia tri tu post va`o bien temp
			$temp = "";
			//Read from method POST
			if($this->array_data_store[$i] == 0){
				if(isset($_POST[$this->array_data_value[$i]])) $temp = $_POST[$this->array_data_value[$i]];
			}
			//Read from global variable
			else{
				$temp_var = $this->array_data_value[$i];
				global $$temp_var;
				$temp = $$temp_var;
			}
			//neu data_require la 1
			if($this->array_data_require[$this->array_data_field[$i]] == 1){
				switch($this->array_data_type[$i]){
					//string
					case 0:
					case 5:
					case 6:
						//neu temp = rong -> show error
						if($temp == "" || strlen($temp) < strlen($this->array_data_default_value[$i])) $errormsg .= "&bull; " . $this->array_data_error_message[$i] . "<br />";
						break;
					case 1:
						//neu temp ko phai kieu int -> error
						if(intval($temp) < intval($this->array_data_default_value[$i])) $errormsg .= "&bull; " . $this->array_data_error_message[$i] . "<br />";
						break;
					case 2:
						//neu temp ko phai kieu email -> error
						$result = ereg("^[^@ ]+@[^@ ]+\.[^@ ]+$", $temp, $trashed);
						if(!$result) $errormsg .= "&bull; " . $this->array_data_error_message[$i] . "<br />";
						break;
					case 3:
						//neu temp ko phai kieu dbl -> error
						if(doubleval($temp) < doubleval($this->array_data_default_value[$i])) $errormsg .= "&bull; " . $this->array_data_error_message[$i] . "<br />";
						break;
						//password hash
					case 4:
						//neu temp = rong -> show error
						if($temp == "" || strlen($temp) < strlen($this->array_data_default_value[$i])) $errormsg .= "&bull; " . $this->array_data_error_message[$i] . "<br />";
						break;
				}
			}
			//Remove quote
			$temp = str_replace("\'", "'", $temp);
			$temp = str_replace("'", "''", $temp);
			//neu data_unique = 1 (duy nhat trong database)
			if($this->array_data_unique[$i] == 1){
				$add_sql = "";
				if($id_field != "" && $id_value > 0){
					$add_sql = " AND " . $id_field . " <> " . intval($id_value);
				}
				$db_select = new db_query("SELECT " . $this->array_data_field[$i] . "
													FROM " . $this->table_name . "
													WHERE " . $this->array_data_field[$i] . " = '" . $temp . "' " . $add_sql . " LIMIT 1");
				if(mysqli_num_rows($db_select->result) > 0){
					$errormsg .= "&bull; " . $this->array_data_error_message2[$i] . "<br />";
				}
			}
		}
		return $errormsg;
	}

	/*
	evaluate variable for email
	*/
	function evaluate(){
		for ($i=0;$i<=$this->number_of_field;$i++){
			//echo $this->array_data_value[$i] . "<br>";
			$temp = $this->array_data_value[$i];
			global $$temp;
			if ($this->array_data_store[$i]==0){
				if (isset($_POST[$temp])) $$temp = $_POST[$temp];
				else $$temp = $this->array_data_default_value[$i];
			}
		}
	}
	/*
	phần chèn vào trường search
	created by dinhtoan1905
	1: trường tìm kiếm (pro_search)
	2: các trường để tìm kiếm (pro_name,pro_teaser) gồm một array key là tên truờng, value là lấy từ post hay từ biến
	3: giá trị thêm vào (cat_name)
	//*/
	function add_Field_Seach($field_name,$arrayField = array()){
		$temp = '';
		if(count($arrayField)>0){
			foreach($arrayField as $key=>$value){
				if(intval($value) == 0){
					if(isset($_POST[$key])){
						$temp .= $_POST[$key] . ' ';
						//echo $_POST[$key];
					}
				}
				if(intval($value) == 1){
					$temp_var = $key;
					global $$temp_var;
					$temp .= $$temp_var . ' ';
				}
			}//end for
		}// end if
		//echo $temp;
		//Remove quote
		$temp 	= 	str_replace("\'", "'", $temp);
		$temp 	= 	str_replace("'", "''", $temp);
		$temp 	= 	$this->removeTagHtml($temp);
		$temp		=	mb_strtolower($temp,"UTF-8");
		$temp		= str_replace(chr(13),"",$temp);//bo dau tab
		$temp		= str_replace("  "," ",$temp);//bo dau tab
		$temp		= str_replace("  "," ",$temp);//bo dau tab
		$temp		= str_replace("  "," ",$temp);//bo dau tab
		$temp		= str_replace("  "," ",$temp);//bo dau tab
		$temp		= str_replace("  "," ",$temp);//bo dau tab
		$temp		= str_replace("  "," ",$temp);//bo dau ta
		$temp		=  $temp . ' ' . removeAccent($temp);


		$this->number_of_field++;
		$this->array_data_field[$this->number_of_field]				= $field_name;
		$this->array_data_value[$this->number_of_field]				= 'not from post';
		$this->array_data_type[$this->number_of_field]				= 0;
		$this->array_data_store[$this->number_of_field]				= 0;
		$this->array_data_default_value[$this->number_of_field]	= $temp;
		$this->array_data_require[$field_name]							= 0;
		$this->array_data_error_message[$this->number_of_field]	= "";
		$this->array_data_unique[$this->number_of_field]			= 0;
		$this->array_data_error_message2[$this->number_of_field] = "";
		unset($temp);

	}

	//loại bỏ các thẻ html
	function removeTagHtml($string){
		$string = preg_replace ('/<script.*?\>.*?<\/script>/si', ' ', $string);
		$string = preg_replace ('/<style.*?\>.*?<\/style>/si', ' ', $string);
		$string = preg_replace ('/<.*?\>/si', ' ', $string);
		$string = str_replace ('&nbsp;', ' ', $string);
		$string = html_entity_decode ($string);
		return $string;
	}
	/*
	gennerate form submit
	*/

	/*
	debug
	*/
	function debug(){
		echo "<font face='Tahoma, Verdana, Arial' style='font-size:12px'>";
		echo "<br />------------ Start debug ------------<br />";
		for($i=0; $i<=$this->number_of_field; $i++){
			$data_store = "";
			if($this->array_data_store[$i] == 0) $data_store = "Method POST";
			elseif($this->array_data_store[$i] == 1) $data_store = "Variable";
			echo " - Variable: <b>" . ($i+1) . "</b><br />";
			echo "&nbsp;&nbsp;&nbsp;&nbsp; + Data field: <b>" . $this->array_data_field[$i] . "</b><br />";
			echo "&nbsp;&nbsp;&nbsp;&nbsp; + Data store: <b>" . $data_store . "</b><br />";
		}
		echo "------------ End debug ------------<br />";
		echo "</font>";
	}

	/*
	hàm tích hợp tooltip help
	created by dinhtoan1905
	*/
	function tooltiphelp(){
		$strreturn = '<link rel="stylesheet" type="text/css" href="/js/tooltip/balloontip.css">';
		$strreturn .= '<script language="javascript" src="/js/tooltip/balloontip.js"></script>';
		foreach($this->array_data_value as $key=>$value){
			$strreturn .= '<div id="' . $value . '_help" class="balloonstyle">' . help($value) . '</div>';
		}
		echo $strreturn;
	}

	/*
	ham copy record
	created by dinhtoan1905
	*/
	function copyRecord($tableName,$field_id,$field_name,$record_id,$key='',$listFieldFile = '',$filePath = ''){

			$arrayFile 		= explode(",",$listFieldFile);
			$strupdate 		= '';
			global $medium_width;
			global $medium_heght;
			global $medium2_heght;
			global $medium2_width;
			global $small_width;
			global $small_heght;
			if($listFieldFile!=''){

				$db_file 		= new db_query("SELECT " . $listFieldFile ." FROM " . $tableName . " WHERE " . $field_id . "=" . $record_id . " LIMIT 1");
				if($row = mysqli_fetch_array($db_file->result)){
					foreach($arrayFile as $key=>$value){
						if($row[$value]!=''){
							if(file_exists($filePath . $row[$value])){
								$newfile = generate_name($row[$value]);
								@copy($filePath . $row[$value],$filePath . $newfile);
								@copy($filePath . 'small_' . $row[$value],$filePath . 'small_' . $newfile);
								@copy($filePath . 'medium_' . $row[$value],$filePath . 'medium_' . $newfile);
								@copy($filePath . 'medium2_' . $row[$value],$filePath . 'medium2_' . $newfile);
								$strupdate .= "," . $value . " = '" . $newfile . "'";
							}
						}
					}
				}
			}
			$db_record		=	new db_execute("DROP TABLE IF EXISTS temp_" . $tableName);
			$db_record		=	new db_execute("CREATE table temp_" . $tableName . " SELECT * FROM " . $tableName . " WHERE " . $field_id . " = " . $record_id);
			if($key!=''){
				$db_record		=	new db_execute("update temp_" . $tableName . " SET " . $key . " = (select MAX(" . $key . ") FROM " . $tableName . ")+1 WHERE " . $field_id . " = " . $record_id);
			}
			$db_record		=	new db_query("SELECT MAX(" . $field_id . ") AS idmax FROM " . $tableName);
			$newid			=	0;
			if($row=mysqli_fetch_assoc($db_record->result)){
				$newid		=	intval($row["idmax"])+1;
			}
			$reFieldName	=	($field_name!='') ? "," . $field_name . " = CONCAT(''," . $field_name . ")" : '';
			$reFieldName	.= $strupdate;
			$db_record		=	new db_query("UPDATE temp_" . $tableName . " SET " . $field_id . "=" . $newid . $reFieldName . " WHERE " . $field_id . "=" . $record_id);
			$db_record		=	new db_execute("INSERT INTO " . $tableName . " SELECT * FROM temp_" . $tableName . " WHERE " . $field_id . " = " . $newid);
			$db_record		=	new db_execute("DROP TABLE IF EXISTS temp_" . $tableName);
			unset($db_record);
		return 	$newid;
	}
}
?>