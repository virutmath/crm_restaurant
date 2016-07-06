<?
$frame_src = getValue('href','str','POST','',3);
$frame_src = url_add_params($frame_src,array('frame_source_request'=>'iframe'));
$module_name = getValue('modal_name','str','POST',3);
$module_rightname = getValue('modal_rightname','str','POST',3);
$rainTpl = new RainTPL();
$rainTpl->assign('frame_src',$frame_src);
$rainTpl->assign('module_name',$module_name);
$rainTpl->assign('module_rightname',$module_rightname);
$rainTpl->draw('modal_template');