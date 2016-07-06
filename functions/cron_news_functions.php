<?
function get_law_index($law)
{
    //quy uoc luat : law_string(index)
    $law = explode('(', $law);
    if (isset($law[1])) {
        $index = $law[1];
        $law = $law[0];
        return array('law' => $law, 'index' => rtrim($index, ')'));
    } else {
        $law = $law[0];
        return array('law' => $law, 'index' => 0);
    }
}

function get_law_remove($law)
{
    //quy uoc tập hợp luật remove ngăn cách bởi dấu |
    $array_remove = explode('|', $law);
    $array_return = array();
    foreach ($array_remove as $remove) {
        $array_return[] = get_law_index($remove);
    }
    return $array_return;
}

function pre_cron_news_detail($link_data = array(
        'lin_id' => '',
        'lin_url' => '',
        'lin_cat_id'=>'',
        'law_detail_title' => '',
        'law_detail_content' => '',
        'law_detail_teaser' => '',
        'law_detail_tag' => '',
        'law_detail_remove' => ''
    ),$accept_no_image = 0)
{
    $link_id = $link_data['lin_id'];
    $link_url = $link_data['lin_url'];
    $link_domain = get_domain($link_url);
    $law_title = get_law_index($link_data['law_detail_title']);
    $law_content = get_law_index($link_data['law_detail_content']);
    $law_teaser = get_law_index($link_data['law_detail_teaser']);
    $law_tag = get_law_index($link_data['law_detail_tag']);
    $law_remove = get_law_remove($link_data['law_detail_remove']);

    if (!check_url_status($link_url, array(200, 301, 304, 302))) {
        //cap nhat link ko lay duoc
        if($link_id){
            $db_update = new db_execute('UPDATE links SET lin_status = ' . LINK_STATUS_FAIL . ' WHERE lin_id = ' . $link_id);
            unset($db_update);
        }
        $array_return = array('success'=>0,'error'=>'Link không tồn tại hoặc đã bị xóa : ' . $link_url,'error_code'=>1);
        return $array_return;
    }
    $html_content = curl_get_content($link_url);
    $html_content = str_get_html($html_content);
    //lấy ảnh trước - nếu ko có ảnh thì die()
    //lưu ảnh đại diện
    $image = trim($html_content->find('[property="og:image"]', 0)->content);
    $array_save_image = save_image_url($image);
    if ($array_save_image['error'] || !$array_save_image['name']) {
        if(!$accept_no_image && $link_id){
            $db_update = new db_execute('UPDATE links SET lin_status = ' . LINK_STATUS_FAIL . ' WHERE lin_id = ' . $link_id);
            unset($db_update);
            $array_return = array('success'=>0,'error'=>'Tin không có ảnh ' . $link_url,'error_code'=>1);
            return $array_return;
        }
    }
    //Lấy tiêu đề
    $title = $html_content->find($law_title['law'], $law_title['index'])->innertext;
    if (!$title) {
        //lỗi không lấy được tin
        //cập nhật link ko lấy được
        if($link_id){
            $db_update = new db_execute('UPDATE links SET lin_status = ' . LINK_STATUS_FAIL . ' WHERE lin_id = ' . $link_id);
            unset($db_update);
        }
        $array_return = array('success'=>0,'error'=>'Link không có tiêu đề hoặc luật đã bị thay đổi : ' . $link_url,'error_code'=>1);
        return $array_return;
    }
    //xử lý remove các content như quảng cáo...
    foreach ($law_remove as $remove) {
        $j = 0;
        foreach ($html_content->find($remove['law']) as $elm_rm) {
            if ($j == $remove['index']) {
                $elm_rm->outertext = '';
                break;
            }
            $j++;
        }
    }
    //lấy content
    $content = $html_content->find($law_content['law'], $law_content['index'])->innertext;
    //lấy ảnh đại diện

    //lấy tag
    $tags_html = $html_content->find($law_tag['law'], $law_tag['index']);
    $tag_string = '';
    if ($tags_html) {
        //các tag của bài thường được lọc theo từng thẻ a, tách text của tag từng thẻ a rồi nối vào tag_string
        foreach ($tags_html->find('a') as $t_elem) {
            $tag_string .= removeHTML(trim($t_elem->innertext)) . ',';
        }
        $tag_string = rtrim($tag_string, ',');
    }
    //lấy teaser
    $teaser = $html_content->find($law_teaser['law'], $law_content['index'])->innertext;


    $time = time();
    $active = 1;
    //xử lý dữ liệu
    $title = replaceFCK($title,1);
    $title = replaceNCR($title);
    $title = remove_source($title);
    $title = removeHTML($title);
    $title_md5 = md5($title);

    $content = replaceFCK($content,1);
    $content = replaceNCR($content);
    $content = remove_script($content);
    $content = removeLink($content);
    $content = remove_source($content);

    //Nếu content trống thì bỏ qua
    if (!trim($content)) {
        if($link_id){
            $db_update = new db_execute('UPDATE links SET lin_status = ' . LINK_STATUS_FAIL . ' WHERE lin_id = ' . $link_id);
            unset($db_update);
        }
        $array_return = array('success'=>0,'error'=>'Nội dung trống hoặc không đúng luật ' . $link_url,'error_code'=>1);
        unset($db_update);
        return $array_return;
    }

    $tag_string = replaceFCK($tag_string,1);
    $tag_string = replaceNCR($tag_string);
    $tag_string = removeHTML($tag_string);

    $teaser = replaceFCK($teaser,1);
    $teaser = replaceNCR($teaser);
    $teaser = removeHTML($teaser);
    $teaser = removeLink($teaser);
    $teaser = remove_source($teaser);

    $category = $link_data['lin_cat_id'];
    //lấy ảnh trong detail
    $all_image_detail = get_image_src_from_html($content, $link_domain);
    foreach ($all_image_detail as $key => $img_src) {
        //save anh va replace anh trong chi tiết
        $img_save = save_image_url($img_src);
        if (!$img_save['error']) {
            //đã lưu ảnh - thay thế link ảnh vào content
            $content = str_replace($img_src, $img_save['link'], $content);
        }
    }

    //echo $image;

    $image = $array_save_image['name'];
    $array_return = array(
        'success'=>1,
        'title'=>$title,
        'title_md5'=>$title_md5,
        'content'=>$content,
        'teaser'=>$teaser,
        'category'=>$category,
        'image'=>$image,
        'link_id'=>$link_id,
        'link_url'=>$link_url,
        'tag_string'=>$tag_string,
    );
    return $array_return;
}