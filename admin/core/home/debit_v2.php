<?
require_once 'inc_security.php';
$total_money = getValue('total','int','GET',0);
$content_column = '';
$form = new form();
$content_column .= $form->form_open('debit_form');
$content_column .= $form->text(array(
    'label'=>'Số tiền phải trả',
    'name'=>'debit_total_money',
    'id'=>'debit_total_money',
    'value'=> number_format($total_money),
    'readonly'=>1,
    'addon'=>DEFAULT_MONEY_UNIT
));
$content_column .= $form->text(array(
    'label'=>'Thanh toán',
    'name'=>'debit_money_paid',
    'id'=>'debit_money_paid',
    'value'=>0,
    'addon'=>DEFAULT_MONEY_UNIT
));
$content_column .= $form->text(array(
    'label'=> 'Còn lại',
    'name'=>'debit_money_debit',
    'id'=>'debit_money_debit',
    'value'=>number_format($total_money),
    'readonly'=>1,
    'addon'=>DEFAULT_MONEY_UNIT
));
$content_column .= $form->text(array(
    'label'=>'Ngày hẹn trả',
    'name'=>'debit_date_debit',
    'id'=>'debit_date_debit',
    'value'=>date('d/m/Y', time() + 84600)
));
$content_column .= '<div class="form-group debit-submit">
    <button type="button" class="btn btn-primary" onclick="debitSubmit()">Đồng ý</button>
    <button class="btn btn-default">Hủy bỏ</button>
</div>';
$content_column .= $form->form_close();
add_more_css('css/debit.css?v=1',$load_header);
$rainTpl = new RainTPL();
$rainTpl->assign('load_header',$load_header);
$rainTpl->assign('content_column',$content_column);
$rainTpl->assign('total_money',$total_money);
$rainTpl->draw('/v2/home/debit');