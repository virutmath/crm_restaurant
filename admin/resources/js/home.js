$(function(){
    var iframe_height = $(window).height() - 56;
    $('.menu-function').unbind('click').click(function(){
        $(this).toggleClass('active');
    });
    $('.list-function-menu-user').click(function(){
        $(this).toggleClass('active');
    });
    $('#main-content').height(iframe_height);
});

// Mở khung đổi mật khẩu
function changePassword(id){
    var mwindow = new Mindows;
    mwindow.width   = 400;
    mwindow.height  = 250;
    mwindow.resize  = true;
    mwindow.iframe('core/users/change_password.php','Cập nhật mật khẩu',id);
}

// ham dong mwindow
function communicateParentWindow($action, $data) {
    switch ($action) {
        case 'closeSetting' :
            $('.mwindow-close').trigger('click');
            break;
        case 'changePassword':
            $('.mwindow-close').trigger('click');
            break;
        case 'activeTab' :
            var moduleID = $data;
            var activeTab = $('#menu').find('[data-module-id='+moduleID+']');
            if(activeTab.length) {
                $('#menu').find('a.active').removeClass('active');
                activeTab.find('a').addClass('active');
            }
    }
}