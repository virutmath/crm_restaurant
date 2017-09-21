<?

if(strpos($_SERVER['HTTP_HOST'],'127.0.0.1') === false && strpos($_SERVER['HTTP_HOST'],'localhost') === false && strpos($_SERVER['HTTP_HOST'],'192.168') === false){

    define('DB_HOST','localhost');
    define('DB_USER','u637983774_crm');
	define('DB_NAME','u637983774_crm');
	define('DB_PASS','PskNKIne64');
}else{

    define('DB_HOST','mysql');
    define('DB_USER','root');
	define('DB_NAME','crm_restaurant');
	define('DB_PASS','123456');
}

