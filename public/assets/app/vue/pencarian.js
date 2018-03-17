// Get data Search json
function getDataSearch(api, key) {
	var app = new Vue({
		el: '#app',
		data: {
			search: '',
			json: { key : [], 'users' : [] },
			detail: '',
			dispositionArray: '',
			dispositionInfo: '',
			active: false
		},
		created: function () {
			var _this = this;
			// Search
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
				element.siblings('label').html('<i class="fa fa-circle-o-notch fa-spin fa-fw"></i> &nbsp;Proses').attr('disabled', 'disabled');
				element.closest('form').submit();
			},
			idDispositionArray: function (data) {
				var id = new Array();
				var info = new Array();
				for (var i = 0; i < data.length; i++) {
					if (data[i].user[0] != null) {
						id.push(data[i].user[0]._id.$oid);
					}
				}
				for (var i = 0; i < data.length; i++) {
					info.push(data[i]);
				}
				return this.dispositionArray = id, this.dispositionInfo = info;
			}
		},
		computed: {
			filteredUsers:function() {
				var self = this;
				function compare(a, b) {
					if (a.name < b.name)
						return -1;
					if (a.name > b.name)
						return 1;
					return 0;
				}
				return this.json.users.sort(compare).filter(function(user) {
					return user.name.toLowerCase().indexOf(self.search.toLowerCase())>=0;
				});
			}
		},
		filters: {
			moment: function (date) {
				var day = moment(date, "x").format("DD");
				var month = moment(date, "x").format("MM");
				var year = moment(date, "x").format("YYYY");
				return day + "/" + month + "/" + year;
			}
		}
	});
}

// Get data Detail Search json
function getDataSearchDetail(api, key) {
	var app = new Vue({
		el: '#app',
		data: {
			search: '',
			json: { key : [], 'users' : [] },
			dispositionArray: '',
			active: false
		},
		created: function () {
			var _this = this;
			// Search
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
			idDispositionArray: function (data) {
				var id = new Array();
				var info = new Array();
				for (var i = 0; i < data.length; i++) {
					if (data[i].user[0] != null) {
						id.push(data[i].user[0]._id.$oid);
					}
				}
				for (var i = 0; i < data.length; i++) {
					info.push(data[i]);
				}
				return this.dispositionArray = id, this.dispositionInfo = info;
			}
		},
		filters: {
			moment: function (date) {
				var day = moment(date, "x").format("DD");
				var month = moment(date, "x").format("MM");
				var year = moment(date, "x").format("YYYY");
				return day + "/" + month + "/" + year;
			}
		}
	});
}