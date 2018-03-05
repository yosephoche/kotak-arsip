// Get data Surat IMB json
function getDataLicenseImb(api, key) {
	var app = new Vue({
		el: '#app',
		data: {
			search: '',
			json: { key : [] },
			detail: '',
			users: { "users" : [] },
			active: false
		},
		created: function () {
			var _this = this;
			// Surat Izin
			$.getJSON(api[0], function (json) {
				_this.json = json;
			});
			// Users
			$.getJSON(api[1], function (json) {
				_this.users = json;
			});

		},
		methods: {
			detailSidebar: function (val, e) {
				this.detail = val;
				var element = $(e.target).closest('.item');
				
				// remove all class active except this
				element.siblings().removeClass('active');
				
				// Give class active
				element.toggleClass('active');

				// Unselect
				if (element.hasClass('active') == false) {
					this.detail = '';
				}
			},
			notification: function (e) {
				var element = $(e.target).closest('.new-notif');
				element.find('.fa-bell').removeClass('animated infinite');
				element.removeClass('new-notif');
				element.find('.badge').remove();
			},
			navToggle: function (e) {
				var element = $(e.target).closest('.nav-toggle');
				if (this.active == false) {
					this.active = true;
					navShow();
				} else {
					this.active = false;
					navClose();
				}
			},
			inputFileSubmit: function (e) {
				var element = $(e.target);
				element.closest('form').submit();
			}
		},
		computed: {
			filteredUsers:function() {
				var self = this;
				return this.users.users.filter(function(user) {
					return user.name.toLowerCase().indexOf(self.search.toLowerCase())>=0;
				});
			}
		},
		filters: {
			moment: function (date) {
				var day = moment(date, "x").date();
				var month = moment(date, "x").month();
				var year = moment(date, "x").year();
				return day + "/" + month + "/" + year;
			}
		}
	});
}


// Get data Surat Izin Berkas json
function getDataLicenseFiles(api, key) {
	var app = new Vue({
		el: '#app',
		data: {
			json: { key : [] },
			detail: '',
			active: false
		},
		created: function () {
			var _this = this;
			// Surat Izin
			$.getJSON(api, function (json) {
				_this.json = json;
			});

		},
		methods: {
			detailSidebar: function (val, e) {
				this.detail = val;
				var element = $(e.target).closest('.item');
				
				// remove all class active except this
				element.siblings().removeClass('active');
				
				// Give class active
				element.toggleClass('active');

				// Unselect
				if (element.hasClass('active') == false) {
					this.detail = '';
				}
			},
			notification: function (e) {
				var element = $(e.target).closest('.new-notif');
				element.find('.fa-bell').removeClass('animated infinite');
				element.removeClass('new-notif');
				element.find('.badge').remove();
			}
		},
		filters: {
			moment: function (date) {
				var day = moment(date, "x").date();
				var month = moment(date, "x").month();
				var year = moment(date, "x").year();
				return day + "/" + month + "/" + year;
			}
		}
	});
}