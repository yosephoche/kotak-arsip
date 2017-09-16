Vue.component('detail', {
	template: '#sidebar-detail',
	props: {
		detail: Object
	},
	filters: {
		moment: function (date) {
			var day = moment(date, "x").date();
			var month = moment(date, "x").month() + 1;
			var year = moment(date, "x").year();
			return day + "/" + month + "/" + year;
		}
	}
});

Vue.component('no-select', {
	template: '#no-select'
});