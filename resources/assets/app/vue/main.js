// Get data Surat Masuk json
function getDataSuratMasuk(api, key) {
	var app = new Vue({
		el: '#app',
		data: {
			search: '',
			json: { key : [] },
			detail: '',
			pengguna: { "pengguna" : [] }
		},
		created: function () {
			var _this = this;
			// Surat Masuk
			$.getJSON(api[0], function (json) {
				_this.json = json;
			});
			// Pengguna
			$.getJSON(api[1], function (json) {
				_this.pengguna = json;
			});
		},
		methods: {
			detailSidebar: function (val, e) {
				this.detail = val;
				var element = $(e.target).closest('.item');
				console.log(element);
				
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
		computed: {
			filteredUsers:function() {
				var self = this;
				return this.pengguna.pengguna.filter(function(user) {
					return user.nama.toLowerCase().indexOf(self.search.toLowerCase())>=0;
				});
			}
		}
	});
}

// Get data Detail Surat Masuk json
function getDataSuratMasukDetail(api, key) {
	var app = new Vue({
		el: '#app',
		data: {
			search: '',
			json: { key : [] },
			pengguna: { "pengguna" : [] }
		},
		created: function () {
			var _this = this;
			// Surat Masuk
			$.getJSON(api[0], function (json) {
				_this.json = json;
			});
			// Pengguna
			$.getJSON(api[1], function (json) {
				_this.pengguna = json;
			});
		},
		computed: {
			filteredUsers:function() {
				var self = this;
				return this.pengguna.pengguna.filter(function(user) {
					return user.nama.toLowerCase().indexOf(self.search.toLowerCase())>=0;
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