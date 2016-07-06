$(document).ready(function(){
    // dong window
    var close   = $('.bill-close');
    close.click(function(){
        window.parent.communicateParentWindow('close_detail');
    });
    // click nut cap nhat
    var save_pay_debit  = $('.save-pay-debit');
    var pay_all         = $('#pay_all');
    var total_money     = $('.total_money');
    var next_pay        = $('.next-pay');
    var id_object       = $('.information-debit').data('id_object');
    var type_object     = $('.information-debit').data('type');
    var card            = $('#card');
    // luu thanh toan hoa don
    save_pay_debit.click(function(){
        var ghichu          = $('.text-area-note').val();
        var type_pay        = 0;
        var next_money_pay  = next_pay.autoNumeric('get');
        if (next_money_pay == 0) return false;
        if(pay_all.is(':checked')) next_money_pay  = total_money.data("total_money");
        if(card.is(':checked')) type_pay        = 1;
        $.ajax({
            type : 'post',
            data : 
            {
                action : 'payDebit',
                next_money_pay: next_money_pay,
                type_pay : type_pay,
                ghichu: ghichu,
                id_object: id_object,
                type_object: type_object,
            },
            url : 'ajax.php',
            dataType : 'json',
            success : function(resp){
                if(resp.success){
                    window.parent.communicateParentWindow('save_pay_debit');
                }
            }
        })
    }); 
    // gioi han so tien tra 
    $('#pay_next_money').autoNumeric({
        vMin : 0,
        vMax : total_debit
    });    
});
// function click check tra het so tien no
function pay_all_debit(){
    if($('#pay_all').is(':checked')){
        var total_money = $('.total_money').data("total_money");
        $('.next-pay').val(total_money);
    }else{
        $('.next-pay').val(0);
    }
}
