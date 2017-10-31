// Get data Folder json
function getDataFolder(api, key) {
	var app = new Vue({
		el: '#app',
		data: {
			folder: '',
			json: { key : [], 'users' : [] },
			detail: '',
		},
		created: function () {
			var _this = this;
			// Folder
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

// Get data Detail Folder json
function getDataFolderDetail(api, key) {
	var app = new Vue({
		el: '#app',
		data: {
			folder: '',
			json: { key : [], 'users' : [] },
			detail: '',
			dispositionArray: '',
			dispositionInfo: ''
		},
		created: function () {
			var _this = this;
			// Folder
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
				return this.json.users.filter(function(user) {
					return user.name.toLowerCase().indexOf(self.folder.toLowerCase())>=0;
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