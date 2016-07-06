/* Hàm để xóa dữ liệu các bảng trong database về mặc định*/
function resetDefault(){
    var array_table = [];
    $('input.group_table').each(function () {

        if(this.checked == true){
            item = $(this).val();
            array_table.push(item);
        }
    });
    $.ajax({
        type: 'post',
        data: {action: 'resetDefaultAll',tables:array_table},
        dataType: 'json',
        url: 'ajax.php',
        success: function (resp) {
            if (resp.success == 1) {
                alert(resp.msg);
                window.location.assign("add_agencies.php")
            } else {
                alert(resp.error);
            }
        }
    })
}

/* Thêm mới của hàng mặc đinh*/
    function addAgenDefault(){
        var $name       = $('#name_agencies').val();
        var $name_store = $('#name_stores').val();
        var $address    = $('#address_agencies').val();
        var $phone      = $('#phone_agencies').val();
        var $note       = $('#note_agencies').val();
        if($name == ''){
            alert('Tên cửa hàng không được trống');
            return false;
        }
        if($name_store == ''){
            alert('Tên kho hàng không được trống');
            return false;
        }
        $.ajax({
            type: 'post',
            data:
            {   action      : 'addAgenStoreDefault',
                name        : $name,
                name_store  : $name_store,
                address     : $address,
                phone       : $phone,
                note        : $note
            },
            dataType: 'json',
            url: 'ajax.php',
            success: function (resp) {
                if (resp.success == 1) {
                    alert(resp.msg);
                    window.location.assign("add_serv_desk.php")
                } else {
                    alert(resp.error);
                }
            }
        })
    }

/* Thêm mới quầy phục vụ mặc định*/
function addServDesk(){
    var $name        = $('#name_servdesk').val();
    var $agencies_id = $('#cb_agencies').val();
    var $phone       = $('#phone_servdesk').val();
    var $note        = $('#note_servdesk').val();
    if($name == ''){
        alert('Tên cửa hàng không được trống');
        return false;
    }
    if($agencies_id == ''){
        alert('Tên kho hàng không được trống');
        return false;
    }
    $.ajax({
        type: 'post',
        data:
        {   action      : 'addServDesk',
            name        : $name,
            agencies_id : $agencies_id,
            phone       : $phone,
            note        : $note
        },
        dataType: 'json',
        url: 'ajax.php',
        success: function (resp) {
            if (resp.success == 1) {
                alert(resp.msg);
                window.location.assign("../user_config.php")
            } else {
                alert(resp.error);
            }
        }
    })
}

/* Lựa chọn tất cả các bảng để checked */
$('#check-all').unbind('click').click(function () {
    if(this.checked){
        $('.group_table').each(function(){
            this.checked = true;
        });
    }else {
        $('.group_table').each(function(){
            this.checked = false;
        });
    }
});
