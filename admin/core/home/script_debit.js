var debit_money_paid_ele = $('#debit_money_paid');
var debit_date_ele = $('#debit_date_debit');
debit_money_paid_ele.autoNumeric({
    mDec : 0,
    vMin : 0,
    vMax : total_money,
    lZero : 'deny'
});
debit_date_ele.miniDatePicker({
    dp_location : false
});

debit_money_paid_ele.unbind('keyup').on('keyup', function () {
    var money = $(this).autoNumeric('get');
    var debit = total_money - money;
    $('#debit_money_debit').val(number_format(debit));
});
$('form[name="debit_form"]').on('submit', function (e) {
    e.preventDefault();
    debitSubmit();
});
function debitSubmit() {
    var money = parseInt(debit_money_paid_ele.autoNumeric('get'));
    var date = debit_date_ele.val();
    if(money && money < total_money) {
        window.parent.communicateParentWindow('setDebit',{money : money, time : date});
    }else {
        alert('Số tiền thanh toán không phù hợp!');
    }
}