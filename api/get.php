<?
require_once 'config.php';

class RestaurantGet
{
    var $download_result;
    var $username;
    var $password;

    function __construct()
    {
        $this->username = getValue('username', 'str', 'POST', '');
        $this->password = getValue('password', 'str', 'POST', '');
        if (!$this->username || !$this->password) {
            die('Username and password are required');
        }
        //check xem username password có đúng là user admin gốc không
        $user_id = checkLogin($this->username, $this->password);
        if ($user_id != 1) {
            die('Username and password aren\'t valid');
        }
    }

    public function getListAgency() {
        $db = new db_query('SELECT * FROM agencies');
        $array_agencies = $db->resultArray();unset($db);
        $this->add($array_agencies);
    }

    public function get()
    {
        $function = getValue('action', 'str', 'POST', '');
        if (method_exists($this, $function)) {
            $this->$function();
            ob_clean();
            echo $this->download_result;
        } else {
            ob_clean();
            die('Method download -- ' . $function . ' -- doesn\'t exist!');
        }
    }

    private function add($string = '')
    {
        if (is_array($string)) {
            $string = json_encode($string);
        }
        $this->download_result .= $string;
        return $this;
    }
}
$get = new RestaurantGet();
$get->get();