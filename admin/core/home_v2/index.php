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
	<link rel="stylesheet" href="/admin/resources/bower_components/bootstrap/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="/admin/resources/bower_components/angular-material/angular-material.min.css">
	<link rel="stylesheet" href="/admin/resources/bower_components/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="/admin/resources/bower_components/Ionicons/css/ionicons.min.css">
	<link rel="stylesheet" href="/admin/resources/css/common_v2.css">
	<link rel="stylesheet" href="css/home.css">
</head>
<body ng-controller="HomeController as ctrl">
<section layout="row" flex layout-fill="">
	<md-toolbar>
		<div class="md-toolbar-tools">
			<h2 class="md-flex">Danh mục</h2>
		</div>
	</md-toolbar>
	<md-sidenav
			class="md-sidenav-right"
			md-component-id="right"
			md-is-locked-open="$mdMedia('gt-sm')"
			md-whiteframe="4">
        <p class="left-sidebar-header padding-10 mt2">
            <i class="fa fa-chevron-left pull-left mt5"></i>
            <span class="f18 padding-10l">Danh sách bàn</span>
        </p>
        <div class="padding-10">
            <select ng-model="ctrl.section" class="form-control">
                <option ng-value="section" ng-repeat="section in ctrl.sections">{{section.sec_name}}</option>
            </select>
        </div>
        <div layout="row" layout-wrap>
            <div layout-padding flex="50" ng-repeat="desk in ctrl.desks[ctrl.section.sec_id]">
                <div class="desk-item text-center">{{desk.des_name}}</div>
            </div>
        </div>
	</md-sidenav>
</section>

</body>
<script src="/admin/resources/bower_components/jquery/dist/jquery.min.js"></script>
<script src="/admin/resources/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="/admin/resources/bower_components/angular/angular.js"></script>
<script src="/admin/resources/bower_components/angular-animate/angular-animate.min.js"></script>
<script src="/admin/resources/bower_components/angular-aria/angular-aria.min.js"></script>
<script src="/admin/resources/bower_components/angular-sanitize/angular-sanitize.min.js"></script>
<script src="/admin/resources/bower_components/angular-cookies/angular-cookies.min.js"></script>
<script src="/admin/resources/bower_components/angular-material/angular-material.min.js"></script>
<script src="/admin/resources/js/v2/app.js"></script>
<script src="/admin/resources/js/v2/requestService.js"></script>
<script src="js/home.js"></script>
</html>