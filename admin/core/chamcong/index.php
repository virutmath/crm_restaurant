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
<body ng-controller="TimeCheckingController as ctrl">
<div class="container">
   <div class="row">
      <div class="col-xs-12">
         <h3 class="text-center">Chấm công ngày <?= date('Y-m-d') ?></h3>
      </div>
   </div>
   <div class="table-listing">
      <table class="table table-bordered">
         <tr>
            <th class="text-center">Tên nhân viên</th>
            <th class="text-center">Chấm công</th>
            <th class="text-center">Thao tác</th>
         </tr>
         <tr ng-repeat="user in ctrl.list">
            <td>{{user.use_name}}</td>
            <td class="text-center">
               <button class="btn btn-primary" ng-click="ctrl.checkin(user)">Checkin</button>
               <br>
               <br>
               <button class="btn btn-danger" ng-click="ctrl.checkout(user)">Checkout</button>
            </td>
            <td class="text-center">
               <md-checkbox ng-model="user.eating" aria-label="Ăn ca" ng-change="ctrl.updateEating(user)">
                  Ăn ca
               </md-checkbox>
            </td>
         </tr>
      </table>
   </div>
   <div class="time-log">
      <table class="table">
         <caption>Lịch sử checkin checkout trong ngày</caption>
         <tr>
            <th>Tên nhân viên</th>
            <th>Log</th>
         </tr>
         <tr ng-repeat="user in ctrl.timeLogs">
            <td>{{user.use_name}}</td>
            <td>
               <p><span class="label label-success">IN</span>: {{user.time_in}}</p>
               <p><span class="label label-danger">OUT</span>: {{user.time_out}}</p>
               <p><i class="text-danger">Thời gian làm việc</i>: {{ctrl.working_time(user.time_in, user.time_out)}} phút
               </p>
            </td>
         </tr>
      </table>
   </div>
   <div class="time-work">
      <table class="table">
         <caption>Chấm công ngày</caption>
         <tr>
            <th>Nhân viên</th>
            <th>Ca sáng</th>
            <th>Ăn trưa</th>
            <th>Ca chiều</th>
            <th>Ăn tối</th>
            <th>Tổng (giờ)</th>
         </tr>
         <tr ng-repeat="user in ctrl.timeWork">
            <td>{{user.use_name}}</td>
            <td>{{user.ca1/60 | number: 0}} phút</td>
            <td>{{user.lunch ? '30 phút' : 0}}</td>
            <td>{{user.ca2/60 | number:0}} phút</td>
            <td>{{user.dinner ? '30 phút' : 0}}</td>
            <td>{{user.total/3600 | number: 1}}</td>
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