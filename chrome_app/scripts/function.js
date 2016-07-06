function isOnline(callback_online, callback_offline) {
    setInterval(function () {
        $.ajax({
            type : 'get',
            cache : 'false',
            url : 'http://static.khang.vn:8080/pictures/dotted.gif?t=' + Date.now(),
            success : function (resp) {
                callback_online();
            },
            error : function () {
                callback_offline();
            }
        })
    },3000);
}