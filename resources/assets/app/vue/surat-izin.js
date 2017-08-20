// Get data Surat Masuk json
function getDataLicense(api, key) {
	var app = new Vue({
		el: '#app',
		data: {
			search: '',
			sort: 'asc',
			json: { key : [] },
			detail: '',
		},
		created: function () {
			var _this = this;
			// Surat Masuk
			$.getJSON(api, function (json) {
				_this.json = json;
			});
		},
		computed: {
			orderedLicense: function () {
				return _.orderBy(this.json.license, 'type', this.sort);
			}
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
			},
			sortBy: function (e) {
				// change icon
				$(e.target.closest('th')).find('i').toggleClass('fa-angle-down fa-angle-up');
				
				if (this.sort === "asc") {
					this.sort = "desc";
				} else {
					this.sort = "asc";
				}

			}
		}
	});
}