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
	<?= trim($assets_css) ?>
	<link rel="stylesheet" href="/admin/resources/css/common_v2.css?v=<?= $assets_version ?>">
	<link rel="stylesheet" href="css/home.css?v=<?= $assets_version ?>">
</head>
<body ng-controller="HomeController as ctrl">
<div class="page-preloader"></div>
<section>
	<div class="wrap-sidenav" ng-include="'includes/sidebar.html'"></div>
	<div class="main-content">
		<nav class="navbar navbar-default" ng-include="'includes/navigation_top.html'"></nav>
		<div class="notify-area">
			<p class="alert alert-danger" ng-show="ctrl.error_msg">
				<button type="button" class="close" ng-click="ctrl.error_msg=null"><span
							aria-hidden="true">&times;</span></button>
				{{ctrl.error_msg}}
			</p>
		</div>
		<div class="menu-listing" ng-include="'includes/menu_listing.html'"></div>
	</div>
</section>
<ng-include src="'includes/modal.html'"></ng-include>
<?= trim($assets_js) ?>
<script src="js/<?= $build_folder ?>/home.js?v=<?= $assets_version ?>"></script>
<script>
	var loaderPage = function () {
		$(".page-preloader").fadeOut("slow");
	};
	loaderPage();
</script>
</body>
</html>