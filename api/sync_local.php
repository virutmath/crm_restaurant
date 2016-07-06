<?
require_once 'config.php';
class LocalSynchronize {
    var $synchronize_return;
    var $username;
    var $password;
    function __construct() {
        $this->username = getValue('username', 'str', 'POST', '');
        $this->password = getValue('password', 'str', 'POST', '');
        if (!$this->username || !$this->password) {
            die('Username and password are required');
        }
        //kiểm tra đăng nhập - password mã hóa
        $db_check	= new db_query("SELECT adm_id
								 FROM admin_users
								 LEFT JOIN admin_users_groups ON adu_group_id = adm_group_id
								 WHERE adm_loginname = '" . $this->username . "' AND adm_password = '" . $this->password."'");
        if(mysqli_num_rows($db_check->result) <= 0){
            http_response_code(403);
            die('Username hoặc password không chính xác');
        }
    }

    public function execute()
    {
        $function = getValue('action', 'str', 'POST', '');
        if (method_exists($this, $function)) {
            $this->$function();
            ob_clean();
            echo $this->synchronize_return;
        } else {
            ob_clean();
            die('Method download -- ' . $function . ' -- doesn\'t exist!');
        }
    }

    public function syncLogQuery() {
        $array_return = array('success'=>1,'error'=>0);
        $queries = getValue('queries','str','POST','');

        $queries = json_decode(base64_decode($queries),1);
        if(!$queries) {
            $this->add($array_return);
            die();
        }
//        $array_return['sdfsdf'] = $queries;
        foreach($queries as $time=>$list_query) {
            foreach($list_query as $query) {
                $query = decode_combine($query);
                //$array_return['queries'][] = $query;
                $db_execute = new db_execute($query);
                unset($db_execute);
            }
        }
        $this->add($array_return);
    }

    private function add($string = '')
    {
        if (is_array($string)) {
            $string = json_encode($string);
        }
        $this->synchronize_return .= $string;
        return $this;
    }
}
$sync = new LocalSynchronize();
$sync->execute();
