/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年8月1日
* To change this template use File | Settings | File Templates.
*/
define("autodiv/autodiv", function(e) {
	var i = {
		resize: function() {
			var e = 500,
				i = 168,
				n = 0,
				t = navigator.userAgent.toLowerCase();
			n += t.indexOf("360se") > -1 ? 3 : t.indexOf("firefox") > -1 ? 1 : 2;
			var r = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
			e > r && (r = e);
			var o = r - i - n,
				a = {
					map_body_box: o,
					map_canvas: o - 72,
					inforight: o + 1,
					right_result_wrap: o - 29
				};
			for (var d in a) {
				var u = $("#" + d);
				u.height(a[d])
			}
		}
	};
	return i
});
define("modules/esf/SFMap", ["modules/SFUtil", "modules/esf/MapApi", "lazyload/lazyload", "scrollbar/scrollbar"], function(a) {
	"use strict";
	var e = a("modules/esf/MapApi"),
		t = a("jquery"),
		r = a("modules/SFUtil"),
		i = seajs.data.vars,
		s = i.city,
		o = i.cityx,
		n = i.cityy,
		l = i.zoom = 12,
		p = i.localStorage,
		c = {
			map: new e("mapCanvas", n, o, l, {
				NotAddNavCtrl: !1,
				BindEvent: !0
			}),
			API_PATH: i.ajaxfilePath,
			api: {
				GET_FILTER_DISTAREA: "getDistArea",
				GET_FILTER_SUBWAY: "getSubway",
				GET_SEARCH: "ajaxSearch",
				Collect: "addSelect"
			},
			config: {
				leveledMenu: {
					district: {
						id: "district",
						subId: "area",
						data: [],
						defaultVal: "区域找房",
						title: "选择区域"
					},
					subway: {
						id: "subway",
						subId: "subwaystation",
						data: [],
						defaultVal: "地铁找房",
						title: "选择地铁"
					}
				}
			},
			params: {
				district: "",
				subwayline: "",
				subwaystation: "",
				price: "",
				room: "",
				area: "",
				towards: "",
				floor: "",
				hage: "",
				equipment: "",
				keyword: "",
				comarea: "",
				orderby: "",
				isyouhui: "",
				x1: "",
				y1: "",
				x2: "",
				y2: "",
				newCode: "",
				houseNum: "",
				schoolDist: "",
				schoolid: "",
				ecshop: ""
			},
			markerList: {},
			firstLoad: !0,
			searchType: "city",
			searchWhileMove: !0,
			cityBounds: {},
			cityCenter: {
				x: o,
				y: n
			},
			bounds: {},
			subwayMarkers: [],
			activCls: "activClo",
			init: function() {
				var a = this;
				a.cityBounds = a.map.gethdBounds(), a.bindEvent(), a.initParams(), a.searchResult(), setTimeout(a.initFilterData(), 200), a.busline = new BMap.BusLineSearch(a.map._map, {
					renderOptions: {},
					onGetBusListComplete: function(a) {
						if (a) {
							var e = a.getBusListItem(0);
							c.busline.getBusLine(e)
						}
					},
					onGetBusLineComplete: function(e) {
						var t = e.getPolyline();
						t.setStrokeColor("#e84a01"), a.subwayMarkers.push(t), a.map._map.addOverlay(t)
					}
				}), a.driving = new BMap.DrivingRoute(a.map._map, {
					renderOptions: {
						map: a.map._map,
						autoViewport: !0
					}
				})
			},
			panBy: function() {
				var a = this;
				a.map._map.panBy(198, 0, {
					noAnimation: !0
				})
			},
			resize: function(a) {
				var e = t(".wrapper").eq(0),
					r = t(".mapL").eq(0),
					i = window.innerHeight - e.offset().top - 1,
					s = 348,
					o = 0;
				if (t(".priceTrends,.houseNum").length || (o = 48, t(".searchResult").length || (o = 80)), s -= o, a) {
					var n = t(".scrollf").height() + 16,
						l = i - s;
					return l > n ? n : l
				}
				var p = i + "px";
				e.height(p), r.css({
					"max-height": e.height()
				})
			},
			bindEvent: function() {
				var a = this;
				t(window).resize(function() {
					a.resize()
				}), a.map._map.panBy(198, 0, {
					noAnimation: !0
				}), t(function() {
					var e = t("#Tab");
					e.on("click", function() {
						t(".searchResult").toggleClass("hide"), t(".mapList").toggleClass("hide"), t("#Tab").toggleClass("h"), t(".mapLT").toggleClass("mapLT_pz"), t(".search_nei").toggleClass("mt_100"), a.mapListShow = !a.mapListShow;
						var e = a.mapListShow ? -198 : 198;
						a.map._map.panBy(e, 0, {
							noAnimation: !0
						})
					}), t("#searchResult").on("click", ".floatArea", function() {
						var e = t(this),
							r = e.attr("data_id"),
							i = e.children("span").eq(0).text(),
							s = e.attr("data_x"),
							o = e.attr("data_y");
						a.params.subwayline ? (t("#subwayParamContent").text(i), a.subwaystationDisp = i, a.params.subwaystation = r, c.isClickStation = "16", c.searchResult(null, 16, !1, s, o)) : a.gotoDistrict(r, i, s, o)
					});
					var r = t("#area"),
						i = t("#subway"),
						s = t(".area"),
						o = t(".haveSel em"),
						n = t("#distAreaLeft"),
						l = t("#distAreaRight"),
						p = t("#subwayLeft"),
						d = t("#subwayRight"),
						m = t("#schoolDist"),
						h = t("#schoolDistContent"),
						u = t("#schoolDist em");
					m.on("mouseenter", function() {
						u.addClass("up"), h.show()
					}).on("mouseleave", function() {
						u.removeClass("up"), h.hide()
					}), r.on("mouseenter", function() {
						o.eq(0).addClass("up");
						var a = l.children().eq(0);
						"" == a.text() ? s.css("width", "120px") : s.css("width", "550px"), s.eq(0).show(), a.show();
						var e = l.height() + 30;
						e = 288 > e ? 288 : e, n.css("minHeight", e).find("li").css("backgroundColor", "#f3f3f3")
					}).on("mouseleave", function() {
						s.eq(0).hide(), o.eq(0).removeClass("up"), l.children().hide()
					}), i.on("mouseenter", function() {
						p.find("li").css("backgroundColor", "#f3f3f3"), s.eq(1).show(), o.eq(1).addClass("up");
						var a = d.children().eq(0),
							e = a ? a.data("id") : "sorryNo";
						d.children("[data-id=" + e + "]").show(), t("#esf_E01_09").css("width", "100px"), d.children().hide()
					}).on("mouseleave", function() {
						s.eq(1).hide(), o.eq(1).removeClass("up"), d.children().hide()
					});
					var f = t(".seaSelectM");
					f.mouseenter(function() {
						var a = t(this);
						a.find(".haveSelM").next().show(), a.find("em").addClass("up")
					}).mouseleave(function() {
						var a = t(this);
						a.find(".haveSelM").next().hide(), a.find("em").removeClass("up")
					})
				})
			},
			moreConNum: function(a, e, r) {
				var i = this.params,
					s = 0,
					o = "全部";
				"" !== i.floor && o !== i.floor && s++, i.hage && o !== i.hage && s++, i.towards && o !== i.towards && s++, i.equipment && o !== i.equipment && s++, t("#" + a).find("a").css("color", "#333"), r && o !== r && e.css("color", "#c00");
				var n = t("#moresearchcount");
				s > 0 ? n.text("(" + s + ")") : n.text("")
			},
			moreStyle: function(a, e) {
				var r = this;
				t("#" + a).find("a").each(function(i, s) {
					var o = t(s);
					e === o.attr("data-id") && r.moreConNum(a, o, e)
				})
			},
			clearOtherOption: function(a, e) {
				var r = this,
					i = t("#areaParamContent"),
					s = t("#subwayParamContent"),
					o = t("#esf_E01_46"),
					n = t("#schoolDistContent li div"),
					l = t("#subwayParam"),
					p = t("#areaParam");
				switch (a) {
				case "keyword":
					r.params.subwayline = "", r.params.subwaystation = "", r.params.district = "", r.params.comarea = "", s.text("地铁找房"), i.text("区域找房"), r.params.schoolDist = "", r.params.schoolid = "", n.removeClass("on"), l.children().removeClass("shai_after"), p.children().removeClass("shai_after");
					break;
				case "district":
					r.params.keyword = "", o.val(""), r.params.subwayline = "", r.params.subwaystation = "", s.text("地铁找房"), r.params.comarea && (r.params.schoolDist = "", r.params.schoolid = "", n.removeClass("on"));
					break;
				case "subway":
					r.params.keyword = "", o.val(""), r.params.district = "", r.params.comarea = "", r.params.schoolDist = "", r.params.schoolid = "", n.removeClass("on"), i.text("区域找房"), p.children().removeClass("shai_after");
					break;
				case "map":
					r.params.keyword = "", o.val(""), e || (r.params.subwayline = "", r.params.subwaystation = "", s.html("地铁找房"), l.children().removeClass("shai_after")), r.params.district = "", r.params.comarea = "", i.html("区域找房"), p.children().removeClass("shai_after");
					break;
				case "schoolDist":
					r.params.keyword = "", o.val(""), r.params.comarea && (r.params.district = "", r.params.comarea = "", i.html("区域找房")), r.params.subwayline = "", r.params.subwaystation = "", s.html("地铁找房"), p.children().removeClass("shai_after");
					break;
				case "ecshop":
					r.params.keyword = "", o.val(""), r.params.district = "", r.params.comarea = "", r.params.schoolDist = "", r.params.schoolid = "", n.removeClass("on"), i.text("区域找房"), p.children().removeClass("shai_after"), r.params.subwayline = "", r.params.subwaystation = "", s.text("地铁找房")
				}
				r.params.newCode = "", r.params.houseNum = ""
			},
			initParams: function() {
				var a = this,
					e = a.params;
				if (i.equipment) {
					var r = i.equipment;
					t("#equipmentWrap").find("a").each(function(i, s) {
						var o = t(s);
						r === o.text() && (e.equipment = o.attr("data-id"), a.moreConNum("equipmentWrap", o, r))
					})
				}
				if (i.towards) {
					var s = i.towards;
					t("#towardsWrap").find("a").each(function(r, i) {
						var o = t(i);
						s === o.attr("data-value") && (e.towards = o.attr("data-id"), a.moreConNum("towardsWrap", o, s))
					})
				}
				if (i.floor) {
					var o = i.floor.replace("[", "").replace("]", "").replace(",", "-");
					e.floor = o, a.moreStyle("floorWrap", o)
				}
				if (i.hage) {
					var n = i.hage.replace("[", "").replace("]", "").replace(",", "-");
					e.hage = n, a.moreStyle("hageWrap", n)
				}
				var l = i.district,
					p = i.comarea,
					c = t("#areaParam"),
					d = t("#areaParamContent");
				if (i.district ? (i.comarea ? (e.district = i.district, p = i.comarea, e.comarea = p, d.text(p)) : (e.district = l, d.text(l)), c.children().addClass("shai_after")) : c.children().removeClass("shai_after"), i.isyouhui && (e.isyouhui = i.isyouhui, t("#esf_E01_42 div").addClass("on")), i.keyword) {
					var m = i.keyword;
					e.keyword = m, t("#esf_E01_46").val(m)
				}
				var h = i.subwayline,
					u = i.subwaystation,
					f = t("#subwayParam"),
					y = t("#subwayParamContent");
				if (i.subwayline ? (i.subwaystation ? (e.subwayline = h, e.subwaystation = u, y.text(u)) : (e.subwayline = h, y.text(h)), f.children().addClass("shai_after")) : f.children().removeClass("shai_after"), i.areamin || i.areamax) {
					var v = t("#areaParamCont"),
						w = t("#acreageWrap"),
						g = "";
					i.areamin > 0 && i.areamax ? (g = i.areamin + "-" + i.areamax + "平米", e.area = i.areamin + "-" + i.areamax) : i.areamin > 0 ? (g = i.areamin + "平米以上", e.area = i.areamin + "-") : i.areamax && (g = i.areamax + "平米以下", e.area = "0-" + i.areamax), v.text(g || "不限"), w.find("a[data-id=" + e.area + "]").addClass(a.activCls)
				}
				if (i.pricemin || i.pricemax) {
					var x = t("#priceParamContent"),
						b = t("#priceWrap"),
						C = "";
					i.pricemin > 0 && i.pricemax ? (C = i.pricemin + "-" + i.pricemax + "万", e.price = i.pricemin + "-" + i.pricemax) : i.pricemin > 0 ? (C = i.pricemin + "万以上", e.price = i.pricemin + "-") : i.pricemax && (C = i.pricemax + "万以下", e.price = "0-" + i.pricemax), x.text(C || "不限"), b.find("a[data-id=" + e.price + "]").addClass(a.activCls)
				}
				i.room && (e.room = "[5,99]" == i.room ? "99" : i.room, t("#roomWrap").find("a").each(function(r, i) {
					var s = t(i);
					e.room === s.attr("data-id") && (t("#huxingParamContent").text(s.text()), s.addClass(a.activCls))
				})), i.orderby && (e.orderby = i.orderby)
			},
			windowOpen: function(a, e) {
				e = e || "_blank";
				var r = "soufun_search_open_new_window";
				t("a#" + r).remove();
				var i = '<a href="' + a + '" id="' + r + '" target="' + e + '"><span id="thisa"></span></a>';
				t("body").append(i), t("#thisa").click()
			},
			picAddress: function(a) {
				var e = a.picAddress;
				return e || (e = i.imgUrl + "img/shipin0.jpg"), e
			},
			setMetaMarkers: function(a, e) {
				if (a && a.length) {
					for (var t = this, s = [], o = a.length, n = 0; o > n; n++) {
						var l = a[n];
						if (!l.yidi) {
							l.imgPath = i.imgUrl, l.picAddress = t.picAddress(l), l.tel = l.tel400 || l.tel, l.tel || (l.tel = "暂无"), l.purpose || (l.purpose = "暂无"), l.startTime || (l.startTime = "暂无"), l.developer || (l.developer = "暂无"), l.address || (l.address = "暂无"), l.price_type || (l.price_type = "价格"), l.price_num = l.price_num ? l.price_num : l.price;
							var p = !l.price_unit || l.price_unit && -1 === l.price_unit.indexOf("万");
							if (l.price_num && !isNaN(l.price_num) && p && parseFloat(l.price_num) >= 1e3 && (l.price_num = (l.price_num / 1e4).toFixed(1) + "万"), l.price_unit && (l.price_unit = l.price_unit.replace("平方米", "㎡"), l.price_unit = l.price_unit.replace("平", "㎡")), l.title_s = l.title, e && (l.title_m = l.title), l.title) {
								var c = r.getStrlen(l.title);
								c > 36 && (l.title_s = r.subStrcn(l.title, 36)), e && c > 18 && (l.title_m = r.subStrcn(l.title, 18))
							} else l.title = l.name || l.projname || "暂无", l.title_s = "暂无", e && (l.title_m = "暂无");
							l.householdpic ? l.householdpichtml = l.householdpic : l.householdpichtml = "暂无资料", t.markerList[l.newCode] = l, t.keyPointInfo && t.keyPointInfo.name == l.title ? s.unshift(l) : s.push(l)
						}
					}
					return s
				}
			},
			showResult: function(a, e, t, r, i, s, o) {
				var n = this,
					l = n.setMetaMarkers(e, !0);
				return t = t || !1, r = r || !1, n.map.drawMarkers(l, t, r, a, i, s, o)
			},
			resizeScrollBar: function() {
				var a = this;
				t("#scrollbar1").scrollbar({
					type: "scrollbar",
					height: a.resize(!0),
					width: 480,
					scrollerEase: 7,
					dragVertical: !0,
					dragHorizontal: !0,
					barWidth: 10,
					draggerVerticalSize: "auto",
					draggerHorizontalSize: "auto",
					roundCorners: 5,
					distanceFromBar: -2,
					mouseWheel: !0,
					mouseWheelOrientation: "vertical",
					mouseWheelSpeed: 13,
					draggerColor: "#ccc",
					draggerOverColor: "#ccc",
					barColor: "#f1f1f1",
					barOverColor: "#f1f1f1"
				})
			},
			showInorderBind: function() {
				var e = this,
					i = t("#sortWrap"),
					s = t("#collect");
				i.on("click", "a", function() {
					e.clickComplete(t(this), "sort")
				}), e.resizeScrollBar(), a.async("lazyload/lazyload", function() {
					t("img[data-original]").lazyload()
				}), s.on("click", function() {
					var a = t(this),
						i = a.next(),
						s = "shoucang_hou";
					if (!r.getCookie("sfut")) return void t("#loginbox,#layer").show();
					if (!a.hasClass(s)) {
						var o = {
							a: e.api.Collect,
							houseId: i.data("id"),
							name: encodeURIComponent(i.find("h3").text()),
							address: encodeURIComponent(i.attr("data-address")),
							linkurl: encodeURIComponent(i.attr("data-url")),
							price: t(".priceTrends span:first").text(),
							dataType: "text"
						},
							n = function(e) {
								"1" === e && a.addClass(s)
							},
							l = function() {};
						r.ajax(e.API_PATH, "post", o, n, l)
					}
				});
				var o = t("#picContainer");
				o.on("mouseover", "a[data_id]", function() {
					var a = t(this).attr("data_id"),
						e = t("#mapCanvas a[data-buildid=" + a + "]");
					e.css("backgroundColor", "#199752"), e.find("div").css("borderTop", "6px solid #199752"), e.find("span").show();
					var r = e.parent().parent();
					r.css("zIndex", Math.abs(r.css("zIndex")))
				}), o.on("mouseout", "a[data_id]", function() {
					var a = t(this).attr("data_id"),
						e = t("#mapCanvas a[data-buildid=" + a + "]");
					if (e && "true" !== e.attr("data-selected")) {
						e.css("backgroundColor", e.data("bgcolor") || "#f14646"), e.find("div").css("borderTop", e.data("bgtcolor") || "#f14646"), e.find("span").hide();
						var r = e.parent().parent();
						r.css("zIndex", 0 - Math.abs(r.css("zIndex")))
					}
				});
				var n = "fangworld_esf_loupanListPics";
				if (o.on("click", "a", function() {
					if (p) {
						var a = t(this),
							e = a.attr("data_id"),
							r = JSON.parse(p.getItem(n)) || []; - 1 === t.inArray(e, r) && (a.css("backgroundColor", "#f3f3f3"), r.push(e), p.setItem(n, '["' + r.join('","') + '"]'))
					}
				}), p) for (var l = JSON.parse(p.getItem(n)) || [], c = 0, d = l.length; d > c; c++) {
					var m = l[c];
					m && o.find("a[data_id=" + m + "]").css("backgroundColor", "#f3f3f3")
				}
				t("#Tab").removeClass("h"), t(".mapLT").removeClass("mapLT_pz"), t(".search_nei").removeClass("mt_100")
			},
			searchResult: function(e, i, o, n, l, p) {
				var c = this,
					d = t("#searchResult");
				c.NotFirstDrag = !1, p && (c.params.newCode = p.newCode, c.params.houseNum = p.houseNum), c.params.x1 = c.params.x2 = c.params.y1 = c.params.y2 = "", c.params.zoom = c.params.PageNo = "";
				var m = t.extend({}, c.params),
					h = "get";
				m.a = c.api.GET_SEARCH, m.city = s;
				var u;
				n && parseFloat(n) > 0 && l && parseFloat(l) > 0 && (c.map.setCenter(l, n, i), c.panBy(), c.map.centerstartSta = c.map._map.pointToOverlayPixel(c.map.getCenter()));
				var f = t("#esf_E01_46"),
					y = f.val() || "";
				y = y.replace(/(^\s*)|(\s*$)/g, ""), "楼盘名/地名/开发商" === y ? y = "" : f.val(y), c.keepkw = y, m.keyword = y, c.isDrag = o, "zoom" === o || m.subwayline || m.district || m.comarea || m.schoolid || m.keyword || "line" === c.searchType || "station" === c.searchType ? m.comarea || m.schoolid ? m.searchtype = "loupan" : m.searchtype = "" : m.searchtype = c.searchType, (m.district || m.comarea || m.subwayline || c.map._map.getZoom() > 13) && (m.ecshop = "ecshophouse"), o && (m.schoolid = "", "zoom" === o && (i > 13 ? m.ecshop = "ecshophouse" : t("#esf_E01_64").find("div[class=on]").length ? m.ecshop = "ecshop" : m.ecshop = "")), !o && (m.subwayline || m.district || m.comarea || m.keyword) ? m = t.extend({
					mapmode: "y"
				}, m, c.cityBounds) : (e || (c.bounds = c.map.gethdBounds()), m = t.extend({
					mapmode: "y"
				}, m, c.bounds)), c.isClickStation ? m.zoom = c.isClickStation : m.zoom = c.map._map.getZoom(), "all" !== m.district && "all" !== m.subwayline || (m.zoom = "12", m.searchtype = "city", m.district = "", m.comarea = "", m.subwayline = ""), c.page = e || 1, c.pageParam = e, m.PageNo = c.page;
				var v = !1,
					w = null;
				c.params = m;
				var g = function(e) {
						c.isClickStation = "", c.searchType = e.searchtype || "loupan";
						var r, s = 0;
						if ("undefined" != typeof e.loupan && (r = "object" != typeof e.loupan ? JSON.parse(e.loupan) : e.loupan), c.pageParam && 1 !== c.pageParam || d.children(".searchResult").remove(), d.children(".mapList").remove(), e.list) {
							d.append(e.list);
							var n = d.children(".searchResult");
							n.text().trim() || n.remove(), c.resize(), c.showInorderBind(), t("#esf_E01_39").off("click").on("click", "a", function() {
								c.searchResult(t(this).data("id"))
							})
						}
						if (!c.pageParam) {
							if (r && r.loupan && r.loupan.hit && r.loupan.hit.length ? (r.hit = r.loupan.hit, c.subwaySearchType = r.loupan.type) : c.subwaySearchType = "", r && r.hit && r.hit.length && (s = r.hit.length), e && e.school && e.school.hit && e.school.hit.length && !s && (s = e.school.hit.length), e && e.subway && e.subway.hit && e.subway.hit.length && !s && (s = e.subway.hit.length), e && e.ecshop && e.ecshop.length && !s && (s = e.ecshop.length), m.newCode) t("#esf_E01_39").on("click", "a", function() {
								c.searchResult(t(this).data("id"))
							});
							else if (c.map.clearMarkers(), c.driving.clearResults(), s && r && r.hit && (m.keyword && (w = !1), "city" !== c.searchType && "dist" !== c.searchType && ("schoolcity" !== c.searchType && "school" !== c.searchType || m.district) ? (!m.schoolDist || m.schoolDist && m.schoolid) && c.showResult(s, r.hit, v, w, i, u, o) : c.showDistrict(r.hit)), s && e && e.ecshop && c.showDistrict(e.ecshop), a.async(["//js.ub.fang.com/_ub.js?v=201407181100"], function() {
								e.list && 1 === m.PageNo && c.yhxw()
							}), m.subwayline || m.subwaystation) {
								if (m.subwaystation && !r.subway, !1) for (var l = c.config.leveledMenu.subway.data, p = l.length, h = 0; p > h; h++) {
									var f = l[h];
									if (f.id === m.subwayline) {
										r ? (r.subway = {}, r.subway.hit = []) : (r = {
											subway: {}
										}, r.subway.hit = []);
										for (var y = 0, g = f.stations.length; g > y; y++) {
											var x = f.stations[y];
											r.subwayStat.push({
												name: x.station_name,
												station: 1,
												x: x.x,
												y: x.y
											})
										}
										break
									}
								}
								m.subwayline && e && e.subway && e.subway.hit && e.subway.hit.length ? (c.showResult(s, e.subway.hit, v, w, i, u, o), c.subwayStat = !0) : c.subwayStat = !1
							}
							m.schoolDist && e && e.school && e.school.hit && e.school.hit.length && c.showResult(s, e.school.hit, v, w, i, u, o), t("#searchResult").scrollTop(0), c.firstLoad = !1
						}
					},
					x = function(a) {
						console.log(a)
					};
				c.ajaxRqt = r.ajax(c.API_PATH, h, m, g, x, c.onLoading)
			},
			showDistrict: function(a) {
				var e = this;
				e.map.convertPoint(a)
			},
			hideTipsAndSuggest: function() {
				var a = t("#search_ad"),
					e = t("#panel_esf_E01_46");
				a && a.hide(), e && e.hide()
			},
			onSubwayFailure: function() {
				var a = t("#subway");
				a.prev().remove(), a.remove()
			},
			initFilterData: function() {
				var a = this;
				r.ajax(a.API_PATH, "get", {
					a: a.api.GET_FILTER_DISTAREA,
					city: s
				}, function(e) {
					a.initDistArea(e)
				}, a.onFailure, a.onLoading), r.ajax(a.API_PATH, "get", {
					a: a.api.GET_FILTER_SUBWAY,
					city: s
				}, function(e) {
					a.initSubway(e)
				}, a.onSubwayFailure, a.onLoading)
			},
			initDistArea: function(a) {
				var e = this,
					r = '<li class="clearfix" data-id="mmm,ggg"><a data-id="ttt" data-x="pppx" data-y="pppy">xxx</a>';
				r += '<a data-id="sss" data-x="pppx" data-y="pppy">yyy</a></li>';
				var i = '<li class="clearfix" data-id="ggg"><a style="width: 90px;" data-id="ttt" data-x="pppx" data-y="pppy">xxx</a></li>',
					s = '<li class="clearfix" data-id="mmm"><a data-id="ttt" data-x="pppx" data-y="pppy">xxx</a></li>',
					o = '<ul class="clearfix hide" data-id="nnn">',
					n = "</ul>",
					l = '<li class="areaRtitle" data-id="pppid" data-x="pppx" data-y="pppy">xxx</li>',
					p = '<li><a data-id="pppid" data-x="pppx" data-y="pppy">xxx</a></li>',
					d = '<div class="lineH hide"></div>';
				if (a && a.length) {
					e.config.leveledMenu.district.data = a;
					for (var m, h, u = t("#area"), f = "", y = "", v = "全部", w = "all", g = "", x = "", b = t("#distAreaLeft"), C = t("#distAreaRight"), _ = "", k = 0, P = a.length; P > k; k++) {
						m = a[k], y = "";
						var S = m.area;
						if (15 > P ? (64 !== b.width() && (b.width(64), C.css("left", 65)), y = 0 === k ? s.replace("mmm", w).replace("ttt", w).replace("pppx", "").replace("pppy", "").replace("xxx", v) : "", y += s.replace("mmm", m.id).replace("ttt", m.id).replace("pppx", m.x).replace("pppy", m.y), y = y.replace("xxx", m.name), f += y) : m.name.length > 4 ? (y = i.replace("xxx", m.name).replace("ggg", m.id), y = y.replace("ttt", m.id), y = y.replace("pppx", m.x).replace("pppy", m.y), f += y, v = "", w = "", g = "", x = "") : v ? (y = r.replace("xxx", v).replace("yyy", m.name).replace("ggg", m.id), y = y.replace("mmm", w).replace("ttt", w).replace("sss", m.id), y = y.replace("pppx", g).replace("pppy", x).replace("pppx", m.x).replace("pppy", m.y), f += y, v = "", w = "", g = "", x = "") : k === P - 1 || a[k + 1].name.length > 4 ? (y = i.replace("xxx", m.name).replace("ggg", m.id), y = y.replace("ttt", m.id), y = y.replace("pppx", m.x).replace("pppy", m.y), f += y, v = "", w = "", g = "", x = "") : (v = m.name, w = m.id, g = m.x, x = m.y), S.length > 0) {
							var R = o.replace("nnn", m.id);
							_ += R + l.replace("pppid", m.id).replace("xxx", m.name), _ = _.replace("pppx", m.x || "").replace("pppy", m.y || ""), _ += p.replace("pppid", "all").replace("xxx", "全部").replace("pppx", m.x || "").replace("pppy", m.y || "");
							for (var T = 0, I = S.length; I > T; T++) h = S[T], _ += p.replace("pppid", h.id).replace("xxx", h.name).replace("pppx", h.x || ""), _ = _.replace("pppy", h.y || "");
							_ += n + d
						} else _ += "<ul></ul>"
					}
					b.append(f), C.append(_), b.on("mouseenter", "li", function() {
						var a = t(this),
							e = a.attr("data-id").split(",");
						C.find("ul,.lineH").hide();
						var r = t(".area"),
							i = 128;
						a.parent().find("a").length < 16 && (i = 64), r.width(i);
						for (var s = 0, o = e.length; o > s; s++) {
							var n = C.find("ul[data-id=" + e[s] + "]");
							n.length > 0 && r.css("width", "550px"), s === o - 1 ? n.show() : n.show().next().show()
						}
						b.find("li").css("backgroundColor", "#f3f3f3"), a.css("backgroundColor", "white");
						var l = C.height() + 30;
						l = 288 > l ? 288 : l, b.css("minHeight", l)
					}), u.on("click", "li a", function() {
						c.clickComplete(t(this), "district")
					})
				}
			},
			initSubway: function(a) {
				var e = this,
					r = '<li class="clearfix" data-id="ttt"><a data-id="ttt">sss</a></li>',
					i = '<ul class="clearfix hide" data-title="ttt" data-id="nnn">',
					s = "</ul>",
					o = '</ul><ul class="clearfix hide" data-title="ttt" data-id="nnn">',
					n = '<li data-id="yyy"><a data-x="pppx" data-y="pppy">xxx</a></li>';
				if (a && a.length) {
					e.config.leveledMenu.subway.data = a;
					for (var l, p, d = '<li class="clearfix" data-id="all"><a data-id="all">全部</a></li>', m = t("#subwayLeft"), h = t("#subwayRight"), u = "", f = 0, y = a.length; y > f; f++) {
						l = a[f], d += r.replace(/ttt/g, l.id).replace("sss", l.name);
						var v = l.stations;
						u += i.replace("nnn", l.id).replace("ttt", l.name);
						for (var w = a.length >= 12 ? a.length : 12, g = 0, x = v.length; x > g; g++) p = v[g], 0 !== g && g % w === 0 && (u += o.replace("nnn", l.id).replace("ttt", l.name)), u += n.replace("yyy", p.id).replace("xxx", p.station_name), u = u.replace("pppx", p.x || "").replace("pppy", p.y || "");
						u += s
					}
					m.append(d), h.append(u), m.on("mouseenter", "li", function() {
						var a = t(this),
							e = a.data("id");
						h.find("ul").hide();
						var r = h.find("ul[data-id=" + e + "]"),
							i = t("#esf_E01_09");
						"all" !== t(this).data("id") ? i.css("width", 600 - 116 * (4 - r.length)) : i.css("width", "100px"), r.show(), m.find("li").css("backgroundColor", "#f3f3f3"), a.css("backgroundColor", "white")
					}), t("#subway").on("click", "li a", function() {
						c.clickComplete(t(this), "subway")
					})
				} else e.onSubwayFailure()
			},
			onLoading: function() {},
			gotoDistrict: function(a, e, r, i, s) {
				var o = this,
					n = t("#areaParamContent"),
					l = 14;
				s ? (o.params.schoolid = s, l = 16, o.clearOtherOption("schoolDist")) : (n.html(e), "dist" === o.searchType ? (o.params.comarea = a, l = 16) : o.params.district = a, o.clearOtherOption("district")), o.searchResult(null, l, !1, r, i)
			},
			clearAllParams: function() {
				var a = this,
					e = a.params;
				for (var r in e) e.hasOwnProperty(r) && "function" != typeof e[r] && (e[r] = "");
				var i = t("#moresearchcount"),
					s = t("#areaParam"),
					o = t("#subwayParam"),
					n = t("#towardsWrap a"),
					l = t("#floorWrap a"),
					p = t("#hageWrap a"),
					c = t("#equipmentWrap a"),
					d = t("#priceParam"),
					m = t("#huxingParam"),
					h = t("#acreageParam");
				i.html(""), s.html('<span id="areaParamContent">区域找房</span><em class=""></em>'), o.html('<span id="subwayParamContent">地铁找房</span><em class=""></em>'), d.html('<span id="priceParamContent">总价</span><em class=""></em>'), m.html('<span id="huxingParamContent">户型</span><em class=""></em>'), h.html('<span id="acreageParam">面积</span><em class=""></em>'), t("#schoolDistContent li div").removeClass("on"), n.css("color", "#333"), l.css("color", "#333"), p.css("color", "#333"), c.css("color", "#333"), t("#priceWrap").find("a").removeClass(a.activCls), t("#roomWrap").find("a").removeClass(a.activCls), t("#acreageWrap").find("a").removeClass(a.activCls)
			},
			markFilter: function(a, e) {
				var t = this;
				switch (e) {
				case "district":
					a.parent().find("li a").removeClass(t.activCls), a.find("a[data-id=" + t.params.district + "]").addClass(t.activCls);
					break;
				case "comarea":
					a.parent().parent().parent().find("li a").removeClass(t.activCls), a.addClass(t.activCls);
					break;
				case "subwayline":
					a.parent().find("li>a").removeClass(t.activCls), a.find("a").addClass(t.activCls);
					break;
				default:
					a.parent().parent().find("a").removeClass(t.activCls), a.addClass(t.activCls)
				}
			},
			clickComplete: function(a, e) {
				var r, i = this,
					s = 16;
				if (a) {
					var l = a.html(),
						p = a.attr("data-id") || "",
						d = "",
						m = "",
						h = t("#esf_E01_13");
					switch (e) {
					case "keyword":
						var u = t("#esf_E01_46").val();
						if ("楼盘名/地名/开发商" === u && "" === t.trim(u)) return !1;
						i.searchSign = 1, i.params.keyword = t.trim(u), i.clearOtherOption(e);
						break;
					case "district":
						var f = t("#areaParamContent"),
							y = a.parent().parent().children(".areaRtitle");
						m = y.attr("data-id"), d = y.text(), "all" !== p || d ? "全部" === l ? (i.params.district = m, i.params.comarea = "", f.html(d), s = 14) : (f.html(l), m ? (i.params.district = m, i.params.comarea = p) : (i.params.district = p, i.params.comarea = "", s = 14)) : (s = 12, i.clearAllParams(), i.map.clearMarkers(), i.map.clearOverlays(), c.firstLoad = !0, i.params.district = "all"), t("#esf_E01_08").hide(), i.clearOtherOption(e);
						break;
					case "subway":
						var v = t("#subwayParamContent"),
							w = a.parent();
						if (d = w.parent().data("title"), m = w.parent().attr("data-id"), p = p || w.attr("data-id"), "all" === p) i.params.subwayline = "all", i.params.subwaystation = "", s = 12, i.clearAllParams(), i.map.clearMarkers(), i.map.clearOverlays(), c.firstLoad = !0, v.html("地铁找房");
						else if (v.html(l), i.subwaylinDisp = d || l, i.subwaystationDisp = l, d) i.params.subwayline = m, i.params.subwaystation = p, i.isClickStation = "16", s = 16;
						else {
							i.params.subwayline = p, i.params.subwaystation = "";
							var g = i.config.leveledMenu.subway.data.filter(function(a) {
								return a.id === p
							});
							if (g.length) {
								for (var x = g[0].stations, b = 0, C = 0, _ = x.length, k = 0, P = x.length; P > k; k++) {
									var S = x[k];
									b += parseFloat(S.x), C += parseFloat(S.y), parseFloat(S.x) && parseFloat(S.y) || _--
								}
								r = {
									x: b / _,
									y: C / _
								}
							}
							i.isClickStation = "13", s = 13
						}
						t("#esf_E01_09").hide(), i.clearOtherOption(e);
						break;
					case "area":
						var R = t("#areaParamCont");
						"不限" === l ? (l = "面积", i.params.area = "") : i.params.area = p, i.markFilter(a), R.text(l), t("#esf_E01_12").hide();
						break;
					case "price":
						var T = t("#priceParamContent");
						p ? i.params.price = p : (i.params.price = "", l = "总价"), i.markFilter(a), T.text(l), t("#esf_E01_10").hide();
						break;
					case "room":
						var I = t("#huxingParamContent");
						p ? i.params.room = p : (i.params.room = "", l = "户型"), i.markFilter(a), I.text(l), t("#esf_E01_11").hide();
						break;
					case "towards":
						i.params.towards = p || "", i.moreConNum("towardsWrap", a, l), h.hide();
						break;
					case "floor":
						i.params.floor = p || "", i.moreConNum("floorWrap", a, l), h.hide();
						break;
					case "hage":
						i.params.hage = p || "", h.hide(), i.moreConNum("hageWrap", a, l);
						break;
					case "equipment":
						i.params.equipment = p || "", h.hide(), i.moreConNum("equipmentWrap", a, l);
						break;
					case "sort":
						if ("zhineng" == a.attr("id")) i.params.orderby = 30;
						else {
							var A = a.children("em");
							"up" === A.attr("class") ? (A.attr("class", "down"), "orderPrice" === a.attr("id") ? i.params.orderby = 3 : i.params.orderby = 16) : (A.attr("class", "up"), "orderPrice" === a.attr("id") ? i.params.orderby = 4 : i.params.orderby = 16)
						}
						var L = t(".mapListT li.on");
						L.length > 0 && L.removeClass("on"), a.parent("li").addClass("on");
						break;
					case "schoolDist":
						var D, E = t("#schoolDistContent"),
							W = a.find("div");
						p = p || a.find("a").data("id"), i.params.schoolid = "", p ? (W.toggleClass("on"), D = E.find("div[class=on]").next(), p = "", D.each(function(a, e) {
							p += t(e).attr("data-id") + ","
						}), p ? (p = p.substr(0, p.length - 1), i.params.schoolDist = p) : i.params.schoolDist = "", i.clearOtherOption(e)) : (E.find("li>div").removeClass("on"), i.params.schoolDist = "")
					}
				}
				var z = a ? a.data("x") : "",
					q = a ? a.data("y") : "";
				i.firstLoad && (z = o, q = n), r && (z = r.x, q = r.y), "subway" !== e && (i.isClickStation = ""), i.searchResult(null, s, !1, z, q)
			},
			yhxw: function() {
				var a = this,
					e = a.params,
					r = {},
					s = 0;
				if (r["vwg.page"] = "ehmap", "" !== e.keyword && (r["vwe.key"] = encodeURIComponent(e.keyword)), "" !== e.subwaystation) {
					var o = t("#subwayLeft a");
					for (s = 0; s < o.length; s++) if (t(o[s]).attr("data-id") === e.subwayline) {
						r["vwe.subway"] = encodeURIComponent(t(o[s]).text()) + "^";
						break
					}
					r["vwe.subway"] += encodeURIComponent(t("#subwayParamContent").text())
				} else if ("" !== e.subwayline) r["vwe.subway"] = encodeURIComponent(t("#subwayParamContent").text()) + "^";
				else if ("" !== e.comarea) {
					var n = t("#distAreaLeft a");
					for (s = 0; s < n.length; s++) if (t(n[s]).attr("data-id") === e.district) {
						r["vwe.position"] = encodeURIComponent(t(n[s]).text()) + "^";
						break
					}
					r["vwe.position"] += encodeURIComponent(t("#areaParamContent").text())
				} else "" !== e.district && (r["vwe.position"] = encodeURIComponent(t("#areaParamContent").text()) + "^");
				"" !== e.price && (r["vwe.totalprice"] = e.price, "-" === e.price.substr(e.price.length - 1, 1) && (r["vwe.totalprice"] += "99999"));
				var l = t("#huxingParamContent").text();
				"户型" !== l && "不限" !== l && (r["vwe.housetype"] = encodeURIComponent(l));
				var p = t("#areaParamCont").text();
				if ("面积" !== p && "不限" !== p && (r["vwe.area"] = encodeURIComponent(p)), "" !== e.hage) {
					var c = t("#hageWrap a");
					for (s = 0; s < c.length; s++) if (t(c[s]).attr("data-id") === e.hage) {
						r["vwe.houseage"] = encodeURIComponent(t(c[s]).text());
						break
					}
				}
				if ("" !== e.towards) {
					var d = t("#towardsWrap a");
					for (s = 0; s < d.length; s++) if (t(d[s]).attr("data-id") === e.towards) {
						r["vwe.direction"] = encodeURIComponent(t(d[s]).text());
						break
					}
				}
				if ("" !== e.floor) {
					var m = t("#floorWrap a");
					for (s = 0; s < m.length; s++) if (t(m[s]).attr("data-id") === e.floor) {
						r["vwe.floornum"] = encodeURIComponent(t(m[s]).text());
						break
					}
				}
				if ("" !== e.equipment) {
					var h = t("#equipmentWrap a");
					for (s = 0; s < h.length; s++) if (t(h[s]).attr("data-id") === e.equipment) {
						r["vwe.fixstatus"] = encodeURIComponent(t(h[s]).text());
						break
					}
				}
				e.isyouhui ? r["vwe.justlooksfun"] = encodeURIComponent("是") : r["vwe.justlooksfun"] = encodeURIComponent("否"), a.searchWhileMove ? r["vwe.movemapsearch"] = encodeURIComponent("是") : r["vwe.movemapsearch"] = encodeURIComponent("否"), r["vwe.showhouseid"] = "", t(".houseList a").each(function() {
					r["vwe.showhouseid"] += t(this).attr("data_id") + ","
				}), r["vwe.showhouseid"].length > 0 && (r["vwe.showhouseid"] = r["vwe.showhouseid"].slice(0, -2)), _ub.city = i.cityname, _ub.biz = "e", _ub.location = 0, _ub.collect(1, r)
			}
		};
	return c
});
define("modules/esf/dhjs", ["modules/SFUtil", "modules/esf/SFMap", "jquery"], function(e) {
	"use strict";
	var i = seajs.data.vars,
		t = e("modules/SFUtil"),
		a = e("modules/esf/SFMap"),
		o = e("jquery");
	return {
		init: function() {
			function e() {
				var i, t;
				for (E = E.slice(0, 3), i = E.length, N.html(""), t = 0; i > t; t++) N.append('<li><a id="esf_E01_43" class="searchHis clearfix ad" data-id = ' + E[t] + ">" + E[t] + "</a></li>");
				var a = 10 - i;
				for (t = 0; a > t; t++) N.append(S[t]);
				i && T.appendTo(N).show(), T.off("click").on("click", function() {
					E = [], b.setItem("search_his", E.join(",")), e()
				})
			}
			function n() {
				var i = C.val();
				E = E || [];
				var t, a, o = E.length;
				for (a = 0; o > a; a++) if (t = E[a], t === i) {
					E.splice(a, 1);
					break
				}
				/^\s*$/.test(i) || (E.unshift(i), E = E.slice(0, 3), b.setItem("search_his", E.join(",")), e())
			}
			o(document).on("click", function(e) {
				e = e || window.event;
				var i = o(e.target || e.srcElement),
					a = o("#search_ad"),
					n = o("#panel_esf_E01_46");
				0 == a.length || t.containNode(a, i) || t.containNode(o("#showsuggest"), i) || t.containNode(o("#esf_E01_46"), i) || a.hide(), 0 == n.length || t.containNode(n, i) || t.containNode(o("#showsuggest"), i) || t.containNode(o("#esf_E01_46"), i) || n.hide()
			}), t.getLoginStatus(), o("#esf_E01_07").on("click", function() {
				var e = a.params;
				e.a = "getshortUrl", e.city = i.city;
				var o = function(e) {
						window.location = e.result
					};
				return t.ajax(i.ajaxfilePath, "get", e, o), !1
			}), o("#password").on({
				focus: function() {
					o("#label_pwd").html("")
				},
				blur: function() {
					"" == this.value && o("#label_pwd").html("密码")
				}
			});
			var c = o(".mapChoose"),
				s = function() {
					a.params.newCode = "", a.params.houseNum = "";
					var e = o(this),
						i = e.children().eq(0);
					i.toggleClass("on");
					var t = i.hasClass("on");
					"esf_E01_42" === e.attr("id") ? (t ? a.params.isyouhui = "y" : a.params.isyouhui = "", a.searchResult()) : "esf_E01_41" === e.attr("id") ? t ? a.searchWhileMove = !0 : a.searchWhileMove = !1 : t ? (a.params.ecshop = "ecshop", a.searchType = "city", a.clearOtherOption("ecshop"), a.searchResult(null, 12, !1, a.cityCenter.x, a.cityCenter.y)) : (a.params.ecshop = "", a.searchResult())
				};
			c.on("click", "li", s);
			var l = o("#priceParamContent"),
				r = o("#priceInputBtn"),
				u = o("#priceBegTxt"),
				h = o("#priceEndTxt");
			h.on("blur", function() {
				var e = o(this),
					i = e.val();
				(isNaN(o.trim(i)) || parseFloat(o.trim(i)) <= 0) && e.val("")
			}).on("keypress", function() {
				return h.val().trim().length >= 5 ? !1 : void 0
			}), u.on("blur", function() {
				var e = o(this),
					i = e.val();
				(isNaN(o.trim(i)) || parseFloat(o.trim(i)) <= 0) && e.val("")
			}).on("keypress", function() {
				return u.val().trim().length >= 5 ? !1 : void 0
			}), r.on("click", function() {
				var e = u.val(),
					i = h.val(),
					t = "",
					n = e && !isNaN(e),
					c = i && !isNaN(i);
				if (n && c) {
					var s = 0;
					parseFloat(i) < parseFloat(e) && (s = e, e = i, i = s), t = e + "-" + i + "万", a.params.price = e + "-" + i
				} else n ? (t = e + "万以上", a.params.price = e + "-") : c && (t = i + "万以下", a.params.price = "0-" + i);
				l.text(t || "总价"), u.val(""), h.val("");
				var r = o("#priceWrap");
				r.find("a").removeClass(a.activCls), r.find("a[data-id=" + a.params.price + "]").addClass(a.activCls), a.clickComplete(null, "price"), o("#esf_E01_10").slideUp()
			});
			var p = o("#acreageWrap"),
				f = o("#priceWrap"),
				d = o("#roomWrap"),
				m = o("#towardsWrap"),
				v = o("#floorWrap"),
				k = o("#equipmentWrap"),
				_ = o("#hageWrap"),
				g = o("#schoolDistContent"),
				w = o("#esf_E01_06"),
				C = o("#esf_E01_46"),
				y = o("#sortWrap"),
				N = o("#search_ad"),
				T = o(".removeBtn"),
				b = i.localStorage,
				E = [],
				S = N.find(".adv");
			if (b) {
				var x = b.getItem("search_his");
				x && (E = x.split(",") || []), e(), N.on("click", ".searchHis", function() {
					var e = o(this),
						i = e.attr("data-id");
					i && o("#esf_E01_46").css("color", "#000"), C.val(i), a.clickComplete(o(this), "keyword"), n()
				})
			}
			w.click(function() {
				var e = o.trim(C.val());
				e === i.goldAdTitle && i.goldAdTitle ? a.windowOpen(i.goldAdUrl, "_blank") : (t.searchType = "fromSearchBtn", a.clickToSearchStartTime = (new Date).getTime(), a.clickComplete(o(this), "keyword"), n())
			}), C.keydown(function(e) {
				t.searchType = "fromSearchBtn", a.clickToSearchStartTime = (new Date).getTime();
				var i = e || window.event;
				return "13" == i.keyCode ? (i && i.preventDefault ? i.preventDefault() : window.event.returnValue = !1, a.clickComplete(o(this), "keyword"), n(), !1) : void 0
			}), g.on("click", "li", function() {
				a.clickComplete(o(this), "schoolDist")
			}), p.on("click", "li a", function() {
				a.clickComplete(o(this), "area")
			}), f.on("click", "li a", function() {
				a.clickComplete(o(this), "price")
			}), d.on("click", "li a", function() {
				a.clickComplete(o(this), "room")
			}), m.on("click", " a", function() {
				a.clickComplete(o(this), "towards")
			}), v.on("click", "a", function() {
				a.clickComplete(o(this), "floor")
			}), _.on("click", "a", function() {
				a.clickComplete(o(this), "hage")
			}), k.on("click", "a", function() {
				a.clickComplete(o(this), "equipment")
			}), y.on("click", "a", function() {
				a.clickComplete(o(this), "sort")
			}), o("#loginBox_close").click(function() {
				o("#loginbox,#layer").hide()
			}), o("#username").focus(function() {
				this.value = ""
			}).blur(function() {
				"" == o.trim(this.value) && (this.value = "手机号/邮箱/用户名")
			}), o("#nhlogin_tx").focus(function() {
				o(this).hide(), o("#password").show().focus()
			}), o("#password").focus(function() {
				this.value == this.defaultValue && (this.value = "", this.select(), this.style.color = "#333")
			}).blur(function() {
				var e = o("#nhlogin_tx"),
					i = o(this);
				"" == i.val() ? (e.show(), i.hide()) : (e.hide(), i.show())
			}), o("#verify").focus(function() {
				this.value = ""
			}).blur(function() {
				"" == o.trim(this.value) && (this.value = "请输入验证码")
			}), o("a.nhlogin_a").click(function() {
				o("#randImage").attr("src", "?c=user&a=authCode&random=" + Math.random())
			}), o("#submit_btn").click(function() {
				t.login("esf")
			})
		}
	}
});
define("modules/esf/suggest", ["jquery"], function(e) {
	"use strict";
	var t = seajs.data.vars,
		s = t.city,
		a = e("jquery");
	return {
		suggest_selected: 0,
		suggest_url: t.ajaxfilePath,
		suggest_tip: "",
		createPanel: function() {
			if (!this.panel) {
				var e = document.createElement("ul");
				e.id = "panel_esf_E01_46", e.className = "seaTip dis", e.innerHTML = this.suggest_tip, e.onmouseover = function() {}, e.onmouseout = function() {}, a("#suggestId").append(e), this.panel = e
			}
			return this.panel
		},
		init: function() {
			var e = this;
			a("#esf_E01_46").on("focus", function(t) {
				e.showsuggest(t), e.suggest(t)
			}).on("keyup", function(t) {
				e.suggest(t)
			}).on("blur", function(t) {
				e.closesuggest(t)
			}).on("click", function(t) {
				e.suggest(t)
			})
		},
		showMenu: function(e) {
			var t = a("#" + e),
				s = a("#panel_" + e);
			0 !== t.length && 0 !== s.length && s.show()
		},
		closesuggest: function(e) {
			var t = e.target;
			t.value || (t.value = t.defaultValue, t.style.color = "#999")
		},
		showsuggest: function(e) {
			var t = a("#search_ad"),
				s = e.target;
			this.panel && (this.panel.style.display = "none"), s.value == s.defaultValue && (s.value = "", s.style.color = "#000"), "" == s.value && t.find("li").length && t.show()
		},
		suggest: function(t) {
			var i = a("#search_ad"),
				l = t.target,
				n = t.keyCode,
				r = this,
				c = r.createPanel();
			if (/^\s*$/.test(l.value) ? (c.style.display = "none", i.find("li").length && i.show()) : a("#search_ad").hide(), 13 == n) c.style.display = "none";
			else if (!n || 37 > n || n > 40) {
				this.suggest_selected = 0;
				var u = "get",
					d = {
						a: "getSearchTips",
						city: s,
						q: l.value
					},
					o = function(e) {
						var t, s = e.searchTips,
							i = a(c),
							l = '<li id="esf_D02_iii"><a href="hhh"  data-key="xxx" target="ttt" data-district="ddd" data-comarea="ccc" class="clearfix"><div>xxx<span>ddd</span><span>ccc</span></div>qqq</a></li>';
						if (s && s.length > 0) {
							var n = s,
								u = r.suggest_tip;
							i.html(u);
							for (var d = 14, o = 0, g = n.length; g > o; o++) {
								t = l.replace("iii", d++);
								var h = n[o],
									f = n[o].projname;
								if (f && "object" != typeof f) {
									t = t.replace(/xxx/g, f), t = h.houseurl ? h.allnum && h.allnum > 0 && (!h.type || "zhishi" !== h.type) ? t.replace("hhh", h.houseurl).replace("ttt", "_self") : t.replace("hhh", h.houseurl).replace("ttt", "_blank") : t.replace('href="hhh"', "").replace("ttt", ""), t = t.replace(/ccc/g, h.comerce || ""), t = t.replace(/ddd/g, h.district || "");
									var p = "";
									h.esfcount && h.esfcount > 0 && (p = "<span>qqq条房源</span>".replace("qqq", h.esfcount), "zhishi" === h.type && (p = p.replace("房源", ""))), t = t.replace("qqq", p);
									var v = a(t);
									v.on("click", function() {
										r.matchPanel(this)
									}), i.append(v)
								}
							}
							i.show()
						} else i.find("li").remove(), i.hide()
					},
					g = e("modules/SFUtil");
				g.ajax(this.suggest_url, u, d, o)
			} else if (c && "none" !== c.style.display) {
				var h = c.childNodes,
					f = h.length;
				if (38 == n || 40 == n) {
					38 == n && 2 < this.suggest_selected && this.suggest_selected--, 40 == n && this.suggest_selected < f && (this.suggest_selected += 0 == this.suggest_selected ? 2 : 1);
					for (var p = 1; f > p; p++) h[p].className = p == this.suggest_selected - 1 ? "suggest_selected" : "";
					var v = h[this.suggest_selected - 1].childNodes;
					v = (v[1] || v[0]).innerText, v = v.replace("-品牌直销", ""), a("#esf_E01_46").val(v)
				}
			}
		},
		matchPanel: function(s) {
			var i = this,
				l = a(s).find("a"),
				n = a("#esf_E01_46"),
				r = 16,
				c = e("modules/esf/SFMap"),
				u = l[1] || l[0],
				d = a(u),
				o = d.attr("href") || "";
			if (0 === d.length) return !1;
			if (n.val(d.attr("data-key")), a(i.panel).hide(), "" === o || o.indexOf("javascript") > -1) {
				var g = d.data("key"),
					h = d.data("district"),
					f = d.data("comarea");
				if (c.clearAllParams(), g === h || g === f ? (n.val(""), t.keyword = "", g === f ? (t.comarea = f, t.district = h) : g === h && (t.district = h, t.comarea = "", r = 14)) : (t.district = t.comarea = "", t.keyword = g), c.initParams(), 1 === arguments.length) {
					var p = "",
						v = "";
					if (c.config.leveledMenu.district && c.config.leveledMenu.district.data.length) for (var _ = c.config.leveledMenu.district.data, m = !1, y = 0, x = _.length; x > y && !m; y++) {
						var q = _[y],
							k = q.area;
						(q.name === t.district || q.name === t.comarea) && (p = q.x, v = q.y, m = !0);
						for (var w = 0, j = k.length; j > w; w++) {
							var P = k[w];
							(P.name === t.district || P.name === t.comarea) && (p = P.x, v = P.y, m = !0)
						}
					}
					c.searchResult(null, r, !1, p, v)
				}
			}
		}
	}
});