var app = angular.module('crmRestaurant',['ngMaterial']);

app.constant('COMMON_CONFIG',{
    "baseFolder" : "crm_server_offline",
    "dbName" : "crmDB",
    onlineAppUrl: 'http://localhost:8027/admin',
    commonCrm : '/data/crm.php',
    getDetailDesk : '/data/detailDesk.php'
});

//khai báo service dùng chung
app.factory('commonService', function($window, $q){
    var toScale = function() {
        //chỉnh kích thước listing-menu
        $('#listing-menu').height($window.innerHeight - 48 - 174 - 32);
    };
    var self = this;
    this.ipServer = '127.0.0.1';
    this.protocol = 'http://';
    this.scaleWindow =  function() {
        toScale();
        $(window).resize(function() {
            toScale();
        });
    };
    return this;
});

app.run(function($window, $rootScope) {
    $rootScope.wHeight = $window.innerHeight;
    $rootScope.wWidth = $window.innerWidth;
    $rootScope.online = navigator.onLine;
    $window.addEventListener("offline", function () {
        $rootScope.$apply(function() {
            $rootScope.online = false;
        });
    }, false);
    $window.addEventListener("online", function () {
        $rootScope.$apply(function() {
            $rootScope.online = true;
        });
    }, false);

});


app.controller('CommonController', function($scope, COMMON_CONFIG, $window) {
    $scope.connection_status = 'Ngoại tuyến';
    $scope.onlineAppUrl = COMMON_CONFIG.onlineAppUrl;
    $window.isOnline(function () {
        $scope.connection_status = 'Trực tuyến';
    }, function () {
        $scope.connection_status = 'Ngoại tuyến';
    })
});

app.controller('HomeController', function ($rootScope, $q, $http, COMMON_CONFIG, commonService) {
    var self = this;
    self.ipServer = commonService.ipServer;
    self.list_menu = [];
    self.list_desk = [];
    self.current_menu = null;
    self.current_desk = {
        cud_start_time : 'dd/mm/YYYY H:i'
    };
    self.config = {
        common : {
            con_restaurant_name : ''
        }
    };
    self.list_menu = {};

    chrome.storage.local.get(['ip_server','username','password'], function (result) {
        self.current_menu = 1;
        self.ipServer = result.ip_server;
        var url_get_data = commonService.protocol + self.ipServer + '/'+ COMMON_CONFIG.baseFolder + COMMON_CONFIG.commonCrm;

        $http({
            method : 'POST',
            url : url_get_data,
            data : {username : result.username, password : result.password}
        }).success(function (resp) {
            self.list_menu = resp.list_menu;
            self.config.common = resp.config_common;
            self.list_desk = resp.list_desk;
        })
    });


    //scale window
    commonService.scaleWindow();

    this.selectDesk = function (desk_id) {
        self.current_desk = $('.desk-item[data-id='+desk_id+']');
        $('.desk-item').removeClass('selected');
        self.current_desk.addClass('selected');
        //nếu bàn đang mở thì load detail
        if(self.current_desk.hasClass('active')) {
            var url_get_detail_desk = commonService.protocol + self.ipServer + '/'+ COMMON_CONFIG.baseFolder + COMMON_CONFIG.getDetailDesk;
            $http.get(url_get_detail_desk, {desk_id : desk_id}).success(function (resp) {

            });

        }
    }
});

app.controller('SettingController',['$scope', '$http', '$q', '$window', function($scope, $http, $q, $window) {

    var self = this;
    this.loading = '';
    this.ipServer = '127.0.0.1';
    this.username = '';
    this.password = '';
    this.saveSetting = function(){
        chrome.storage.local.set({
            'ip_server' : self.ipServer,
            'username' : self.username,
            'password' : self.password
        });
    };

    this.getSetting  = function(){
        var d = $q.defer();
        chrome.storage.local.get(['ip_server','username','password'], function (result) {
            d.resolve(result);
        });
        return d.promise;
    };


    this.getSetting().then(function (setting) {
        self.ipServer = setting.ip_server;
        self.username = setting.username;
        self.password = setting.password;
    });

}]);