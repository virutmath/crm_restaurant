crm.service('requestService', function ($http) {
	let self = this;
	self.api = (method, url, params, callback) => {
		let httpConfig = {
			method: method,
			url: url,
		};
		if (method == 'get') httpConfig.params = params;
		else httpConfig.data = params;

		$http(httpConfig).then((response) => {
			// console.log(response);
			if(callback) {
				callback(null,response.data)
			}
		}, (response) => {
			if(callback) {
				callback(response);
			}
		})
	}
});