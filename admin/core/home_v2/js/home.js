crm.controller('HomeController', function (requestService) {
	let self = this;
	//lấy ra số bàn
	let params = {
		action: 'getDesks',
	};
	requestService.api('get', 'ajax.php', params, (error, resp) => {
		if (error) {
			return false;
		}
		self.desks = resp.desks;
		self.sections = resp.sections;
		self.section = self.sections[0];
	});
});