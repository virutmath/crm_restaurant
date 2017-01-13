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
   <title>Chấm công nhân viên</title>
   <link rel="stylesheet" href="/admin/resources/bower_components/bootstrap/dist/css/bootstrap.min.css">
   <link rel="stylesheet" href="/admin/resources/bower_components/angular-material/angular-material.min.css">
   <link rel="stylesheet" href="/admin/resources/bower_components/font-awesome/css/font-awesome.min.css">
   <link rel="stylesheet" href="/admin/resources/bower_components/Ionicons/css/ionicons.min.css">
</head>
<body ng-controller="TimeCheckingController as $ctrl">
<div class="container">
   <div class="row">
      <div class="col-xs-12">
         <h3 class="text-center">Chấm công ngày 01/01/2016</h3>
      </div>
   </div>
   <div class="table-listing">
      <table class="table table-bordered">
         <tr>
            <th>Tên nhân viên</th>
            <th>Sáng</th>
            <th>Chiều</th>
         </tr>
         <tr ng-repeat="user in $ctrl.list">
            <td>{{user.use_name}}</td>
            <td>
               <button class="btn btn-primary">Checkin</button>
               <button class="btn btn-danger">Checkout</button>
               <md-checkbox ng-model="user.hasLunch" aria-label="Ăn trưa" ng-change="updateEating(user)">
                    Ăn trưa 
                </md-checkbox>
            </td>
            <td>
               <button class="btn btn-primary">Checkin</button>
               <button class="btn btn-danger">Checkout</button>
               <md-checkbox ng-model="user.hasDinner" aria-label="Ăn tối">
                    Ăn tối
                </md-checkbox>
            </td>
         </tr>
      </table>
   </div>
   <div class="time-log">
        <table class="table">
            <tr>
                <th>Tên nhân viên</th>
                <th>Log</th>
                <th>Tổng giờ làm</th>
            </tr>
            <tr ng-repeat="user in $ctrl.timeLogs">
                <td>{{user.use_name}}</td>
                <td>
                    Giờ vào : {{user.}}
                </td>
            </tr>
        </table>
   </div>
</div>
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
<script src="script.js"></script>
</html>