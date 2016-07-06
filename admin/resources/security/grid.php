<?php
class dataGrid{
    var $stt = 0;
    private $field_id;
    private $arrayField = array();
    private $arrayHiddenCondition = array();
    private $arrayHiddenHeader = array();
    private $arraySearch = array();
    private $arrayAddSearch = array();
    private $arraySort = array();
    private $arrayLabel = array();
    private $arrayType = array();
    private $arrayExtra = array();
    private $total_row = 0;
    private $page_size = 30;
    private $container = '';
    public function __construct($field_id,$page_size = 30, $ajax_paging_container = ''){
        $this->field_id = $field_id;
        $this->page_size = $page_size;
        $this->container = $ajax_paging_container;
    }
    
    //Các hàm add field để hiển thị trong bảng listing
    function add($field_name,$label,$type = 'string',$search = 0 ,$sort = 0,$extra = ''){
        $this->arrayField[$this->stt] = $field_name;
        $this->arrayLabel[$this->stt] = $label;
        $this->arrayType[$this->stt] = $type;
        $this->arrayExtra[$this->stt] = $extra;
        if($search) $this->arraySearch[$this->stt] = $field_name;
        if($sort) $this->arraySort[$this->stt] = $field_name;
        $this->stt++;
    }

    /**
     * @param $field_name : tên trường cần add vào điều kiện where
     * @param $field_value : giá trị cố định của trường
     * @param string $field_type : kiểu dữ liệu của trường : là string - str hay number - int - interger
     */
    function addHiddenCondition($field_name, $field_value, $field_type = 'int') {
        switch($field_type) {
            case 'string':
            case 'str':
                $field_value = '"'.$field_value.'"';
                break;
            case 'number':
            case 'int':
            case 'interger':
            default:
                $field_value = intval($field_value);
                break;
        }
        $this->arrayHiddenCondition[$field_name] = $field_value;
    }

    /**
     * @param $field_name
     * @param $field_value
     * Thêm 1 hidden input vào grid header
     */
    function addHiddenHeader($field_name,$field_value) {
        $this->arrayHiddenHeader[$field_name] = $field_value;
    }

