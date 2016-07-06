<?

function base64_url_encode($input)
{
    return strtr(base64_encode($input), '+/=', '_,-');
}

function base64_url_decode($input)
{
    return base64_decode(strtr($input, '_,-', '+/='));
}

function bigintval($bigint)
{
    $bigint = preg_replace("/[^0-9]/i", "", $bigint);
    if ($bigint == "") $bigint = 0;
    return $bigint;
}

function breakKey($content, $title, $tag = 0)
{
    $title = mb_strtolower($title, "UTF-8");
    $array = explode(".", $content);
    $contentReturn = '';
    $arrayKey = explode(" ", $title);
    $arraySort = array();
    $arResult = array();
    foreach ($array as $key => $value) {
        $value = mb_strtolower($value, "UTF-8");
        $arrayCau = explode(" ", $value);
        $result = array_intersect($arrayKey, $arrayCau);
        $arraySort[$key] = count($result);
        $arResult[$key] = $result;
    }
    arsort($arraySort);
    $i = 0;
    foreach ($arraySort as $key => $value) {
        $i++;
        if (isset($array[$key]) && isset($arResult[$key])) {
            $contentReturn = $contentReturn . replaceTag(cut_string($array[$key], 200), $arResult[$key]) . '. ';
        }
        if ($i == 3) break;
    }
    unset($result);
    unset($arraySort);
    unset($arResult);
    if ($tag == 1) $this->text .= strip_tags($contentReturn);
    $contentReturn = str_replace("</b>", " ", $contentReturn);

    return $contentReturn;
}

function check_url_status($url, $status = array())
{
    if (!filter_var($url, FILTER_VALIDATE_URL)) return false;
    $header = get_headers($url);
    $url_status = $header[0];
    $check_status = 0;
    switch ($url_status) {
        case 'HTTP/1.1 200 OK':
        case 'HTTP/1.0 200 OK' :
            $check_status = 200;
            break;
        case 'HTTP/1.1 301 Moved Permanently':
            $check_status = 301;
            break;
        case 'HTTP/1.1 404 File not found':
            $check_status = 404;
            break;
        default :
            $check_status = 200;
            break;
    }
    if ($status) {
        if (!is_array($status)) {
            $status = array($status);
        }
        return in_array($check_status, $status);
    } else {
        return $url_status;
    }

}


function checkIP($arrayIPAllow = array())
{
    if (!in_array($_SERVER['REMOTE_ADDR'], $arrayIPAllow)) {
        return false;
    }
    return true;
}

function check_ip_test()
{
    return $_SERVER['REMOTE_ADDR'] == IP_ADDRESS_TEST || $_SERVER['SERVER_NAME'] == 'localhost';
}

function check_ip_cron()
{
    return $_SERVER['REMOTE_ADDR'] == IP_ADDRESS_CRON;
}

function check_authen()
{
    //$realm	= "demo.luongcao.com";
    $realm = $_SERVER['HTTP_HOST'];
    if (empty($_SERVER['PHP_AUTH_DIGEST'])) {
        header('HTTP/1.1 401 Unauthorized');
        header('WWW-Authenticate: Digest realm="' . $realm .
            '",qop="auth",nonce="' . uniqid() . '",opaque="' . md5($realm) . '"');
        die('Text to send if user hits Cancel button');
    }
}

function cache_set($cid, $body, $expired_time = 0)
{
    if (!(is_string($body) || is_numeric($body)) || $cid == '') {
        return false;
    }
    $time = time();
    if ($expired_time <= $time) {
        $expired_time = $time + 86400;
    }
    $key = md5($cid);
    $sql = 'DELETE FROM cache WHERE cid = \'' . $key . '\'';
    $db_ex = new db_execute($sql);
    unset($db_ex);
    $sql = 'INSERT INTO cache (cid, body, created, expired_time) VALUES (\'' . $key . '\', \'' . $body . '\', ' . $time . ', ' . $expired_time . ')';
    $db_ex = new db_execute($sql);
    unset($db_ex);
    return true;
}

/**
 * Lay cache
 *
 * @param $cid
 * Type: string - key cache
 */

function cache_get($cid)
{
    if ($cid == '') {
        return false;
    }
    $key = md5($cid);
    $sql = 'SELECT * FROM cache WHERE cid = \'' . $key . '\' LIMIT 0,1';
    $db_query = new db_query($sql);
    $cache = mysqli_fetch_assoc($db_query->result);
    if (empty($cache) || $cache['expired_time'] < time() || empty($cache['body'])) {
        return false;
    } else {
        return $cache;
    }
}

/**
 * Xoa tat ca cache het han
 */

function cache_clear()
{
    $sql = 'DELETE FROM cache WHERE expired_time < ' . time() . '';
    $db_ex = new db_execute($sql);
    return true;
}

/**
 * Xoa cache
 *
 * @param $cid
 * Type: string - key cache
 */

function cache_del($cid)
{
    if ($cid == '') {
        return false;
    }
    $key = md5($cid);
    $sql = 'DELETE FROM cache WHERE cid = \'' . $key . '\'';
    $db_ex = new db_execute($sql);
    return true;
}

function curl_get_content($url, $post = "", $refer = "", $usecookie = false)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);

    if ($post) {
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
    }

    if ($refer) {
        curl_setopt($curl, CURLOPT_REFERER, $refer);
    }

    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/6.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.7) Gecko/20050414 Firefox/1.0.3");
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    //curl_setopt($curl, CURLOPT_TIMEOUT_MS, 5000);

    if ($usecookie) {
        curl_setopt($curl, CURLOPT_COOKIEJAR, $usecookie);
        curl_setopt($curl, CURLOPT_COOKIEFILE, $usecookie);
    }

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $html = curl_exec($curl);
    if (curl_error($curl)) {
        echo 'Loi CURL : ' . (curl_error($curl));
    }
    curl_close($curl);
    return $html;
}

function curl_multiRequest($data, $options = array())
{

    // array of curl handles
    $curly = array();
    // data to be returned
    $result = array();

    // multi handle
    $mh = curl_multi_init();

    // loop through $data and create curl handles
    // then add them to the multi-handle
    foreach ($data as $id => $d) {

        $curly[$id] = curl_init();

        $url = (is_array($d) && !empty($d['url'])) ? $d['url'] : $d;
        curl_setopt($curly[$id], CURLOPT_URL, $url);
        curl_setopt($curly[$id], CURLOPT_HEADER, 0);
        curl_setopt($curly[$id], CURLOPT_RETURNTRANSFER, 1);

        // post?
        if (is_array($d)) {
            if (!empty($d['post'])) {
                curl_setopt($curly[$id], CURLOPT_POST, 1);
                curl_setopt($curly[$id], CURLOPT_POSTFIELDS, $d['post']);
            }
        }

        // extra options?
        if (!empty($options)) {
            curl_setopt_array($curly[$id], $options);
        }

        curl_multi_add_handle($mh, $curly[$id]);
    }

    // execute the handles
    $running = null;
    do {
        curl_multi_exec($mh, $running);
    } while ($running > 0);


    // get content and remove handles
    foreach ($curly as $id => $c) {
        $result[$id] = curl_multi_getcontent($c);
        curl_multi_remove_handle($mh, $c);
    }

    // all done
    curl_multi_close($mh);

    return $result;
}


function callback($buffer)
{
    $str = array(chr(9));
    $buffer = str_replace($str, "", $buffer);
    return $buffer;
}

function check_email_address($email)
{
    //First, we check that there's one @ symbol, and that the lengths are right
    if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
        //Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
        return false;
    }
    //Split it into sections to make life easier
    $email_array = explode("@", $email);
    $local_array = explode(".", $email_array[0]);
    for ($i = 0; $i < sizeof($local_array); $i++) {
        if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
            return false;
        }
    }
    if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) {
        //Check if domain is IP. If not, it should be valid domain name
        $domain_array = explode(".", $email_array[1]);
        if (sizeof($domain_array) < 2) {
            return false; // Not enough parts to domain
        }
        for ($i = 0; $i < sizeof($domain_array); $i++) {
            if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {
                return false;
            }
        }
    }
    return true;
}

/**
 * Check email address
 * @param  [type] $email [description]
 * @return [bool]        [description]
 */
