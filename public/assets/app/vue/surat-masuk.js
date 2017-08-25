// Get data Incoming Mail json
function getDataIncomingMail(api, key) {
	var app = new Vue({
		el: '#app',
		data: {
			search: '',
			json: { key : [], 'users' : [] },
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
			},
			inputFileSubmit: function (e) {
				var element = $(e.target);
				element.siblings('label').html('<i class="fa fa-circle-o-notch fa-spin fa-fw"></i> &nbsp;Proses').attr('disabled', 'disabled');
				element.closest('form').submit();
			}
		},
		computed: {
			filteredUsers:function() {
				var self = this;
				return this.json.users.filter(function(user) {
					return user.name.toLowerCase().indexOf(self.search.toLowerCase())>=0;
				});
			}
		}
	});
}

// Get data Detail Incoming Mail json
function getDataIncomingMailDetail(api, key) {
	var app = new Vue({
		el: '#app',
		data: {
			search: '',
			json: { key : [], 'users' : [] }
		},
		created: function () {
			var _this = this;
			// Incoming Mail
			$.getJSON(api, function (json) {
				_this.json = json;
			});
		},
		computed: {
			filteredUsers:function() {
				var self = this;
				return this.json.users.filter(function(user) {
					return user.name.toLowerCase().indexOf(self.search.toLowerCase())>=0;
				});
			}
		},
		methods: {
			favorite: function (e) {
				var element = $(e.target).closest('#favorite');
				element.find('i').toggleClass('fa-star fa-star-o');
			}
		}
	});
}