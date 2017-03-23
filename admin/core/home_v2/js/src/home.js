crm.controller('HomeController', function (requestService, $window) {
	let self = this;
	self.loading = true;
	self.desks = [];
	self.sections = [];
	self.menus = [];
	self.menu = null;
	self.desk = null;
	self.dish_number = 1;
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
	self.showAddDish = (menu) => {
		if (!self.showDesk) {
			self.error_msg = 'Chọn một bàn để bắt đầu thêm thực đơn';
			return false;
		}else{
			self.error_msg = null;
		}
		self.menu = menu;
		$('#modal-menu-number').modal('show');
	};
	self.addDish = () => {
		if (!(self.showDesk && self.menu)) {
			self.error_msg = 'Chọn một bàn để thêm thực đơn';
			return false;
		}else {
			self.error_msg = null;
		}
		let params = {
			action: 'addDish',
			desk: self.desk.des_id,
			menu: self.menu.men_id,
			number: self.dish_number
		};
		self.loading = true;
		requestService.api('post', 'ajax.php', params, (e, resp) => {
			self.loading = false;
			if (e) {
				return false;
			}
			self.desk.menus = resp;
			$('#modal-menu-number').modal('hide');
		});
	};
	self.deleteMenu = (menu) => {
		if (!self.showDesk) return false;
		let params = {
			action: 'deleteMenu',
			desk: self.desk.des_id,
			menu: menu.men_id
		};

		self.loading = true;
		requestService.api('post', 'ajax.php', params, (e, resp) => {
			self.loading = false;
			if (e) {
				return false;
			}
			self.desk.menus = resp;
		});
	};
	self.incMenu = (menu) => {
		if (!self.showDesk) return false;
		let params = {
			action: 'incMenu',
			desk: self.desk.des_id,
			menu: menu.men_id
		};
		self.loading = true;
		requestService.api('post', 'ajax.php', params, (e, resp) => {
			self.loading = false;
			if (e) {
				return false;
			}
			self.desk.menus = resp;
		});
	};
	self.decMenu = (menu) => {
		if (!self.showDesk) return false;
		//nếu số lượng đang là 0 thì ko giảm nữa
		if(!menu.cdm_number) return false;
		self.loading = true;
		let params = {
			action: 'decMenu',
			desk: self.desk.des_id,
			menu: menu.men_id
		};
		requestService.api('post', 'ajax.php', params, (e, resp) => {
			self.loading = false;
			if (e) {
				alert(e.msg);
				return false;
			}
			self.desk.menus = resp;
		});
	};
	init();
});