<?php

/**
 * Generate Keywords From Content
 */
class TagsGenerate
{
    private $text;
    private $title;
    private $teaser;
    const MIN_CHAR = 3;
    const STOP_WORDS = 'a ha,a lô,à ơi,á,à,á à,ạ,ạ ơi,ai,ai ai,ai nấy,ái,ái chà,ái dà,alô,amen,áng,ào,ắt,ắt hẳn,ắt là,âu là,ầu ơ,ấy,bài,bản,bao giờ,bao lâu,bao nả,bao nhiêu,bay biến,bằng,bằng ấy,bằng không,bằng nấy,bắt đầu từ,bập bà bập bõm,bập bõm,bất chợt,bất cứ,bất đồ,bất giác,bất kể,bất kì,bất kỳ,bất luận,bất nhược,bất quá,bất thình lình,bất tử,bây bẩy,bây chừ,bây giờ,bây giờ,bây nhiêu,bấy,bấy giờ,bấy chầy,bấy chừ,bấy giờ,bấy lâu,bấy lâu nay,bấy nay,bấy nhiêu,bèn,béng,bển,bệt,biết bao,biết bao nhiêu,biết chừng nào,biết đâu,biết đâu chừng,biết đâu đấy,biết mấy,bộ,bội phần,bông,bỗng,bỗng chốc,bỗng dưng,bỗng đâu,bỗng không,bỗng nhiên,bỏ mẹ,bớ,bởi,bởi chưng,bởi nhưng,bởi thế,bởi vậy,bởi vì,bức,cả,cả thảy cái,các,cả thảy,cả thể,càng,căn,căn cắt,cật lực,cật sức,cây,cha ,cha chả,chành chạnh,chao ôi,chắc,chắc hẳn,chăn chắn,chăng,chẳng lẽ,chẳng những,chẳng nữa,chẳng phải,chậc,chầm chập,chết nỗi,chết tiệt,chết thật,chí chết,chỉn,chính,chính là,chính thị,chỉ,chỉ do,chỉ là,chỉ tại,chỉ vì,chiếc,cho đến,cho đến khi,cho nên,cho tới,cho tới khi,choa,chốc chốc,chớ,chớ chi,chợt,chú,chu cha,chú mày,chú mình,chui cha,chùn chùn,chùn chũn,chủn,chung cục,chung qui,chung quy,chung quy lại,chúng mình,chúng ta,chúng tôi,chứ,chứ lị,có chăng là,có dễ,có vẻ,cóc khô,coi bộ,coi mòi,con,còn,cô,cô mình,cổ lai,công nhiên,cơ,cơ chừng,cơ hồ,cơ mà,cơn,cu cậu,của,cùng,cùng cực,cùng nhau,cùng với,cũng,cũng như,cũng vậy,cũng vậy thôi,cứ,cứ việc,cực kì cực kỳ,cực lực,cuộc,cuốn,dào,dạ,dần dà,dần dần,dầu sao,dẫu,dẫu sao,dễ sợ,dễ thường,do,do vì,do đó,do vậy,dở chừng,dù cho,dù rằng,duy,dữ,dưới,đã,được,đại để,đại loại,đại nhân,đại phàm,đang,đáng lẽ,đáng lí,đáng lý,đành đạch,đánh đùng,đáo để,nấy,nên chi,nền,nếu,nếu như,ngay,ngay cả,ngay lập tức,ngay lúc,ngay khi,ngay từ,ngay tức khắc,ngày càng,ngày ngày,ngày xưa,ngày xửa,ngăn ngắt,nghe chừng,nghe đâu,nghen,nghiễm nhiên,nghỉm,ngõ hầu,ngoải,ngoài,ngôi,ngọn,ngọt,ngộ nhỡ,ngươi,nhau,nhân dịp,nhân tiện,nhất,nhất đán,nhất định,nhất loạt,nhất luật,nhất mực,nhất nhất,nhất quyết,nhất sinh,nhất tâm,nhất tề,nhất thiết,nhé,nhỉ,nhiên hậu,nhiệt liệt,nhón nhén,nhỡ ra,nhung nhăng,như,như chơi,như không,như quả,như thể,như tuồng,như vậy,nhưng nhưng mà,những,những ai,những như,nhược bằng,nó,nóc,nọ,nổi,nớ,nữa,nức nở,oai oái,oái,ô hay,ô hô,ô kê,ô kìa,ồ,ôi chao,ôi thôi,ối dào,ối giời,ối giời ơi,ôkê,ổng,ơ,ơ hay,ơ kìa,ờ,ớ,ơi,phải,phải chi,phải chăng,phăn phắt,phắt,phè,phỉ phui,pho,phóc,phỏng,phỏng như,phót,phốc,phụt,phương chi,phứt,qua quít,qua quýt,quả,quả đúng,quả làquả tang,quả thật,quả tình,quả vậy,quá,quá chừng,quá độ,quá đỗi,quá lắm,quá sá,quá thể,quá trời,quá ư,quá xá,quý hồ,quyển,quyết,quyết nhiên,ra,ra phết,ra trò,ráo,ráo trọi,rày,răng,rằng,rằng là,rất,rất chi là,rất đỗi,rất mực,ren rén,rén,rích,riệt,riu ríu,rón rén,rồi,rốt cục,rốt cuộc,rút cục,rứa,sa sả sạch,sao,sau chót,sau cùng,sau cuối,sau đó,sắp,sất,sẽ,sì,song le,số là,sốt sột,sở dĩ,suýt,sự,tà tà,tại,tại vì,tấm,tấn,tự vì,tanh,tăm tắp,tắp,tắp lự,tất cả,tất tần tật,tất tật,tất thảy,tênh,tha hồ,thà,thà là,thà rằng,thái quá,than ôi,thanh,thành ra,thành thử,thảo hèn,thảo nào,thậm,thậm chí,thật lực,thật vậy,thật ra,thẩy,thế,thế à,thế là,thế mà,thế nào,thế nên,thế ra,thế thì,thếch,thi thoảng,thì,thình lình,thỉnh thoảng,thoạt,thoạt nhiên,thoắt,thỏm,thọt,thốc,thốc tháo,thộc,thôi,thốt,thốt nhiên,thuần,thục mạng,thúng thắng,thửa,thực ra,thực vậy,thương ôi,tiện thể,tiếp đó,tiếp theo,tít mù,tỏ ra,tỏ vẻ,tò te,toà,toé khói,toẹt,tọt,tốc tả,tôi,tối ư,tông tốc,tột tràn cung mây,trên,trển,trệt,trếu tráo,trệu trạo,trong,trỏng,trời đất ơi,trước,trước đây,trước đó,trước kia,trước nay,trước tiên,trừ phi,tù tì,tuần tự,tuốt luốt,tuốt tuồn tuột,tuốt tuột,tuy,tuy nhiên,tuy rằng,tuy thế,tuy vậy,tuyệt nhiên,từng,tức thì,tức tốc,tựu trung,ủa,úi,úi chà,úi dào,ư,ứ hự,ứ ừ,ử,ừ,và,vả chăng,vả lại,vạn nhất,văng tê,vẫn,vâng,vậy,vậy là,vậy thì,veo,veo veo,vèo,về,vì,vì chưng,vì thế,vì vậy,ví bằng,ví dù,ví phỏng,ví thử,vị tất,vô hình trung,vô kể,vô luận,vô vàn,vốn dĩ,với,với lại,vở,vung tàn tán,vung tán tàn,vung thiên địa,vụt,vừa mới,xa xả,xăm xăm,xăm xắm,xăm xúi,xềnh xệch,xệp,xiết bao,xoành xoạch,xoẳn,xoét,xoẹt,xon xón,xuất kì bất ý,xuất kỳ bất ý,xuể,xuống,ý,ý chừng,ý da';
    const BAD_WORDS = 'nguyễn bá thanh, dương chí dũng, trương tấn sang, nguyễn minh triết, chủ tịch nước, thủ tướng, chính phủ, thủ tướng x, tổng bí thư, nguyễn phú trọng, trọng lú,  nguyễn tấn dũng, hoàng sa, trường sa, hoang sa, truong sa, nguyễn tấn dũng, nguyen tan dung, đảng,công an,đảng cộng sản,dang cong san,việt nam cộng hòa,vietnam cong hoa, vietnam cộng hòa,viet nam cong hoa, vn cong hoa,việt nam dân chủ cộng hòa,viet nam dan chu cong hoa,vietnam dan chu cong hoa,phản động,bạo động,bạo loạn,bác hồ,hồ chí minh,nguyễn ái quốc,địt,lồn,buồi,dái,cặc';
    const GOOGLE_SUGGEST_LINK = 'http://google.com.vn/complete/search?output=toolbar&q=%s';
    const COCCOC_SUGGEST_KEYWORDS_LINK = 'http://coccoc.com/autocomplete.json?term=%s';

