crm.controller('TimeCheckingController', function (requestService) {
	let self = this;
	self.lunch = true;
	self.dinner = true;
	self.errors = [];
	//lấy danh sách nhân viên
	self.init = () => {
		let params = {
			action: 'getListEmployee',
		};
		requestService.api('get', 'ajax.php', params, (error, resp) => {
			if (error) {
				return false;
			}
			self.list = resp.list;
			// console.log(self.list);
		});
		getTimeLogs();
		self.getTimeWork();
	};

	self.checkin = (user) => {
		let params = {
			user_id: user.use_id,
			action: 'checkin'
		};
		requestService.api('post', 'ajax.php', params, (e, resp) => {
			getTimeLogs();
			self.getTimeWork();
		})
	};

	self.checkout = (user) => {
		let params = {
			user_id: user.use_id,
			action: 'checkout'
		};
		requestService.api('post', 'ajax.php', params, (e, resp) => {
			getTimeLogs();
			self.getTimeWork();
		})
	};

	function getTimeLogs() {
		requestService.api('post', 'ajax.php', {action: 'getLog'}, (e, resp) => {
			// console.log(resp);
			self.timeLogs = resp.list;
		})
	}

	self.getTimeWork = ()=>{
		requestService.api('post', 'ajax.php', {action: 'getTimeWork'}, (e, resp) => {
			// console.log(resp);
			self.timeWork = resp.list;

		})
	};

	self.updateEating = (user)=>{
		let params = {
			action: 'eating',
			user_id : user.use_id,
			eating: user.eating ? 1 : 0
		};
		requestService.api('post','ajax.php', params, (e,resp)=>{
			if(e) {
				self.errors.push(e);
			}
		})
	};

	self.init();

	self.working_time = (time_in, time_out) => {
		let ti = new Date(time_in)
			,to = new Date(time_out);
		return Math.round(Math.abs(to - ti)/1000/60);
	}

});