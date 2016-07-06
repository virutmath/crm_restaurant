<?
$frame_src = getValue('url','str','POST','',3);
//gán thêm frame_request để xác nhận kiểu hiển thị
$frame_src = url_add_params($frame_src, array('frame_source_request'=>'mindow'));
$title = getValue('title','str','POST',3);

$rainTpl = new RainTPL();
$rainTpl->assign('frame_src',$frame_src);
$rainTpl->assign('mindow_title',$title);
$rainTpl->draw('mindow_iframe');