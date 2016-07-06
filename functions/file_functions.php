<?
function checkExtension($filename, $allowList){
	$sExtension = $filename;
	$allowArray	= explode(",", $allowList);
	$allowPass	= 0;
	for($i=0; $i<count($allowArray); $i++){
		if($sExtension == $allowArray[$i]) $allowPass = 1;
	}
	return $allowPass;
}

function check_upload_extension($filename,$allow_list){
	$sExtension	= getExtension($filename);
	$allow_arr	= explode(",", $allow_list);
	$pass = 0;
	for($i=0; $i<count($allow_arr); $i++){
		if($sExtension == $allow_arr[$i]) $pass = 1;
	}
	return $pass;
}

function delete_file($table_name,$id_field,$id_field_value,$field_select,$ff_imagepath){
	$db_select = new db_query("SELECT " . $field_select . " " .
									"FROM " . $table_name . " " .
									"WHERE " . $id_field . "=" . $id_field_value
									);
	while($row=mysqli_fetch_array($db_select->result)){
		if(file_exists($ff_imagepath . $row[$field_select])) @unlink($ff_imagepath . $row[$field_select]);
		if(file_exists($ff_imagepath . "tiny_" . $row[$field_select])) @unlink($ff_imagepath . "tiny_" . $row[$field_select]);
		if(file_exists($ff_imagepath . "small_" . $row[$field_select])) @unlink($ff_imagepath . "small_" . $row[$field_select]);
		if(file_exists($ff_imagepath . "ssmall_" . $row[$field_select])) @unlink($ff_imagepath . "ssmall_" . $row[$field_select]);
		if(file_exists($ff_imagepath . "medium_" . $row[$field_select])) @unlink($ff_imagepath . "medium_" . $row[$field_select]);
	}	
	unset($db_select);
	$db_ex = new db_execute("UPDATE " . $table_name . " SET " . $field_select . " = null WHERE " . $id_field . "=" . $id_field_value);
	unset($db_ex);					
}

function generate_name($filename = ''){
    if($filename){
        $filename   =  explode("?", $filename);
        $filename   =  $filename[0];
        $name = "";
        for($i=0; $i<5; $i++){
            $name .= chr(rand(97,122));
        }
        $name.= time();

        $ext	= substr($filename, (strrpos($filename, ".") + 1));
        return $name . "." . $ext;
    }else{
        $name = "";
        for($i=0; $i<5; $i++){
            $name .= chr(rand(97,122));
        }
        $name.= time();
        return $name;
    }
}

function get_link_resize_image($folder_image,$image_name, $size_type = '450') {

    $time = (int)preg_replace('/[^0-9]*/','',$image_name);
    $time = getdate($time);
    
    return '/pictures/'.$folder_image.'/'.$time['year'].'/'.$time['mon'].'/'. $size_type . '/' .$image_name;
}

function get_folder_path_resize_image($folder_image,$image_name, $size_type = '450') {

    $time = (int)preg_replace('/[^0-9]*/','',$image_name);
    $time = getdate($time);
    $folder_path = '/pictures/'.$folder_image.'/'.$time['year'].'/'.$time['mon'].'/'. $size_type . '/';
    if(!file_exists('..'.$folder_path)){
        mkdir('..'.$folder_path,755,true);
    }
    return $folder_path;
}

function getExtension($filename){
	$sExtension = substr($filename, (strrpos($filename, ".") + 1));
	$sExtension = strtolower($sExtension);
	return $sExtension;
}
function showImageMap($markers, $center = "", $zoom = '14', $size = '480x450'){
	global $path_maps;
	$path_root = $_SERVER["DOCUMENT_ROOT"];
	if($center == "") $center = $markers;
	$filename = md5($markers) . ".gif";
	if(file_exists($path_root . $path_maps . $filename)){
		return $path_maps . $filename;
	}else{
		$url_download = 'http://www.google.com/staticmap?center=' . $center . '&markers=' . $markers . ',red&zoom=' . $zoom . '&size=' . $size;
		downloadFile($url_download,$path_root . $path_maps . $filename);
		return $path_maps . $filename;
	}
}

function get_post_image_link($image_name, $folder = 'resize', $size = '600', $prefix = ''){
    $post_created = (int)preg_replace('/[^0-9]*/','',$image_name);
    if($post_created < TIME_NEW_UPLOAD){
        $path = PATH_IMG_POSTS.$prefix.$image_name;
    }else{
        $path = SERVER_STATIC . get_link_resize_image($folder, $image_name, $size);
    }
    return $path;
}

function get_image_path_upload($time = 0, $folder = 'resize', $size = 600){
    if($time == 0){
        $time = time();
    }
    if($time < TIME_NEW_UPLOAD){
        return PATH_IMG_UPLOAD;
    }else{
        $time = getdate($time);
        return '/pictures/'.$folder.'/'.$time['year'].'/'.$time['mon'].'/'. $size . '/';
    }
}

function prepare_directory($dir){
    if(!file_exists($dir)){
        @mkdir($dir,755,true);
    }
    return $dir;
}
function prepare_directory_from_filename($filename){
    if(!file_exists(dirname(".." . $filename))){
        mkdir(dirname(".." . $filename), 0755, true);
    }
}
function downloadFile($url_download, $save_as){
	set_time_limit(0);
	ini_set('display_errors',true);//Just in case we get some errors, let us know....	
	$fp = fopen ($save_as, 'w');//This is the file where we save the information
	$ch = curl_init($url_download);//Here is the file we are downloading
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_FILE, $fp);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_exec($ch);
	curl_close($ch);
	fclose($fp);
}

//You do not need to alter these functions
function croped_image($path, $filename, $width, $height, $new_width, $new_height, $start_width, $start_height, $quality = 100, $type = "s_", $new_path = ""){
   
   $percent		=	1;
	$sExtension = substr($filename, (strrpos($filename, ".") + 1));
	$sExtension = strtolower($sExtension);
	$image		= '';
	//echo $sExtension . "<br>";
	//echo $sExtension . "<br>";
	
	// Resample
	$image_p = imagecreatetruecolor($new_width, $new_height);
	//check extension file for create
	switch($sExtension){
		case "gif":
			$image = imagecreatefromgif($path . $filename);
			break;
		case $sExtension == "jpg" || $sExtension == "jpe" || $sExtension == "jpeg":
			$image = imagecreatefromjpeg($path . $filename);
			break;
		case "png":
			$image = imagecreatefrompng($path . $filename);
			break;
	}
	
	//Copy and resize part of an image with resampling
	imagecopyresampled($image_p, $image, 0, 0, $start_width, $start_height, $new_width, $new_height, $width, $height);
   //imagecopyresampled($newImage,$source,0,0,$start_width,$start_height,$newImageWidth,$newImageHeight,$width,$height);
	// Output
	
	// check new_path, nếu new_path tồn tại sẽ save ra đó, thay path = new_path
	if($new_path != "") $path = $new_path;
	
   $new_filename  =  $path . $type . $filename;
	switch($sExtension){
   	case "gif":
   		imagegif($image_p, $new_filename);
   		break;
   	case $sExtension == "jpg" || $sExtension == "jpe" || $sExtension == "jpeg":
   		imagejpeg($image_p, $new_filename, $quality);
   		break;
   	case "png":
   		imagepng($image_p, $new_filename);
   		break;
	}
	imagedestroy($image_p);
   return $type . $filename;
}
?>