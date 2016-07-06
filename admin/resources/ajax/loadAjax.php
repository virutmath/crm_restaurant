<?
require_once '../security/security.php';
$action = getValue('name','str','GET','',3);

switch($action){
    case 'loadModal':
        if(file_exists('load_modal.php'))
            include_once 'load_modal.php';
        break;
    case 'loadMindow' :
        if(file_exists('load_mindow.php'))
            include_once 'load_mindow.php';
        break;
}