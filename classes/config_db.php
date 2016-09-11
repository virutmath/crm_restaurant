<?
if(strpos($_SERVER['HTTP_HOST'],'localhost') === false){
	define('DB_USER','u637983774_crm');
	define('DB_NAME','u637983774_crm');
	define('DB_PASS','PskNKIne64');
}else{
	define('DB_USER','root');
	define('DB_NAME','crm_restaurant');
	define('DB_PASS','');
}

