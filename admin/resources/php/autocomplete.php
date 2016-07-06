<?
require_once '../security/security.php';
$keyword = getValue('query','str','GET','',2);
if($keyword){
    $db_query = new db_query('SELECT *
                                FROM suggestion_text
                                WHERE sug_text LIKE "%'.$keyword.'%"
                                ORDER BY sug_text ASC
                                LIMIT 10');
    $array_return = array('suggestions'=>array());
    while($row = mysqli_fetch_assoc($db_query->result)){
        $array_return['suggestions'][] = $row['sug_text'];
    }
    $array_return['query'] = $keyword;
    echo json_encode($array_return);
}
