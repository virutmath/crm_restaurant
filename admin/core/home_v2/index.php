<?php
require_once 'inc_security.php';

?>
<!DOCTYPE html>
<html lang="en" ng-app="crm">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Bán hàng</title>
    <link rel="manifest" href="/manifest.json">
    <?=trim($assets_css)?>
	<link rel="stylesheet" href="/admin/resources/css/common_v2.css?v=<?=$assets_version?>">
	<link rel="stylesheet" href="css/home.css?v=<?=$assets_version?>">
</head>
<body ng-controller="HomeController as ctrl">
<section>
	<div class="sidenav pull-right">
        <p class="left-sidebar-header padding-10 mt2">
            <i class="fa fa-chevron-left pull-left mt5" ng-show="ctrl.showDesk" ng-click="ctrl.showDesk=false;"></i>
            <span class="f18 padding-10l" ng-bind="ctrl.showDesk ? 'Chi tiết bàn' : 'Danh sách bàn'"></span>
        </p>
        <div ng-hide="ctrl.showDesk">
            <div class="padding-10">
                <select ng-model="ctrl.section" class="form-control">
                    <option ng-value="section" ng-repeat="section in ctrl.sections">{{section.sec_name}}</option>
                </select>
            </div>
            <div layout="row" layout-wrap>
                <div layout-padding flex="50" ng-repeat="desk in ctrl.desks[ctrl.section.sec_id]">
                    <div class="desk-item text-center" ng-click="ctrl.selectDesk(desk)">{{desk.des_name}}</div>
                </div>
            </div>
        </div>
        <div ng-show="ctrl.showDesk" class="padding-10">
            <p>{{ctrl.desk.des_name}}</p>
            <div class="desk-detail p5">
                <div class="row padding-10b text-center">
                    <div class="col-xs-7"><b>Tên thực đơn</b></div>
                    <div class="col-xs-2"><b>SL</b></div>
                    <div class="col-xs-3"><b>Đơn giá</b></div>
                </div>
                <div class="row padding-10b" ng-repeat="menu in ctrl.desk.menus" ng-click="menu.showTool = !menu.showTool">
                    <div class="col-xs-7">{{menu.men_name}}</div>
                    <div class="col-xs-2 text-center">{{menu.cdm_number}}</div>
                    <div class="col-xs-3 text-right">{{menu.cdm_price | currency:'':0}}đ</div>
                    <div ng-show="menu.showTool">
                        <div class="col-xs-6 text-center lh32 f18">
                            <i class="ion ion-social-usd"></i> <b class="text-danger">{{menu.cdm_number * menu.cdm_price | currency: '':0}}đ</b>
                        </div>
                        <div class="col-xs-6 f24 text-right text-primary">
                            <i class="ion ion-trash-a margin-10r"></i>
                            <i class="ion ion-plus-round margin-10r"></i>
                            <i class="ion ion-minus-round"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <loading ng-show="ctrl.loading"></loading>
    </div>
    <div class="main-content">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <ul class="nav navbar-nav">
                    <li>Bán hàng</li>
                </ul>
            </div>
        </nav>
        <div class="col-xs-6 col-sm-4 col-md-2 menu-item margin-10b" ng-repeat="menu in ctrl.menus" ng-click="ctrl.addDish(menu)">
            <div class="imgthumb">
                <img ng-src="{{menu.image_url}}" alt="Ảnh món">
            </div>
            <p>{{menu.men_name}}</p>
        </div>
    </div>
</section>

</body>
<?=trim($assets_js)?>
<script src="js/<?=$build_folder?>/home.js?v=<?=$assets_version?>"></script>
</html>