    /**
     * @param $name
     * @param $field
     * @param $type
     * @param string $value
     * @param string $default
     * ham add them cac truong search
        name : tiêu đề
        field : tên trường
        type : kiểu search
        value : giá trị nếu kiểu array thì truyền vào một array
        default: giá trị mặc định
     *  submit : khi onchange co submit khong
     */
    function addSearch($name,$field,$type,$value = '',$default="", $submit = false){
        $str = '';
        $value = $value ? $value : $default;
        $str .= '&nbsp;' .$name . '&nbsp;';
        switch($type){
            //kiểu array
            case "array":
                $onchange = "";
                if($submit == true) {
                    $onchange   =   " onchange='$(this).closest(\"form\").submit();' ";
                }
                $str .= '<select name="' . $field . '" id="' . $field . '" class="textbox form-control" '.$onchange.'>';
                foreach($value as $id=>$text){
                    $str .= '<option value="' . $id . '" ' . (($default==$id) ? 'selected' : '') . '>' . $text . '</option>';
                }
                $str .= '</select>';
                break;

            //kiểu ngày tháng
            case "date":
                $value = $value ? $value : ($default ? $default : date('d/m/Y'));
                $value = getValue($field,"str","GET",$value);
                $str .= '<input type="text" name="'.$field.'" id="'.$field.'" value="'.$value.'" datepick-element="1" class="form-control">';
                break;

            //kiểu text box
            case "text":
                $value = getValue($field,"str","GET",'Tìm kiếm');
                $str .= '<input type="text" class="form-control" name="' . $field . '" id="' . $field . '" value="' . $value . '">';
                break;
        }
        $this->arrayAddSearch[] = $str;
    }
    function showHeader($total_row,$extra = '',$table_extra = '', $form_action = ''){
        $this->total_row = $total_row;
        $form_action = $form_action ? $form_action : $_SERVER['SCRIPT_NAME'];
        //Hiển thị phần search và header của table
        $header = '<div class="grid_header">';
        if(count($this->arraySearch) || count($this->arrayAddSearch)){
            $header .= '<form name="grid_search" class="form-inline" action="' . $form_action . '" method="get">';
            $header .= form_hidden('search',1);
            //nếu có hidden field khác được khai báo qua hàm addHiddenCondition thì add thêm vào header
            if(count($this->arrayHiddenCondition)) {
                foreach($this->arrayHiddenCondition as $field=>$value) {
                    $header .= form_hidden($field, $value);
                }
            }
            if(count($this->arrayHiddenHeader)) {
                foreach($this->arrayHiddenHeader as $field=>$value) {
                    $header .= form_hidden($field, $value);
                }
            }
            //tạo các input search
            foreach($this->arraySearch as $key=>$value){
                $fieldname = $this->arrayField[$key];
                $strExtra = isset($this->arrayExtra[$key]) ? $this->arrayExtra[$key] : '';
                switch($this->arrayType[$key]){
                    case 'string':
                        //Field lấy ra là string
                        //value lấy ra từ query string
                        $queryValue = getValue($fieldname,'str','GET','');
                        $header .= '<input type="text" class="form-control" name="'.$value.'" placeholder="'.$this->arrayLabel[$key].'" value="'.$queryValue.'" '.$strExtra.' />&nbsp;';
                    break;
                    case 'array':
                        //Field lấy ra là select box
                        global $$fieldname;
                        $header .= '<select name="'.$value.'" '.$strExtra.' class="form-control">';
                        $header .= '<option value="-1">'.$this->arrayLabel[$key].'</option>';
                        $slValue = getValue($fieldname,'str','GET','');
                        foreach($$fieldname as $k=>$v){
                            $selected = ($k == $slValue ? 'selected="selected"' : '');
                            $header .= '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
                        }
                        $header .= '</select>&nbsp;';
                    break;

                }
            }
            //Hiển thị phần custom search
            foreach($this->arrayAddSearch as $value){
                $header .= $value;
            }
            $header .= '<input type="submit" value="Tìm kiếm" class="btn btn-default btn-xs"/>';
            $header .= '</form>';
        }
        $header .= '</div>'.'<script type="text/javascript">if(window.jQuery) {$(document).ready(function(){Grid = Grid || new Grid();});}</script>'.$extra;//Kết thúc phần search
        
        //Bắt đầu hiển thị phần header của table
        $header .= form_open_multipart('quickedit.php',array('name'=>'listing'),array('iQuick'=>'update'));
        $header .= '<div class="table-listing-bound">';
        if(strpos($table_extra,'id=') > -1) {
            $header .= '<table class="table table-bordered table-hover table-listing" '.$table_extra.'>';
        }else {
            $header .= '<table class="table table-bordered table-hover table-listing" id="table-listing" '.$table_extra.'>';
        }
        $header .= '<thead><tr>';
        if(file_exists('quickedit.php')){
            $header .= '<th>ID</th>
                    <th><input type="checkbox" id="check_all" class="check" onclick="Grid.checkall()"/></th>
                    <th><input type="image" src="../img/save.png" style="border:0px;height:16px;cursor:pointer;" onclick="document.listing.submit()"/></th>';
        }else{
            $header .= '<th width="32px;">STT</th>';
        }
        foreach($this->arrayLabel as $key=>$label){
            $c = isset($this->arraySort[$key]) ? $this->urlsort($this->arrayField[$key]) : ''; 
            $header .= '<th><strong>'.$label.'</strong> '.$c.'</th>';
        }
        $header .= '</tr></thead>';
        return $header;
        
    }
    function start_tr($i, $record_id, $add_html = ""){
		$page = getValue("page");
		if($page<1) $page = 1;
		$str = '<tbody><tr id="record_'.$record_id.'" '.$add_html.'>';
        $str .= $this->showId($i, $page);
        if(file_exists('quickedit.php')){
            $str .= $this->showCheck($i, $record_id);
        }

		return $str;
		
	}
	function end_tr(){
		$str = '</tr></tbody>';
		return $str;
	}
    
