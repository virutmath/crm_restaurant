<?
function t($str){
    if(!TRANSLATED)
        return $str;
    else{
        $str = replaceMQ($str);
        $str = mb_strtolower($str,'UTF-8');
        $db = new db_query('SELECT tra_text FROM translate_text WHERE tra_keyword = "'.$str .'" LIMIT 1');
        $text = mysqli_fetch_assoc($db->result);
        if($text){
            $text = $text['tra_text'];
            return $text;
        }
        //Nếu chưa tồn tại từ này thì insert vào csdl
        $db_insert = new db_execute('INSERT INTO translate_text (tra_keyword,tra_text) VALUES("'.$str.'","'.$str.'")');
        return '{'.$str.'}';
    }
}