crm.service('requestService', function ($http) {
	let self = this;
	self.api = (method, url, params, callback) => {
		let httpConfig = {
			method: method,
			url: url,
			headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		};
		if (method == 'get') httpConfig.params = params;
		else httpConfig.data = $.param(params);

		$http(httpConfig).then((response) => {
			// console.log(response);
			if(callback) {
				callback(null,response.data)
			}
		}, (response) => {
			if(callback) {
				callback(response.data);
			}
		})
	}
});