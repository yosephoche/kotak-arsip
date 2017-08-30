// Get data Pengguna json
function getDataUsers(api, key) {
	var app = new Vue({
		el: '#app',
		data: {
			sort: 'asc',
			json: { key : [] },
			detail: '',
			sortKey: 'name',
			userName: '',
			userEmail: '',
			userHp: '',
			userPosition: '',
			userPhoto: ''
		},
		created: function () {
			var _this = this;
			// Pengguna
			$.getJSON(api, function (json) {
				_this.json = json;
			});
		},
		computed: {
			orderedUsers: function () {
				return _.orderBy(this.json.users, this.sortKey, this.sort);
			}
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
			sortBy: function (key, e) {
				this.sortKey = key;

				// change icon
				var element = $(e.target.closest('th'));
				$('.sort').removeClass('sort');
				element.addClass('sort');

				$('th i.i-sort').remove();
				
				if (this.sort === "asc") {
					$('.sort').append(' <i class="fa fa-angle-up i-sort"></i>');
					this.sort = "desc";
				} else {
					$('.sort').append(' <i class="fa fa-angle-down i-sort"></i>');
					this.sort = "asc";
				}

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