function check_email_address_v2($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function cut_string($str, $length, $char = " ...")
{
    //Nếu chuỗi cần cắt nhỏ hơn $length thì return luôn
    $strlen = mb_strlen($str, "UTF-8");
    if ($strlen <= $length) return $str;

    //Cắt chiều dài chuỗi $str tới đoạn cần lấy
    $substr = mb_substr($str, 0, $length, "UTF-8");
    if (mb_substr($str, $length, 1, "UTF-8") == " ") return $substr . $char;

    //Xác định dấu " " cuối cùng trong chuỗi $substr vừa cắt
    $strPoint = mb_strrpos($substr, " ", "UTF-8");

    //Return string
    if ($strPoint < $length - 20) return $substr . $char;
    else return mb_substr($substr, 0, $strPoint, "UTF-8") . $char;
}

function createChecksumDetailPost($post_id, $author_id)
{
    return md5($author_id . $post_id);
}


//curl multi thread
function curlExec($url_array, $data)
{
    if (is_array($url_array)) {
        $arrayReturn = array();
        $curl_arr = array();
        //create the multiple cURL handle
        $master = curl_multi_init();
        foreach ($url_array as $key => $arr) {
            $curl_arr[$key] = curl_init($arr["url"]);
            curl_setopt($curl_arr[$key], CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl_arr[$key], CURLOPT_HTTPHEADER, array("Content-Type" => "application/json", "Accept" => "application/json", "ApiKey" => "41460b3f-8f35-4878-b78d-49ca7f29c071"));
            curl_setopt($curl_arr[$key], CURLOPT_HEADER, 0);
            curl_setopt($curl_arr[$key], CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl_arr[$key], CURLOPT_SSL_VERIFYHOST, false);
            //curl_setopt($curl_arr[$key], CURLOPT_POSTFIELDS, $arr["data"]);
            curl_multi_add_handle($master, $curl_arr[$key]);
            curl_close($curl_arr[$key]);
        }
        //foreach($url as $key => $value)

        do {
            curl_multi_exec($master, $running);
        } while ($running > 0);
        curl_multi_close($master);

        foreach ($url_array as $key => $arr) {
            $arrayReturn[$key] = curl_multi_getcontent($curl_arr[$key]);
            curl_close($curl_arr[$key]);
            unset($curl_arr[$key]);
        }
        return $arrayReturn;
    }
}

//check refer
function check_refer_site()
{
    if (!isset($_SERVER['HTTP_REFERER'])) {
        return true;
    }
    $global_refer_site = array('localhost',
        'facebook.com',
        'www.facebook.com',
        'hott.vn',
        'www.hott.vn',
        'cunach.com',
        'me.zing.vn',
        'twitter.com',
        'google.com',
        'www.google.com',
        'google.com.vn',
        'www.google.com.vn',
        'bing.com',
        'yahoo.com',
        'coccoc.com');
    $url = parse_url($_SERVER['HTTP_REFERER']);
    if (in_array($url['host'], $global_refer_site)) return true;
    else return false;
}

/* check google bot
 * return true neu la google bot
 * */
function check_crawler_bot()
{
    if (!isset($_SERVER['HTTP_USER_AGENT']))
        return true;
    $browser = $_SERVER['HTTP_USER_AGENT'];
    $is_bot = false;
    switch (true) {
        case strpos($browser, 'Googlebot') > -1:
            # code...
            $is_bot = true;
            break;
        case strpos($browser, 'facebook') > -1 :
            $is_bot = true;
            break;
        case strpos($browser, 'bingbot') > -1 :
            $is_bot = true;
            break;
        case strpos($browser, 'coccoc') > -1 :
            $is_bot = true;
            break;
        case strpos($browser, 'crawler') > -1 || strpos($browser, 'Crawler') > -1:
            $is_bot = true;
            break;
        default:
            # code...
            $is_bot = false;
            break;
    }
    return $is_bot;
}

function date_convert_string($time)
{
    //hàm convert khoảng thời gian trong quá khứ thành chuỗi, định dạng giống facebook
    $current = time();
    $hieuso = $current - $time;
    if ($hieuso / 60 <= 1) {
        return 'vừa mới đây';
    }
    if ($hieuso / 60 > 1 && $hieuso / 3600 < 1) {
        return (int)($hieuso / 60) . ' phút trước';
    }
    if ($hieuso / 3600 >= 1 && $hieuso / 86400 < 1) {
        return (int)($hieuso / 3600) . ' giờ trước';
    }
    if ($hieuso / 86400 >= 1 && $hieuso / (86400 * 3) < 1) {
        return (int)($hieuso / 86400) . ' ngày trước';
    }
    if ($hieuso / (86400 * 3) >= 1 && $hieuso / (86400 * 30) < 1) {
        //nhiều hơn 2 ngày nhưng chưa đến 1 tháng thì trả về ngày chính xác
        return date('d \t\h\á\n\g m', $time);
    }
    if ($hieuso / (86400 * 30) >= 1 && $hieuso / (86400 * 30 * 3) < 1) {
        //ít hơn 2 tháng thì trả về tháng trước hoặc 2 tháng trước
        if ($hieuso / (86400 * 30 * 2) < 2)
            return 'tháng trước';
        else
            return '2 tháng trước';
    }
    if ($hieuso / (86400 * 30 * 3) >= 1) {
        //Nhiều hơn 1 năm thì trả về ngày chính xác
        return date('d \t\h\á\n\g m Y', $time);
    }
}

function error_404_document()
{
    header("HTTP/1.0 404 Not Found");
    $rainTpl = new RainTPL();
    echo $rainTpl->draw('error_404', 1);
    exit();
}


function format_number($number, $edit = 0)
{
    if ($edit == 0) {
        $return = number_format($number, 2, ".", ",");
        if (intval(substr($return, -2, 2)) == 0) $return = number_format($number, 0, ".", ",");
        elseif (intval(substr($return, -1, 1)) == 0) $return = number_format($number, 1, ".", ",");
        return $return;
    } else {
        $return = number_format($number, 2, ".", "");
        if (intval(substr($return, -2, 2)) == 0) $return = number_format($number, 0, ".", "");
        return $return;
    }
}

function get_domain($url)
{
    $parse = parse_url($url);
    return $parse['scheme'] ? $parse['scheme'] . '://' . $parse['host'] : 'http://' . $parse['host'];
}


function getURL($serverName = 0, $scriptName = 0, $fileName = 1, $queryString = 1, $varDenied = '')
{

    $url = '';
    $slash = '/';
    if ($scriptName != 0) $slash = "";
    if ($serverName != 0) {
        if (isset($_SERVER['SERVER_NAME'])) {
            $url .= 'http://' . $_SERVER['SERVER_NAME'];
            if (isset($_SERVER['SERVER_PORT'])) $url .= ":" . $_SERVER['SERVER_PORT'];
            $url .= $slash;
        }
    }
    if ($scriptName != 0) {
        if (isset($_SERVER['SCRIPT_NAME'])) $url .= substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], '/') + 1);
    }
    if ($fileName != 0) {
        if (isset($_SERVER['SCRIPT_NAME'])) $url .= substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], '/') + 1);
    }
    if ($queryString != 0) {
        $url .= '?';
        reset($_GET);
        $i = 0;
        if ($varDenied != '') {
            $arrVarDenied = explode('|', $varDenied);
            while (list($k, $v) = each($_GET)) {
                if (array_search($k, $arrVarDenied) === false) {
                    $i++;
                    if ($i > 1) $url .= '&' . $k . '=' . @urlencode($v);
                    else $url .= $k . '=' . @urlencode($v);
                }
            }
        } else {
            while (list($k, $v) = each($_GET)) {
                $i++;
                if ($i > 1) $url .= '&' . $k . '=' . @urlencode($v);
                else $url .= $k . '=' . @urlencode($v);
            }
        }
    }
    $url = str_replace('"', '&quot;', strval($url));
    return $url;
}

function getValue($value_name, $data_type = "int", $method = "GET", $default_value = 0, $advance = 0)
{

    $value = $default_value;
    switch ($method) {
        case "GET":
            if (isset($_GET[$value_name])) $value = $_GET[$value_name];
            break;
        case "POST":
            if (isset($_POST[$value_name])) $value = $_POST[$value_name];
            break;
        case "COOKIE":
            if (isset($_COOKIE[$value_name])) $value = $_COOKIE[$value_name];
            break;
        case "SESSION":
            if (isset($_SESSION[$value_name])) $value = $_SESSION[$value_name];
            break;
        default:
            if (isset($_GET[$value_name])) $value = $_GET[$value_name];
            break;
    }
    $valueArray = array("int" => @intval($value), "str" => trim(@strval($value)), "flo" => @floatval($value), "dbl" => @doubleval($value), "arr" => $value);
    foreach ($valueArray as $key => $returnValue) {
        if ($data_type == $key) {
            if ($advance != 0) {
                switch ($advance) {
                    case 1:
                        $returnValue = replaceMQ($returnValue);
                        break;
                    case 2:
                        $returnValue = htmlspecialbo($returnValue);
                        break;
                    case 3:
                        $returnValue = htmlspecialbo(replaceMQ($returnValue));
                        break;
                }
            }
            //Do số quá lớn nên phải kiểm tra trước khi trả về giá trị
            if (($data_type != "str") && ($data_type != "arr") && (strval($returnValue) == "INF")) return 0;
            return $returnValue;
            break;
        }
    }
    return (intval($value));
}

function getValueBound($string_bound = "")
{
    $string = trim(preg_replace("/[^0-9.,]/i", " ", $string_bound));
    $fou = explode(",", $string);
    $arr_return = array();
    for ($i = 0; $i <= 3; $i++) {
        $arr_return[$i] = isset($fou[$i]) ? doubleval($fou[$i]) : 0;
    }
    return $arr_return;
}

function generate_picture_name($picture_name = '')
{
    if (!$picture_name)
        return generate_random_string(3) . time();
    else {
        $tmp = explode('.', $picture_name);
        $extension = array_pop($tmp);
        return generate_random_string(3) . time() . '.' . $extension;
    }
}

function get_cookie_anatomy_sex()
{
    $sex = getValue('anatomy-sex', 'str', 'COOKIE', 'male', 3);
    switch ($sex) {
        case 'female':
            $return = DOITUONG_NUGIOI;
            break;
        case 'male':
        default :
            $return = DOITUONG_NAMGIOI;
            break;
    }
    return $return;
}

function get_header_type($url)
{
    $header = get_headers($url, 1);
    $header = $header['Content-Type'];
    $header_type = '';
    switch ($header) {
        case  'image/jpeg':
        case 'image/jpg':
            $header_type = 'jpg';
            break;
        case 'image/png':
            $header_type = 'png';
            break;
        case 'image/gif':
            $header_type = 'gif';
            break;
    }
    return $header_type;
}

function get_picture_path($image_name, $type = 'organic')
{
    if (!$image_name) {
        return '';
    }
    $time = preg_replace('/[^0-9]*/', '', $image_name);
    $time = getdate($time);
    if(defined('DOMAIN_STATIC')){
        return DOMAIN_STATIC . '/pictures/' . $time['year'] . '/' . $time['mon'] . '/' . $type . '/' . $image_name;
    }else{
        return '/pictures/' . $time['year'] . '/' . $time['mon'] . '/' . $type . '/' . $image_name;
    }
}

function get_picture_dir($image_name, $type = 'organic')
{
    if (!$image_name) {
        return '';
    }
    $time = preg_replace('/[^0-9]*/', '', $image_name);
    $time = getdate($time);
    return '/pictures/' . $time['year'] . '/' . $time['mon'] . '/' . $type;
}

function get_limit_query_string($page_current, $limit_size)
{
    return ' LIMIT ' . intval(($page_current - 1) * $limit_size) . ',' . $limit_size . ' ';
}

function generate_dir_upload($picture_name, $type)
{
    if (!$picture_name) {
        return false;
    }
    $time = preg_replace('/[^0-9]*/', '', $picture_name);
    $time = getdate($time);
    $path_dir = $_SERVER['DOCUMENT_ROOT'] . '/pictures/' . $time['year'] . '/' . $time['mon'] . '/' . $type . '/';
    if (file_exists($path_dir)) {
        return $path_dir;
    }
    if (mkdir($path_dir, 0777, 1)) {
        return $path_dir;
    } else {
        return false;
    }
}

