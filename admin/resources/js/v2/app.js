'use strict';
let crm = angular.module('crm', ['ngMaterial', 'ngSanitize', 'ngCookies']);
crm.config(function($mdThemingProvider) {
	$mdThemingProvider.theme('default')
		.primaryPalette('green')
		.accentPalette('orange');
});