    private function showId($i, $page){
		$str = '<td width="15" class="center" ><span style="color:#142E62; font-weight:bold">' . ($i+(($page-1)*$this->page_size)) . '</span></td>';
		return $str;
	}
    private function showCheck($i, $record_id){
        $str = '<td width="15" class="center"><input type="checkbox" class="check" name="record_id[]" id="record_' . $i . '" value="' . $record_id . '"></td>';
        $str .= '<td width="15" class="center"><img src="../../resources/img/save.png" style="cursor:pointer;" onclick="document.getElementById(\'record_' . $i . '\').checked = true;document.listing.submit()"/></td>';
        return $str;
    }
    function showCheckbox($field,$value,$record_id){
        $input = form_checkbox($field,1,$value,'onclick="Grid.update_active(\''.$field.'\','.$record_id.')" id="'.$field.'_'.$record_id.'" ');
        $str = '<td width="15" class="center">'.$input.'</td>';
        return $str;
    }
	function showEdit($record_id, $extra = ''){
		return '<td width="10" class="center"><a href="edit.php?record_id=' .  $record_id . '"><i class="fa fa-pencil"></i></a></td>';
	}
	function showDelete($record_id, $extra = ''){
		return '<td width="10" class="center"><a href="#" onclick="Grid.delete_one('.$record_id.');return false;"><i class="fa fa-trash-o"></i></a></td>';
	}
    function showFooter($extra = ''){
        $cols = $this->stt + 3;
        $footer = '<tr class="footer"><td colspan="'.$cols.'">';
        if(file_exists('quickedit.php')){
            $footer .= '&nbsp;<a href="#" onclick="Grid.delete_all('.$this->total_row.');return false;" style="float:left;margin-right:20px;">Delete all selected <i class="icon-remove icon-red"></i></a>';
        }
        $footer .= '<span class="fl nowrap">Hiển thị '. $this->total_row . '/' .$this->total_record .' dòng</span>';
        $footer .= $this->generate_page();
        $footer .= '</td></tr></table></div>';
        $footer .= form_close($extra);
        return $footer;
    }
    function sqlSearch(){
        $search		= getValue("search","int","GET",0);
		$str 			= '';
        //add thêm condition vào câu sql Search khi có hiddenSearch
        if(count($this->arrayHiddenCondition)) {
            foreach($this->arrayHiddenCondition as $key =>$value) {
                $str .= ' AND ' . $key . '=' . $value . ' ';
            }
        }
		if($search == 1){
			foreach($this->arraySearch as $key=>$field){
			
				$keyword		= getValue($field,"str","GET","");
				if($keyword == $this->arrayLabel[$key]) $keyword = "";
				$keyword		= str_replace(" ","%",$keyword);
				$keyword		= str_replace("\'","'",$keyword);
				$keyword		= str_replace("'","''",$keyword);
				switch($this->arrayType[$key]){
					case "string":
						if(trim($keyword)!='') $str 		.= ' AND ' . $field . ' LIKE "%' . $keyword . '%" ';
					break;
					case "array":
						if(intval($keyword)> -1) $str 		.= ' AND ' . $field . '=' . intval($keyword) . ' ';
					break;
				}
			}
		}
		return $str;
    }
    function sqlSort(){
		$sort 		= getValue("sort","str","GET","");
		$field	 	= getValue("sortname","str","GET","");
		$str 			= '';
		if(in_array($field,$this->arraySort) && ($sort == "asc" || $sort == "desc")){
			$str 		= $field . ' ' . $sort . ',';
		}
		return $str;
	}
    function urlsort($field){
		$str 	= '';
		if(in_array($field,$this->arraySort)){

			$url 			= getURL(0,1,1,1,"sort|sortname");
			$sort 		= getValue("sort","str","GET","");
			$sortname 	= getValue("sortname","str","GET","");
			if($sortname!=$field) $sort = "";
			switch($sort){
				case "asc":
					$url 	= $url . '&sort=desc';
				break;
				case "desc":
					$url 	= $url . '&sort=asc';
				break;
                default:
					$url 	= $url . "&sort=asc";
				break;
			}
			$url 	= $url . '&sortname=' . $field;
            if(count($this->arrayHiddenHeader)) {
                foreach($this->arrayHiddenHeader as $k=>$v) {
                    $url .= '&'.$k.'='.$v;
                }
            }
            if(count($this->arrayHiddenCondition)) {
                foreach($this->arrayHiddenCondition as $k=>$v) {
                    $url .= '&'.$k.'='.$v;
                }
            }

			$str = '<a href = "'.$url.'" class="table-sorting"><i class="fa fa-sort-alpha-'.($sort == 'desc' ? 'desc' : 'asc') .'"></i></a>';
		}
		return $str;
	}
    