function generate_random_string($length = 10)
{
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

function get_link_upload_image($picture_name)
{

}

/*
 * $active_cat = int => id của cat cần active
 * $active_cat = string => 'home'=> trang chủ, 'qaa'=> hỏi đáp, 'mbox'=>tủ thuốc
 */
function get_all_categories($active_cat = '')
{
    global $_static_value;
    if (isset($_static_value['categories_menu'])) {
        return $_static_value;
    }
    $db = new db_query('SELECT cat_id,cat_name
                        FROM categories
                        WHERE cat_type = '.CATEGORY_TYPE_NEWS);
    $cat = array();
    if (is_int($active_cat)) {
        $cat[] = array('cat_id' => 0, 'cat_name' => 'Trang chủ', 'link_cat' => '/home', 'is_active' => 0);
        while ($row = mysqli_fetch_assoc($db->result)) {
            if ($row['cat_id'] == $active_cat) {
                $row['is_active'] = 1;
            } else {
                $row['is_active'] = 0;
            }
            $row['link_cat'] = generate_cat_url($row);
            $cat[] = $row;
        }
        $cat[] = array('cat_id' => 0, 'cat_name' => 'Hỏi - đáp', 'link_cat' => generate_qaa_url(), 'is_active' => 0);
        $cat[] = array('cat_id' => 0, 'cat_name' => 'Tủ thuốc', 'link_cat' => generate_medicine_box_url(), 'is_active' => 0);
    } else {
        switch ($active_cat) {
            case 'home':
                $cat[] = array('cat_id' => 0, 'cat_name' => 'Trang chủ', 'link_cat' => '/home', 'is_active' => 1);
                while ($row = mysqli_fetch_assoc($db->result)) {
                    $row['link_cat'] = generate_cat_url($row);
                    $cat[] = $row;
                }
                $cat[] = array('cat_id' => 0, 'cat_name' => 'Hỏi - đáp', 'link_cat' => generate_qaa_url(), 'is_active' => 0);
                $cat[] = array('cat_id' => 0, 'cat_name' => 'Tủ thuốc', 'link_cat' => generate_medicine_box_url(), 'is_active' => 0);
                break;
            //hỏi đáp
            case 'qaa':
                $cat[] = array('cat_id' => 0, 'cat_name' => 'Trang chủ', 'link_cat' => '/home', 'is_active' => 0);
                while ($row = mysqli_fetch_assoc($db->result)) {
                    $row['link_cat'] = generate_cat_url($row);
                    $cat[] = $row;
                }
                $cat[] = array('cat_id' => 0, 'cat_name' => 'Hỏi - đáp', 'link_cat' => generate_qaa_url(), 'is_active' => 1);
                $cat[] = array('cat_id' => 0, 'cat_name' => 'Tủ thuốc', 'link_cat' => generate_medicine_box_url(), 'is_active' => 0);
                break;
            //tủ thuốc
            case 'mbox':
                $cat[] = array('cat_id' => 0, 'cat_name' => 'Trang chủ', 'link_cat' => '/home', 'is_active' => 0);
                while ($row = mysqli_fetch_assoc($db->result)) {
                    $row['link_cat'] = generate_cat_url($row);
                    $cat[] = $row;
                }
                $cat[] = array('cat_id' => 0, 'cat_name' => 'Hỏi - đáp', 'link_cat' => generate_qaa_url(), 'is_active' => 0);
                $cat[] = array('cat_id' => 0, 'cat_name' => 'Tủ thuốc', 'link_cat' => generate_medicine_box_url(), 'is_active' => 1);
                break;
        }
    }

    unset($db);
    $_static_value['categories_menu'] = $cat;
    return $cat;
}



function get_color_category($cat_id)
{
    $color_code = $cat_id % 5;
    switch ($color_code) {
        case 0:
            return '#cf4e00';
        case 1:
            return '#3f8e3b';
        case 2:
            return '#a11d0b';
        case 3:
            return '#984b55';
        case 4:
            return '#01819e';
    }
}

function get_facebook_like_button($url)
{
    return '<div class="fb-like" data-href="' . $url . '" data-layout="standard" data-action="like" data-show-faces="true" data-share="true"></div>';
}

function get_facebook_comment_frame($url)
{
    return '<div class="fb-comments" data-href="' . $url . '" data-numposts="5" data-colorscheme="light" data-width="700"></div>';
}

function get_facebook_embedded_post($url)
{
    return '<div class="fb-post" data-href="' . $url . '" data-width="500"></div>';
}


function get_image_src_from_html($html_content, $domain = '')
{
    $array_return = array();
    $html_img = str_get_html($html_content);
    $img_details = $html_img->find('img');
    foreach ($img_details as $img_detail) {
        $src_img = $img_detail->src;
        //kiểm tra xem ảnh đã có chưa
        if (in_array($src_img, $array_return)) continue;
        // kiểm tra xem đường dẫn ảnh có phải tuyệt đối không nếu không thì cho domain vào
        $array = parse_url($src_img);
        if (!isset($array['scheme']) || !isset($array['host'])) {
            $array_return[] = $domain . $src_img;
        } else {
            $array_return[] = $src_img;
        }
    }
    return $array_return;
}

function get_qaa_listing_sidebar()
{
    //ktra đã có query chưa
    global $_static_value;
    if (isset($_static_value['qaa_listing_sidebar'])) return $_static_value['qaa_listing_sidebar'];
    //select ra 1 số bệnh thường gặp
    $array_disease = array();
    $db_cat_disease = new db_query('SELECT cdi_id,cdi_name
                                    FROM cat_disease
                                    LIMIT 5');
    while ($row = mysqli_fetch_assoc($db_cat_disease->result)) {
        //xử lý tên bệnh
        $row['cdi_name'] = mb_strtolower($row['cdi_name'], 'utf8');
        $row['cdi_name'] = str_replace(array('bệnh', 'chứng'), '', $row['cdi_name']);
        $row['cdi_name'] = trim($row['cdi_name']);
        $row['link_detail'] = '#';
        $array_disease[] = $row;
    }
    unset($db_cat_disease);
    //select ra các câu hỏi liên quan đến mỗi bệnh này
    //hiện giờ chưa có map tin <=> bệnh nên select tạm 8 tin
    $array_list_qaa = array();
    $db_list_qaa = new db_query('SELECT que_title,que_id,cat_name
                                 FROM questions
                                 LEFT JOIN categories ON cat_id = que_cat_id
                                 WHERE que_type = 0 AND que_status = 1
                                 ORDER BY que_date DESC
                                 LIMIT 9');
    $count_i = 0;
    while ($row_list = mysqli_fetch_assoc($db_list_qaa->result)) {
        $row_list['link_detail'] = generate_hoidap_detail($row_list);
        $row_list['is_first'] = !$count_i ? 1 : 0;
        $array_list_qaa[] = $row_list;
        $count_i++;
    }
    $_static_value['qaa_listing_sidebar'] = array(
        'list_disease' => $array_disease,
        'list_qaa' => $array_list_qaa
    );
    return $_static_value['qaa_listing_sidebar'];
}

function get_anatomy_data($sex = DOITUONG_NAMGIOI, $type = SECTION_TYPE_FRONT)
{
    global $_static_value;
    if (isset($_static_value['anatomy_data'])) return $_static_value['anatomy_data'];
    if ($sex == DOITUONG_NAMGIOI) {
        $human_front_image = $type == SECTION_TYPE_FRONT ? '/themes/pc/img/nam-truoc.jpg' : '/themes/pc/img/nam-sau.jpg';
    } else {
        $human_front_image = $type == SECTION_TYPE_FRONT ? '/themes/pc/img/nu-truoc.jpg' : '/themes/pc/img/nu-sau.jpg';
    }

    $anatomy_data = array('image' => $human_front_image);
    //query ra theo đối tượng và loại từ bảng anatomy_map
    $db_query = new db_query('SELECT anatomy_map.*,sec_id,sec_name
                              FROM anatomy_map
                              STRAIGHT_JOIN sections ON sec_id = ana_sec_id
                              WHERE ana_type = ' . $type . ' AND ana_sex = ' . $sex);
    while ($row = mysqli_fetch_assoc($db_query->result)) {
        $row['link_detail'] = generate_section_detail($row);
        $anatomy_data['data'][] = $row;
    }
    $_static_value['anatomy_data'] = $anatomy_data;
    return $anatomy_data;
}

function get_news_xem_nhieu($number = 8)
{
    global $_static_value;
    if (isset($_static_value['news_xem_nhieu'])) return $_static_value['news_xem_nhieu'];
    $number = intval($number);
    $limit_size = intval($number * 2);
    $db_query = new db_query('SELECT new_id,new_title,cat_name
                              FROM news
                               LEFT JOIN categories ON cat_id = new_cat_id
                              WHERE new_active = 1
                              AND new_date <= ' . TIMESTAMP . '
                              AND new_view >= ' . MIN_NEWS_VIEW . '
                              AND new_date >= '.MIN_TIME_NEWS_HOT.'
                              ORDER BY new_view DESC
                              LIMIT 0,' . $limit_size);
    $array_news = array();
    while ($row = mysqli_fetch_assoc($db_query->result)) {
        $row['link_detail'] = generate_news_detail_url($row);
        $row['new_title'] = htmlspecialbo($row['new_title']);
        $array_news[] = $row;
    }
    if(!$array_news){
        //nếu không có tin thì lấy tin mới nhất
        $sql = 'SELECT new_id,new_title,cat_name
                FROM news
                LEFT JOIN categories ON cat_id = new_cat_id
                WHERE new_active = 1
                AND new_date <= ' . TIMESTAMP . '
                AND new_view >= ' . MIN_NEWS_VIEW . '
                ORDER BY new_view DESC
                LIMIT '.$limit_size;
        $db_query = new db_query($sql);
        while ($row = mysqli_fetch_assoc($db_query->result)) {
            $row['link_detail'] = generate_news_detail_url($row);
            $row['new_title'] = htmlspecialbo($row['new_title']);
            $array_news[] = $row;
        }
    }
    shuffle($array_news);
    $count = 0;
    $return_news = array();
    foreach ($array_news as $a_news) {
        $count++;
        if ($count > $number) break;
        $return_news[] = $a_news;
    }
    $_static_value['news_xem_nhieu'] = $return_news;
    return $return_news;

}

function get_news_random($number = 8)
{
    global $_static_value;
    if (isset($_static_value['news_random'])) return $_static_value['news_random'];
    $number = intval($number);
    $limit_size = intval($number * 10);
    $db_query = new db_query('SELECT new_id,new_title,cat_name
                              FROM news
                               LEFT JOIN categories ON cat_id = new_cat_id
                              WHERE new_active = 1
                              AND new_date <= ' . TIMESTAMP . '
                              AND new_view >= ' . MIN_NEWS_VIEW . '
                              LIMIT 0,' . $limit_size);
    $array_news = array();
    while ($row = mysqli_fetch_assoc($db_query->result)) {
        $row['link_detail'] = generate_news_detail_url($row);
        $row['new_title'] = htmlspecialbo($row['new_title']);
        $array_news[] = $row;
    }
    unset($db_query);
    shuffle($array_news);
    $count = 0;
    $return_news = array();
    foreach ($array_news as $a_news) {
        $count++;
        if ($count > $number) break;
        $return_news[] = $a_news;
    }
    $_static_value['news_random'] = $return_news;
    return $return_news;

}

function get_news_ban_co_biet($number = 4)
{
    global $_static_value;
    if (isset($_static_value['ban_co_biet'])) return $_static_value['ban_co_biet'];
    //id của danh mục bạn có biết
    $id_cat = 3;
    $limit_size = intval($number) * 5;
    $sql = 'SELECT new_id,new_picture,new_title,cat_name,cat_id
            FROM news
            LEFT JOIN categories ON cat_id = new_cat_id
            WHERE new_cat_id = ' . $id_cat . '
                AND new_active = 1
                AND new_view >= ' . MIN_NEWS_VIEW . '
                AND new_date <= ' . TIMESTAMP . '
                AND new_date >= '.MIN_TIME_NEWS_HOT.'
            LIMIT ' . $limit_size;
    $db_query = new db_query($sql);
    $array_news = array();
    while ($row = mysqli_fetch_assoc($db_query->result)) {
        $row['link_detail'] = generate_news_detail_url($row);
        $row['new_title'] = htmlspecialbo($row['new_title']);
        $array_news['link_cat'] = generate_cat_url($row);
        $array_news['cat_name'] = $row['cat_name'];
        $array_news['array_news'][] = $row;
    }
    if(!$array_news){
        //nếu không có tin thì lấy tin bạn có biết mới nhất
        $sql = 'SELECT new_id,new_picture,new_title,cat_name,cat_id
                FROM news
                LEFT JOIN categories ON cat_id = new_cat_id
                WHERE new_cat_id = ' . $id_cat . '
                    AND new_active = 1
                    AND new_date <= '.TIMESTAMP.'
                ORDER BY new_date DESC
                LIMIT '.$limit_size;
        $db_query = new db_query($sql);
        while ($row = mysqli_fetch_assoc($db_query->result)) {
            $row['link_detail'] = generate_news_detail_url($row);
            $row['new_title'] = htmlspecialbo($row['new_title']);
            $array_news['link_cat'] = generate_cat_url($row);
            $array_news['cat_name'] = $row['cat_name'];
            $array_news['array_news'][] = $row;
        }
    }

    unset($db_query);
    shuffle($array_news['array_news']);
    $count = 0;
    $return_news = array();
    foreach ($array_news['array_news'] as $a_news) {
        $count++;
        if ($count == 1) {
            $a_news['new_picture'] = get_picture_path($a_news['new_picture'], 'medium');
            $a_news['is_first'] = 1;
        }
        if ($count > $number) break;
        $return_news[] = $a_news;
    }
    $array_news['array_news'] = $return_news;
    $_static_value['ban_co_biet'] = $array_news;
    return $array_news;
}

function get_all_section(){
    global $_static_value;
    if (isset($_static_value['all_section'])) return $_static_value['all_section'];
    $db_section = new db_query('SELECT sec_id,sec_name FROM sections');
    $section = array();
    while($row = mysqli_fetch_assoc($db_section->result)){
        $section[$row['sec_id']] = $row['sec_name'];
    }
    $_static_value['all_section'] = $section;
    return $section;
}

function get_question_relate($tag_string, $number, $question_origin, $question_category){
    if(!$tag_string){
        return array();
    }
    $tag_string = explode(',',$tag_string);
    $array_question = array();
    $array_id = array();
    foreach($tag_string as $tag_keyword){
        $db_query = new db_query('SELECT que_id,que_title,que_date,cat_name
                                  FROM questions
                                  LEFT JOIN categories ON que_cat_id = cat_id
                                  WHERE que_cat_id = '.$question_category.' AND que_tags LIKE "%'.$tag_keyword.'%" AND que_id <> '.$question_origin.'
                                  ORDER BY que_date DESC
                                  LIMIT 20');
        while($row = mysqli_fetch_assoc($db_query->result)){
            $row['que_title'] = htmlspecialbo($row['que_title']);
            $row['que_date'] = date('d/m',$row['que_date']);
            $row['link_detail'] = generate_hoidap_detail($row);
            $array_question[] = $row;
            $array_id[] = $row['que_id'];
        }
    }
    $array_question = array_unique($array_question);
    $array_id = array_unique($array_id);
    shuffle($array_question);
    $array_question = array_slice($array_question,0,$number);
    shuffle($array_id);
    $array_id = array_slice($array_id,0,$number);
    //update vào bảng news
    if($array_id){
        $array_id = implode(',',$array_id);
    }else{
        $array_id = '';
    }
    $db_update = new db_execute('UPDATE questions SET que_relate = "'.$array_id.'" WHERE que_id = '.$question_origin);
    unset($db_update);
    return $array_question;
}

function htmlspecialbo($str)
{
    $arrDenied = array('<', '>', '\"', '"');
    $arrReplace = array('&lt;', '&gt;', '&quot;', '&quot;');
    $str = str_replace($arrDenied, $arrReplace, $str);
    return $str;
}


// function to parse the http auth header
function http_digest_parse($txt)
{
    // protect against missing data
    $needed_parts = array('nonce' => 1, 'nc' => 1, 'cnonce' => 1, 'qop' => 1, 'username' => 1, 'uri' => 1, 'response' => 1);
    $data = array();
    $keys = implode('|', array_keys($needed_parts));

    preg_match_all('@(' . $keys . ')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $txt, $matches, PREG_SET_ORDER);

    foreach ($matches as $m) {
        $data[$m[1]] = $m[3] ? $m[3] : $m[4];
        unset($needed_parts[$m[1]]);
    }

    return $needed_parts ? false : $data;
}

function javascript_writer($str)
{
    $mytextencode = "";
    for ($i = 0; $i < strlen($str); $i++) {
        $mytextencode .= ord(substr($str, $i, 1)) . ",";
    }
    if ($mytextencode != "") $mytextencode .= "32";
    return "<script language='javascript'>document.write(String.fromCharCode(" . $mytextencode . "));</script>";
}

function lang_path()
{
    global $lang_id;
    global $array_lang;
    global $con_root_path;
    $default_lang = 1;
    $path = ($lang_id == $default_lang) ? $con_root_path : $con_root_path . $array_lang[$lang_id][0] . "/";
    return $path;
}

function link_embeb_video($id, $auto_play = 0)
{
    $params = array(
        'version' => 3,
        'feature' => 'oembed',
        'autoplay' => $auto_play,
        'wmode' => 'opaque',
        'rel' => 0,
        'showinfo' => 0,
        'modestbranding' => 1,
        'enablejsapi' => 1,
        'ps' => 'docs',
        'nologo' => 1,
        'theme' => 'dark',
        'color' => 'white',
        'iv_load_policy' => 0,
        'cc_load_policy' => 1
    );

    $query_string = '';
    foreach ($params as $param => $value) {
        $query_string .= $param . '=' . $value . '&';
    }
    $query_string = trim($query_string, '&');
    return 'http://www.youtube.com/embed/' . $id . '?' . $query_string;
}

/**
 * Log Function
 * @param  string $filename Ten file
 * @param  string $content Noi dung
 * @return void
 */
function logs($filename, $content)
{
    $arrayInfo = debug_backtrace();
    $arrayInfo = array_shift($arrayInfo);

    $log_path = $_SERVER["DOCUMENT_ROOT"] . "/logs/";
    $handle = @fopen($log_path . $filename . ".cfn", "a");
    //Neu handle chua co mo thêm ../
    if (!$handle) $handle = @fopen($log_path . $filename . ".cfn", "a");
    //Neu ko mo dc lan 2 thi exit luon
    if (!$handle) return;
    fwrite($handle,
        "-------------------------------------------------------------------------------------------------------------------"
        . "\n| DATETIME\t| " . date("d/m/Y - G:i:s")
        . "\n| FILE\t\t| " . $arrayInfo['file']
        . "\n| IP\t\t\t| " . @$_SERVER["REMOTE_ADDR"]
            . "\n| LINE\t\t| " . $arrayInfo['line']
            . "\n| FUNCTION\t| " . $arrayInfo['function']
            . "\n| MESSAGES\t| " . $content . "\n"
            . "-------------------------------------------------------------------------------------------------------------------\n");
    fclose($handle);
}

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}


function redirect($url)
{
    $url = htmlspecialbo($url);
    echo '<script type="text/javascript">window.location.href = "' . $url . '";</script>';
    exit();
}

function move302($url)
{
    header('location:' . $url);
}
function move301($url){
    header("HTTP/1.1 301 Moved Permanently");
    header('location:' . $url);
}
function pjax_param_create($array = array())
{
    $array = base64_encode(json_encode($array));
    return $array;
}

function pjax_param_parse($string = '')
{
    $array = json_decode(base64_decode($string), 1);
    return $array;
}

function reload($second = 0)
{
    echo '<meta http-equiv="REFRESH" content="' . $second . '" />';
    exit();
}


function replace_xss_js($string)
{
    $string = preg_replace('/http:\/\//si', '', $string);
    return "http://" . $string;
}

function removeAccent($mystring)
{
    $marTViet = array(
        // Chữ thường
        "à", "á", "ạ", "ả", "ã", "â", "ầ", "ấ", "ậ", "ẩ", "ẫ", "ă", "ằ", "ắ", "ặ", "ẳ", "ẵ",
        "è", "é", "ẹ", "ẻ", "ẽ", "ê", "ề", "ế", "ệ", "ể", "ễ",
        "ì", "í", "ị", "ỉ", "ĩ",
        "ò", "ó", "ọ", "ỏ", "õ", "ô", "ồ", "ố", "ộ", "ổ", "ỗ", "ơ", "ờ", "ớ", "ợ", "ở", "ỡ",
        "ù", "ú", "ụ", "ủ", "ũ", "ư", "ừ", "ứ", "ự", "ử", "ữ",
        "ỳ", "ý", "ỵ", "ỷ", "ỹ",
        "đ", "Đ", "'",
        // Chữ hoa
        "À", "Á", "Ạ", "Ả", "Ã", "Â", "Ầ", "Ấ", "Ậ", "Ẩ", "Ẫ", "Ă", "Ằ", "Ắ", "Ặ", "Ẳ", "Ẵ",
        "È", "É", "Ẹ", "Ẻ", "Ẽ", "Ê", "Ề", "Ế", "Ệ", "Ể", "Ễ",
        "Ì", "Í", "Ị", "Ỉ", "Ĩ",
        "Ò", "Ó", "Ọ", "Ỏ", "Õ", "Ô", "Ồ", "Ố", "Ộ", "Ổ", "Ỗ", "Ơ", "Ờ", "Ớ", "Ợ", "Ở", "Ỡ",
        "Ù", "Ú", "Ụ", "Ủ", "Ũ", "Ư", "Ừ", "Ứ", "Ự", "Ử", "Ữ",
        "Ỳ", "Ý", "Ỵ", "Ỷ", "Ỹ",
        "Đ", "Đ", "'",
    );
    $marKoDau = array(
        /// Chữ thường
        "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a",
        "e", "e", "e", "e", "e", "e", "e", "e", "e", "e", "e",
        "i", "i", "i", "i", "i",
        "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o",
        "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u",
        "y", "y", "y", "y", "y",
        "d", "D", "",
        //Chữ hoa
        "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A",
        "E", "E", "E", "E", "E", "E", "E", "E", "E", "E", "E",
        "I", "I", "I", "I", "I",
        "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O",
        "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U",
        "Y", "Y", "Y", "Y", "Y",
        "D", "D", "",
    );
    return str_replace($marTViet, $marKoDau, $mystring);
}

function removeHTML($string, $keep_nl = false)
{
    $string = preg_replace('/<script.*?\>.*?<\/script>/si', ' ', $string);
    $string = preg_replace('/<style.*?\>.*?<\/style>/si', ' ', $string);
    $string = preg_replace('/<xml.*?\>.*?<\/xml>/si', ' ', $string);
    if($keep_nl){
        $string = str_replace('<br>',"\r\n",$string);
        $string = str_replace('<br/>',"\r\n",$string);
        $string = str_replace('<br />',"\r\n",$string);
        $string = preg_replace('/<.*?\>/si', ' ', $string);
        $string = str_replace('&nbsp;', ' ', $string);
        $string = mb_convert_encoding($string, "UTF-8", "UTF-8");
        $string = str_replace(array(chr(9)), ' ', $string);
    }else{
        $string = preg_replace('/<.*?\>/si', ' ', $string);
        $string = str_replace('&nbsp;', ' ', $string);
        $string = mb_convert_encoding($string, "UTF-8", "UTF-8");
        $string = str_replace(array(chr(9), chr(10), chr(13)), ' ', $string);
    }
    for ($i = 0; $i <= 5; $i++) $string = str_replace('  ', ' ', $string);
    return $string;
}

function remove_script($string)
{
    $string = preg_replace('/<script.*?\>.*?<\/script>/si', ' ', $string);
    $string = preg_replace('/<xml.*?\>.*?<\/xml>/si', ' ', $string);
    $string = preg_replace('/<style.*?\>.*?<\/style>/si', ' ', $string);
    $string = mb_convert_encoding($string, "UTF-8", "UTF-8");
    return $string;
}

function removeLink($string)
{
    $string = preg_replace('/<a.*?\>/si', '', $string);
    $string = preg_replace('/<\/a>/si', '', $string);
    return $string;
}

function replaceFCK($string, $type = 0)
{
    $arrayChar = array(
        "Ç" => "&Ccedil;",
        "ç" => "&ccedil;",
        "Ë" => "&Euml;",
        "ë" => "&euml;",
        "Ć" => "&#262;",
        "ć" => "&#263;",
        "Č" => "&#268;",
        "č" => "&#269;",
        "Đ" => "&#272;",
        "đ" => "&#273;",
        "Š" => "&#352;",
        "š" => "&#353;",
        "Ž" => "&#381;",
        "ž" => "&#382;",
        "À" => "&Agrave;",
        "à" => "&agrave;",
        "Ç" => "&Ccedil;",
        "ç" => "&ccedil;",
        "È" => "&Egrave;",
        "è" => "&egrave;",
        "É" => "&Eacute;",
        "é" => "&eacute;",
        "Í" => "&Iacute;",
        "í" => "&iacute;",
        "Ï" => "&Iuml;",
        "ï" => "&iuml;",
        "Ò" => "&Ograve;",
        "ò" => "&ograve;",
        "Ó" => "&Oacute;",
        "ó" => "&oacute;",
        "Ú" => "&Uacute;",
        "ú" => "&uacute;",
        "Ü" => "&Uuml;",
        "ü" => "&uuml;",
        "·" => "&middot;",
        "Ć" => "&#262;",
        "ć" => "&#263;",
        "Č" => "&#268;",
        "č" => "&#269;",
        "Đ" => "&#272;",
        "đ" => "&#273;",
        "Š" => "&#352;",
        "š" => "&#353;",
        "Ž" => "&#381;",
        "ž" => "&#382;",
        "Á" => "&Aacute;",
        "á" => "&aacute;",
        "Č" => "&#268;",
        "č" => "&#269;",
        "Ď" => "&#270;",
        "ď" => "&#271;",
        "É" => "&Eacute;",
        "é" => "&eacute;",
        "Ě" => "&#282;",
        "ě" => "&#283;",
        "Í" => "&Iacute;",
        "í" => "&iacute;",
        "Ň" => "&#327;",
        "ň" => "&#328;",
        "Ó" => "&Oacute;",
        "ó" => "&oacute;",
        "Ř" => "&#344;",
        "ř" => "&#345;",
        "Š" => "&#352;",
        "š" => "&#353;",
        "Ť" => "&#356;",
        "ť" => "&#357;",
        "Ú" => "&Uacute;",
        "ú" => "&uacute;",
        "Ů" => "&#366;",
        "ů" => "&#367;",
        "Ý" => "&Yacute;",
        "ý" => "&yacute;",
        "Ž" => "&#381;",
        "ž" => "&#382;",
        "Æ" => "&AElig;",
        "æ" => "&aelig;",
        "Ø" => "&Oslash;",
        "ø" => "&oslash;",
        "Å" => "&Aring;",
        "å" => "&aring;",
        "É" => "&Eacute;",
        "é" => "&eacute;",
        "Ë" => "&Euml;",
        "ë" => "&euml;",
        "Ó" => "&Oacute;",
        "ó" => "&oacute;",
        "Ĉ" => "&#264;",
        "ĉ" => "&#265;",
        "Ĝ" => "&#284;",
        "ĝ" => "&#285;",
        "Ĥ" => "&#292;",
        "ĥ" => "&#293;",
        "Ĵ" => "&#308;",
        "ĵ" => "&#309;",
        "Ŝ" => "&#348;",
        "ŝ" => "&#349;",
        "Ŭ" => "&#364;",
        "ŭ" => "&#365;",
        "Ä" => "&Auml;",
        "ä" => "&auml;",
        "Ö" => "&Ouml;",
        "ö" => "&ouml;",
        "Õ" => "&Otilde;",
        "õ" => "&otilde;",
        "Ü" => "&Uuml;",
        "ü" => "&uuml;",
        "Á" => "&Aacute;",
        "á" => "&aacute;",
        "Ð" => "&ETH;",
        "ð" => "&eth;",
        "Í" => "&Iacute;",
        "í" => "&iacute;",
        "Ó" => "&Oacute;",
        "ó" => "&oacute;",
        "Ú" => "&Uacute;",
        "ú" => "&uacute;",
        "Ý" => "&Yacute;",
        "ý" => "&yacute;",
        "Æ" => "&AElig;",
        "æ" => "&aelig;",
        "Ø" => "&Oslash;",
        "ø" => "&oslash;",
        "Ä" => "&Auml;",
        "ä" => "&auml;",
        "Ö" => "&Ouml;",
        "ö" => "&ouml;",
        "À" => "&Agrave;",
        "à" => "&agrave;",
        "Â" => "&Acirc;",
        "â" => "&acirc;",
        "Ç" => "&Ccedil;",
        "ç" => "&ccedil;",
        "È" => "&Egrave;",
        "è" => "&egrave;",
        "É" => "&Eacute;",
        "é" => "&eacute;",
        "Ê" => "&Ecirc;",
        "ê" => "&ecirc;",
        "Ë" => "&Euml;",
        "ë" => "&euml;",
        "Î" => "&Icirc;",
        "î" => "&icirc;",
        "Ï" => "&Iuml;",
        "ï" => "&iuml;",
        "Ô" => "&Ocirc;",
        "ô" => "&ocirc;",
        "Œ" => "&OElig;",
        "œ" => "&oelig;",
        "Ù" => "&Ugrave;",
        "ù" => "&ugrave;",
        "Û" => "&Ucirc;",
        "û" => "&ucirc;",
        "Ü" => "&Uuml;",
        "ü" => "&uuml;",
        "Ÿ" => "&#376;",
        "ÿ" => "&yuml;",
        "Ä" => "&Auml;",
        "ä" => "&auml;",
        "Ö" => "&Ouml;",
        "ö" => "&ouml;",
        "Ü" => "&Uuml;",
        "ü" => "&uuml;",
        "ß" => "&szlig;",
        "Á" => "&Aacute;",
        "á" => "&aacute;",
        "Â" => "&Acirc;",
        "â" => "&acirc;",
        "Ã" => "&Atilde;",
        "ã" => "&atilde;",
        "Í" => "&Iacute;",
        "í" => "&iacute;",
        "Î" => "&Icirc;",
        "î" => "&icirc;",
        "Ĩ" => "&#296;",
        "ĩ" => "&#297;",
        "Ú" => "&Uacute;",
        "ù" => "&ugrave;",
        "Û" => "&Ucirc;",
        "û" => "&ucirc;",
        "Ũ" => "&#360;",
        "ũ" => "&#361;",
        "ĸ" => "&#312;",
        "Á" => "&Aacute;",
        "á" => "&aacute;",
        "É" => "&Eacute;",
        "é" => "&eacute;",
        "Í" => "&Iacute;",
        "í" => "&iacute;",
        "Ó" => "&Oacute;",
        "ó" => "&oacute;",
        "Ö" => "&Ouml;",
        "ö" => "&ouml;",
        "Ő" => "&#336;",
        "ő" => "&#337;",
        "Ú" => "&Uacute;",
        "ú" => "&uacute;",
        "Ü" => "&Uuml;",
        "ü" => "&uuml;",
        "Ű" => "&#368;",
        "ű" => "&#369;",
        "Á" => "&Aacute;",
        "á" => "&aacute;",
        "Ð" => "&ETH;",
        "ð" => "&eth;",
        "É" => "&Eacute;",
        "é" => "&eacute;",
        "Í" => "&Iacute;",
        "í" => "&iacute;",
        "Ó" => "&Oacute;",
        "ó" => "&oacute;",
        "Ú" => "&Uacute;",
        "ú" => "&uacute;",
        "Ý" => "&Yacute;",
        "ý" => "&yacute;",
        "Þ" => "&THORN;",
        "þ" => "&thorn;",
        "Æ" => "&AElig;",
        "æ" => "&aelig;",
        "Ö" => "&Ouml;",
        "ö" => "&uml;",
        "Á" => "&Aacute;",
        "á" => "&aacute;",
        "É" => "&Eacute;",
        "é" => "&eacute;",
        "Í" => "&Iacute;",
        "í" => "&iacute;",
        "Ó" => "&Oacute;",
        "ó" => "&oacute;",
        "Ú" => "&Uacute;",
        "ú" => "&uacute;",
        "À" => "&Agrave;",
        "à" => "&agrave;",
        "Â" => "&Acirc;",
        "â" => "&acirc;",
        "È" => "&Egrave;",
        "è" => "&egrave;",
        "É" => "&Eacute;",
        "é" => "&eacute;",
        "Ê" => "&Ecirc;",
        "ê" => "&ecirc;",
        "Ì" => "&Igrave;",
        "ì" => "&igrave;",
        "Í" => "&Iacute;",
        "í" => "&iacute;",
        "Î" => "&Icirc;",
        "î" => "&icirc;",
        "Ï" => "&Iuml;",
        "ï" => "&iuml;",
        "Ò" => "&Ograve;",
        "ò" => "&ograve;",
        "Ô" => "&Ocirc;",
        "ô" => "&ocirc;",
        "Ù" => "&Ugrave;",
        "ù" => "&ugrave;",
        "Û" => "&Ucirc;",
        "û" => "&ucirc;",
        "Ā" => "&#256;",
        "ā" => "&#257;",
        "Č" => "&#268;",
        "č" => "&#269;",
        "Ē" => "&#274;",
        "ē" => "&#275;",
        "Ģ" => "&#290;",
        "ģ" => "&#291;",
        "Ī" => "&#298;",
        "ī" => "&#299;",
        "Ķ" => "&#310;",
        "ķ" => "&#311;",
        "Ļ" => "&#315;",
        "ļ" => "&#316;",
        "Ņ" => "&#325;",
        "ņ" => "&#326;",
        "Ŗ" => "&#342;",
        "ŗ" => "&#343;",
        "Š" => "&#352;",
        "š" => "&#353;",
        "Ū" => "&#362;",
        "ū" => "&#363;",
        "Ž" => "&#381;",
        "ž" => "&#382;",
        "Æ" => "&AElig;",
        "æ" => "&aelig;",
        "Ø" => "&Oslash;",
        "ø" => "&oslash;",
        "Å" => "&Aring;",
        "å" => "&aring;",
        "Ą" => "&#260;",
        "ą" => "&#261;",
        "Ć" => "&#262;",
        "ć" => "&#263;",
        "Ę" => "&#280;",
        "ę" => "&#281;",
        "Ł" => "&#321;",
        "ł" => "&#322;",
        "Ń" => "&#323;",
        "ń" => "&#324;",
        "Ó" => "&Oacute;",
        "ó" => "&oacute;",
        "Ś" => "&#346;",
        "ś" => "&#347;",
        "Ź" => "&#377;",
        "ź" => "&#378;",
        "Ż" => "&#379;",
        "ż" => "&#380;",
        "À" => "&Agrave;",
        "à" => "&agrave;",
        "Á" => "&Aacute;",
        "á" => "&aacute;",
        "Â" => "&Acirc;",
        "â" => "&acirc;",
        "Ã" => "&Atilde;",
        "ã" => "&atilde;",
        "Ç" => "&Ccedil;",
        "ç" => "&ccedil;",
        "È" => "&Egrave;",
        "è" => "&egrave;",
        "É" => "&Eacute;",
        "é" => "&eacute;",
        "Ê" => "&Ecirc;",
        "ê" => "&ecirc;",
        "Ì" => "&Igrave;",
        "ì" => "&igrave;",
        "Í" => "&Iacute;",
        "í" => "&iacute;",
        "Ï" => "&Iuml;",
        "ï" => "&iuml;",
        "Ò" => "&Ograve;",
        "ò" => "&ograve;",
        "Ó" => "&Oacute;",
        "ó" => "&oacute;",
        "Õ" => "&Otilde;",
        "õ" => "&otilde;",
        "Ù" => "&Ugrave;",
        "ù" => "&ugrave;",
        "Ú" => "&Uacute;",
        "ú" => "&uacute;",
        "Ü" => "&Uuml;",
        "ü" => "&uuml;",
        "ª" => "&ordf;",
        "º" => "&ordm;",
        "Ă" => "&#258;",
        "ă" => "&#259;",
        "Â" => "&Acirc;",
        "â" => "&acirc;",
        "Î" => "&Icirc;",
        "î" => "&icirc;",
        "Ş" => "&#350;",
        "ş" => "&#351;",
        "Ţ" => "&#354;",
        "ţ" => "&#355;",
        "Á" => "&Aacute;",
        "á" => "&aacute;",
        "Č" => "&#268;",
        "č" => "&#269;",
        "Đ" => "&#272;",
        "đ" => "&#273;",
        "Ŋ" => "&#330;",
        "ŋ" => "&#331;",
        "Š" => "&#352;",
        "š" => "&#353;",
        "Ŧ" => "&#358;",
        "ŧ" => "&#359;",
        "Ž" => "&#381;",
        "ž" => "&#382;",
        "À" => "&Agrave;",
        "à" => "&agrave;",
        "È" => "&Egrave;",
        "è" => "&egrave;",
        "É" => "&Eacute;",
        "é" => "&eacute;",
        "Ì" => "&Igrave;",
        "ì" => "&igrave;",
        "Ò" => "&Ograve;",
        "ò" => "&ograve;",
        "Ó" => "&Oacute;",
        "ó" => "&oacute;",
        "Ù" => "&Ugrave;",
        "ù" => "&ugrave;",
        "Á" => "&Aacute;",
        "á" => "&aacute;",
        "Ä" => "&Auml;",
        "ä" => "&auml;",
        "Č" => "&#268;",
        "č" => "&#269;",
        "Ď" => "&#270;",
        "ď" => "&#271;",
        "É" => "&Eacute;",
        "é" => "&eacute;",
        "Ĺ" => "&#313;",
        "ĺ" => "&#314;",
        "Ľ" => "&#317;",
        "ľ" => "&#318;",
        "Ň" => "&#327;",
        "ň" => "&#328;",
        "Ó" => "&Oacute;",
        "ó" => "&oacute;",
        "Ô" => "&Ocirc;",
        "ô" => "&ocirc;",
        "Ŕ" => "&#340;",
        "ŕ" => "&#341;",
        "Š" => "&#352;",
        "š" => "&#353;",
        "Ť" => "&#356;",
        "ť" => "&#357;",
        "Ú" => "&Uacute;",
        "ú" => "&uacute;",
        "Ý" => "&Yacute;",
        "ý" => "&yacute;",
        "Ž" => "&#381;",
        "ž" => "&#382;",
        "Č" => "&#268;",
        "č" => "&#269;",
        "Š" => "&#352;",
        "š" => "&#353;",
        "Ž" => "&#381;",
        "ž" => "&#382;",
        "Á" => "&Aacute;",
        "á" => "&aacute;",
        "É" => "&Eacute;",
        "é" => "&eacute;",
        "Í" => "&Iacute;",
        "í" => "&iacute;",
        "Ó" => "&Oacute;",
        "ó" => "&oacute;",
        "Ñ" => "&Ntilde;",
        "ñ" => "&ntilde;",
        "Ú" => "&Uacute;",
        "ú" => "&uacute;",
        "Ü" => "&Uuml;",
        "ü" => "&uuml;",
        "¡" => "&iexcl;",
        "ª" => "&ordf;",
        "¿" => "&iquest;",
        "º" => "&ordm;",
        "Å" => "&Aring;",
        "å" => "&aring;",
        "Ä" => "&Auml;",
        "ä" => "&auml;",
        "Ö" => "&Ouml;",
        "ö" => "&ouml;",
        "Ç" => "&Ccedil;",
        "ç" => "&ccedil;",
        "Ğ" => "&#286;",
        "ğ" => "&#287;",
        "İ" => "&#304;",
        "ı" => "&#305;",
        "Ö" => "&Ouml;",
        "ö" => "&ouml;",
        "Ş" => "&#350;",
        "ş" => "&#351;",
        "Ü" => "&Uuml;",
        "ü" => "&uuml;",
        "€" => "&euro;",
        "£" => "&pound;",
        "«" => "&laquo;",
        "»" => "&raquo;",
        "•" => "&bull;",
        "†" => "&dagger;",
        "©" => "&copy;",
        "®" => "&reg;",
        "™" => "&trade;",
        "°" => "&deg;",
        "‰" => "&permil;",
        "µ" => "&micro;",
        "·" => "&middot;",
        "–" => "&ndash;",
        "—" => "&mdash;",
        "“" => "&#8220;",
        "”" => "&#8221;",
        "\"" => "&#034;",
        "№" => "&#8470;");
    $array_fck = array("&Agrave;", "&Aacute;", "&Acirc;", "&Atilde;", "&Egrave;", "&Eacute;", "&Ecirc;", "&Igrave;", "&Iacute;", "&Icirc;",
        "&Iuml;", "&ETH;", "&Ograve;", "&Oacute;", "&Ocirc;", "&Otilde;", "&Ugrave;", "&Uacute;", "&Yacute;", "&agrave;",
        "&aacute;", "&acirc;", "&atilde;", "&egrave;", "&eacute;", "&ecirc;", "&igrave;", "&iacute;", "&ograve;", "&oacute;",
        "&ocirc;", "&otilde;", "&ugrave;", "&uacute;", "&ucirc;", "&yacute;",
    );
    $array_fck1 = array("&Agrave", "&Aacute", "&Acirc", "&Atilde", "&Egrave", "&Eacute", "&Ecirc", "&Igrave", "&Iacute", "&Icirc",
        "&Iuml", "&ETH", "&Ograve", "&Oacute", "&Ocirc", "&Otilde", "&Ugrave", "&Uacute", "&Yacute", "&agrave",
        "&aacute", "&acirc", "&atilde", "&egrave", "&eacute", "&ecirc", "&igrave", "&iacute", "&ograve", "&oacute",
        "&ocirc", "&otilde", "&ugrave", "&uacute", "&ucirc", "&yacute", "&agrave"
    );
    $array_text = array("À", "Á", "Â", "Ã", "È", "É", "Ê", "Ì", "Í", "Î",
        "Ï", "Ð", "Ò", "Ó", "Ô", "Õ", "Ù", "Ú", "Ý", "à",
        "á", "â", "ã", "è", "é", "ê", "ì", "í", "ò", "ó",
        "ô", "õ", "ù", "ú", "û", "ý", "ạ"
    );
    if ($type == 1) {
        foreach ($arrayChar as $key => $val) {
            $string = str_replace($val, $key, $string);
        }
    } else $string = str_replace($array_text, $array_fck, $string);
    return $string;
}

function replaceJS($text)
{
    $arr_str = array("\'", "'", '"', "&#39", "&#39;", chr(10), chr(13), "\n");
    $arr_rep = array(" ", " ", '&quot;', " ", " ", " ", " ");
    $text = str_replace($arr_str, $arr_rep, $text);
    $text = str_replace("    ", " ", $text);
    $text = str_replace("   ", " ", $text);
    $text = str_replace("  ", " ", $text);
    return $text;
}

function replace_keyword_search($keyword, $lower = 1)
{
    if ($lower == 1) $keyword = mb_strtolower($keyword, "UTF-8");
    $keyword = replaceMQ($keyword);
    $arrRep = array("'", '"', "-", "+", "=", "*", "?", "/", "!", "~", "#", "@", "%", "$", "^", "&", "(", ")", ";", ":", "\\", ".", ",", "[", "]", "{", "}", "‘", "’", '“', '”');
    $keyword = str_replace($arrRep, " ", $keyword);
    $keyword = str_replace("  ", " ", $keyword);
    $keyword = str_replace("  ", " ", $keyword);
    return $keyword;
}

function replaceMQ($text)
{
    $text = str_replace("\\", "", $text);
    $text = str_replace("\'", "'", $text);
    $text = str_replace("'", "''", $text);
    return $text;
}

function remove_magic_quote($str)
{
    $str = str_replace("\'", "'", $str);
    $str = str_replace("\&quot;", "&quot;", $str);
    $str = str_replace("\\\\", "\\", $str);
    return $str;
}


function removeSpecialChar($string)
{
    $string = str_replace("@", '', $string);
    $string = str_replace("!", '', $string);
    $string = str_replace("#", '', $string);
    $string = str_replace("$", '', $string);
    $string = str_replace("%", '', $string);
    $string = str_replace("^", '', $string);
    $string = str_replace("&", '', $string);
    $string = str_replace("*", '', $string);
    $string = str_replace("(", '', $string);
    $string = str_replace(")", '', $string);
    $string = str_replace("_", '', $string);
    $string = str_replace("+", '', $string);
    $string = str_replace("+", '', $string);
    $string = str_replace(":", '', $string);

    return $string;
}

function remove_source($text)
{
    $arrKey = array(
        '(VnMedia) - '
    , '(VnMedia)'
    , '(AutoPro)-'
    , '(AutoPro)'
    , '(Dân trí) -'
    , '(Dân trí)'
    , 'Giadinh.net -'
    , '(Giadinh.net) - '
    , 'Giadinh.net'
    , 'GiadinhNet -'
    , 'GiadinhNet '
    , '[Kênh 14]-'
    , '[Kênh 14] -'
    , '[Kênh 14]'
    , '(TNO)'
    , '(thegioioto)'
    , 'TPO - ', 'TP- ', 'TP - ', 'TPO – '
    , '(VietNamNet) -'
    , '(VietNamNet)'
    , '(VnMedia)-'
    , '(VnMedia) -'
    , '(VnMedia)'
    , '(Techz.vn)'
    , '(Tinmoi.vn)'
    , 'VOV.VN'
    , '(Kienthuc.net.vn) -'
    , '(Xã hội) -'
    , '(Ngoisao.vn) -'

    );
    $text = str_replace($arrKey, "", $text);
    return $text;
}

function replaceNCR($str)
{
    $codeNCR = array("&#224;", "&#225;", "&#7841;", "&#7843;", "&#227;", "&#226;", "&#7847;", "&#7845;", "&#7853;", "&#7849;", "&#7851;", "&#259;", "&#7857;", "&#7855;", "&#7863;", "&#7859;", "&#7861;",
        "&#232;", "&#233;", "&#7865;", "&#7867;", "&#7869;", "&#234;", "&#7873;", "&#7871;", "&#7879;", "&#7875;", "&#7877;",
        "&#236;", "&#237;", "&#7883;", "&#7881;", "&#297;",
        "&#242;", "&#243;", "&#7885;", "&#7887;", "&#245;", "&#244;", "&#7891;", "&#7889;", "&#7897;", "&#7893;", "&#7895;", "&#417;", "&#7901;", "&#7899;", "&#7907;", "&#7903;", "&#7905;",
        "&#249;", "&#250;", "&#7909;", "&#7911;", "&#361;", "&#432;", "&#7915;", "&#7913;", "&#7921;", "&#7917;", "&#7919;",
        "&#7923;", "&#253;", "&#7925;", "&#7927;", "&#7929;",
        "&#273;",

        "&#192;", "&#193;", "&#7840;", "&#7842;", "&#195;", "&#194;", "&#7846;", "&#7844;", "&#7852;", "&#7848;", "&#7850;", "&#258;", "&#7856;", "&#7854;", "&#7862;", "&#7858;", "&#7860;",
        "&#200;", "&#201;", "&#7864;", "&#7866;", "&#7868;", "&#202;", "&#7872;", "&#7870;", "&#7878;", "&#7874;", "&#7876;",
        "&#204;", "&#205;", "&#7882;", "&#7880;", "&#296;",
        "&#210;", "&#211;", "&#7884;", "&#7886;", "&#213;", "&#212;", "&#7890;", "&#7888;", "&#7896;", "&#7892;", "&#7894;", "&#416;", "&#7900;", "&#7898;", "&#7906;", "&#7902;", "&#7904;",
        "&#217;", "&#218;", "&#7908;", "&#7910;", "&#360;", "&#431;", "&#7914;", "&#7912;", "&#7920;", "&#7916;", "&#7918;",
        "&#7922;", "&#221;", "&#7924;", "&#7926;", "&#7928;",
        "&#272;",
    );

    $codeVN = array("à", "á", "ạ", "ả", "ã", "â", "ầ", "ấ", "ậ", "ẩ", "ẫ", "ă", "ằ", "ắ", "ặ", "ẳ", "ẵ",
        "è", "é", "ẹ", "ẻ", "ẽ", "ê", "ề", "ế", "ệ", "ể", "ễ",
        "ì", "í", "ị", "ỉ", "ĩ",
        "ò", "ó", "ọ", "ỏ", "õ", "ô", "ồ", "ố", "ộ", "ổ", "ỗ", "ơ", "ờ", "ớ", "ợ", "ở", "ỡ",
        "ù", "ú", "ụ", "ủ", "ũ", "ư", "ừ", "ứ", "ự", "ử", "ữ",
        "ỳ", "ý", "ỵ", "ỷ", "ỹ",
        "đ",

        "À", "Á", "Ạ", "Ả", "Ã", "Â", "Ầ", "Ấ", "Ậ", "Ẩ", "Ẫ", "Ă", "Ằ", "Ắ", "Ặ", "Ẳ", "Ẵ",
        "È", "É", "Ẹ", "Ẻ", "Ẽ", "Ê", "Ề", "Ế", "Ệ", "Ể", "Ễ",
        "Ì", "Í", "Ị", "Ỉ", "Ĩ",
        "Ò", "Ó", "Ọ", "Ỏ", "Õ", "Ô", "Ồ", "Ố", "Ộ", "Ổ", "Ỗ", "Ơ", "Ờ", "Ớ", "Ợ", "Ở", "Ỡ",
        "Ù", "Ú", "Ụ", "Ủ", "Ũ", "Ư", "Ừ", "Ứ", "Ự", "Ử", "Ữ",
        "Ỳ", "Ý", "Ỵ", "Ỷ", "Ỹ",
        "Đ",
    );

    $str = str_replace($codeNCR, $codeVN, $str);
    return $str;
}


function setCookieValue($key, $value, $time)
{
    setcookie($key, $value, time() + $time, "/");
    setcookie($key, $value, time() + $time, "/", "khang.vn");
    setcookie($key, $value, time() + $time, "/", ".khang.vn");
}

function set_keyword_search($keyword)
{
    $keyword = removeHTML($keyword);
    $keyword = replaceMQ($keyword);
    $keyword = htmlspecialchars($keyword);
    $keyword = mb_strtolower($keyword);
    $myuser = new user();
    if ($myuser->logged()) {
        $user_id = $myuser->id;
    } else {
        $user_id = 0;
    }
    $db_insert = new db_execute('INSERT INTO keyword_temp(key_text,key_user_id) VALUES("' . $keyword . '",' . $user_id . ')');
    unset($db_insert);
}


function sort_desc_array_value($a, $b)
{
    if ($a["count"] == $b["count"]) {
        return 0;
    }
    return ($a["count"] > $b["count"]) ? -1 : 1;
}

function sort_user_name_asc($a, $b)
{
    $tempa = ord(substr($a["use_login"], 0, 1));
    $tempb = ord(substr($b["use_login"], 0, 1));
    if ($tempa == $tempb) {
        return 0;
    }
    return ($tempa < $tempb) ? -1 : 1;
}

function sort_user_name_desc($a, $b)
{
    $tempa = ord(substr($a["use_login"], 0, 1));
    $tempb = ord(substr($b["use_login"], 0, 1));
    if ($tempa == $tempb) {
        return 0;
    }
    return ($tempa > $tempb) ? -1 : 1;
}

function sort_user_time_asc($a, $b)
{
    $tempa = $a["last_gim"];
    $tempb = $b["last_gim"];
    if ($tempa == $tempb) {
        return 0;
    }
    return ($tempa < $tempb) ? -1 : 1;
}

function sort_user_time_desc($a, $b)
{
    $tempa = $a["last_gim"];
    $tempb = $b["last_gim"];
    if ($tempa == $tempb) {
        return 0;
    }
    return ($tempa > $tempb) ? -1 : 1;
}


function save_image_url($url)
{
    $array_return = array('error' => 0, 'name' => '', 'ext' => '', 'path' => '');
    $image_content = curl_get_content($url);
    $filename = generate_picture_name();
    $path_dir = generate_dir_upload($filename, 'organic');
    $extension = get_header_type($url);
    if (!$extension) {
        $array_return['error'] = 1;
        return $array_return;
    }
    $filename = $filename . '.' . $extension;
    if (file_put_contents($path_dir . $filename, $image_content)) {
        $array_return = array('name' => $filename,
            'ext' => $extension,
            'path' => $path_dir . $filename,
            'error' => 0,
            'link' => get_picture_path($filename));
    }
    return $array_return;
}

function stop_service()
{
    header("HTTP/1.0 200");
    $rainTpl = new RainTPL();
    echo $rainTpl->draw('stop_service', 1);
    exit();
}

function tdt($variable)
{
    global $lang_display;
    if (isset($lang_display[$variable])) {
        if (trim($lang_display[$variable]) == "") {
            return "#" . $variable . "#";
        } else {
            $arrStr = array("\\\\'", '\"');
            $arrRep = array("\\'", '"');
            return str_replace($arrStr, $arrRep, $lang_display[$variable]);
        }
    } else {
        return "_@" . $variable . "@_";
    }
}

function url_add_params($url, $array_params) {
    $url_arr = explode('?',$url);
    $linked_str = '&';
    if(count($url_arr) == 1){
        $linked_str = '?';
    }
    $url .= $linked_str . http_build_query($array_params);
    return $url;
}
// hàm tương tác phân tích lấy dữ liệu từ file Excel
function analyzeExcel($filename)
{
    $inputFileType  = PHPExcel_IOFactory::identify($filename);
    $objReader      = PHPExcel_IOFactory::createReader($inputFileType);
    $objReader->setReadDataOnly(true);
    /**  Load $inputFileName to a PHPExcel Object  **/
    $objPHPExcel    = $objReader->load("$filename");
    $total_sheets   = $objPHPExcel->getSheetCount();  
    $allSheetName   = $objPHPExcel->getSheetNames();
    $objWorksheet   = $objPHPExcel->setActiveSheetIndex(0);
    // $highestRow lưu số lượng hàng trong Excel 
    $highestRow     = $objWorksheet->getHighestRow();
    $highestColumn  = $objWorksheet->getHighestColumn();
    // $highestColumnIndex lưu số lượng cột trong Excel 
    $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
    // $arraydata dùng để lưu dữ liệu của file Excel
    $arraydata      = array();
    for ($row = 2; $row <= $highestRow;++$row)
    {
        for ($col = 0; $col <$highestColumnIndex;++$col)
        {
            $value=$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
            $arraydata[$row-2][$col]=$value;
        }
    }
    // $listMenu dùng để lưu danh sách chi tiết của tất cả menu
    $listMenu =  array();
    // $arr mảng tạm để lưu chi tiết của 1 menu
    $arr = array();
    // bắt đầu xử lý
    for ($i = 1; $i < count($arraydata); $i++)
    {
        $arr['ten_thucdon'] = $arraydata[$i][1];
        $arr['donvi_tinh'] = $arraydata[$i][2];
        $arr['gia_ban'] = $arraydata[$i][3];
        $arr['menu_cap_1'] = $arraydata[$i][5];
        $arr['menu_cap_2'] = $arraydata[$i][6];
        $nguyenlieu = array();
        $chitiet = array();
        // lấy ra nguyên liêu từ cột thứ 7 đến tông số cột
        for ($j = 7 ; $j < $highestColumnIndex ; $j++)
        {
            $chitiet['ten_nguyenlieu']  = $arraydata[0][$j];
            $chitiet['soluong']         = $arraydata[$i][$j];
            if($chitiet['soluong'] != '')
            {
                $nguyenlieu[] = $chitiet;
            }
            
        }
        $arr['nguyenlieu'] = $nguyenlieu;
        $listMenu[] = $arr;
    }
    return $listMenu;
}

// ham insert thuc don va nguyen lieu lam Banh tu file excel
function analyzeExcel_Pie($filename)
{
    $inputFileType  = PHPExcel_IOFactory::identify($filename);
    $objReader      = PHPExcel_IOFactory::createReader($inputFileType);
    $objReader->setReadDataOnly(true);
    /**  Load $inputFileName to a PHPExcel Object  **/
    $objPHPExcel    = $objReader->load("$filename");  
    $total_sheets   = $objPHPExcel->getSheetCount();  
    $allSheetName   = $objPHPExcel->getSheetNames();
    $objWorksheet   = $objPHPExcel->setActiveSheetIndex(0);
    // $highestRow lưu số lượng hàng trong Excel 
    $highestRow     = $objWorksheet->getHighestRow();
    $highestColumn  = $objWorksheet->getHighestColumn();
    // $highestColumnIndex lưu số lượng cột trong Excel 
    $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
    // $arraydata dùng để lưu dữ liệu của file Excel
    $arraydata      = array();
    for ($row = 2; $row <= $highestRow;++$row)
    {
        for ($col = 0; $col <$highestColumnIndex;++$col)
        {
            $value=$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
            $arraydata[$row-2][$col]=$value;
        }
    }
    // $listMenu dùng để lưu danh sách chi tiết của tất cả menu
    $listMenu =  array();
    // $arr mảng tạm để lưu chi tiết của 1 menu
    $arr = array();
    // bắt đầu xử lý
    for ($i = 1; $i < count($arraydata); $i++)
    {
        $arr['ten_thucdon'] = $arraydata[$i][1];
        $arr['donvi_tinh'] = $arraydata[$i][2];
        $arr['gia_ban'] = $arraydata[$i][3];
        $arr['menu_cap_1'] = $arraydata[$i][4];
        $arr['menu_cap_2'] = $arraydata[$i][5];
        $arr['nguyen_lieu'] = $arraydata[$i][6];
        $listMenu[] = $arr;
    }
    return $listMenu;
}
// ham insert nguyen lieu tu file excel
function analyzeExcel_Material($filename)
{
    $inputFileType  = PHPExcel_IOFactory::identify($filename);
    $objReader      = PHPExcel_IOFactory::createReader($inputFileType);
    $objReader->setReadDataOnly(true);
    /**  Load $inputFileName to a PHPExcel Object  **/
    $objPHPExcel    = $objReader->load("$filename");  
    $total_sheets   = $objPHPExcel->getSheetCount();  
    $allSheetName   = $objPHPExcel->getSheetNames();
    $objWorksheet   = $objPHPExcel->setActiveSheetIndex(0);
    // $highestRow lưu số lượng hàng trong Excel 
    $highestRow     = $objWorksheet->getHighestRow();
    $highestColumn  = $objWorksheet->getHighestColumn();
    // $highestColumnIndex lưu số lượng cột trong Excel 
    $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
    // $arraydata dùng để lưu dữ liệu của file Excel
    $arraydata      = array();
    for ($row = 2; $row <= $highestRow;++$row)
    {
        for ($col = 0; $col <$highestColumnIndex;++$col)
        {
            $value=$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
            $arraydata[$row-2][$col]=$value;
        }
    }
    // $listMenu dùng để lưu danh sách chi tiết của tất cả menu
    $listMenu =  array();
    // $arr mảng tạm để lưu chi tiết của 1 menu
    $arr = array();
    // bắt đầu xử lý
    for ($i = 1; $i < count($arraydata); $i++)
    {
        $arr['ten_nguyenlieu'] = $arraydata[$i][0];
        $arr['donvi_tinh'] = $arraydata[$i][1];
        $arr['menu_cap_1'] = $arraydata[$i][3];
        $listMenu[] = $arr;
    }
    return $listMenu;
}