<?
class user{
    var $id;
    private $logged = 0;
    var $email;
    var $phone;
    var $password;
    var $fullname;
    var $vg_id;
    public function __construct($use_email = '',$password = ''){
        $checkcookie  = 0;
        $this->logged = 1;
        return true;
        if ($use_email == "") {
            if (isset($_COOKIE["_emu"])) $use_email = $_COOKIE["_emu"];
        }
        if ($password == "") {
            if (isset($_COOKIE["_emp"])) $password = $_COOKIE["_emp"];
            $checkcookie = 1;
        } else {
            //remove \' if gpc_magic_quote = on
            $password = str_replace("\'", "'", $password);
        }

        if ($use_email == "" && $password == "") return;
        $db_user = new db_query('SELECT * FROM users WHERE use_email = "'.$use_email.'"');

        if($row = mysqli_fetch_assoc($db_user->result)){
            if ($checkcookie == 0) $password = md5($password . $row["use_security"]);
            if($password == $row['use_password']){
                $this->logged = 1;
                $this->id = $row['use_id'];
                $this->fullname = $row['use_name'];
                $this->email = $row['use_email'];
                $this->password = $password;
                $this->phone = $row['use_phone'];
                $this->vg_id = $row['use_vg_id'];
            }
        }
    }
    function savecookie($time = 0)
    {
        if ($this->logged != 1) return false;
        if ($time > 0) {
            setcookie("_emu", $this->email, time() + $time, "/");
            setcookie("_emp", $this->password, time() + $time, "/");
            //setcookie("u_id",$this->u_id,time()+$time);
        } else {
            setcookie("_emu", $this->email, null, "/");
            setcookie("_emp", $this->password, null, "/");
            //setcookie("u_id",$this->u_id);
        }
    }
    public function logged(){
        return $this->logged;
    }
    function logout()
    {
        setcookie("_emu", " ", null, "/");
        setcookie("_emp", " ", null, "/");
        $_COOKIE["_emu"]  = "";
        $_COOKIE["_emp"] = "";
        //setcookie("u_id","",time()-200000);
        $this->logged = 0;
    }
}