    function generate_page(){
		$str = '<div class="fr show-all-page">';
		if($this->total_record>$this->page_size){
		
			$total_page 	= $this->total_record/$this->page_size;
            //tính tổng số trang
            if($total_page > intval($total_page)) $total_page = intval($total_page + 1);
			$page			   = getValue("page","int","GET",1);
			if($page<1) $page = 1;
			$str 				.= '<a href="' . getURL(0,1,1,1,"page") . '&page=1" onclick="ajaxPaging(this, \''. $this->container .'\', event)"><i class="fa fa-fast-backward"></i></a>';
			if($page>1) $str 	.= '<a href="' . getURL(0,1,1,1,"page") . '&page=' . ($page-1) .'" onclick="ajaxPaging(this, \''. $this->container .'\' ,event)""><i class="fa fa-step-backward"></i></a>';
			
			$start = $page-1;
			if($start<1) $start = 1;
			
			$end = $page+1;
			if($page<1) $end = $end+(1-$page);

			if($end > $total_page) $end=intval($total_page);
			if($end < $total_page) $end++;
			
			for($i=$start;$i<=$end;$i++){
				$str 			.= '<a href="' . getURL(0,1,1,1,"page") . '&page=' . $i . '" onclick="ajaxPaging(this, \''. $this->container .'\', event)">' . (($i==$page) ? '<span class="s">' . $i . '</span>' : '<span>' . $i . '</span>') . '</a>';
			}
			
			if($page<$total_page) $str 	.= '<a href="' . getURL(0,1,1,1,"page") . '&page=' . ($page+1) .'" onclick="ajaxPaging(this, \''. $this->container .'\', event)"><i class="fa fa-step-forward"></i></a>';
			$str 				.= '<a href="' . getURL(0,1,1,1,"page") . '&page=' . $total_page . '" onclick="ajaxPaging(this, \''. $this->container .'\', event)"><i class="fa fa-fast-forward"></i></a>';
		
		}
		$str .= '</div>';
		return $str;
	}
    
    function limit($total_record){
		$this->total_record = $total_record;
		$page			   = getValue("page","int","GET",1);
		if($page<1) $page = 1;
		$str = "LIMIT " . ($page-1) * $this->page_size . "," . $this->page_size;
		return $str;
	}
    function getPictureThumb($filename,$field_name = ''){
        $str = '';
        if(!$field_name){
            //Không có sửa ảnh
            $str .= '<div style="width:80px;height:80px;margin:0 auto;padding-bottom:5px;"><img src="'.$filename.'" style="width:100%" alt="Không có ảnh"/></div>';
            return $str;
        }
    }
}
?>