<?
require_once 'inc_security.php';
//Phần xử lý
$action_modal = getValue('action_modal','str','POST','',2);
$action = getValue('action','str','POST','',2);
if($action == 'execute') {
    switch ($action_modal) {

    }
}

//Phần hiển thị
//Khởi tạo
$left_control       = '';
$right_control      = '';
$footer_control     = '';
$left_column        = '';
$right_column       = '';
$context_menu       = '';
$left_column_title  = 'BÁO CÁO - THỐNG KÊ';
$right_column_title = 'CHI TIẾT BÁO CÁO THỐNG KÊ';

// phấn menu left
$left_column .= '
    <div class="menu_sidebar">
        <ul>
            <li><a href="#" data-cat="report_transfer"> <i class="fa fa-file-text-o"></i> Báo xuất nhập tồn kho hàng</a></li>
            <li><a href="#" data-cat="report_stock"> <i class="fa fa-file-text-o"></i> Báo cáo giá trị tồn kho</a></li>
            <li><a href="#" data-cat="report_bill_detail"> <i class="fa fa-file-text-o"></i> Báo cáo chi tiết bán hàng</a></li>
            <li><a href="#" data-cat="report_bill_admin"> <i class="fa fa-file-text-o"></i> Báo cáo thu chi theo phiên đăng nhập</a></li>
            <br/>
            <li><a href="#" data-cat="revenue_bill"> <i class="fa fa-table"></i> Thống kê doanh thu theo hóa đơn</a></li>
            <li><a href="#" data-cat="revenue_fund"> <i class="fa fa-table"></i> Thống kê doanh thu theo quỹ tiền</a></li>
            <li><a href="#" data-cat="revenue_menus"> <i class="fa fa-table"></i> Thống kê bán hàng theo thực đơn</a></li>
            <li><a href="#" data-cat="revenue_staff"> <i class="fa fa-table"></i> Thống kê doanh thu theo nhân viên</a></li>
            <li><a href="#" data-cat="revenue_customers"> <i class="fa fa-table"></i> Thống kê doanh thu theo khách hàng</a></li>
            <br/>
            <li><a data-cat="report_bill"> <i class="fa fa-file-text"></i> Thống kê chi phí theo hóa đơn</a></li>
            <li><a data-cat="report_fund"> <i class="fa fa-file-text"></i> Thống kê chi phí theo quỹ tiền</a></li>
            <li><a href="#" data-cat="report_product"> <i class="fa fa-file-text"></i> Thống kê chi phí theo mặt hàng</a></li>
            <br/>
            <li><a data-cat="expenditures_bill"> <i class="fa fa-file-text"></i> Thống kê thu chi</a></li>
        </ul>
    </div>
';



$rainTpl = new RainTPL();
add_more_css('custom.css',$load_header);
$rainTpl->assign('load_header',$load_header);
$rainTpl->assign('left_control',$left_control);
$rainTpl->assign('right_control',$right_control);
$rainTpl->assign('footer_control', $footer_control);
$rainTpl->assign('left_column',$left_column);
$rainTpl->assign('right_column',$right_column);
$rainTpl->assign('left_column_title',$left_column_title);
$rainTpl->assign('right_column_title',$right_column_title);
$custom_script = file_get_contents('script.html');
$rainTpl->assign('custom_script',$custom_script);
$rainTpl->draw('fullwidth_2column_report');