// Get data Surat IMB json
function getDataLicenseImb(api, key) {
	var app = new Vue({
		el: '#app',
		data: {
			search: '',
			json: { key : [] },
			detail: '',
			users: { "users" : [] }
		},
		created: function () {
			var _this = this;
			// Incoming Mail
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
		}
	});
}


// Get data Surat Izin Berkas json
function getDataLicenseFiles(api, key) {
	var app = new Vue({
		el: '#app',
		data: {
			json: { key : [] },
			detail: ''
		},
		created: function () {
			var _this = this;
			// Incoming Mail
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
		}
	});
}