Vue.component('detail', {
	template: '#sidebar-detail',
	props: {
		detail: Object
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

Vue.component('no-select', {
	template: '#no-select'
});