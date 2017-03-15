crm.controller('HomeController', function (requestService, $window) {
	let self = this;
	self.loading = true;
	function init() {
		//lấy ra số bàn
		let params = {
			action: 'getDesks',
		};
		requestService.api('get', 'ajax.php', params, (error, resp) => {
			self.loading = false;
			if (error) {
				return false;
			}
			self.desks = resp.desks;
			self.sections = resp.sections;
			self.section = self.sections[0];
		});
		requestService.api('get', 'ajax.php', {action: 'getMenus'}, (error, resp) => {
			self.loading = false;
			self.menus = resp;
		});
	}

	self.selectDesk = (desk) => {
		self.loading = true;
		self.showDesk = true;
		self.desk = desk;
		let params = {
			action: 'getDeskDetail',
			id: self.desk.des_id
		};
		//lay chi tiet ban
		requestService.api('get', 'ajax.php', params, (error, resp) => {
			self.loading = false;
			if (error) {
				return false;
			}
			self.desk.menus = resp;
		})
	};
	self.addDish = (menu) => {
		if (!self.showDesk) return false;
		let params = {
			action: 'addDish',
			desk: self.desk.des_id,
			menu: menu.men_id
		};
		self.loading = true;
		requestService.api('post', 'ajax.php', params, (e,resp)=>{
			self.loading = false;
			if(e) {
				return false;
			}
			self.desk.menus = resp;
		});
	};
	init();
});