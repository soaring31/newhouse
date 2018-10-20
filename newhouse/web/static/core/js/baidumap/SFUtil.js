/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年8月1日
* To change this template use File | Settings | File Templates.
*/
define("modules/SFUtil", ["jquery", "rsa/RSA.min.js"], function(t) {
	"use strict";
	var e = t("jquery");
	t("rsa/RSA.min.js");
	var a = seajs.data.vars,
		o = (a.ajaxfilePath, {
			searchType: !1,
			setCookie: function(t, e, a) {
				var o = 30,
					n = "",
					i = new Date;
				if (a) {
					var r = window.location.host.split(".");
					a = r.length > 1 ? "." + r[r.length - 2] + "." + r[r.length - 1] : ".fang.com", n = ";path=/;domain=" + a
				}
				i.setTime(i.getTime() + 24 * o * 60 * 60 * 1e3), document.cookie = t + "=" + e + ";expires=" + i.toGMTString() + n
			},
			getCookie: function(t) {
				var e, a = new RegExp("(^| )" + t + "=([^;]*)(;|$)");
				return (e = document.cookie.match(a)) ? e[2] : null
			},
			delCookie: function(t) {
				var e = this,
					a = new Date;
				a.setTime(a.getTime() - 1);
				var o = e.getCookie(t);
				null != o && (document.cookie = t + "=" + o + ";expires=" + a.toGMTString())
			},
			containNode: function(t, e) {
				return e.closest(t).length > 0
			},
			ajaxUrl: window.location.href,
			ajax: function(t, e, a, o, n) {
				var i = function(t) {
						o(t)
					};
				if (!a.a) {
					var r = t.split("/").pop();
					a.a = r.split(".")[0]
				}
				var s = {
					url: this.ajaxUrl,
					type: e || "get",
					dataType: a.dataType || "json",
					data: a,
					success: i,
					error: n
				};
				return "ajaxSearch" == a.a && "fromSearchBtn" == this.searchType && (navigator.userAgent.indexOf("Firefox") <= 0 && (s.async = !1), this.searchType = !1), jQuery.ajax(s)
			},
			getStrlen: function(t) {
				for (var e = t.length, a = 0, o = 0; e > o; o++) t.charCodeAt(o) < 27 || t.charCodeAt(o) > 126 ? a += 3 : a++;
				return a
			},
			subStrcn: function(t, e) {
				for (var a = t.length, o = 0, n = 0, i = 0; a > i && (n = t.charCodeAt(i) < 27 || t.charCodeAt(i) > 126 ? 3 : 1, o += n, !(o > e)); i++);
				return t.substring(0, i)
			},
			getLoginStatus: function() {
				var t = this,
					o = a.username;
				t.loginBarNew || (t.loginBarNew = e("#loginBarNew"));
				var n = o,
					i = t.loginBarNew.find("a").eq(0),
					r = t.loginBarNew.find("a").eq(1),
					s = t.loginBarNew.children("div").get(2);
				t.loginStatus || (t.loginStatus = {
					href: i.attr("href")
				}), n ? (i.attr({
					target: "_blank"
				}).off("click").on("click", function() {
					return t.logout(), !1
				}).text("退出"), r.attr({
					href: "http://my.fang.com/?city=" + a.city,
					target: "_blank"
				}).off("click").text(n), s.className = "s4", s.onmousemove = function() {
					this.className = "s4 on2014"
				}, s.onmouseout = function() {
					this.className = "s4"
				}, s.getElementsByTagName("div")[0].style.cssText = "text-overflow: ellipsis;white-space: nowrap;") : (i.attr({
					href: t.loginStatus.href,
					target: "_self"
				}).off("click").text("注册"), r.attr({
					href: "javascript:;",
					target: "_blank"
				}).off("click").on("click", function() {
					return e("#loginbox,#layer").show(), !1
				}).text("登录"), s.className = "s4a", s.onmousemove = function() {
					this.className = "s4a on2014"
				}, s.onmouseout = function() {
					this.className = "s4a"
				})
			},
			login: function() {
				var t = this,
					o = "",
					n = "",
					i = e("#username"),
					r = e("#password"),
					s = e("#login_tip");
				if (0 != i.length && (o = i.val()), 0 != r.length && (n = r.val()), "" === o || "手机号/邮箱/用户名" === o) return void s.html("账号不能为空");
				if ("" === n || "请输入密码" === n) return void s.html("密码不能为空");
				var c = new RSAKeyPair("010001", "", "978C0A92D2173439707498F0944AA476B1B62595877DD6FA87F6E2AC6DCB3D0BF0B82857439C99B5091192BC134889DFF60C562EC54EFBA4FF2F9D55ADBCCEA4A2FBA80CB398ED501280A007C83AF30C3D1A142D6133C63012B90AB26AC60C898FB66EDC3192C3EC4FF66925A64003B72496099F4F09A9FB72A2CF9E4D770C41"),
					l = encryptedString(c, n),
					u = {
						Uid: o,
						Pwd: l,
						Service: "map",
						token: t.getCookie("token"),
						rsa: 0
					},
					g = "get",
					f = location.protocol + "//passport.fang.com/login.api";
				u.city = a.city;
				var h = function(o) {
						"Success" === o.Message ? (e("#loginbox").hide(), a.username = o.UserName, t.getLoginStatus(), r.val(""), s.html(""), e("#layer").hide()) : s.html(o.msg || "账号/密码错误")
					},
					m = function() {
						s.html("账号/密码错误")
					};
				e.ajax({
					type: g,
					url: f,
					dataType: "jsonp",
					jsonp: "callback",
					data: u,
					success: h,
					error: m
				})
			},
			logout: function() {
				var t = this,
					e = this.ajaxUrl,
					o = {
						c: "user",
						a: "logout",
						city: a.city
					},
					n = "get",
					i = function(e) {
						e ? (a.username = "", t.getLoginStatus(), t.setCookie("token", e.data, "root"), t.delCookie("sfut")) : alert("退出失败")
					},
					r = function() {
						alert("退出失败")
					};
				t.ajax(e, n, o, i, r)
			}
		});
	return o.setCookie("token", a.loginCookie, "root"), o
});
define("modules/esf/MapApi", ["bmap/BMap", "BMapLib", "bmap/MapWrapper"], function(a) {
	"use strict";

	function e(a) {
		this._markers = [], this._map = a
	}
	var t = seajs.data.vars,
		n = a("jquery"),
		i = new BMap.Point(t.cityx, t.cityy),
		o = t.localStorage,
		r = function() {
			for (var t = arguments, i = this, o = 116.404, r = 39.915, s = "mapObj", d = 12, l = {
				minZoom: 10,
				maxZoom: 18,
				enableMapClick: !1
			}, p = 0; p < t.length; p++) 0 === p ? s = t[0] : 1 === p ? r = t[1] : 2 === p ? o = t[2] : 3 === p ? d = t[3] : 4 === p && n.extend(l, t[4]);
			this.dragend = !0, this.isClick = 0, this.citycenter = new BMap.Point(o, r), this.zoomAdapt = 15, this.container = document.getElementById(s), this.ZindexsMin = -2e9, this.ZindexsMax = 2e9;
			var c = new BMap.Map(s, l);
			c.centerAndZoom(this.citycenter, d), c.enableScrollWheelZoom(), c.disableDoubleClickZoom(), c.disableInertialDragging(), l.NotAddNavCtrl ? c.addControl(new BMap.ScaleControl) : (c.addControl(new BMap.NavigationControl({
				anchor: BMAP_ANCHOR_BOTTOM_RIGHT
			})), c.addControl(new BMap.ScaleControl({
				anchor: BMAP_ANCHOR_BOTTOM_RIGHT
			}))), l.BindEvent && (c.addEventListener("dragstart", function() {
				var e = a("modules/esf/SFMap");
				i.dragend = !0, e.NotFirstDrag || (e.NotFirstDrag = !0, i.centerstart = i._map.pointToOverlayPixel(i.getCenter()), "station" !== e.searchType && (i.centerstartSta = i._map.pointToOverlayPixel(i.getCenter())))
			}), c.addEventListener("dragend", function() {
				try {
					var e = a("modules/esf/SFMap");
					if (!e.searchWhileMove || !i.dragend || "city" === e.searchType) return;
					var t = i._map.pointToOverlayPixel(i.getCenter()),
						o = Math.abs(t.x - i.centerstart.x),
						r = Math.abs(t.y - i.centerstart.y),
						s = Math.sqrt(o * o + r * r * 1.75),
						d = n(window).width(),
						l = Math.abs(t.x - i.centerstartSta.x),
						p = Math.abs(t.y - i.centerstartSta.y),
						c = Math.sqrt(l * l + p * p * 1.75);
					5 * s > d && setTimeout(function() {
						i.dragend = !1, 1.6 * c > d ? e.clearOtherOption("map") : e.clearOtherOption("map", "zoom"), e.searchResult(null, "", !0)
					}, 200)
				} catch (u) {}
			}), c.addEventListener("zoomstart", function() {
				i.zoomStart = !0
			}), c.addEventListener("zoomend", function() {
				var e = a("modules/esf/SFMap"),
					t = e.map._map.getZoom();
				14 > t && "city" === e.searchType || (i.zoomStart && (e.clearOtherOption("map", !0), e.searchResult(null, t, "zoom")), i.zoomStart = !1)
			})), this._map = c, this._markerManager = new e(this._map), this.first = !0
		};
	return r.prototype = {
		gethdBounds: function() {
			var a = this._map,
				e = a.getBounds(),
				t = e.getSouthWest(),
				n = e.getNorthEast(),
				i = a.pointToPixel(t),
				o = a.pixelToPoint(new BMap.Pixel(i.x, i.y)),
				r = a.pointToPixel(n),
				s = a.pixelToPoint(new BMap.Pixel(r.x, r.y));
			return {
				x1: o.lng,
				x2: s.lng,
				y1: o.lat,
				y2: s.lat
			}
		},
		subString: function(a, e) {
			var t = 0,
				n = "";
			if (!a) return "";
			for (var i = 0; i < a.length; i++) if (a.charCodeAt(i) > 128 ? t += 3 : t++, n += a.charAt(i), t >= e) return n;
			return n
		},
		checkNum: function(a) {
			return a % 2 == 0
		},
		drawMarkers: function(e, t, n, i, o, r, s) {
			var d, l = this,
				p = l._map,
				c = new BMap.Bounds;
			if (e) {
				var u, m = [],
					f = 0,
					h = !1,
					v = a("modules/esf/SFMap"),
					g = e.length;
				if (l.stationMarker = null, g > 0) {
					for (var y = 0; g > y; y++) {
						var _ = e[y];
						parseInt(_.y) && parseInt(_.x) && (u = new BMap.Point(_.x, _.y), v.params.subwaystation && _.station && v.params.subwaystation !== _.id || m.push(u), h || (h = u), c.extend(u), d = l.createMarker(_), l.addMarker(d))
					}
					"city" === v.searchType ? l.viewAuto = !1 : l.viewAuto = !0, v.params.subwaystation && !e[0].station ? l.subwayStatLPMarkers = m : v.params.subwaystation || (l.subwayStatLPMarkers = []), v.params.subwayline && v.busline.getBusList(v.subwaylinDisp), !v.isDrag && v.params.keyword && m.length ? p.setViewport(m) : !v.isDrag
				}
				return f
			}
		},
		stopPropagation: function(a) {
			window.event ? window.event.cancelBubble = !0 : a.stopPropagation()
		},
		stopDefault: function(a) {
			return a && a.preventDefault ? a.preventDefault() : window.event.returnValue = !1, !1
		},
		pageHtml: function(a, e, t, n) {
			for (var i = e; t >= i; i++) a += n == i ? '<a class="current" name=' + i + ">" + i + "</a> " : '<a href="javascript:void(0)" name=' + i + ">" + i + "</a>";
			return a
		},
		addMarker: function(a) {
			var e = this,
				t = e._map,
				n = e._markerManager;
			t.addOverlay(a), n._markers.push(a)
		},
		clearMarkers: function(a) {
			this._markerManager.clearMarkers(a)
		},
		clearOverlays: function() {},
		setCenter: function(a, e, t) {
			if (a && e) {
				var n = t || this._map.getZoom();
				this._map.centerAndZoom(new BMap.Point(e, a), n)
			}
		},
		getCenter: function() {
			return this._map.getCenter()
		},
		getZoom: function() {
			return this._map.getZoom()
		},
		panBy: function(a, e) {
			this._map.panBy(a, e)
		},
		panTo: function(a, e) {
			a && e && this._map.panTo(new BMap.Point(e, a))
		},
		createMarker: function(e) {
			var t, i = this,
				r = a("modules/esf/SFMap"),
				s = r.searchType,
				d = r.subwaySearchType,
				l = new BMap.Point(e.x, e.y),
				p = "",
				c = i.getZoom();
			if ("undefined" == typeof e.station && "line" !== s && (!r.params.subwaystation || c > 15 && r.params.subwaystation)) {
				if ("undefined" != typeof e.newcode) {
					e.domain = e.domain ? e.domain : "javascript:void(0)", e.price_num = e.price_num ? e.price_num : "", e.price_unit = e.price_unit ? e.price_unit : "", e.title = e.title ? e.title : "", e.title = e.title || e.projname, e.id = e.id ? e.id : "-1", e.tao = e.tao && e.tao > 0 ? " " + e.tao + "套   " : "", e.price_num || e.price_unit || e.tao || (e.price_num = e.title);
					var u = "",
						m = "",
						f = "",
						h = "";
					if (o && o.getItem("fangworld_esf_loupanMarkers")) {
						var v = JSON.parse(o.getItem("fangworld_esf_loupanMarkers")); - 1 !== n.inArray(e.newcode, v) && (u = 'style="background-color:#FEA09B;"', f = 'style="border-top: 6px solid #FEA09b;"', m = "#FEA09B", h = "6px solid #FEA09b")
					}
					p = '<div class="lpTip clearfix l_5015" id="esf_E01_34">', p += "<a " + u + 'href="' + e.domain + '" data-buildid="' + e.newcode + '" data-bgColor="' + m + '" data-bgTColor="' + h + '">' + (parseFloat(e.price_num, 10) ? e.price_num + e.price_unit : "") + e.tao, p += "<div " + f + '></div> <span class="dis">' + e.title + "</span></a></div>", t = new BMap.Size(-44, -22)
				} else if ("ecshop" === e.type) {
					var g = "",
						y = 'style="display:none"';
					e.id === r.ecshopId ? (g = ' on" data-on ', y = "", r.ecshopId = "") : g = '"';
					var _ = "";
					parseInt(e.housenum) && (_ = "<u>" + e.housenum + "套在售房源</u>"), p = '<div class="lpTip2" id="esf_E01_66" data-id="' + e.id + '">  <div class="mendian" ' + y + '>    <span class="shang">      <b>' + e.shopname + '</b>      <a id="esf_E01_65">去这里</a>    </span>    <p><u>电话 ' + e.hotline + "</u>" + _ + '</p>    <div class="jiantou"></div>  </div>  <a class="mendian ' + g + ">  <b></b>  </a></div>", p = n(p)[0], t = new BMap.Size(-104, -62)
				} else if ("city" === s || "dist" === s || "schoolcity" === s || "school" === s || d) {
					var w, b = "",
						M = "",
						k = "",
						x = "";
					if (w = e.projname || e.schoolname, e.schoolname && (x = 'alt="' + e.schoolname + '" title="' + e.schoolname + '"'), w.length > 4 && (w = w.substr(0, 4)), r.params.schoolid || c > 15 && r.params.schoolDist) t = new BMap.Size(-60, -10), p = ' <div class="lpTip" id="' + e.schoolid + '" data-title="' + e.schoolname + '" ><a class="xuequ"' + x + "><i></i><b>" + e.schoolname + "</b></a></div>";
					else {
						t = new BMap.Size(-42, -32), o && o.getItem("fangworld_esf_zoneMarkers") && (v = JSON.parse(o.getItem("fangworld_esf_zoneMarkers")), -1 !== n.inArray(e.projname, v) && (b = 'class="on"', M = "on")), e.price = e.price || e.minprice, e.price && !isNaN(e.price) && parseFloat(e.price) > 0 && (k = (e.price / 1e4).toFixed(1) + "万元/㎡");
						var C = e.tao + "套",
							B = "";
						"schoolcity" === s && (C = e.schoolStat + "所学校"), "school" === s && (B = 'data-schoolid="' + e.schoolid + '"', C = e.totalrelhousenum + "套");
						var T = r.params.district ? "esf_E01_33" : "esf_E01_32";
						p = '<ul id="' + T + '" class="lpNum"><li ' + b + '><a data-bgclass="' + M + '" data-zoneid="' + (e.projname || e.schoolid) + '" ' + B + x + ">" + w + "<br>" + k + "<br>" + C + "</a></li></ul>"
					}
				}
			} else if (("railwayother" === s || "railway" === s) && e.station) {
				var z = [];
				if (r.params.subwayline || r.params.subwaystation) {
					var P = "";
					r.params.subwaystation === e.id && (P = ' class="on" data-ison="1"'), e.tao = e.tao ? e.tao + "套" : "", z.push('<ul id="esf_E01_35" class="lpLine">'), z.push("<li" + P + '><a data-id="' + e.id + '">' + e.title), z.push("<span> " + e.tao + "</span></a>"), z.push('<div class="dot"></div><div class="sanjiao"><em></em></div>'), z.push("</ul>"), p = z.join("")
				}
				t = new BMap.Size(-64, 0)
			}
			if (p) {
				var S = new BMapLib.RichMarker(p, l, {
					anchor: t
				});
				S.provalue = e;
				var E = n(S.getContent());
				if ("undefined" != typeof e.newcode) S.addEventListener("click", function() {
					r.hideTipsAndSuggest();
					var a, t = "fangworld_esf_loupanMarkers",
						i = "",
						s = [];
					o && (i = o.getItem(t) || "", s = i ? JSON.parse(i) : []);
					var d = n.inArray(e.newcode, s);
					if (a = n("a[data-buildid=" + e.newcode + "]"), -1 === d && (s.push(e.newcode), o && o.setItem(t, '["' + s.join('","') + '"]'), a.attr("data-bgcolor", "#FEA09B"), a.attr("data-bgtcolor", "6px solid #FEA09b"), a.attr("data-selected", !0)), a) {
						var l = n("a[data-buildid] span:visible").parent(),
							p = l.attr("data-bgcolor"),
							c = l.attr("data-bgtcolor");
						l.css("backgroundColor", p || "#f14646"), l.find("div").css("borderTop", c || "6px solid #f14646"), l.find("span").hide(), l.attr("data-selected", !1), a.css("backgroundColor", "#199752"), a.find("div").css("borderTop", "6px solid #199752"), a.find("span").show(), a.attr("data-selected", !0)
					}
					r.searchType = "fangyuan";
					var u = e.tao.replace(/[^\d]*/g, "");
					u > 0 ? r.searchResult(null, 16, !1, null, null, {
						newCode: e.newcode,
						houseNum: u
					}) : window.open(e.houseurl)
				}), S.addEventListener("mouseover", function() {
					var a = n(S._container),
						e = a.find("a[data-buildid]").data("buildid"),
						t = n("#mapCanvas a[data-buildid=" + e + "]");
					if (t.css("backgroundColor", "#199752"), t.find("div").css("borderTop", "6px solid #199752"), t.find("span").show(), (r.params.subwaystation || r.params.schoolid) && i.stationMarker) {
						var o = new BMap.Point(i.stationMarker.getPosition().lng, i.stationMarker.getPosition().lat),
							s = new BMap.Point(S.getPosition().lng, S.getPosition().lat),
							d = new BMap.Polyline([o, s], {
								strokeColor: "#ff8000",
								strokeWeight: 3,
								strokeOpacity: .5
							});
						i.addOverlay(d), i.stationMarkerLine = d;
						var l = a.find(".dis");
						if (!l.attr("hasAdd")) {
							var p = n(i.stationMarker.getContent()),
								c = i._map.getDistance(o, s);
							l.attr("hasAdd", !0), l.text(l.text() + " 距" + (p.find("a").text().split(" ")[0] || p.attr("data-title")) + (c / 1e3).toFixed(1) + "公里")
						}
					}
				}), S.addEventListener("mouseout", function() {
					var a = n(S._container),
						e = a.find("a[data-buildid]"),
						t = e.data("buildid"),
						o = e.attr("data-bgcolor"),
						r = e.attr("data-bgtcolor"),
						s = n("#mapCanvas a[data-buildid=" + t + "]");
					"true" !== s.attr("data-selected") && (s.css("backgroundColor", o || "#f14646"), s.find("div").css("borderTop", r || "6px solid #f14646"), s.find("span").hide()), i.stationMarkerLine && i._map.removeOverlay(i.stationMarkerLine)
				});
				else if ("city" !== s && "dist" !== s && ("schoolcity" !== s && "school" !== s || r.params.schoolid) && !d || !E.find("a[data-zoneid]").length)"undefined" != typeof e.housetype && "huxing" === e.housetype || "undefined" != typeof e.schoolid && (r.params.schoolid && r.params.schoolid === E.attr("id") && (i.stationMarker = S), S.addEventListener("click", function() {
					r.gotoDistrict(null, null, e.x, e.y, e.schoolid)
				}));
				else {
					var L, A, O = "fangworld_esf_zoneMarkers";
					o && (L = o.getItem(O), A = L ? JSON.parse(L) : []), S.addEventListener("click", function() {
						r.hideTipsAndSuggest();
						var a = n.inArray(e.projname, A);
						if (-1 === a) {
							A.push(e.projname), o && o.setItem(O, '["' + A.join('","') + '"]');
							var t = n("a[data-buildid=" + e.projname + "]");
							t.attr("data-bgclass", "on")
						}
						r.gotoDistrict(e.id, e.projname, e.x, e.y, e.schoolid, e.schoolname)
					}), S.addEventListener("mouseover", function() {
						var a = n(S._container),
							e = a.find("a[data-zoneid]").data("zoneid"),
							t = n("#mapCanvas a[data-zoneid=" + e + "]");
						t.parent().attr("class", "")
					}), S.addEventListener("mouseout", function() {
						var a = n(S._container),
							e = a.find("a[data-zoneid]"),
							t = e.data("zoneid"),
							i = e.data("bgclass"),
							o = n("#mapCanvas a[data-zoneid=" + t + "]");
						o.parent().attr("class", i || "")
					})
				}
				if ("undefined" != typeof e.station) r.params.subwaystation && r.params.subwaystation === E.find("a[data-id]").attr("data-id") && (i.stationMarker = S), S.addEventListener("click", function() {
					r.hideTipsAndSuggest(), r.clearOtherOption("subway");
					var a = E.find("li a"),
						e = n(S._container),
						t = e.find(".lpLine .on[data-ison]");
					if (!t.length) {
						var i = a.text(),
							o = a.attr("data-id"),
							s = i.substr(0, i.indexOf(" "));
						n("#subwayParamContent").text(s), r.subwaystationDisp = s, r.params.subwaystation = o, r.isClickStation = "16", r.searchResult(null, 16, !1, S.getPosition().lng, S.getPosition().lat)
					}
				});
				else if ("ecshop" === e.type) {
					var j = n(S.getContent());
					S.addEventListener("mouseover", function(a) {
						var e = n(S._container);
						e.find("div.mendian").show()
					}), S.addEventListener("mouseout", function() {
						var a = n(S._container);
						a.find("a[data-on]").length || a.find("div.mendian").hide()
					});
					var I = j.find(".shang a");
					I.length && I.on("click", function(a) {
						console.log("我想去的地方,在空间里面已经无迹,只能溯着时间去找."), window.open("http://api.map.baidu.com/marker?location=" + e.y + "," + e.x + "&coord_type=bd09ll&src=fang&title=" + e.shopname + "&content=" + e.shopname + "&output=html"), i.stopPropagation(a)
					}), S.addEventListener("click", function() {
						r.hideTipsAndSuggest(), j.find("a.mendian").hasClass("on") || (r.ecshopId = j.attr("data-id") || "", r.params.ecshop = "ecshophouse", r.params.newCode = "", r.searchResult(null, 16, !1, S.getPosition().lng, S.getPosition().lat))
					})
				}
				return i.setTop(S), S
			}
		},
		getDrivingLine: function(a, e, t, n, i) {
			var o = new BMap.Point(e, t),
				r = new BMap.Point(n, i);
			a.search(o, r)
		},
		setTop: function(a) {
			var e = this;
			a && a.addEventListener && (a.addEventListener("mouseover", function() {
				var t = n(a._container);
				t.css("zIndex", e.ZindexsMax++)
			}), a.addEventListener("mouseout", function() {
				var t = n(a._container),
					i = t.find("a[data-buildid=" + a.provalue.newcode + "]");
				i.length && "true" !== i.attr("data-selected") && t.css("zIndex", e.ZindexsMin--)
			}))
		},
		hideMarker: function(a) {
			var e = "tip" + a,
				t = n("#" + e);
			if (t.length > 0) {
				var i = t.parent();
				i.hide()
			}
		},
		hoverMarker: function(a, e, i) {
			var o = this;
			if ("undefined" != typeof a.provalue.newCode) {
				var r = n("#tip" + a.provalue.newCode);
				if (0 !== r.length) if (e) {
					if ("mapFinddingCanvasLabelStyle8" != r.attr("class")) {
						r.attr("class", "lpxxx tf");
						var s = n("#map_canvas").width(),
							d = "alert_tc",
							l = "4",
							p = "",
							c = o.pointToPixel(i),
							u = c.x,
							m = c.y;
						210 > u && 136 > m ? (d = "alert_tc alert_tc2", l = 2, p = "sanjiao2") : 136 > m ? (d = "alert_tc alert_tc3", l = 1, p = "sanjiao3") : 210 > s - u ? (d = "alert_tc alert_tc1", l = 3, p = "sanjiao1") : (d = "alert_tc", l = 4, p = "");
						var f = t.imgUrl + "img/jt_" + l + ".png",
							h = n("#tip_price_" + a.provalue.newCode),
							v = n("#tip_sanjiao_" + a.provalue.newCode);
						h.attr("class", d), v.attr("src", f), v.parent().attr("class", "sanjiao " + p), h.show()
					}
					r.parent().css("zIndex", 2)
				} else if (o.isClick !== a.provalue.newCode && ("undefined" != typeof a.provalue.zindex && r.parent().css("zIndex", a.provalue.zindex), "mapFinddingCanvasLabelStyle8" !== r.attr("class"))) {
					var g = "lpxx tf";
					r.attr("class", g), n("#tip_price_" + a.provalue.newCode).hide()
				}
			}
		},
		closeTip: function() {
			var e = this,
				t = a("modules/esf/SFMap"),
				i = n("#maptip"),
				o = e.isClick;
			e.isClick = 0, i.hide(), i.html(""), o && t.markerList[o] && t.markerList[o].onCloseTip()
		},
		panMap: function(a, e, t) {
			var n = this,
				o = n._map,
				r = o.getViewport(t),
				s = a.getCenter();
			r.zoom > n.zoomAdapt && (r.zoom = n.zoomAdapt), r.zoom < 10 && i ? o.centerAndZoom(i, 10) : o.centerAndZoom(s, r.zoom)
		},
		panMap1: function(a, e, t) {
			var n = this,
				i = n._map,
				o = i.getViewport(t),
				r = a.getCenter();
			o.zoom -= 1, o.zoom > n.zoomAdapt && (o.zoom = n.zoomAdapt), o.zoom < 10 ? i.centerAndZoom(r, 10) : i.centerAndZoom(r, o.zoom)
		},
		addKeyMarker: function(a) {
			var e = this,
				t = e._map;
			if (a.district && a.y && a.x) {
				var i = '<div class="mapFinddingCanvasLabelStyle11" id="divkeymarker"><table cellpadding=0 cellspacing=0 border=0><tr>';
				i += '<td class="s1" >&nbsp;</td><td class="s2" id="tip1010133427"><img src="' + imgPath + 'baidu_n/img/icon004.gif" alt="" />' + a.district + '</td><td class="s3">&nbsp;</td><td class="s4"></td></tr><tr><td colspan="3" class="s5"></td></tr></table></div>';
				var o = new BMapLib.RichMarker(i, new BMap.Point(a.x, a.y), {
					anchor: new BMap.Size(0, -40)
				});
				t.addOverlay(o), n("#divkeymarker").parent().css("zIndex", 0)
			}
		},
		pointToPixel: function(a) {
			return this._map.pointToPixel(a)
		},
		setZoom: function(a) {
			var e = parseInt(a);
			this._map.setZoom(e)
		},
		addOverlay: function(a) {
			this._map.addOverlay(a)
		},
		removeOverlay: function(a) {
			this._map.removeOverlay(a)
		},
		openDis: function() {
			var a = this;
			"undefined" == typeof a._disTool && (a._disTool = new BMapLib.DistanceTool(a._map), a._disTool.addEventListener("drawend", function() {
				a._disTool.close()
			})), a._disTool.open()
		},
		convertPoint: function(a) {
			var e = [];
			if (n.isArray(a) && a.length > 0) {
				for (var t = 0; t < a.length; t++) {
					var i = new BMap.Marker(new BMap.Point(a[t].x, a[t].y));
					a[t].x = i.point.lng, a[t].y = i.point.lat, e.push(a[t])
				}
				this.drawMarkers(e)
			}
		},
		addEvent: function(a, e, t) {
			a.addEventListener ? a.addEventListener(e, t, !1) : a.attachEvent && (a["e" + e + t] = t, a.attachEvent("on" + e, function() {
				a["e" + e + t]()
			}))
		}
	}, e.prototype.addMarker = function(a) {
		this._markers.push(a), this._map.addOverlay(a)
	}, e.prototype.clearMarkers = function(e) {
		var t = this;
		for (var n in t._markers) if (t._markers.hasOwnProperty(n)) {
			var i = t._markers[n];
			if (!i) continue;
			if (e && i.provalue.station) continue;
			t._map.removeOverlay(i), delete t._markers[n]
		}
		var o = a("modules/esf/SFMap");
		if (!e && o.subwayMarkers) for (; o.subwayMarkers.length > 0;) t._map.removeOverlay(o.subwayMarkers.shift())
	}, r
});
define("lazyload/lazyload", ["jquery"], function(e, t, o) {
	var i = e("jquery"),
		n = i(window),
		r = window.document;
	i.fn.lazyload = function(e) {
		function t() {
			var e = 0;
			f.each(function() {
				var t = i(this);
				if (!l.skip_invisible || t.is(":visible")) if (i.abovethetop(this, l) || i.leftofbegin(this, l));
				else if (i.belowthefold(this, l) || i.rightoffold(this, l)) {
					if (++e > l.failure_limit) return !1
				} else t.trigger("appear"), e = 0
			})
		}
		var o, f = this,
			l = {
				threshold: 0,
				failure_limit: 0,
				event: "scroll",
				effect: "show",
				container: "#scrollbar1",
				data_attribute: "original",
				skip_invisible: !0,
				appear: null,
				load: null,
				placeholder: "//js.soufunimg.com/common_m/m_public/images/loadingpic.jpg"
			};
		return e && (void 0 !== e.failurelimit && (e.failure_limit = e.failurelimit, delete e.failurelimit), void 0 !== e.effectspeed && (e.effect_speed = e.effectspeed, delete e.effectspeed), i.extend(l, e)), o = void 0 === l.container || l.container === window ? n : i(l.container), 0 === l.event.indexOf("scroll") && o.bind(l.event, function() {
			return t()
		}), this.each(function() {
			var e = this,
				t = i(e);
			e.loaded = !1, (void 0 === t.attr("src") || t.attr("src") === !1) && t.is("img") && t.attr("src", l.placeholder), t.one("appear", function() {
				if (!this.loaded) {
					if (l.appear) {
						var o = f.length;
						l.appear.call(e, o, l)
					}
					i("<img />").bind("load", function() {
						var o = t.attr("data-" + l.data_attribute);
						t.hide(), t.is("img") ? t.attr("src", o) : t.css("background-image", "url('" + o + "')"), t[l.effect](l.effect_speed), e.loaded = !0;
						var n = i.grep(f, function(e) {
							return !e.loaded
						});
						if (f = i(n), l.load) {
							var r = f.length;
							l.load.call(e, r, l)
						}
					}).attr("src", t.attr("data-" + l.data_attribute))
				}
			}), 0 !== l.event.indexOf("scroll") && t.bind(l.event, function() {
				e.loaded || t.trigger("appear")
			})
		}), n.bind("resize", function() {
			t()
		}), /(?:iphone|ipod|ipad).*os 5/gi.test(navigator.appVersion) && n.bind("pageshow", function(e) {
			e.originalEvent && e.originalEvent.persisted && f.each(function() {
				i(this).trigger("appear")
			})
		}), i(r).ready(function() {
			t()
		}), this
	}, i.belowthefold = function(e, t) {
		var o;
		return o = void 0 === t.container || t.container === window ? (window.innerHeight ? window.innerHeight : n.height()) + n.scrollTop() : i(t.container).offset().top + i(t.container).height(), o <= i(e).offset().top - t.threshold
	}, i.rightoffold = function(e, t) {
		var o;
		return o = void 0 === t.container || t.container === window ? n.width() + n.scrollLeft() : i(t.container).offset().left + i(t.container).width(), o <= i(e).offset().left - t.threshold
	}, i.abovethetop = function(e, t) {
		var o;
		return o = void 0 === t.container || t.container === window ? n.scrollTop() : i(t.container).offset().top, o >= i(e).offset().top + t.threshold + i(e).height()
	}, i.leftofbegin = function(e, t) {
		var o;
		return o = void 0 === t.container || t.container === window ? n.scrollLeft() : i(t.container).offset().left, o >= i(e).offset().left + t.threshold + i(e).width()
	}, i.inviewport = function(e, t) {
		return !(i.rightoffold(e, t) || i.leftofbegin(e, t) || i.belowthefold(e, t) || i.abovethetop(e, t))
	}, i.extend(i.expr[":"], {
		"below-the-fold": function(e) {
			return i.belowthefold(e, {
				threshold: 0
			})
		},
		"above-the-top": function(e) {
			return !i.belowthefold(e, {
				threshold: 0
			})
		},
		"right-of-screen": function(e) {
			return i.rightoffold(e, {
				threshold: 0
			})
		},
		"left-of-screen": function(e) {
			return !i.rightoffold(e, {
				threshold: 0
			})
		},
		"in-viewport": function(e) {
			return i.inviewport(e, {
				threshold: 0
			})
		},
		"above-the-fold": function(e) {
			return !i.belowthefold(e, {
				threshold: 0
			})
		},
		"right-of-fold": function(e) {
			return i.rightoffold(e, {
				threshold: 0
			})
		},
		"left-of-fold": function(e) {
			return !i.rightoffold(e, {
				threshold: 0
			})
		}
	}), o.exports = i
});
!
function(e) {
	function s(s) {
		var t = [].slice.call(arguments, 1),
			o = 0;
		return s = e.event.fix(s || window.event), s.type = "mousewheel", s.originalEvent.wheelDelta && (o = s.originalEvent.wheelDelta / 120), s.originalEvent.detail && (o = -s.originalEvent.detail / 3), t.unshift(s, o), e.event.dispatch.apply(this, t)
	}
	var t = ["DOMMouseScroll", "mousewheel"];
	e.event.special.mousewheel = {
		setup: function() {
			if (this.addEventListener) for (var e = t.length; e;) this.addEventListener(t[--e], s, !1);
			else this.onmousewheel = s
		},
		teardown: function() {
			if (this.removeEventListener) for (var e = t.length; e;) this.removeEventListener(t[--e], s, !1);
			else this.onmousewheel = null
		}
	}, e.fn.extend({
		mousewheel: function(e) {
			return e ? this.bind("mousewheel", e) : this.trigger("mousewheel")
		},
		unmousewheel: function(e) {
			return this.unbind("mousewheel", e)
		}
	})
}(jQuery), function(e, s, t) {
	function o() {
		r = s[i](function() {
			n.each(function() {
				var s = e(this),
					t = s.width(),
					o = s.height(),
					r = e.data(this, u);
				(t !== r.w || o !== r.h) && s.trigger(c, [r.w = t, r.h = o])
			}), o()
		}, a[l])
	}
	var r, n = e([]),
		a = e.resize = e.extend(e.resize, {}),
		i = "setTimeout",
		c = "resize",
		u = c + "-special-event",
		l = "delay",
		d = "throttleWindow";
	a[l] = 250, a[d] = !0, e.event.special[c] = {
		setup: function() {
			if (!a[d] && this[i]) return !1;
			var s = e(this);
			n = n.add(s), e.data(this, u, {
				w: s.width(),
				h: s.height()
			}), 1 === n.length && o()
		},
		teardown: function() {
			if (!a[d] && this[i]) return !1;
			var s = e(this);
			n = n.not(s), s.removeData(u), n.length || clearTimeout(r)
		},
		add: function(s) {
			function o(s, o, n) {
				var a = e(this),
					i = e.data(this, u);
				i.w = o !== t ? o : a.width(), i.h = n !== t ? n : a.height(), r.apply(this, arguments)
			}
			if (!a[d] && this[i]) return !1;
			var r;
			return e.isFunction(s) ? (r = s, o) : (r = s.handler, void(s.handler = o))
		}
	}
}(jQuery, this), function(e) {
	$.fn.scrollbar = function(s) {
		var t, o, r, n, a, i = "scrollbar",
			c = 300,
			u = "auto",
			l = 7,
			d = !1,
			f = !0,
			v = !1,
			h = !0,
			g = !1,
			m = !0,
			p = !1,
			b = !0,
			w = !1,
			C = !0,
			T = .4,
			k = 10,
			z = !0,
			B = !0,
			E = 10,
			I = "auto",
			S = "auto",
			A = 0,
			D = 5,
			y = !0,
			W = "vertical",
			O = 13,
			V = "#111111",
			_ = "#a1dc13",
			X = "#E6E6E6",
			Y = "#CCCCCC",
			x = !1,
			L = "middle",
			H = "middle",
			M = 20,
			F = 20;
		s.type != e && (i = s.type), s.height != e && (c = s.height), s.width != e && (u = s.width), s.scrollerEase != e && (l = s.scrollerEase), s.downBtn != e && (d = !0, t = s.downBtn), s.upBtn != e && (v = !0, o = s.upBtn), s.topBtn != e && (g = !0, r = s.topBtn), s.rightBtn != e && (w = !0, a = s.rightBtn), s.leftBtn != e && (p = !0, n = s.leftBtn), s.dragVertical != e && (z = s.dragVertical), s.dragHorizontal != e && (B = s.dragHorizontal), s.buttonsDisabledAlpha != e && (T = s.buttonsDisabledAlpha), s.buttonsScrollSpeed != e && (k = s.buttonsScrollSpeed), s.barWidth != e && (E = s.barWidth), s.draggerVerticalSize != e && (I = s.draggerVerticalSize), s.roundCorners != e && (A = s.roundCorners), s.distanceFromBar != e && (D = s.distanceFromBar), s.mouseWheel != e && (y = s.mouseWheel), s.mouseWheelOrientation != e && (W = s.mouseWheelOrientation), s.mouseWheelSpeed != e && (O = s.mouseWheelSpeed), s.draggerColor != e && (V = s.draggerColor), s.draggerOverColor != e && (_ = s.draggerOverColor), s.barColor != e && (X = s.barColor), s.barOverColor != e && (Y = s.barOverColor), s.lockToPosition != e && (x = s.lockToPosition), s.lockToAlignVertical != e && (L = s.lockToAlignVertical), s.lockToAlignHorizontal != e && (H = s.lockToAlignHorizontal), s.topAndBottomSpace != e && (M = s.topAndBottomSpace), s.leftAndRightSpace != e && (F = s.leftAndRightSpace), $(this).each(function() {
			var e = "grab",
				s = "mousemove",
				O = "horizontal",
				P = "left",
				j = ".scrollbar_dragger.horizontal",
				Q = "border-radius",
				R = "-khtml-border-radius",
				q = "-webkit-border-radius",
				G = "-moz-border-radius",
				J = "background-color",
				K = "top",
				N = "px",
				U = ".content1",
				Z = "width",
				ee = "px",
				se = "height",
				te = c,
				oe = $(this);
			oe.css(se, te + ee);
			var re = oe.width() - 2,
				ne = $(".scrollbar", oe);
			ne.css(Z, re), ne.css(se, te);
			var ae = $(U, oe),
				ie = 0,
				ce = 0,
				ue = 0,
				le = 0,
				de = ae.height(),
				fe = ae.width();
			"auto" != u && (ae.css(Z, u + N), fe = u);
			var ve, he, ge = !1,
				me = !1,
				pe = !1,
				be = 30,
				we = function() {
					var e = "opacity";
					z && (te > de ? (Te.css(e, 0), ze.css(e, 0)) : (Ce = "auto" == I ? te / (de / te) : I, Te.css(se, Ce + ee), Te.css(e, 1), ze.css(e, 1))), B && (re > fe ? (Ie.css(e, 0), Ae.css(e, 0)) : (Be = "auto" == S ? re / (fe / re) : S, Ie.css(Z, Be + ee), Ie.css(e, 1), Ae.css(e, 1)))
				};
			if ("scrollbar" == i && z) {
				oe.append('<div class="scrollbar_dragger vertical"><div class="back"></div><div class="dragger"></div></div>'), ne.css("margin-right", E + ee), re -= E + D, ne.css(Z, re + N), B || ae.css(Z, re + N), $(".scrollbar_dragger.vertical", oe).css(Z, E + N);
				var Ce, $e = te,
					Te = $(".scrollbar_dragger.vertical .dragger", oe),
					ke = 0;
				Te.css(K, 0 + N), Te.css(Z, E + ee), Te.css(J, V), A > 0 && (Te.css(G, A + ee), Te.css(q, A + ee), Te.css(R, A + ee), Te.css(Q, A + ee));
				var ze = $(".scrollbar_dragger.vertical .back");
				ze.css(Z, E + ee), ze.css(se, te + ee), ze.css(J, X), A > 0 && (ze.css(G, A + ee), ze.css(q, A + ee), ze.css(R, A + ee), ze.css(Q, A + ee)), B || we()
			}
			if ("scrollbar" == i && B) {
				ne.css("margin-bottom", E + ee), te -= E + D, z && ze.css(se, te + ee), ne.css(se, te + N), z || ae.css(se, te + N), $(j, oe).css(se, E + N), $(j, oe).css(Z, re + E + D + N), $(j, oe).css(K, te + D + N);
				var Be, Ee = re,
					Ie = $(".scrollbar_dragger.horizontal .dragger", oe),
					Se = 0;
				Ie.css(se, E + ee), Ie.css(Z, re + ee), Ie.css(J, V), Ie.css(P, 0 + N), A > 0 && (Ie.css(G, A + ee), Ie.css(q, A + ee), Ie.css(R, A + ee), Ie.css(Q, A + ee));
				var Ae = $(".scrollbar_dragger.horizontal .back");
				Ae.css(se, E + ee), Ae.css(Z, re + ee), Ae.css(J, X), A > 0 && (Ae.css(G, A + ee), Ae.css(q, A + ee), Ae.css(R, A + ee), Ae.css(Q, A + ee)), we()
			}
			$(U, oe).resize(function() {
				ae = $(U, oe), de = ae.height(), fe = ae.width(), "auto" != u && (ae.css(Z, u + N), fe = u), "scrollbar" == i && we()
			});
			var De = function() {
					var e = "disabled";
					if (g && (ce >= 0 && m ? (m = !1, r.fadeTo(300, T), r.addClass(e)) : 0 > ce && !m && (m = !0, r.fadeTo(300, 1), r.removeClass(e))), v && (ce >= 0 && h ? (h = !1, o.fadeTo(300, T), o.addClass(e)) : 0 > ce && !h && (h = !0, o.fadeTo(300, 1), o.removeClass(e))), d && (-(de - te) >= ce && f ? (f = !1, t.fadeTo(300, T), t.addClass(e)) : ce > -(de - te) && !f && (f = !0, t.fadeTo(300, 1), t.removeClass(e))), p && (le >= 0 && b ? (b = !1, n.fadeTo(300, T), n.addClass(e)) : 0 > le && !b && (b = !0, n.fadeTo(300, 1), n.removeClass(e))), w && (-(fe - re) >= le && C ? (C = !1, a.fadeTo(300, T), a.addClass(e)) : le > -(fe - re) && !C && (C = !0, a.fadeTo(300, 1), a.removeClass(e))), z) {
						var s = Math.round((ce - ie) / l);
						ie += s, ae.css("top", ie + N), oe.trigger($.Event("scroll"))
					}
					if (B) {
						var s = Math.round((le - ue) / l);
						ue += s, ae.css("left", ue + N)
					}
					ve = setTimeout(De, be)
				};
			if (ve = setTimeout(De, be), "scrollbar" == i) {
				var ye = function(e) {
						if ("vertical" == e) {
							0 > ke && (ke = 0), ke > $e - Ce && (ke = $e - Ce);
							var s = ke / ($e - Ce);
							ce = -(de - te) * s, Te.css(K, ke + N)
						} else if (e == O) {
							0 > Se && (Se = 0), Se > Ee - Be && (Se = Ee - Be);
							var s = Se / (Ee - Be);
							le = -(fe - re) * s, Ie.css(P, Se + N)
						}
					},
					We = function(e) {
						var s, t, o = "vertical";
						me ? (s = e.pageY, t = s - he, ke = positionIni + t) : (s = e.pageX, o = O, t = s - he, Se = positionIni + t), ye(o)
					};
				z && (Te.mouseover(function() {
					Te.css(J, _)
				}), Te.mouseout(function() {
					me || Te.css(J, V)
				}), ze.mouseover(function() {
					ze.css(J, Y)
				}), ze.mouseout(function() {
					ze.css(J, X)
				}), Te.mousedown(function(e) {
					return de > te && (he = e.pageY, positionIni = parseInt(Te.css(K), 10), me = !0, $(document).bind(s, We), $(document).mouseup(function() {
						$(document).unbind(s), me = !1, Te.css(J, V)
					})), !1
				}), ze.click(function(e) {
					var s = ze.offset();
					return ke = e.pageY - s.top, ye("vertical"), !1
				})), B && (Ie.mouseover(function() {
					Ie.css(J, _)
				}), Ie.mouseout(function() {
					ge || Ie.css(J, V)
				}), Ae.mouseover(function() {
					Ae.css(J, Y)
				}), Ae.mouseout(function() {
					Ae.css(J, X)
				}), Ie.mousedown(function(e) {
					return he = e.pageX, positionIni = parseInt(Ie.css(P), 10), pe = !0, $(document).bind(s, We), $(document).mouseup(function() {
						$(document).unbind(s), pe = !1, Ie.css(J, V)
					}), !1
				}), Ae.click(function(e) {
					var s = Ae.offset();
					return Se = e.pageX - s.left, ye(O), !1
				})), y && oe.mousewheel(function(e, s) {
					return "vertical" == W ? de > te && (ke -= 13 * s, ye("vertical")) : (Se -= 13 * s, ye(O)), !1
				})
			}
			if ("mousePosition" == i) {
				z || ae.css(se, te + N), B || ae.css(Z, re + N);
				var Oe = ne.offset();
				ne.mousemove(function(e) {
					if (Oe = ne.offset(), de > te) {
						if (z) {
							var s = de + 2 * M - te,
								t = (e.pageY - Oe.top) / te,
								o = M - s * t;
							ce = o
						}
						if (B) {
							var s = fe + 2 * F - re,
								t = (e.pageX - Oe.left) / re,
								o = F - s * t;
							le = o
						}
					}
				}), x && oe.mouseout(function() {
					if (z) {
						var e = de + 2 * M - te;
						ce = "middle" == L ? M - .5 * e : "bottom" == L ? M - e : 0
					}
					if (B) {
						var e = fe + 2 * F - re;
						le = "middle" == H ? F - .5 * e : "right" == H ? F - e : 0
					}
				})
			}
			if (/msie/.test(navigator.userAgent.toLowerCase()) ? ae.get(0).onselectstart = function() {
				return !1
			} : ae.get(0).onmousedown = function(e) {
				e.preventDefault()
			}, "dragAndDrop" == i) {
				z || ae.css(se, te + N), B || ae.css(Z, re + N);
				var Ve, _e, Xe, Ye, xe = 0,
					Le = 0,
					He = 0,
					Me = 0,
					We = function(e) {
						if (z) {
							var s = e.pageY,
								t = s - Xe;
							ce = Ve + t, He = t - xe, xe = t
						}
						if (B) {
							var o = e.pageX,
								r = o - Ye;
							le = _e + r, Me = r - Le, Le = r
						}
					};
				ne.addClass(e), oe.mousemove(function(e) {
					e.preventDefault()
				}), ne.mousedown(function(t) {
					var o = "grabbing";
					return ne.removeClass(e), ne.addClass(o), t.preventDefault ? t.preventDefault() : t.returnValue = !1, z && (Xe = t.pageY, ce = ie, Ve = ce), B && (Ye = t.pageX, le = ue, _e = le), ge = !0, $(document).bind(s, We), $(document).mouseup(function(t) {
						if (ne.addClass(e), ne.removeClass(o), $(document).unbind(s, We), ge = !1, z) {
							var r = de - te;
							ce += 30 * He, ce > 0 ? ce = 0 : -r > ce && (ce = -r), He = 0
						}
						if (B) {
							var n = fe - re;
							le += 30 * Me, le > 0 ? le = 0 : -n > le && (le = -n), Me = 0
						}
						return !1
					}), !1
				})
			}
			if (g && r.click(function() {
				return ce = 0, !1
			}), d) {
				var Fe;
				t.mousedown(function() {
					var e = function() {
							ce -= k, -(de - te) > ce && (ce = -(de - te))
						};
					return Fe = setInterval(e, 30), $(document).mouseup(function() {
						clearInterval(Fe)
					}), !1
				})
			}
			if (v) {
				var Fe;
				o.mousedown(function() {
					var e = function() {
							ce += k, ce > 0 && (ce = 0)
						};
					return Fe = setInterval(e, be), $(document).mouseup(function() {
						clearInterval(Fe)
					}), !1
				})
			}
			if (p) {
				var Fe;
				n.mousedown(function() {
					var e = function() {
							le += k, le > 0 && (le = 0)
						};
					return Fe = setInterval(e, be), $(document).mouseup(function() {
						clearInterval(Fe)
					}), !1
				})
			}
			if (w) {
				var Fe;
				a.mousedown(function() {
					var e = function() {
							le -= k, -(fe - re) > le && (le = -(fe - re))
						};
					return Fe = setInterval(e, be), $(document).mouseup(function() {
						clearInterval(Fe)
					}), !1
				})
			}
		})
	}
}();