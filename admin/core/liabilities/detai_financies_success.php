<?
require_once 'inc_security.php';
if(!isset($_GET['id'])){
    die;
}
$record_id          = getValue('id','int','GET',0);
$content_column     = '';
$footer_control     = '';
$list = new dataGrid($record_id,1);
$list->add('', 'Số HĐ');
$list->add('', 'Ngày ghi nợ');
$list->add('', 'Tổng ghi nợ');
$list->add('', 'Thanh toán');


$db_financies       = new db_query('SELECT * FROM financial 
                                    WHERE fin_id = ' . $record_id .' 
                                    ORDER BY ' . $list->sqlSort() . ' fin_id
                                    ASC ' . $list->limit(1));
if($data_financial  = mysqli_fetch_assoc($db_financies->result)){
    //số tiền thanh toán
    $thanhtoan                          = $data_financial['fin_money'];
// nếu fin cat id là thanh toán công nợ khách hàng thì select bảng bill in lấy ra ngày nợ túc là ngày tạo hóa đơn nợ
    if($data_financial['fin_cat_id']    == 33){
        $db_bill                        = new db_query('SELECT * FROM bill_in WHERE bii_id = ' . $data_financial['fin_billcode']);
        if($data_bill                   = mysqli_fetch_assoc($db_bill->result)){
            $ngay_no                    = $data_bill['bii_end_time'];
            $bill_id                    = $data_bill['bii_id'];
            $so_no                      = $data_bill['bii_money_debit'] + $data_financial['fin_money'];
            
        }
    }
// nếu fin cat id là thanh toán công nợ nhà cung cấp thì select bảng bill out lấy ra ngày nợ túc là ngày tạo hóa đơn nợ    
    elseif($data_financial['fin_cat_id']    == 32){
        $db_bill                        = new db_query('SELECT * FROM bill_out WHERE bio_id = ' . $data_financial['fin_billcode']);
        if($data_bill                   = mysqli_fetch_assoc($db_bill->result)){
            $ngay_no                    = $data_bill['bio_start_time'];
            $bill_id                    = $data_bill['bio_id'];
            $so_no                      = $data_bill['bio_money_debit'] + $data_financial['fin_money'];
        }
    }
    unset($db_bill);
}unset($db_financies);

$content_column .= '<div class="section-content">';
$content_column .= $list->showHeader(1);
$content_column .= $list->start_tr(1,$bill_id,'class="menu-normal record-item" data-record_id="'.$bill_id.'"');
$content_column .= '<td class="center">'.format_codenumber($bill_id,6,'').'</td>';
$content_column .= '<td class="center">'.date('d/m/Y h:i',$ngay_no).'</td>';
$content_column .= '<td class="text-right">'.number_format($so_no).'</td>';
$content_column .= '<td class="text-right">'.number_format($thanhtoan).'</td>';
$content_column .= $list->end_tr();
$content_column .= $list->showFooter();
$content_column .= '</div>';
$custom_script  = 
'<script>
    var windowHeight        = windowHeight || $(window).height();
    var wrapperHeight       = windowHeight;
    var wrapperContent      = $(\'#wrapper-full\');
    wrapperContent.height(wrapperHeight);
    var sectionContent_class = $(\'.section-content\');
    sectionContent_class.height(wrapperHeight - 10);
    var table_scoll = $(\'.table-listing-bound\');
    table_scoll.height(wrapperHeight - 10);
    table_scoll.enscroll({
        showOnHover: true,
        minScrollbarLength: 28,
        addPaddingToPane : false
    });
</script>';

$rainTpl = new RainTPL();
$rainTpl->assign('load_header',$load_header);
$rainTpl->assign('content_column', $content_column);
$rainTpl->assign('footer_control', $footer_control);
$rainTpl->assign('custom_script',$custom_script);
$rainTpl->draw('mindow_iframe_1column');
?>