<?
require_once 'inc_security.php';
$total_money = getValue('total','int','POST',0);
$content_column = '<div class="mwindow-wrapper">';
$content_column .= '<div class="mwindow-header"><label>Công nợ khách hàng</label><span class="mwindow-close">&times;</span></div>';
$content_column .= '<div class="content-mini-window" style="padding : 10px 15px;">';
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
$content_column .= '</div></div></div>';
$content_column .= '<script>var total_money = '.$total_money.'</script>';
echo $content_column . file_get_contents('script_debit.html');