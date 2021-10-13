var vue = new Vue({
	el: '#app',
	data: {
		Lists: [],
		type: 'match',
		swiperSlide: function () { },
		datas: {},
		types: 1,
		url: 'tradeAccount.html?id=',
		changeCount: 0,
		leverCount: 0,
		microCount: 0,
		show: true,
		hideit: '*****',
		legalCount:0,
        totalCount:0,
        yubao_linv:0,
        yubao:0,
        yubao_lixi:0,
        yubao_total:0,
        ulipaiList:[],
        trusteeship_funds:0,
        expected_earnings_today:0,
        cumulative_revenue:0,
        orders_in_custody:0,

	},
	mounted: function () {
		let that = this;
		let text = '';
		that.listAjax(text);
		that.swipers();
	},
	filters: {
		toFixedTwo: function (value) {
			var vals = iTofixed(value,5)
			return vals;
		},
		toFixedfour: function (value) {
			var vals = iTofixed(value,4)
			return vals;
		}
	},
	methods: {
		// 头部轮播图
		search() {
			let that = this;
			let text = $('.search_text').val();
			that.listAjax(text);
			// socket实时更新数据
			that.socket();
		},
		//socket连接封装
		socket() {
			let that = this;
			var socket = io(socket_api);
			socket.on('connect', function (datas) {
				//socket.emit('login', data.message.id);
				// 后端推送来消息时
				socket.on('kline', function (msg) {
					// console.log(msg);
					// // if (msg.type == 'kline') {
					// 	// now_price
					// 	console.log(that.lists)
					// 	that.lists.find((item) => item.currency == msg.currency_id).usdt_price = msg.close;
					console.log(that.list)

						for (i in that.list) {
							if (that.list[i].currency == msg.currency_id) {
								that.list[i].usdt_price = msg.close;
							}
						}
					// }
				});
			});
		},
		listAjax(texts) {
			let that = this;
			initDataTokens({
				url: 'wallet/list',
				data: {
					currency_name: texts
				},
				type: 'post'
			}, function (res) {
				if (res.type == 'ok') {
					if (texts == '') {
						that.datas = res.message;
					}
					
					that.ulipaiList=res.message.ulipaigoods;
					that.trusteeship_funds=res.message.trusteeship_funds;
                    that.expected_earnings_today=res.message.expected_earnings_today;
                    that.cumulative_revenue=res.message.cumulative_revenue;
                    that.orders_in_custody=res.message.orders_in_custody;
					
					that.yubao_linv=res.message.yubao_linv;
					that.yubao=res.message.yubao;
					that.yubao_lixi=res.message.yubaolixi;
					that.yubao_total=res.message.yubaototal;
					that.changeCount = res.message.change_wallet.usdt_totle;
					that.leverCount = res.message.lever_wallet.usdt_totle;
					that.microCount = res.message.micro_wallet.usdt_totle+res.message.yubao;
					that.legalCount = res.message.legal_wallet.usdt_totle;
					that.totalCount=res.message.change_wallet.usdt_totle+res.message.lever_wallet.usdt_totle+res.message.micro_wallet.usdt_totle+res.message.legal_wallet.usdt_totle;
					if (that.type == 'lever') {
						that.Lists = res.message.lever_wallet.balance;
						that.types = 0;
					} else if (that.type == 'micro') {
						that.Lists = res.message.micro_wallet.balance;
						that.types = 1;
					} else if (that.type == 'match') {
						that.Lists = res.message.change_wallet.balance;
						that.types = 2;
					}else if (that.type == 'legal') {
						that.Lists = res.message.legal_wallet.balance;
						that.types = 3;
					}
				}

			})
				// initDataTokens({
				// 	url: 'update_balance',
				// }, function (res) {
				// 	if (res.type == 'ok') {

				// 	}

				// })
		},
		// 头部轮播图切换
		swipers() {
			let that = this;
			that.swiperSlide = new Swiper('.mycontainer', {
				slidesPerView: 'auto',
				on: {
					transitionEnd: function () {
						$('.search_text').val('');
						current = that.swiperSlide.snapIndex;
						// i = current;
						// if (current == 0) {
						// 	that.types = 0;
						// 	that.type = 'lever';
						// 	that.Lists = that.datas.lever_wallet.balance;
						// } else if (current == 1) {
						// 	that.types = 1;
						// 	that.Lists = that.datas.micro_wallet.balance;
						// 	that.type = 'micro';
						// } else if (current == 2) {
						// 	that.types = 2;
						// 	that.Lists = that.datas.change_wallet.balance;
						// 	that.type = 'match';
						// }else if (current == 3) {
						// 	that.types = 3;
						// 	that.Lists = that.datas.legal_wallet.balance;
						// 	that.type = 'legal';
						// }
						// 2021-10-10强行修改
						that.Lists = that.datas.change_wallet.balance;
						that.type = 'match';


					},
				},
			});
		},
		tabClick(options) {
			$('.search_text').val('');
			let that = this;
			that.type = options;
			if (options == 'lever') {
				// that.swiperSlide.slideTo(0);
				that.types = 0;
				that.Lists = that.datas.lever_wallet.balance;
			} else if (options == 'micro') {
				// that.swiperSlide.slideTo(1);
				that.types = 1;
				that.Lists = that.datas.micro_wallet.balance;
			} else if (options == 'match') {
				// that.swiperSlide.slideTo(2);
				that.types = 2;
				that.Lists = that.datas.change_wallet.balance;
			}else if (options == 'legal') {
				// that.swiperSlide.slideTo(3);
				that.types = 3;
				that.Lists = that.datas.legal_wallet.balance;
			}
		},
		// 链接跳转
		links(options) {
			let that = this;
			if (that.type == 'lever') {
				window.location.href = 'leverAccount.html?id=' + options + '&type=3';
			} else if (that.type == 'micro') {
				window.location.href = 'microAccount.html?id=' + options + '&type=4';
			} else if (that.type == 'match') {
				window.location.href = 'matchAccount.html?id=' + options + '&type=2';
			}else if (that.type == 'legal') {
				window.location.href = 'legalAccount.html?id=' + options + '&type=1';
			}
		},
		hide() {
			this.show = !this.show;
		}

	}
});

