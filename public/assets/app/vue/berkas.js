// Get data Files json
function getDataFiles(api, key) {
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
			// Files
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
					if (data[i] != null) {
						id.push(data[i]._id.$oid);
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

// Get data Detail Files json
function getDataFilesDetail(api, key) {
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
			// Files
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
					if (data[i] != null) {
						id.push(data[i]._id.$oid);
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
			},
			datetime: function (date) {
				return moment(date).format("DD/MM/YYYY hh:mm:ss");
			},
			fromnow: function (date) {
				moment.locale('fr', {
					months : 'Januari_Februari_Maret_April_Mei_Juni_Juli_Agustus_September_Oktober_November_Desember'.split('_'),
					monthsShort : 'Jan_Feb_Mar_Apr_Mei_Jun_Jul_Ags_Sep_Okt_Nov_Des'.split('_'),
					weekdays : 'Minggu_Senin_Selasa_Rabu_Kamis_Jumat_Sabtu'.split('_'),
					weekdaysShort : 'Min_Sen_Sel_Rab_Kam_Jum_Sab'.split('_'),
					weekdaysMin : 'Mg_Sn_Sl_Rb_Km_Jm_Sb'.split('_'),
					weekdaysParseExact : true,
					longDateFormat : {
						LT : 'HH.mm',
						LTS : 'HH.mm.ss',
						L : 'DD/MM/YYYY',
						LL : 'D MMMM YYYY',
						LLL : 'D MMMM YYYY [pukul] HH.mm',
						LLLL : 'dddd, D MMMM YYYY [pukul] HH.mm'
					},
					meridiemParse: /pagi|siang|sore|malam/,
					meridiemHour : function (hour, meridiem) {
						if (hour === 12) {
							hour = 0;
						}
						if (meridiem === 'pagi') {
							return hour;
						} else if (meridiem === 'siang') {
							return hour >= 11 ? hour : hour + 12;
						} else if (meridiem === 'sore' || meridiem === 'malam') {
							return hour + 12;
						}
					},
					meridiem : function (hours, minutes, isLower) {
						if (hours < 11) {
							return 'pagi';
						} else if (hours < 15) {
							return 'siang';
						} else if (hours < 19) {
							return 'sore';
						} else {
							return 'malam';
						}
					},
					calendar : {
						sameDay : '[Hari ini pukul] LT',
						nextDay : '[Besok pukul] LT',
						nextWeek : 'dddd [pukul] LT',
						lastDay : '[Kemarin pukul] LT',
						lastWeek : 'dddd [lalu pukul] LT',
						sameElse : 'L'
					},
					relativeTime : {
						future : 'dalam %s',
						past : '%s yang lalu',
						s : 'beberapa detik',
						m : 'semenit',
						mm : '%d menit',
						h : 'sejam',
						hh : '%d jam',
						d : 'sehari',
						dd : '%d hari',
						M : 'sebulan',
						MM : '%d bulan',
						y : 'setahun',
						yy : '%d tahun'
					},
					week : {
						dow : 1, // Monday is the first day of the week.
						doy : 7  // The week that contains Jan 1st is the first week of the year.
					}
				});
				return moment(date).fromNow();
			}
		}
	});
}