    function __construct($text, $title = '', $teaser = '')
    {
        $this->text = self::clean($text);
        $this->title = mb_strtolower(self::clean($title), 'UTF-8');
        $this->teaser = mb_strtolower(self::clean($teaser), 'UTF-8');
    }

    private static function clean($text)
    {
        //Remove HTML Tags
        $text = trim(strip_tags($text));

        /*
        //Remove stopwords
        $stop_words = explode(',', self::STOP_WORDS);
        foreach($stop_words as $stop_word)
        {
           $text = str_replace(' '. $stop_word . ' ', ' ', $text);
        }
        */
        //Remove Special charactor
        $text = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $text);
        $array_special_char = array(chr(9), chr(10), chr(13), "nbsp", "-", '"', ".", "?", ":", "*", "%", "#", "|", "/", "\\", ",", "‘", "’", '“', '”', "&nbsp;");
        $text = str_replace($array_special_char, " ", $text);

        //Remove Mutil Space
        //$text = preg_replace('!\s+!', ' ', $text);
        $text = str_replace("   ", " ", $text);
        $text = str_replace("  ", " ", $text);
        $text = str_replace("  ", " ", $text);
        $text = str_replace("  ", " ", $text);
        $text = str_replace("  ", " ", $text);
        $text = str_replace("  ", " ", $text);
        $text = str_replace("  ", " ", $text);
        return trim($text);
    }

    /**
     * Extract some keywords from content
     * 1. Extract keywords with first charactor is Uppercse
     * 2. Rxxtract keywords appearance in title and teaser
     */
    private function extract_keywords()
    {
        $text = $this->text;
        $title = $this->title;
        $teaser = $this->teaser;

        $collection_words = array();

        $content_arr = explode(" ", $text);
        $content_arr = array_filter($content_arr);
        $content_arr = array_values($content_arr);
        $upper_words = array();
        //Extract capitalised words
        foreach ($content_arr as $key => $value):
            if ((ord($value{0}) >= 65 && ord($value{0}) <= 90)) {
                $temp = $value;
                $check = false;
                for ($i = 1; $i <= 5; $i++) {
                    if (isset($content_arr[$key + $i]) && (ord($content_arr[$key + $i]{0}) >= 65 && ord($content_arr[$key + $i]{0}) <= 90)) {
                        $check = true;
                        $temp .= ' ' . $content_arr[$key + $i];
                    } else {
                        break;
                    }
                }
                if ($check) {
                    $temp = mb_strtolower($temp, 'UTF-8');
                    $collection_words[] = $temp;
                    $upper_words[] = $temp;

                }

            }
        endforeach;

        /**
         * Đánh điểm cho mảng chữ hoa
         */
        $upper_words = array_count_values($upper_words);

        $text = mb_strtolower($text, 'UTF-8');

        //Remove stopwords
        $stop_words = explode(',', self::STOP_WORDS);
        foreach ($stop_words as $stop_word) {
            $text = str_replace(' ' . $stop_word . ' ', ' ', $text);
        }
        $content_arr = explode(" ", mb_strtolower($text, 'UTF-8'));
        $content_arr = array_filter($content_arr);
        $content_arr = array_values($content_arr);

        //Remove words length not grater than MIN_CHAR
        foreach ($content_arr AS $key => $one_word) {
            if (mb_strlen($one_word, 'UTF-8') < self::MIN_CHAR) {
                unset($content_arr[$key]);
            }
        }
        $content_arr = array_values($content_arr);

        foreach ($content_arr as $k => $value):
            if (isset($content_arr[$k])):
                $collection_words[] = trim($value);

                //Build sence have 1-2-3-4-5 words
                $temp = $value;
                for ($i = 1; $i <= 5; $i++) {
                    for ($j = 1; $j <= $i; $j++) {
                        if ($k + $j < count($content_arr) - $j) {
                            $temp1 = isset($content_arr[$k + $j]) ? $content_arr[$k + $j] : '';
                            $temp .= ' ' . $temp1;
                        }
                    }
                    $collection_words[] = trim($temp);
                    $temp = '';

                }
            endif;
        endforeach;

        $collection_words = array_filter($collection_words);
        $collection_words_count = array_count_values($collection_words);


        $keywords_match = array();
        foreach ($collection_words_count as $one_word => $point) {
            //Nếu thuộc mảng chữ hoa thì nhân điểm với số điểm của chữ hoa
            if (array_key_exists($one_word, $upper_words)) {
                $point *= $upper_words[$one_word];
            }
            $arr_exp = explode(" ", $one_word);
            $point = (count($arr_exp) > 1) ? $point * count($arr_exp) * 1.5 : $point;
            if (mb_strpos($title, $one_word)) {
                $point *= $point;
            }
            if (mb_strpos($teaser, $one_word)) {
                $point += 5;
            }
            $keywords_match[$one_word] = $point;
        }

        arsort($keywords_match);
        //var_dump($keywords_match);
        return $keywords_match;
    }

    private function coccoc_trends($keywords)
    {
        $tags = array();
        if (!$keywords || empty($keywords)) {
            return false;
        }

        //Tổng số điểm của tất cả từ khóa
        $total_point = array_sum($keywords);
        foreach (array_keys($keywords) as $keyword) {
            //Bắn lên Google Trends hoặc CocCoc để lấy từ khóa gợi ý của nó
            $link = sprintf(self::COCCOC_SUGGEST_KEYWORDS_LINK, $keyword);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $link);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $server_output = curl_exec($ch);
            curl_close($ch);

            $cc_result = json_decode($server_output, true);
            if (isset($cc_result['suggestions']) && !empty($cc_result['suggestions'])) {
                //Tính số phần trăm điểm của từ khóa
                $keyword_point = ceil(($keywords[$keyword] / $total_point) * 100);
                //shuffle($cc_result['suggestions']);
                $cc_result['suggestions'] = array_slice($cc_result['suggestions'], 0, $keyword_point);
                $tags = array_merge($tags, $cc_result['suggestions']);
            }
        }
        return $tags;
    }

    private function google_trends($keywords)
    {
        $tags = array();
        if (!$keywords || empty($keywords)) {
            return false;
        }

        $hot_keywords = @reset(array_keys($keywords));

        //Tổng số điểm của tất cả từ khóa
        $total_point = array_sum($keywords);
        foreach (array_keys($keywords) as $keyword) {
            $suggest_result = array();
            //Bắn lên Google Trends hoặc CocCoc để lấy từ khóa gợi ý của nó
            $link = sprintf(self::GOOGLE_SUGGEST_LINK, urlencode($keyword));
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $link);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $server_output = curl_exec($ch);
            $server_output = utf8_encode($server_output);
            curl_close($ch);
            $google_xml = new SimpleXMLElement($server_output);
            foreach ($google_xml->CompleteSuggestion as $complate_suggest) {
                $suggest_result[] = (string)$complate_suggest->suggestion->attributes()->data;
            }
            if (isset($suggest_result) && !empty($suggest_result)) {
                $suggest_result = $this->sort_keywords($suggest_result);
                //Tính số phần trăm điểm của từ khóa
                $keyword_point = ceil(($keywords[$keyword] / $total_point) * 100);
                $suggest_result = array_slice($suggest_result, 0, $keyword_point);
                $tags = array_merge($tags, $suggest_result);
            } else {
                $keywords_suggest = self::coccoc_trends(array($keyword => $keywords[$keyword]));
                $keywords_suggest = $this->sort_keywords($keywords_suggest);
                $keyword_point = ceil(($keywords[$keyword] / $total_point) * 100);
                $keywords_suggest = array_slice($keywords_suggest, 0, $keyword_point);

                $tags = array_merge($tags, $keywords_suggest);
            }
        }
        return array_unique($tags);
    }

    private function sort_keywords($keywords)
    {
        $keywords = array_count_values($keywords);

        $this->title = mb_strtolower($this->title, 'UTF-8');
        $this->teaser = mb_strtolower($this->teaser, 'UTF-8');
        $this->text = mb_strtolower($this->text, 'UTF-8');
        $bad_words = explode(',', self::BAD_WORDS);
        $bad_words = array_map('trim', $bad_words);
        foreach ($keywords as $keyword => $value) {
            $bad = false;
            $keyword_lower = mb_strtolower($keyword);
            foreach ($bad_words as $bad_word) {
                if (mb_strpos(' ' . $keyword_lower . ' ', $bad_word)) {
                    $bad = true;
                    unset($keywords[$keyword]);
                    break;
                }
            }
            if ($bad) {
                continue;
            }
            $keyword_check = mb_strtolower($keyword, 'UTF-8');
            $keyword_check = self::clean($keyword_check);

            if (trim($keyword_check) == '') {
                unset($keywords[$keyword]);
                continue;
            }
            if (mb_strpos($this->title, $keyword_check)) {
                $keywords[$keyword] += 100;
            }
            if (mb_strpos($this->teaser, $keyword_check)) {
                $keywords[$keyword] += 20;
            }
            if (mb_strpos($this->text, $keyword_check)) {
                $keywords[$keyword] += 10;
            }

            $keywords_compare = explode(' ', $keyword_check);
            $keywords_compare = array_filter($keywords_compare);
            foreach ($keywords_compare as $one_word) {
                if (mb_strpos($this->title, $one_word)) {
                    $keywords[$keyword] += 10;
                }
                if (mb_strpos($this->teaser, $one_word)) {
                    $keywords[$keyword] += 5;
                }
                if (mb_strpos($this->text, $one_word)) {
                    $keywords[$keyword]++;
                }
            }

        }
        arsort($keywords);
        return array_keys($keywords);
    }

    public function generate($limit = 10, $min_word = 1)
    {
        $keywords = $this->extract_keywords();
        $bad_words = explode(',', self::BAD_WORDS);
        $bad_words = array_map('trim', $bad_words);
        $keywords = array_slice($keywords, 0, $limit);

        $keywords = $this->google_trends($keywords);
        foreach ($keywords as $key => $keyword) {
            //$keyword_lower = mb_strtolower($keyword);
            foreach ($bad_words as $bad_word) {
                $keyword_lower = removeAccent($keyword);
                $keyword_lower = strtolower($keyword_lower);
                $bad_word = removeAccent($bad_word);
                if (strpos(' '.$keyword_lower.' ', $bad_word)) {
                    unset($keywords[$key]);
                    break;
                }
            }
            //Lọc các từ có bao nhiêu ký tự
            if($min_word >= 2){
                $count_word = explode(' ',$keyword);
                if(count($count_word) < $min_word){
                    unset($keywords[$key]);
                }
            }
        }

        if ($keywords && !empty($keywords)) {
            $keywords = array_unique($keywords);
            $keywords = $this->sort_keywords($keywords);
            $keywords = array_filter($keywords);
            return array_slice($keywords, 0, $limit);
        }
        return array();
    }
}

function TagsGenerate($contents, $title = '', $teaser = '')
{
    return new TagsGenerate($contents, $title, $teaser);
}

?>