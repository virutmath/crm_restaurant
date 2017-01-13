crm.controller('TimeCheckingController', function (requestService) {
	let self = this;
	self.list = [];
	self.lunch = true;
	self.dinner = true;
	//lấy danh sách nhân viên
	self.init = () => {
		let params = {
			action : 'getListEmployee',
		};
		requestService.api('get', 'ajax.php',params,(error,resp)=>{
			if(error) {
				return false;
			}
			self.list = resp.list;
			console.log(self.list);
		})
	};

	self.init();
});