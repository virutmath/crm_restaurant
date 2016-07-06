var ListMenu = ListMenu || [];
$('.print-list-menu').enscroll({
    showOnHover: true,
    minScrollbarLength: 28,
    addPaddingToPane: false
});
$('#print-order').click(function () {
    var tbl_listing = $('#list-menu-print'), print_area = $('div#print-area');
    if(ListMenu.length <= 0) {
        alert('Không thể in thực đơn trống. Vui lòng thêm thực đơn vào bàn hoặc tải lại phiếu in bếp');
        return false;
    }
    $.ajax({
        type : 'post',
        url: 'ajax.php',
        data : {action : 'printOrder', desk_id : $('#desk_id').val(), list_menu : ListMenu},
        dataType : 'json',
        success : function (resp) {
            if(resp.success && resp.list_menu) {
                var str_tr = '';
                for(var i in resp.list_menu) {
                    var menutmp = resp.list_menu[i];
                    var stt = parseInt(i) + 1;
                    str_tr += '<tr>' +
                                '<td class="center">'+stt+'</td>' +
                                '<td>'+menutmp.men_name+'</td>' +
                                '<td class="center">'+menutmp.uni_name+'</td>' +
                                '<td class="center">'+menutmp.print_number+'</td>' +
                              '</tr>';
                }
                str_tr += '<tr><td colspan="4">'+$('#print-note-input').val()+'</td></tr>';
                tbl_listing.find('tbody').html(str_tr);
                window.print();
                //gọi đến hàm communication để đóng cửa sổ
                window.parent.communicateParentWindow('printOrder');
            }else{
                alert(resp.error);
                return false;
            }
        }
    });
});
$('#close-window').click(function () {
    window.parent.communicateParentWindow('printOrder');
});
