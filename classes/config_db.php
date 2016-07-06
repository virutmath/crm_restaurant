<?
if($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '192.168.1.213'){
    define('DB_USER','root');
    define('DB_NAME','crm_restaurant');
    define('DB_PASS','');
}else{
    define('DB_USER','sql_khangtest');
    define('DB_NAME','db_khangtest');
    define('DB_PASS','NIJcTeLakufn46gxuzXsUoNAQ');
}