<?php
$assets_version = 1;
//$build_folder = 'dist';
$build_folder = 'src';
$assets_css ='
<link rel="stylesheet" href="/admin/resources/bower_components/bootstrap/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="/admin/resources/bower_components/angular-material/angular-material.min.css">
<link rel="stylesheet" href="/admin/resources/bower_components/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" href="/admin/resources/bower_components/Ionicons/css/ionicons.min.css">
';

$assets_js = '
<script src="/admin/resources/bower_components/jquery/dist/jquery.min.js"></script>
<script src="/admin/resources/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="/admin/resources/bower_components/lodash/lodash.js"></script>
<script src="/admin/resources/bower_components/angular/angular.js"></script>
<script src="/admin/resources/bower_components/angular-animate/angular-animate.min.js"></script>
<script src="/admin/resources/bower_components/angular-aria/angular-aria.min.js"></script>
<script src="/admin/resources/bower_components/angular-sanitize/angular-sanitize.min.js"></script>
<script src="/admin/resources/bower_components/angular-cookies/angular-cookies.min.js"></script>
<script src="/admin/resources/bower_components/angular-material/angular-material.min.js"></script>
<script src="/admin/resources/js/v2/'.$build_folder.'/app.js?v='.$assets_version.'"></script>
<script src="/admin/resources/js/v2/'.$build_folder.'/components.js?v='.$assets_version.'"></script>
<script src="/admin/resources/js/v2/'.$build_folder.'/requestService.js?v='.$assets_version.'"></script>
';