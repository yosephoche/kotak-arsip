// Get data Incoming Mail json
function getDataIncomingMail(api, key) {
	var app = new Vue({
		el: '#app',
		data: {
			search: '',
			json: { key : [], 'users' : [] },
			detail: '',
			idActive: ''
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
			moment: function () {
				return moment();
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
			},
			checkUserDisposition: function (id) {
				this.idActive = id;
			}
		},
		computed: {
			filteredUsers:function() {
				var self = this;
				return this.json.users.filter(function(user) {
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