/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms create date 2016年8月1日 To change this template use File |
 *          Settings | File Templates.
 */
window.BMAP_AUTHENTIC_KEY = "3a345e5b8b56a7f80605cd95d64634a8";
(function() {
	var aa = void 0,
		g = !0,
		k = null,
		l = !1;

	function n() {
		return function() {}
	}
	function ba(a) {
		return function(b) {
			this[a] = b
		}
	}
	function o(a) {
		return function() {
			return this[a]
		}
	}
	function ca(a) {
		return function() {
			return a
		}
	}
	var da = document,
		p = Math,
		ea = RegExp,
		t = parseInt,
		fa = parseFloat,
		u = "prototype",
		v = "appendChild",
		ga = "removeChild",
		w = "length",
		x = "extend",
		y = "width",
		A = "height",
		ia = "offsetX",
		ja = "offsetY",
		B = "addEventListener",
		ka = "parentNode",
		la = "position";
	var ma, C = ma = C || {
		version: "1.3.4"
	};
	C.K = "$BAIDU$";
	window[C.K] = window[C.K] || {};
	C.object = C.object || {};
	C[x] = C.object[x] = function(a, b) {
		for (var c in b) b.hasOwnProperty(c) && (a[c] = b[c]);
		return a
	};
	C.w = C.w || {};
	C.w.V = function(a) {
		return "string" == typeof a || a instanceof String ? da.getElementById(a) : a && a.nodeName && (1 == a.nodeType || 9 == a.nodeType) ? a : k
	};
	C.V = C.Kb = C.w.V;
	C.w.H = function(a) {
		a = C.w.V(a);
		a.style.display = "none";
		return a
	};
	C.H = C.w.H;
	C.lang = C.lang || {};
	C.lang.nd = function(a) {
		return "[object String]" == Object[u].toString.call(a)
	};
	C.nd = C.lang.nd;
	C.w.bf = function(a) {
		return C.lang.nd(a) ? da.getElementById(a) : a
	};
	C.bf = C.w.bf;
	C.w.contains = function(a, b) {
		var c = C.w.bf,
			a = c(a),
			b = c(b);
		return a.contains ? a != b && a.contains(b) : !! (a.compareDocumentPosition(b) & 16)
	};
	C.N = C.N || {};
	/msie (\d+\.\d)/i.test(navigator.userAgent) && (C.N.S = C.S = da.documentMode || +ea.$1);
	var na = {
		cellpadding: "cellPadding",
		cellspacing: "cellSpacing",
		colspan: "colSpan",
		rowspan: "rowSpan",
		valign: "vAlign",
		usemap: "useMap",
		frameborder: "frameBorder"
	};
	8 > C.N.S ? (na["for"] = "htmlFor", na["class"] = "className") : (na.htmlFor = "for", na.className = "class");
	C.w.Xp = na;
	C.w.pp = function(a, b, c) {
		a = C.w.V(a);
		if ("style" == b) a.style.cssText = c;
		else {
			b = C.w.Xp[b] || b;
			a.setAttribute(b, c)
		}
		return a
	};
	C.pp = C.w.pp;
	C.w.qp = function(a, b) {
		var a = C.w.V(a),
			c;
		for (c in b) C.w.pp(a, c, b[c]);
		return a
	};
	C.qp = C.w.qp;
	C.yf = C.yf || {};
	(function() {
		var a = new ea("(^[\\s\\t\ \　]+)|([\　\ \\s\\t]+$)", "g");
		C.yf.trim = function(b) {
			return ("" + b).replace(a, "")
		}
	})();
	C.trim = C.yf.trim;
	C.yf.ig = function(a, b) {
		var a = "" + a,
			c = Array[u].slice.call(arguments, 1),
			d = Object[u].toString;
		if (c[w]) {
			c = c[w] == 1 ? b !== k && /\[object Array\]|\[object Object\]/.test(d.call(b)) ? b : c : c;
			return a.replace(/#\{(.+?)\}/g, function(a, b) {
				var i = c[b];
				"[object Function]" == d.call(i) && (i = i(b));
				return "undefined" == typeof i ? "" : i
			})
		}
		return a
	};
	C.ig = C.yf.ig;
	C.w.Xb = function(a, b) {
		for (var a = C.w.V(a), c = a.className.split(/\s+/), d = b.split(/\s+/), e, f = d[w], i, j = 0; j < f; ++j) {
			i = 0;
			for (e = c[w]; i < e; ++i) if (c[i] == d[j]) {
				c.splice(i, 1);
				break
			}
		}
		a.className = c.join(" ");
		return a
	};
	C.Xb = C.w.Xb;
	C.w.Qo = function(a, b, c) {
		var a = C.w.V(a),
			d;
		if (a.insertAdjacentHTML) a.insertAdjacentHTML(b, c);
		else {
			d = a.ownerDocument.createRange();
			b = b.toUpperCase();
			if (b == "AFTERBEGIN" || b == "BEFOREEND") {
				d.selectNodeContents(a);
				d.collapse(b == "AFTERBEGIN")
			} else {
				b = b == "BEFOREBEGIN";
				d[b ? "setStartBefore" : "setEndAfter"](a);
				d.collapse(b)
			}
			d.insertNode(d.createContextualFragment(c))
		}
		return a
	};
	C.Qo = C.w.Qo;
	C.w.show = function(a) {
		a = C.w.V(a);
		a.style.display = "";
		return a
	};
	C.show = C.w.show;
	C.w.vo = function(a) {
		a = C.w.V(a);
		return a.nodeType == 9 ? a : a.ownerDocument || a.document
	};
	C.w.Ya = function(a, b) {
		for (var a = C.w.V(a), c = b.split(/\s+/), d = a.className, e = " " + d + " ", f = 0, i = c[w]; f < i; f++) e.indexOf(" " + c[f] + " ") < 0 && (d = d + (" " + c[f]));
		a.className = d;
		return a
	};
	C.Ya = C.w.Ya;
	C.w.mn = C.w.mn || {};
	C.w.Sf = C.w.Sf || [];
	C.w.Sf.filter = function(a, b, c) {
		for (var d = 0, e = C.w.Sf, f; f = e[d]; d++) if (f = f[c]) b = f(a, b);
		return b
	};
	C.yf.ku = function(a) {
		return a.indexOf("-") < 0 && a.indexOf("_") < 0 ? a : a.replace(/[-_][^-_]/g, function(a) {
			return a.charAt(1).toUpperCase()
		})
	};
	C.w.Qe = function(a, b) {
		var c = C.w,
			a = c.V(a),
			b = C.yf.ku(b),
			d = a.style[b];
		if (!d) var e = c.mn[b],
			d = a.currentStyle || (C.N.S ? a.style : getComputedStyle(a, k)),
			d = e && e.get ? e.get(a, d) : d[e || b];
		if (e = c.Sf) d = e.filter(b, d, "get");
		return d
	};
	C.Qe = C.w.Qe;
	/opera\/(\d+\.\d)/i.test(navigator.userAgent) && (C.N.opera = +ea.$1);
	C.N.Vs = /webkit/i.test(navigator.userAgent);
	C.N.Pz = /gecko/i.test(navigator.userAgent) && !/like gecko/i.test(navigator.userAgent);
	C.N.Uo = "CSS1Compat" == da.compatMode;
	C.w.T = function(a) {
		var a = C.w.V(a),
			b = C.w.vo(a),
			c = C.N,
			d = C.w.Qe;
		c.Pz > 0 && b.getBoxObjectFor && d(a, "position");
		var e = {
			left: 0,
			top: 0
		},
			f;
		if (a == (c.S && !c.Uo ? b.body : b.documentElement)) return e;
		if (a.getBoundingClientRect) {
			a = a.getBoundingClientRect();
			e.left = p.floor(a.left) + p.max(b.documentElement.scrollLeft, b.body.scrollLeft);
			e.top = p.floor(a.top) + p.max(b.documentElement.scrollTop, b.body.scrollTop);
			e.left = e.left - b.documentElement.clientLeft;
			e.top = e.top - b.documentElement.clientTop;
			a = b.body;
			b = t(d(a, "borderLeftWidth"));
			d = t(d(a, "borderTopWidth"));
			if (c.S && !c.Uo) {
				e.left = e.left - (isNaN(b) ? 2 : b);
				e.top = e.top - (isNaN(d) ? 2 : d)
			}
		} else {
			f = a;
			do {
				e.left = e.left + f.offsetLeft;
				e.top = e.top + f.offsetTop;
				if (c.Vs > 0 && d(f, "position") == "fixed") {
					e.left = e.left + b.body.scrollLeft;
					e.top = e.top + b.body.scrollTop;
					break
				}
				f = f.offsetParent
			} while (f && f != a);
			if (c.opera > 0 || c.Vs > 0 && d(a, "position") == "absolute") e.top = e.top - b.body.offsetTop;
			for (f = a.offsetParent; f && f != b.body;) {
				e.left = e.left - f.scrollLeft;
				if (!c.opera || f.tagName != "TR") e.top = e.top - f.scrollTop;
				f = f.offsetParent
			}
		}
		return e
	};
	/firefox\/(\d+\.\d)/i.test(navigator.userAgent) && (C.N.Me = +ea.$1);
	var oa = navigator.userAgent;
	/(\d+\.\d)?(?:\.\d)?\s+safari\/?(\d+\.\d+)?/i.test(oa) && !/chrome/i.test(oa) && (C.N.xA = +(ea.$1 || ea.$2));
	/chrome\/(\d+\.\d)/i.test(navigator.userAgent) && (C.N.Wx = +ea.$1);
	C.Nb = C.Nb || {};
	C.Nb.Bd = function(a, b) {
		var c, d, e = a[w];
		if ("function" == typeof b) for (d = 0; d < e; d++) {
			c = a[d];
			c = b.call(a, c, d);
			if (c === l) break
		}
		return a
	};
	C.Bd = C.Nb.Bd;
	C.lang.K = function() {
		return "TANGRAM__" + (window[C.K]._counter++).toString(36)
	};
	window[C.K]._counter = window[C.K]._counter || 1;
	window[C.K]._instances = window[C.K]._instances || {};
	C.lang.ej = function(a) {
		return "[object Function]" == Object[u].toString.call(a)
	};
	C.lang.ma = function(a) {
		this.K = a || C.lang.K();
		window[C.K]._instances[this.K] = this
	};
	window[C.K]._instances = window[C.K]._instances || {};
	C.lang.ma[u].Je = function() {
		delete window[C.K]._instances[this.K];
		for (var a in this) C.lang.ej(this[a]) || delete this[a]
	};
	C.lang.ma[u].toString = function() {
		return "[object " + (this.dv || "Object") + "]"
	};
	C.lang.Ij = function(a, b) {
		this.type = a;
		this.returnValue = g;
		this.target = b || k;
		this.currentTarget = k
	};
	C.lang.ma[u][B] = function(a, b, c) {
		if (C.lang.ej(b)) {
			!this.xe && (this.xe = {});
			var d = this.xe,
				e;
			if (typeof c == "string" && c) {
				if (/[^\w\-]/.test(c)) throw "nonstandard key:" + c;
				e = b.Js = c
			}
			a.indexOf("on") != 0 && (a = "on" + a);
			typeof d[a] != "object" && (d[a] = {});
			e = e || C.lang.K();
			b.Js = e;
			d[a][e] = b
		}
	};
	C.lang.ma[u].removeEventListener = function(a, b) {
		if (C.lang.ej(b)) b = b.Js;
		else if (!C.lang.nd(b)) return;
		!this.xe && (this.xe = {});
		a.indexOf("on") != 0 && (a = "on" + a);
		var c = this.xe;
		c[a] && c[a][b] && delete c[a][b]
	};
	C.lang.ma[u].dispatchEvent = function(a, b) {
		C.lang.nd(a) && (a = new C.lang.Ij(a));
		!this.xe && (this.xe = {});
		var b = b || {},
			c;
		for (c in b) a[c] = b[c];
		var d = this.xe,
			e = a.type;
		a.target = a.target || this;
		a.currentTarget = this;
		e.indexOf("on") != 0 && (e = "on" + e);
		C.lang.ej(this[e]) && this[e].apply(this, arguments);
		if (typeof d[e] == "object") for (c in d[e]) d[e][c].apply(this, arguments);
		return a.returnValue
	};
	C.lang.da = function(a, b, c) {
		var d, e, f = a[u];
		e = new Function;
		e[u] = b[u];
		e = a[u] = new e;
		for (d in f) e[d] = f[d];
		a[u].constructor = a;
		a.cB = b[u];
		if ("string" == typeof c) e.dv = c
	};
	C.da = C.lang.da;
	C.lang.Jd = function(a) {
		return window[C.K]._instances[a] || k
	};
	C.platform = C.platform || {};
	C.platform.Sz = /macintosh/i.test(navigator.userAgent);
	C.platform.Ws = /windows/i.test(navigator.userAgent);
	C.platform.Xz = /x11/i.test(navigator.userAgent);
	C.platform.Ps = /android/i.test(navigator.userAgent);
	/android (\d+\.\d)/i.test(navigator.userAgent) && (C.platform.Er = C.Er = ea.$1);
	C.platform.Qz = /ipad/i.test(navigator.userAgent);
	C.platform.Rz = /iphone/i.test(navigator.userAgent);
	C.lang.Ij[u].ja = function(a) {
		a = window.event || a;
		this.clientX = a.clientX || a.pageX;
		this.clientY = a.clientY || a.pageY;
		this[ia] = a[ia] || a.layerX;
		this[ja] = a[ja] || a.layerY;
		this.screenX = a.screenX;
		this.screenY = a.screenY;
		this.ctrlKey = a.ctrlKey || a.metaKey;
		this.shiftKey = a.shiftKey;
		this.altKey = a.altKey;
		if (a.touches) {
			this.touches = [];
			for (var b = 0; b < a.touches[w]; b++) this.touches.push({
				clientX: a.touches[b].clientX,
				clientY: a.touches[b].clientY,
				screenX: a.touches[b].screenX,
				screenY: a.touches[b].screenY,
				pageX: a.touches[b].pageX,
				pageY: a.touches[b].pageY,
				target: a.touches[b].target,
				identifier: a.touches[b].identifier
			})
		}
		if (a.changedTouches) {
			this.changedTouches = [];
			for (b = 0; b < a.changedTouches[w]; b++) this.changedTouches.push({
				clientX: a.changedTouches[b].clientX,
				clientY: a.changedTouches[b].clientY,
				screenX: a.changedTouches[b].screenX,
				screenY: a.changedTouches[b].screenY,
				pageX: a.changedTouches[b].pageX,
				pageY: a.changedTouches[b].pageY,
				target: a.changedTouches[b].target,
				identifier: a.changedTouches[b].identifier
			})
		}
		if (a.targetTouches) {
			this.targetTouches = [];
			for (b = 0; b < a.targetTouches[w]; b++) this.targetTouches.push({
				clientX: a.targetTouches[b].clientX,
				clientY: a.targetTouches[b].clientY,
				screenX: a.targetTouches[b].screenX,
				screenY: a.targetTouches[b].screenY,
				pageX: a.targetTouches[b].pageX,
				pageY: a.targetTouches[b].pageY,
				target: a.targetTouches[b].target,
				identifier: a.targetTouches[b].identifier
			})
		}
		this.rotation = a.rotation;
		this.scale = a.scale;
		return this
	};
	C.lang.Qk = function(a) {
		var b = window[C.K];
		b.lw && delete b.lw[a]
	};
	C.event = {};
	C.C = C.event.C = function(a, b, c) {
		if (!(a = C.V(a))) return a;
		b = b.replace(/^on/, "");
		if (a[B]) a[B](b, c, l);
		else a.attachEvent && a.attachEvent("on" + b, c);
		return a
	};
	C.nc = C.event.nc = function(a, b, c) {
		if (!(a = C.V(a))) return a;
		b = b.replace(/^on/, "");
		a.removeEventListener ? a.removeEventListener(b, c, l) : a.detachEvent && a.detachEvent("on" + b, c);
		return a
	};
	C.w.Az = function(a) {
		if (!a || !a.className || typeof a.className != "string") return l;
		var b = -1;
		try {
			b = a.className == "BMap_Marker" || a.className.search(new ea("(\\s|^)BMap_Marker(\\s|$)"))
		} catch (c) {
			return l
		}
		return b > -1
	};
	C.fs = function() {
		function a(a) {
			da[B] && (this.element = a, this.is = this.sg ? "touchstart" : "mousedown", this.ko = this.sg ? "touchmove" : "mousemove", this.jo = this.sg ? "touchend" : "mouseup", this.ap = l, this.au = this.$t = 0, this.element[B](this.is, this, l), ma.C(this.element, "mousedown", n()), this.handleEvent(k))
		}
		a[u] = {
			sg: "ontouchstart" in window || "createTouch" in document,
			start: function(a) {
				pa(a);
				this.ap = l;
				this.$t = this.sg ? a.touches[0].clientX : a.clientX;
				this.au = this.sg ? a.touches[0].clientY : a.clientY;
				this.element[B](this.ko, this, l);
				this.element[B](this.jo, this, l)
			},
			move: function(a) {
				qa(a);
				var c = this.sg ? a.touches[0].clientY : a.clientY;
				if (10 < p.abs((this.sg ? a.touches[0].clientX : a.clientX) - this.$t) || 10 < p.abs(c - this.au)) this.ap = g
			},
			end: function(a) {
				qa(a);
				this.ap || (a = da.createEvent("Event"), a.initEvent("tap", l, g), this.element.dispatchEvent(a));
				this.element.removeEventListener(this.ko, this, l);
				this.element.removeEventListener(this.jo, this, l)
			},
			handleEvent: function(a) {
				if (a) switch (a.type) {
				case this.is:
					this.start(a);
					break;
				case this.ko:
					this.move(a);
					break;
				case this.jo:
					this.end(a)
				}
			}
		};
		return function(b) {
			return new a(b)
		}
	}();
	var H = window.BMap || {};
	H.version = "1.5";
	H.ti = [];
	H.Cc = function(a) {
		this.ti.push(a)
	};
	H.Jx = H.apiLoad || n();
	var ra = window.BMAP_AUTHENTIC_KEY;
	window.BMAP_AUTHENTIC_KEY = k;
	var sa = window.BMap_loadScriptTime,
		ta = (new Date).getTime(),
		ua = k,
		va = g;

	function wa(a, b) {
		if (a = C.V(a)) {
			var c = this;
			C.lang.ma.call(c);
			b = b || {};
			c.G = {
				Jn: 200,
				mb: g,
				Vk: l,
				ao: g,
				Ni: l,
				Oi: l,
				eo: g,
				Wk: g,
				Tk: g,
				Oc: 25,
				BB: 240,
				zx: 450,
				qb: I.qb,
				zc: I.zc,
				hl: !! b.hl,
				Gb: b.minZoom || 1,
				dc: b.maxZoom || 18,
				cb: b.mapType || xa,
				WC: l,
				Uk: l,
				Yn: 500,
				By: b.enableHighResolution !== l,
				Cy: b.enableMapClick !== l
			};
			b.enableAutoResize && (c.G.Tk = b.enableAutoResize);
			c.ha = a;
			c.gn(a);
			a.unselectable = "on";
			a.innerHTML = "";
			a[v](c.Ra());
			b.size && this.rd(b.size);
			var d = c.Pb();
			c[y] = d[y];
			c[A] = d[A];
			c[ia] = 0;
			c[ja] = 0;
			c.platform = a.firstChild;
			c.Tc = c.platform.firstChild;
			c.Tc.style[y] = c[y] + "px";
			c.Tc.style[A] = c[A] + "px";
			c.$b = {};
			c.Ad = new K(0, 0);
			c.Fb = new K(0, 0);
			c.ua = 1;
			c.Tb = 0;
			c.Rn = k;
			c.Qn = k;
			c.hb = "";
			c.Dn = "";
			c.Yd = {};
			c.Yd.custom = {};
			c.la = 0;
			b = b || {};
			d = c.cb = c.G.cb;
			c.hc = d.th();
			d === ya && za(5002);
			(d === Aa || d === Ba) && za(5003);
			d = c.G;
			d.tu = b.minZoom;
			d.su = b.maxZoom;
			c.hm();
			c.B = {
				ib: l,
				Qa: 0,
				hj: 0,
				at: 0,
				EC: 0,
				Bn: l,
				jp: -1,
				dd: []
			};
			c.platform.style.cursor = c.G.qb;
			for (d = 0; d < H.ti[w]; d++) H.ti[d](c);
			c.B.jp = d;
			c.L();
			L.load("map", function() {
				c.Mb()
			});
			c.G.Cy && (setTimeout(function() {
				za("load_mapclick")
			}, 1E3), L.load("mapclick", function() {
				window.MPC_Mgr = new Ca(c)
			}, g));
			(C.platform.Ws || C.platform.Sz || C.platform.Xz) && L.load("oppc", function() {
				c.bm()
			});
			Da() && L.load("opmb", function() {
				c.bm()
			});
			a = k;
			c.qn = []
		}
	}
	C.lang.da(wa, C.lang.ma, "Map");
	C[x](wa[u], {
		Ra: function() {
			var a = M("div"),
				b = a.style;
			b.overflow = "visible";
			b[la] = "absolute";
			b.zIndex = "0";
			b.top = b.left = "0px";
			var b = M("div", {
				"class": "BMap_mask"
			}),
				c = b.style;
			c[la] = "absolute";
			c.top = c.left = "0px";
			c.zIndex = "9";
			c.overflow = "hidden";
			c.WebkitUserSelect = "none";
			a[v](b);
			return a
		},
		gn: function(a) {
			var b = a.style;
			b.overflow = "hidden";
			"absolute" != Ea(a)[la] && (b[la] = "relative", b.zIndex = 0);
			b.backgroundColor = "#F3F1EC";
			b.color = "#000";
			b.textAlign = "left"
		},
		L: function() {
			var a = this;
			a.Ai = function() {
				var b = a.Pb();
				if (a[y] != b[y] || a[A] != b[A]) {
					var c = new P(a[y], a[A]),
						d = new Q("onbeforeresize");
					d.size = c;
					a.dispatchEvent(d);
					a.Uf((b[y] - a[y]) / 2, (b[A] - a[A]) / 2);
					a.Tc.style[y] = (a[y] = b[y]) + "px";
					a.Tc.style[A] = (a[A] = b[A]) + "px";
					c = new Q("onresize");
					c.size = b;
					a.dispatchEvent(c)
				}
			};
			a.G.Tk && (a.B.Ei = setInterval(a.Ai, 80))
		},
		Uf: function(a, b, c, d) {
			var e = this.aa().nb(this.fa()),
				f = this.hc,
				i = g;
			c && K.Qs(c) && (this.Ad = new K(c.lng, c.lat), i = l);
			if (c = c && d ? f.wg(c, this.hb) : this.Fb) if (this.Fb = new K(c.lng + a * e, c.lat - b * e), (a = f.qf(this.Fb, this.hb)) && i) this.Ad = a
		},
		Rd: function(a, b) {
			if (Fa(a) && (a = this.Tg(a).zoom, a != this.ua)) {
				this.Tb = this.ua;
				this.ua = a;
				var c;
				b ? c = b : this.Ne() && (c = this.Ne().T());
				c && (c = this.Za(c, this.Tb), this.Uf(this[y] / 2 - c.x, this[A] / 2 - c.y, this.La(c, this.Tb), g));
				this.dispatchEvent(new Q("onzoomstart"));
				this.dispatchEvent(new Q("onzoomstartcode"))
			}
		},
		Il: function(a) {
			this.Rd(a)
		},
		Ip: function(a) {
			this.Rd(this.ua + 1, a)
		},
		Jp: function(a) {
			this.Rd(this.ua - 1, a)
		},
		pe: function(a) {
			a instanceof K && (this.Fb = this.hc.wg(a, this.hb), this.Ad = K.Qs(a) ? new K(a.lng, a.lat) : this.hc.qf(this.Fb, this.hb))
		},
		Nd: function(a, b) {
			a = p.round(a) || 0;
			b = p.round(b) || 0;
			this.Uf(-a, -b)
		},
		un: function(a) {
			a && Ga(a.Zc) && (a.Zc(this), this.dispatchEvent(new Q("onaddcontrol", a)))
		},
		tA: function(a) {
			a && Ga(a.remove) && (a.remove(), this.dispatchEvent(new Q("onremovecontrol", a)))
		},
		fh: function(a) {
			a && Ga(a.sa) && (a.sa(this), this.dispatchEvent(new Q("onaddcontextmenu", a)))
		},
		Dh: function(a) {
			a && Ga(a.remove) && (this.dispatchEvent(new Q("onremovecontextmenu", a)), a.remove())
		},
		Ca: function(a) {
			a && Ga(a.Zc) && (a.Zc(this), this.dispatchEvent(new Q("onaddoverlay", a)))
		},
		jc: function(a) {
			a && Ga(a.remove) && (a.remove(), this.dispatchEvent(new Q("onremoveoverlay", a)))
		},
		Rr: function() {
			this.dispatchEvent(new Q("onclearoverlays"))
		},
		Ci: function(a) {
			a && this.dispatchEvent(new Q("onaddtilelayer", a))
		},
		kj: function(a) {
			a && this.dispatchEvent(new Q("onremovetilelayer", a))
		},
		Ve: function(a) {
			if (this.cb !== a) {
				var b = new Q("onsetmaptype");
				b.UC = this.cb;
				this.cb = this.G.cb = a;
				this.hc = this.cb.th();
				this.Uf(0, 0, this.Ga(), g);
				this.hm();
				var c = this.Tg(this.fa()).zoom;
				this.Rd(c);
				this.dispatchEvent(b);
				b = new Q("onmaptypechange");
				b.ua = c;
				b.cb = a;
				this.dispatchEvent(b);
				(a === Aa || a === Ba) && za(5003)
			}
		},
		qd: function(a) {
			var b = this;
			if (a instanceof K) b.pe(a, {
				noAnimation: g
			});
			else if (Ha(a)) if (b.cb == ya) {
				var c = I.Gn[a];
				c && (pt = c.k, b.qd(pt))
			} else {
				var d = this.Gq();
				d.vp(function(c) {
					0 == d.pg() && 2 == d.ca.result.type && (b.qd(c.of(0).point), ya.lh(a) && b.sp(a))
				});
				d.search(a, {
					log: "center"
				})
			}
		},
		Ic: function(a, b) {
			var c = this;
			if (Ha(a)) if (c.cb == ya) {
				var d = I.Gn[a];
				d && (pt = d.k, c.Ic(pt, b))
			} else {
				var e = c.Gq();
				e.vp(function(d) {
					if (0 == e.pg() && 2 == e.ca.result.type) {
						var d = d.of(0).point,
							f = b || R.ro(e.ca.content.level, c);
						c.Ic(d, f);
						ya.lh(a) && c.sp(a)
					}
				});
				e.search(a, {
					log: "center"
				})
			} else if (a instanceof K && b) {
				b = c.Tg(b).zoom;
				c.Tb = c.ua || b;
				c.ua = b;
				c.Ad = new K(a.lng, a.lat);
				c.Fb = c.hc.wg(c.Ad, c.hb);
				c.Rn = c.Rn || c.ua;
				c.Qn = c.Qn || c.Ad;
				var d = new Q("onload"),
					f = new Q("onloadcode");
				d.point = new K(a.lng, a.lat);
				d.pixel = c.Za(c.Ad, c.ua);
				d.zoom = b;
				c.loaded || (c.loaded = g, c.dispatchEvent(d), ua || (ua = Ia()));
				c.dispatchEvent(f);
				c.dispatchEvent(new Q("onmoveend"));
				c.Tb != c.ua && c.dispatchEvent(new Q("onzoomend"))
			}
		},
		Gq: function() {
			this.B.ft || (this.B.ft = new Ja(1));
			return this.B.ft
		},
		reset: function() {
			this.Ic(this.Qn, this.Rn, g)
		},
		enableDragging: function() {
			this.G.mb = g
		},
		disableDragging: function() {
			this.G.mb = l
		},
		enableInertialDragging: function() {
			this.G.Uk = g
		},
		disableInertialDragging: function() {
			this.G.Uk = l
		},
		enableScrollWheelZoom: function() {
			this.G.Oi = g
		},
		disableScrollWheelZoom: function() {
			this.G.Oi = l
		},
		enableContinuousZoom: function() {
			this.G.Ni = g
		},
		disableContinuousZoom: function() {
			this.G.Ni = l
		},
		enableDoubleClickZoom: function() {
			this.G.ao = g
		},
		disableDoubleClickZoom: function() {
			this.G.ao = l
		},
		enableKeyboard: function() {
			this.G.Vk = g
		},
		disableKeyboard: function() {
			this.G.Vk = l
		},
		enablePinchToZoom: function() {
			this.G.Wk = g
		},
		disablePinchToZoom: function() {
			this.G.Wk = l
		},
		enableAutoResize: function() {
			this.G.Tk = g;
			this.Ai();
			this.B.Ei || (this.B.Ei = setInterval(this.Ai, 80))
		},
		disableAutoResize: function() {
			this.G.Tk = l;
			this.B.Ei && (clearInterval(this.B.Ei), this.B.Ei = k)
		},
		Pb: function() {
			return this.Ji && this.Ji instanceof P ? new P(this.Ji[y], this.Ji[A]) : new P(this.ha.clientWidth, this.ha.clientHeight)
		},
		rd: function(a) {
			a && a instanceof P ? (this.Ji = a, this.ha.style[y] = a[y] + "px", this.ha.style[A] = a[A] + "px") : this.Ji = k
		},
		Ga: o("Ad"),
		fa: o("ua"),
		Vx: function() {
			this.Ai()
		},
		Tg: function(a) {
			var b = this.G.Gb,
				c = this.G.dc,
				d = l;
			a < b && (d = g, a = b);
			a > c && (d = g, a = c);
			return {
				zoom: a,
				lo: d
			}
		},
		Ed: o("ha"),
		Za: function(a, b) {
			b = b || this.fa();
			return this.hc.Za(a, b, this.Fb, this.Pb(), this.hb)
		},
		La: function(a, b) {
			b = b || this.fa();
			return this.hc.La(a, b, this.Fb, this.Pb(), this.hb)
		},
		Od: function(a, b) {
			if (a) {
				var c = this.Za(new K(a.lng, a.lat), b);
				c.x -= this[ia];
				c.y -= this[ja];
				return c
			}
		},
		Gt: function(a, b) {
			if (a) {
				var c = new S(a.x, a.y);
				c.x += this[ia];
				c.y += this[ja];
				return this.La(c, b)
			}
		},
		pointToPixelFor3D: function(a, b) {
			var c = map.hb;
			this.cb == ya && c && Ka.Wr(a, this, b)
		},
		QC: function(a, b) {
			var c = map.hb;
			this.cb == ya && c && Ka.Vr(a, this, b)
		},
		RC: function(a, b) {
			var c = this,
				d = map.hb;
			c.cb == ya && d && Ka.Wr(a, c, function(a) {
				a.x -= c[ia];
				a.y -= c[ja];
				b && b(a)
			})
		},
		PC: function(a, b) {
			var c = map.hb;
			this.cb == ya && c && (a.x += this[ia], a.y += this[ja], Ka.Vr(a, this, b))
		},
		mf: function(a) {
			if (!this.So()) return new La;
			var b = a || {},
				a = b.margins || [0, 0, 0, 0],
				c = b.zoom || k,
				b = this.La({
					x: a[3],
					y: this[A] - a[2]
				}, c),
				a = this.La({
					x: this[y] - a[1],
					y: a[0]
				}, c);
			return new La(b, a)
		},
		So: function() {
			return !!this.loaded
		},
		Hv: function(a, b) {
			for (var c = this.aa(), d = b.margins || [10, 10, 10, 10], e = b.zoomFactor || 0, f = d[1] + d[3], d = d[0] + d[2], i = c.oh(), j = c = c.ng(); j >= i; j--) {
				var m = this.aa().nb(j);
				if (a.Fp().lng / m < this[y] - f && a.Fp().lat / m < this[A] - d) break
			}
			j += e;
			j < i && (j = i);
			j > c && (j = c);
			return j
		},
		el: function(a, b) {
			var c = {
				center: this.Ga(),
				zoom: this.fa()
			};
			if (!a || !a instanceof La && 0 == a[w] || a instanceof La && a.Se()) return c;
			var d = [];
			a instanceof La ? (d.push(a.Gd()), d.push(a.Hd())) : d = a.slice(0);
			for (var b = b || {}, e = [], f = 0, i = d[w]; f < i; f++) e.push(this.hc.wg(d[f], this.hb));
			d = new La;
			for (f = e[w] - 1; 0 <= f; f--) d[x](e[f]);
			if (d.Se()) return c;
			c = d.Ga();
			e = this.Hv(d, b);
			b.margins && (d = b.margins, f = (d[1] - d[3]) / 2, d = (d[0] - d[2]) / 2, i = this.aa().nb(e), b.offset && (f = b.offset[y], d = b.offset[A]), c.lng += i * f, c.lat += i * d);
			c = this.hc.qf(c, this.hb);
			return {
				center: c,
				zoom: e
			}
		},
		Ih: function(a, b) {
			var c;
			c = a && a.center ? a : this.el(a, b);
			var b = b || {},
				d = b.delay || 200;
			if (c.zoom == this.ua && b.enableAnimation != l) {
				var e = this;
				setTimeout(function() {
					e.pe(c.center, {
						duration: 210
					})
				}, d)
			} else this.Ic(c.center, c.zoom)
		},
		fe: o("$b"),
		Ne: function() {
			return this.B.ya && this.B.ya.pa() ? this.B.ya : k
		},
		getDistance: function(a, b) {
			if (a && b) {
				var c = 0;
				return c = T.uo(a, b)
			}
		},
		bz: function() {
			var a = [],
				b = this.X,
				c = this.pc;
			if (b) for (var d in b) b[d] instanceof Ma && a.push(b[d]);
			if (c) {
				d = 0;
				for (b = c[w]; d < b; d++) a.push(c[d])
			}
			return a
		},
		aa: o("cb"),
		bm: function() {
			for (var a = this.B.jp; a < H.ti[w]; a++) H.ti[a](this);
			this.B.jp = a
		},
		sp: function(a) {
			this.hb = ya.lh(a);
			this.Dn = ya.Qy(this.hb);
			this.cb == ya && this.hc instanceof Na && (this.hc.Mn = this.hb)
		},
		setDefaultCursor: function(a) {
			this.G.qb = a;
			this.platform && (this.platform.style.cursor = this.G.qb)
		},
		getDefaultCursor: function() {
			return this.G.qb
		},
		setDraggingCursor: function(a) {
			this.G.zc = a
		},
		getDraggingCursor: function() {
			return this.G.zc
		},
		he: function() {
			return this.G.By && 1 < window.devicePixelRatio
		},
		vn: function(a, b) {
			b ? this.Yd[b] || (this.Yd[b] = {}) : b = "custom";
			a.tag = b;
			a instanceof Oa && (this.Yd[b][a.K] = a, a.sa(this));
			var c = this;
			L.load("hotspot", function() {
				c.bm()
			})
		},
		uA: function(a, b) {
			b || (b = "custom");
			this.Yd[b][a.K] && delete this.Yd[b][a.K]
		},
		Hk: function(a) {
			a || (a = "custom");
			this.Yd[a] = {}
		},
		hm: function() {
			var a = this.he() ? this.cb.u.Dz : this.cb.oh(),
				b = this.he() ? this.cb.u.Cz : this.cb.ng(),
				c = this.G;
			c.Gb = c.tu || a;
			c.dc = c.su || b;
			c.Gb < a && (c.Gb = a);
			c.dc > b && (c.dc = b)
		},
		setMinZoom: function(a) {
			a > this.G.dc && (a = this.G.dc);
			this.G.tu = a;
			this.sr()
		},
		setMaxZoom: function(a) {
			a < this.G.Gb && (a = this.G.Gb);
			this.G.su = a;
			this.sr()
		},
		sr: function() {
			this.hm();
			var a = this.G;
			this.ua < a.Gb ? this.Il(a.Gb) : this.ua > a.dc && this.Il(a.dc);
			var b = new Q("onzoomspanchange");
			b.Gb = a.Gb;
			b.dc = a.dc;
			this.dispatchEvent(b)
		},
		wC: o("qn"),
		getKey: function() {
			return ra
		}
	});

	function za(a, b) {
		if (a) {
			var b = b || {},
				c = "",
				d;
			for (d in b) c = c + "&" + d + "=" + encodeURIComponent(b[d]);
			var e = function(a) {
					a && (Pa = g, setTimeout(function() {
						Qa.src = I.W + "blank.gif?" + a.src
					}, 50))
				},
				f = function() {
					var a = Ra.shift();
					a && e(a)
				};
			d = (1E8 * p.random()).toFixed(0);
			Pa ? Ra.push({
				src: "product=jsapi&v=" + H.version + "&t=" + d + "&code=" + a + c
			}) : e({
				src: "product=jsapi&v=" + H.version + "&t=" + d + "&code=" + a + c
			});
			Sa || (C.C(Qa, "load", function() {
				Pa = l;
				f()
			}), C.C(Qa, "error", function() {
				Pa = l;
				f()
			}), Sa = g)
		}
	}
	var Pa, Sa, Ra = [],
		Qa = new Image;
	za(5E3);

	function Ta(a) {
		var b = {
			duration: 1E3,
			Oc: 30,
			jh: 0,
			td: Ua.ct,
			cp: n()
		};
		this.gd = [];
		if (a) for (var c in a) b[c] = a[c];
		this.u = b;
		if (Fa(b.jh)) {
			var d = this;
			setTimeout(function() {
				d.start()
			}, b.jh)
		} else b.jh != Ta.Mg && this.start()
	}
	Ta.Mg = "INFINITE";
	Ta[u].start = function() {
		this.Nj = Ia();
		this.sm = this.Nj + this.u.duration;
		this.Lm()
	};
	Ta[u].add = function(a) {
		this.gd.push(a)
	};
	Ta[u].Lm = function() {
		var a = this,
			b = Ia();
		b >= a.sm ? (Ga(a.u.Ra) && a.u.Ra(a.u.td(1)), Ga(a.u.finish) && a.u.finish(), 0 < a.gd[w] && (b = a.gd[0], b.gd = [].concat(a.gd.slice(1)), b.start())) : (a.Cl = a.u.td((b - a.Nj) / a.u.duration), Ga(a.u.Ra) && a.u.Ra(a.Cl), a.Cp || (a.yi = setTimeout(function() {
			a.Lm()
		}, 1E3 / a.u.Oc)))
	};
	Ta[u].stop = function(a) {
		this.Cp = g;
		for (var b = 0; b < this.gd[w]; b++) this.gd[b].stop(), this.gd[b] = k;
		this.gd[w] = 0;
		this.yi && (clearTimeout(this.yi), this.yi = k);
		this.u.cp(this.Cl);
		a && (this.sm = this.Nj, this.Lm())
	};
	Ta[u].cancel = function() {
		this.yi && clearTimeout(this.yi);
		this.sm = this.Nj;
		this.Cl = 0
	};
	Ta[u].HA = function(a) {
		0 < this.gd[w] ? this.gd[this.gd[w] - 1].u.finish = a : this.u.finish = a
	};
	var Ua = {
		ct: function(a) {
			return a
		},
		reverse: function(a) {
			return 1 - a
		},
		Zn: function(a) {
			return a * a
		},
		xy: function(a) {
			return p.pow(a, 3)
		},
		Ay: function(a) {
			return -(a * (a - 2))
		},
		zy: function(a) {
			return p.pow(a - 1, 3) + 1
		},
		yy: function(a) {
			return 0.5 > a ? 2 * a * a : -2 * (a - 2) * a - 1
		},
		cC: function(a) {
			return 0.5 > a ? 4 * p.pow(a, 3) : 4 * p.pow(a - 1, 3) + 1
		},
		dC: function(a) {
			return (1 - p.cos(p.PI * a)) / 2
		}
	};
	Ua["ease-in"] = Ua.Zn;
	Ua["ease-out"] = Ua.Ay;
	var I = {
		W: "http://api.map.baidu.com/images/",
		Gn: {
			"北京": {
				wl: "bj",
				k: new K(116.403874, 39.914889)
			},
			"上海": {
				wl: "sh",
				k: new K(121.487899, 31.249162)
			},
			"深圳": {
				wl: "sz",
				k: new K(114.025974, 22.546054)
			},
			"广州": {
				wl: "gz",
				k: new K(113.30765, 23.120049)
			}
		},
		fontFamily: "arial,sans-serif"
	};
	if (C.N.Me) C[x](I, {
		bs: "url(" + I.W + "ruler.cur),crosshair",
		qb: "-moz-grab",
		zc: "-moz-grabbing"
	}), C.platform.Ws && (I.fontFamily = "arial,simsun,sans-serif");
	else if (C.N.Wx || C.N.xA) C[x](I, {
		bs: "url(" + I.W + "ruler.cur) 2 6,crosshair",
		qb: "url(" + I.W + "openhand.cur) 8 8,default",
		zc: "url(" + I.W + "closedhand.cur) 8 8,move"
	});
	else C[x](I, {
		bs: "url(" + I.W + "ruler.cur),crosshair",
		qb: "url(" + I.W + "openhand.cur),default",
		zc: "url(" + I.W + "closedhand.cur),move"
	});

	function Va(a, b) {
		var c = a.style;
		c.left = b[0] + "px";
		c.top = b[1] + "px"
	}
	function Wa(a) {
		0 < C.N.S ? a.unselectable = "on" : a.style.MozUserSelect = "none"
	}
	function Xa(a) {
		return a && a[ka] && 11 != a[ka].nodeType
	}
	function Za(a, b) {
		C.w.Qo(a, "beforeEnd", b);
		return a.lastChild
	}
	function pa(a) {
		a = window.event || a;
		a.stopPropagation ? a.stopPropagation() : a.cancelBubble = g
	}
	function $a(a) {
		a = window.event || a;
		a.preventDefault ? a.preventDefault() : a.returnValue = l;
		return l
	}
	function qa(a) {
		pa(a);
		return $a(a)
	}
	function ab() {
		var a = da.documentElement,
			b = da.body;
		return a && (a.scrollTop || a.scrollLeft) ? [a.scrollTop, a.scrollLeft] : b ? [b.scrollTop, b.scrollLeft] : [0, 0]
	}
	function bb(a, b) {
		if (a && b) return p.round(p.sqrt(p.pow(a.x - b.x, 2) + p.pow(a.y - b.y, 2)))
	}
	function db(a, b) {
		var c = [],
			b = b ||
		function(a) {
			return a
		}, d;
		for (d in a) c.push(d + "=" + b(a[d]));
		return c.join("&")
	}
	function M(a, b, c) {
		var d = da.createElement(a);
		c && (d = da.createElementNS(c, a));
		return C.w.qp(d, b || {})
	}
	function Ea(a) {
		if (a.currentStyle) return a.currentStyle;
		if (a.ownerDocument && a.ownerDocument.defaultView) return a.ownerDocument.defaultView.getComputedStyle(a, k)
	}
	function Ga(a) {
		return "function" == typeof a
	}
	function Fa(a) {
		return "number" == typeof a
	}
	function Ha(a) {
		return "string" == typeof a
	}
	function eb(a) {
		return "undefined" != typeof a
	}
	function fb(a) {
		return "object" == typeof a
	}
	var gb = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";

	function hb(a) {
		var b = "",
			c, d, e = "",
			f, i = "",
			j = 0;
		f = /[^A-Za-z0-9\+\/\=]/g;
		if (!a || f.exec(a)) return a;
		a = a.replace(/[^A-Za-z0-9\+\/\=]/g, "");
		do c = gb.indexOf(a.charAt(j++)), d = gb.indexOf(a.charAt(j++)), f = gb.indexOf(a.charAt(j++)), i = gb.indexOf(a.charAt(j++)), c = c << 2 | d >> 4, d = (d & 15) << 4 | f >> 2, e = (f & 3) << 6 | i, b += String.fromCharCode(c), 64 != f && (b += String.fromCharCode(d)), 64 != i && (b += String.fromCharCode(e));
		while (j < a[w]);
		return b
	}
	var Q = C.lang.Ij;

	function Da() {
		return !(!C.platform.Rz && !C.platform.Qz && !C.platform.Ps)
	}
	function Ia() {
		return (new Date).getTime()
	}
	function ib() {
		var a = da.body[v](M("div"));
		a.innerHTML = '<v:shape id="vml_tester1" adj="1" />';
		var b = a.firstChild;
		if (!b.style) return l;
		b.style.behavior = "url(#default#VML)";
		b = b ? "object" == typeof b.adj : g;
		a[ka][ga](a);
		return b
	}
	function jb() {
		return !!da.implementation.hasFeature("http://www.w3.org/TR/SVG11/feature#Shape", "1.1")
	};

	function lb(a, b) {
		if (b) {
			var c = (1E5 * p.random()).toFixed(0);
			H._rd["_cbk" + c] = function(a) {
				b && b(a);
				delete H._rd["_cbk" + c]
			};
			a += "&callback=BMap._rd._cbk" + c
		}
		var d = M("script", {
			src: a,
			type: "text/javascript",
			charset: "utf-8"
		});
		if (d[B]) d[B]("load", function(a) {
			a = a.target;
			a[ka][ga](a)
		}, l);
		else d.attachEvent && d.attachEvent("onreadystatechange", function() {
			var a = window.event.srcElement;
			if (a && ("loaded" == a.readyState || "complete" == a.readyState)) a[ka][ga](a)
		});
		setTimeout(function() {
			da.getElementsByTagName("head")[0][v](d);
			d = k
		}, 1)
	};
	var mb = {
		map: "20141117084945",
		common: "20141117084945",
		tile: "20141117084945",
		marker: "20141117084945",
		markeranimation: "20141117084945",
		poly: "20141117084945",
		draw: "20141117084945",
		drawbysvg: "20141117084945",
		drawbyvml: "20141117084945",
		drawbycanvas: "20141117084945",
		infowindow: "20141117084945",
		oppc: "20141117084945",
		opmb: "20141117084945",
		menu: "20141117084945",
		control: "20141117084945",
		navictrl: "20141117084945",
		geoctrl: "20141117084945",
		copyrightctrl: "20141117084945",
		scommon: "20141117084945",
		local: "20141117084945",
		route: "20141117084945",
		othersearch: "20141117084945",
		mapclick: "20141117084945",
		buslinesearch: "20141117084945",
		hotspot: "20141117084945",
		autocomplete: "20141117084945",
		coordtrans: "20141117084945",
		coordtransutils: "20141117084945",
		clayer: "20141117084945"
	};
	C.Ll = function() {
		function a(a) {
			return d && !! c[b + a + "_" + mb[a]]
		}
		var b = "BMap_",
			c = window.localStorage,
			d = "localStorage" in window && c !== k && c !== aa;
		return {
			Uz: d,
			set: function(a, f) {
				if (d) {
					for (var i = b + a + "_", j = c[w], m; j--;) m = c.key(j), -1 < m.indexOf(i) && c.removeItem(m);
					try {
						c.setItem(b + a + "_" + mb[a], f)
					} catch (q) {
						c.clear()
					}
				}
			},
			get: function(e) {
				return d && a(e) ? c.getItem(b + e + "_" + mb[e]) : l
			},
			Or: a
		}
	}();

	function L() {}
	C.object[x](L, {
		Ye: {
			Sp: -1,
			Ou: 0,
			Oh: 1
		},
		ws: function() {
			var a = "drawbysvg";
			jb() ? a = "drawbysvg" : ib() ? a = "drawbyvml" : M("canvas").getContext && (a = "drawbycanvas");
			return {
				control: [],
				marker: [],
				poly: ["marker", a],
				drawbysvg: ["draw"],
				drawbyvml: ["draw"],
				drawbycanvas: ["draw"],
				infowindow: ["common", "marker"],
				menu: [],
				oppc: [],
				opmb: [],
				scommon: [],
				local: ["scommon"],
				route: ["scommon"],
				othersearch: ["scommon"],
				autocomplete: ["scommon"],
				mapclick: ["scommon"],
				buslinesearch: ["route"],
				hotspot: [],
				coordtransutils: ["coordtrans"],
				clayer: ["tile"]
			}
		},
		TC: {},
		Np: {
			Uu: "http://api.map.baidu.com/getmodules?v=1.5",
			wx: 5E3
		},
		Sn: l,
		Lb: {
			If: {},
			Og: [],
			rk: []
		},
		load: function(a, b, c) {
			var d = this.Hi(a);
			if (d.Hc == this.Ye.Oh) c && b();
			else {
				if (d.Hc == this.Ye.Sp) {
					this.Tr(a);
					this.Mt(a);
					var e = this;
					e.Sn == l && (e.Sn = g, setTimeout(function() {
						for (var a = [], b = 0, c = e.Lb.Og[w]; b < c; b++) {
							var d = e.Lb.Og[b],
								q = "";
							ma.Ll.Or(d) ? q = ma.Ll.get(d) : (q = "", a.push(d));
							e.Lb.rk.push({
								nt: d,
								$o: q
							})
						}
						e.Sn = l;
						e.Lb.Og[w] = 0;
						0 == a[w] ? e.hs() : lb(e.Np.Uu + "&mod=" + a.join(","))
					}, 1));
					d.Hc = this.Ye.Ou
				}
				d.Oj.push(b)
			}
		},
		Tr: function(a) {
			if (a && this.ws()[a]) for (var a = this.ws()[a], b = 0; b < a[w]; b++) this.Tr(a[b]), this.Lb.If[a[b]] || this.Mt(a[b])
		},
		Mt: function(a) {
			for (var b = 0; b < this.Lb.Og[w]; b++) if (this.Lb.Og[b] == a) return;
			this.Lb.Og.push(a)
		},
		wA: function(a, b) {
			var c = this.Hi(a);
			try {
				eval(b)
			} catch (d) {
				return
			}
			c.Hc = this.Ye.Oh;
			for (var e = 0, f = c.Oj[w]; e < f; e++) c.Oj[e]();
			c.Oj[w] = 0
		},
		Or: function(a, b) {
			var c = this;
			c.timeout = setTimeout(function() {
				c.Lb.If[a].Hc != c.Ye.Oh ? (c.remove(a), c.load(a, b)) : clearTimeout(c.timeout)
			}, c.Np.wx)
		},
		Hi: function(a) {
			this.Lb.If[a] || (this.Lb.If[a] = {}, this.Lb.If[a].Hc = this.Ye.Sp, this.Lb.If[a].Oj = []);
			return this.Lb.If[a]
		},
		remove: function(a) {
			delete this.Hi(a)
		},
		Tx: function(a, b) {
			for (var c = this.Lb.rk, d = 0, e = c[w]; d < e; d++) if ("" == c[d].$o) if (c[d].nt == a) c[d].$o = b;
			else return;
			this.hs()
		},
		hs: function() {
			for (var a = this.Lb.rk, b = 0, c = a[w]; b < c; b++) this.wA(a[b].nt, a[b].$o);
			this.Lb.rk[w] = 0
		}
	});

	function S(a, b) {
		this.x = a || 0;
		this.y = b || 0;
		this.x = this.x;
		this.y = this.y
	}
	S[u].Ob = function(a) {
		return a && a.x == this.x && a.y == this.y
	};

	function P(a, b) {
		this[y] = a || 0;
		this[A] = b || 0
	}
	P[u].Ob = function(a) {
		return a && this[y] == a[y] && this[A] == a[A]
	};

	function Oa(a, b) {
		a && (this.Yq = a, this.K = "spot" + Oa.K++, b = b || {}, this.Fe = b.text || "", this.ik = b.offsets ? b.offsets.slice(0) : [5, 5, 5, 5], this.tr = b.userData || k, this.$d = b.minZoom || k, this.ad = b.maxZoom || k)
	}
	Oa.K = 0;
	C[x](Oa[u], {
		sa: function(a) {
			this.$d == k && (this.$d = a.G.Gb);
			this.ad == k && (this.ad = a.G.dc)
		},
		ba: function(a) {
			a instanceof K && (this.Yq = a)
		},
		T: o("Yq"),
		tj: ba("Fe"),
		Jo: o("Fe"),
		setUserData: ba("tr"),
		getUserData: o("tr")
	});

	function nb() {
		this.z = k;
		this.$a = "control";
		this.ab = this.Ir = g
	}
	C.lang.da(nb, C.lang.ma, "Control");
	C[x](nb[u], {
		initialize: function(a) {
			this.z = a;
			if (this.A) return a.ha[v](this.A), this.A
		},
		Zc: function(a) {
			!this.A && (this.initialize && Ga(this.initialize)) && (this.A = this.initialize(a));
			this.u = this.u || {
				re: l
			};
			this.gn();
			this.mk();
			this.A && (this.A.mi = this)
		},
		gn: function() {
			var a = this.A;
			if (a) {
				var b = a.style;
				b[la] = "absolute";
				b.zIndex = this.jq || "10";
				b.MozUserSelect = "none";
				b.WebkitTextSizeAdjust = "none";
				this.u.re || C.w.Ya(a, "BMap_noprint");
				Da() || C.C(a, "contextmenu", qa)
			}
		},
		remove: function() {
			this.z = k;
			this.A && (this.A[ka] && this.A[ka][ga](this.A), this.A = this.A.mi = k)
		},
		Xa: function() {
			this.A = Za(this.z.ha, "<div unselectable='on'></div>");
			this.ab == l && C.w.H(this.A);
			return this.A
		},
		mk: function() {
			this.vb(this.u.anchor)
		},
		vb: function(a) {
			if (this.YB || !Fa(a) || isNaN(a) || a < ob || 3 < a) a = this.defaultAnchor;
			this.u = this.u || {
				re: l
			};
			this.u.Y = this.u.Y || this.defaultOffset;
			var b = this.u.anchor;
			this.u.anchor = a;
			if (this.A) {
				var c = this.A,
					d = this.u.Y[y],
					e = this.u.Y[A];
				c.style.left = c.style.top = c.style.right = c.style.bottom = "auto";
				switch (a) {
				case ob:
					c.style.top = e + "px";
					c.style.left = d + "px";
					break;
				case pb:
					c.style.top = e + "px";
					c.style.right = d + "px";
					break;
				case qb:
					c.style.bottom = e + "px";
					c.style.left = d + "px";
					break;
				case 3:
					c.style.bottom = e + "px", c.style.right = d + "px"
				}
				c = ["TL", "TR", "BL", "BR"];
				C.w.Xb(this.A, "anchor" + c[b]);
				C.w.Ya(this.A, "anchor" + c[a])
			}
		},
		po: function() {
			return this.u.anchor
		},
		kc: function(a) {
			a instanceof P && (this.u = this.u || {
				re: l
			}, this.u.Y = new P(a[y], a[A]), this.A && this.vb(this.u.anchor))
		},
		Oe: function() {
			return this.u.Y
		},
		Ac: o("A"),
		show: function() {
			this.ab != g && (this.ab = g, this.A && C.w.show(this.A))
		},
		H: function() {
			this.ab != l && (this.ab = l, this.A && C.w.H(this.A))
		},
		isPrintable: function() {
			return !!this.u.re
		},
		Te: function() {
			return !this.A && !this.z ? l : !! this.ab
		}
	});
	var ob = 0,
		pb = 1,
		qb = 2;

	function rb(a) {
		nb.call(this);
		a = a || {};
		this.u = {
			re: l,
			yp: a.showZoomInfo || g,
			anchor: a.anchor,
			Y: a.offset,
			type: a.type
		};
		this.defaultAnchor = Da() ? 3 : ob;
		this.defaultOffset = new P(10, 10);
		this.vb(a.anchor);
		this.Ig(a.type);
		this.Ae()
	}
	C.lang.da(rb, nb, "NavigationControl");
	C[x](rb[u], {
		initialize: function(a) {
			this.z = a;
			return this.A
		},
		Ig: function(a) {
			this.u.type = Fa(a) && 0 <= a && 3 >= a ? a : 0
		},
		vh: function() {
			return this.u.type
		},
		Ae: function() {
			var a = this;
			L.load("navictrl", function() {
				a.ze()
			})
		}
	});

	function tb(a) {
		nb.call(this);
		a = a || {};
		this.u = {
			anchor: a.anchor || qb,
			Y: a.offset || new P(10, 30),
			VA: a.showAddressBar || l,
			fC: a.enableAutoLocation || l,
			ht: a.locationIcon || k
		};
		var b = this;
		this.jq = 1200;
		b.sB = [];
		this.Yg = [];
		L.load("geoctrl", function() {
			(function d() {
				if (0 !== b.Yg[w]) {
					var a = b.Yg.shift();
					b[a.method].apply(b, a.arguments);
					d()
				}
			})();
			b.Tu()
		})
	}
	C.lang.da(tb, nb, "GeolocationControl");
	C[x](tb[u], {
		location: function() {
			this.Yg.push({
				method: "location",
				arguments: arguments
			})
		},
		getAddressComponent: ca(k)
	});

	function ub(a) {
		nb.call(this);
		a = a || {};
		this.u = {
			re: l,
			anchor: a.anchor,
			Y: a.offset
		};
		this.Ma = [];
		this.defaultAnchor = qb;
		this.defaultOffset = new P(5, 2);
		this.vb(a.anchor);
		this.Ir = l;
		this.Ae()
	}
	C.lang.da(ub, nb, "CopyrightControl");
	C.object[x](ub[u], {
		initialize: function(a) {
			this.z = a;
			return this.A
		},
		zk: function(a) {
			if (a && Fa(a.id) && !isNaN(a.id)) {
				var b = {
					bounds: k,
					content: ""
				},
					c;
				for (c in a) b[c] = a[c];
				if (a = this.kg(a.id)) for (var d in b) a[d] = b[d];
				else this.Ma.push(b)
			}
		},
		kg: function(a) {
			for (var b = 0, c = this.Ma[w]; b < c; b++) if (this.Ma[b].id == a) return this.Ma[b]
		},
		to: o("Ma"),
		kp: function(a) {
			for (var b = 0, c = this.Ma[w]; b < c; b++) this.Ma[b].id == a && (r = this.Ma.splice(b, 1), b--, c = this.Ma[w])
		},
		Ae: function() {
			var a = this;
			L.load("copyrightctrl", function() {
				a.ze()
			})
		}
	});

	function vb(a) {
		nb.call(this);
		a = a || {};
		this.u = {
			re: l,
			size: a.size || new P(150, 150),
			padding: 5,
			pa: a.isOpen === g ? g : l,
			zB: 4,
			Y: a.offset,
			anchor: a.anchor
		};
		this.defaultAnchor = 3;
		this.defaultOffset = new P(0, 0);
		this.Vh = this.Wh = 13;
		this.vb(a.anchor);
		this.rd(this.u.size);
		this.Ae()
	}
	C.lang.da(vb, nb, "OverviewMapControl");
	C[x](vb[u], {
		initialize: function(a) {
			this.z = a;
			return this.A
		},
		vb: function(a) {
			nb[u].vb.call(this, a)
		},
		ac: function() {
			this.ac.$g = g;
			this.u.pa = !this.u.pa;
			this.A || (this.ac.$g = l)
		},
		rd: function(a) {
			a instanceof P || (a = new P(150, 150));
			a[y] = 0 < a[y] ? a[y] : 150;
			a[A] = 0 < a[A] ? a[A] : 150;
			this.u.size = a
		},
		Pb: function() {
			return this.u.size
		},
		pa: function() {
			return this.u.pa
		},
		Ae: function() {
			var a = this;
			L.load("control", function() {
				a.ze()
			})
		}
	});

	function wb(a) {
		nb.call(this);
		a = a || {};
		this.u = {
			re: l,
			color: "black",
			ud: "metric",
			Y: a.offset
		};
		this.defaultAnchor = qb;
		this.defaultOffset = new P(81, 18);
		this.vb(a.anchor);
		this.ae = {
			metric: {
				name: "metric",
				Ur: 1,
				Ns: 1E3,
				ou: "米",
				pu: "公里"
			},
			us: {
				name: "us",
				Ur: 3.2808,
				Ns: 5280,
				ou: "英尺",
				pu: "英里"
			}
		};
		this.ae[this.u.ud] || (this.u.ud = "metric");
		this.fr = k;
		this.Tq = {};
		this.Ae()
	}
	C.lang.da(wb, nb, "ScaleControl");
	C.object[x](wb[u], {
		initialize: function(a) {
			this.z = a;
			return this.A
		},
		rp: function(a) {
			this.u.color = a + ""
		},
		kC: function() {
			return this.u.color
		},
		xp: function(a) {
			this.u.ud = this.ae[a] && this.ae[a].name || this.u.ud
		},
		vz: function() {
			return this.u.ud
		},
		Ae: function() {
			var a = this;
			L.load("control", function() {
				a.ze()
			})
		}
	});
	var xb = 0;

	function yb(a) {
		nb.call(this);
		a = a || {};
		this.defaultAnchor = pb;
		this.defaultOffset = new P(10, 10);
		this.u = {
			re: l,
			Md: [xa, Aa, Ba, ya],
			type: a.type || xb,
			Y: a.offset || this.defaultOffset,
			hC: g
		};
		this.vb(a.anchor);
		"[object Array]" == Object[u].toString.call(a.mapTypes) && (this.u.Md = a.mapTypes.slice(0));
		this.Ae()
	}
	C.lang.da(yb, nb, "MapTypeControl");
	C.object[x](yb[u], {
		initialize: function(a) {
			this.z = a;
			return this.A
		},
		Ae: function() {
			var a = this;
			L.load("control", function() {
				a.ze()
			})
		}
	});

	function zb(a) {
		C.lang.ma.call(this);
		this.u = {
			ha: k,
			cursor: "default"
		};
		this.u = C[x](this.u, a);
		this.$a = "contextmenu";
		this.z = k;
		this.U = [];
		this.bd = [];
		this.qc = [];
		this.Ok = this.Gi = k;
		this.Zd = l;
		var b = this;
		L.load("menu", function() {
			b.Mb()
		})
	}
	C.lang.da(zb, C.lang.ma, "ContextMenu");
	C.object[x](zb[u], {
		sa: function(a, b) {
			this.z = a;
			this.Mf = b || k
		},
		remove: function() {
			this.z = this.Mf = k
		},
		Bk: function(a) {
			if (a && !("menuitem" != a.$a || "" == a.Fe || 0 >= a.yx)) {
				for (var b = 0, c = this.U[w]; b < c; b++) if (this.U[b] === a) return;
				this.U.push(a);
				this.bd.push(a)
			}
		},
		removeItem: function(a) {
			if (a && "menuitem" == a.$a) {
				for (var b = 0, c = this.U[w]; b < c; b++) this.U[b] === a && (this.U[b].remove(), this.U.splice(b, 1), c--);
				b = 0;
				for (c = this.bd[w]; b < c; b++) this.bd[b] === a && (this.bd[b].remove(), this.bd.splice(b, 1), c--)
			}
		},
		wn: function() {
			this.U.push({
				$a: "divider",
				af: this.qc[w]
			});
			this.qc.push({
				w: k
			})
		},
		lp: function(a) {
			if (this.qc[a]) {
				for (var b = 0, c = this.U[w]; b < c; b++) this.U[b] && ("divider" == this.U[b].$a && this.U[b].af == a) && (this.U.splice(b, 1), c--), this.U[b] && ("divider" == this.U[b].$a && this.U[b].af > a) && this.U[b].af--;
				this.qc.splice(a, 1)
			}
		},
		Ac: o("A"),
		show: function() {
			this.Zd != g && (this.Zd = g)
		},
		H: function() {
			this.Zd != l && (this.Zd = l)
		},
		FA: function(a) {
			a && (this.u.cursor = a)
		},
		getItem: function(a) {
			return this.bd[a]
		}
	});

	function Ab(a, b, c) {
		if (a && Ga(b)) {
			C.lang.ma.call(this);
			this.u = {
				width: 100,
				id: ""
			};
			c = c || {};
			this.u[y] = 1 * c.width ? c.width : 100;
			this.u.id = c.id ? c.id : "";
			this.Fe = a + "";
			this.em = b;
			this.z = k;
			this.$a = "menuitem";
			this.A = this.Ud = k;
			this.Wd = g;
			var d = this;
			L.load("menu", function() {
				d.Mb()
			})
		}
	}
	C.lang.da(Ab, C.lang.ma, "MenuItem");
	C.object[x](Ab[u], {
		sa: function(a, b) {
			this.z = a;
			this.Ud = b
		},
		remove: function() {
			this.z = this.Ud = k
		},
		tj: function(a) {
			a && (this.Fe = a + "")
		},
		Ac: o("A"),
		enable: function() {
			this.Wd = g
		},
		disable: function() {
			this.Wd = l
		}
	});

	function La(a, b) {
		a && !b && (b = a);
		this.uc = this.tc = this.xc = this.wc = this.Tf = this.Lf = k;
		a && (this.Tf = new K(a.lng, a.lat), this.Lf = new K(b.lng, b.lat), this.xc = a.lng, this.wc = a.lat, this.uc = b.lng, this.tc = b.lat)
	}
	C.object[x](La[u], {
		Se: function() {
			return !this.Tf || !this.Lf
		},
		Ob: function(a) {
			return !(a instanceof La) || this.Se() ? l : this.Hd().Ob(a.Hd()) && this.Gd().Ob(a.Gd())
		},
		Hd: o("Tf"),
		Gd: o("Lf"),
		cy: function(a) {
			return !(a instanceof La) || this.Se() || a.Se() ? l : a.xc > this.xc && a.uc < this.uc && a.wc > this.wc && a.tc < this.tc
		},
		Ga: function() {
			return this.Se() ? k : new K((this.xc + this.uc) / 2, (this.wc + this.tc) / 2)
		},
		Os: function(a) {
			if (!(a instanceof La) || p.max(a.xc, a.uc) < p.min(this.xc, this.uc) || p.min(a.xc, a.uc) > p.max(this.xc, this.uc) || p.max(a.wc, a.tc) < p.min(this.wc, this.tc) || p.min(a.wc, a.tc) > p.max(this.wc, this.tc)) return k;
			var b = p.max(this.xc, a.xc),
				c = p.min(this.uc, a.uc),
				d = p.max(this.wc, a.wc),
				a = p.min(this.tc, a.tc);
			return new La(new K(b, d), new K(c, a))
		},
		dy: function(a) {
			return !(a instanceof K) || this.Se() ? l : a.lng >= this.xc && a.lng <= this.uc && a.lat >= this.wc && a.lat <= this.tc
		},
		extend: function(a) {
			if (a instanceof K) {
				var b = a.lng,
					a = a.lat;
				this.Tf || (this.Tf = new K(0, 0));
				this.Lf || (this.Lf = new K(0, 0));
				if (!this.xc || this.xc > b) this.Tf.lng = this.xc = b;
				if (!this.uc || this.uc < b) this.Lf.lng = this.uc = b;
				if (!this.wc || this.wc > a) this.Tf.lat = this.wc = a;
				if (!this.tc || this.tc < a) this.Lf.lat = this.tc = a
			}
		},
		Fp: function() {
			return this.Se() ? new K(0, 0) : new K(p.abs(this.uc - this.xc), p.abs(this.tc - this.wc))
		}
	});

	function K(a, b) {
		isNaN(a) && (a = hb(a), a = isNaN(a) ? 0 : a);
		Ha(a) && (a = fa(a));
		isNaN(b) && (b = hb(b), b = isNaN(b) ? 0 : b);
		Ha(b) && (b = fa(b));
		this.lng = a;
		this.lat = b
	}
	K.Qs = function(a) {
		return a && 180 >= a.lng && -180 <= a.lng && 74 >= a.lat && -74 <= a.lat
	};
	K[u].Ob = function(a) {
		return a && this.lat == a.lat && this.lng == a.lng
	};

	function Bb() {}
	Bb[u].ll = function() {
		throw "lngLatToPoint方法未实现";
	};
	Bb[u].hp = function() {
		throw "pointToLngLat方法未实现";
	};

	function Cb() {};
	var Ka = {
		Wr: function(a, b, c) {
			L.load("coordtransutils", function() {
				Ka.Lx(a, b, c)
			}, g)
		},
		Vr: function(a, b, c) {
			L.load("coordtransutils", function() {
				Ka.Kx(a, b, c)
			}, g)
		}
	};

	function T() {}
	T[u] = new Bb;
	C[x](T, {
		Au: 6370996.81,
		Vp: [1.289059486E7, 8362377.87, 5591021, 3481989.83, 1678043.12, 0],
		Kj: [75, 60, 45, 30, 15, 0],
		Eu: [
			[1.410526172116255E-8, 8.98305509648872E-6, -1.9939833816331, 200.9824383106796, -187.2403703815547, 91.6087516669843, -23.38765649603339, 2.57121317296198, -0.03801003308653, 1.73379812E7],
			[-7.435856389565537E-9, 8.983055097726239E-6, -0.78625201886289, 96.32687599759846, -1.85204757529826, -59.36935905485877, 47.40033549296737, -16.50741931063887, 2.28786674699375, 1.026014486E7],
			[-3.030883460898826E-8, 8.98305509983578E-6, 0.30071316287616, 59.74293618442277, 7.357984074871, -25.38371002664745, 13.45380521110908, -3.29883767235584, 0.32710905363475, 6856817.37],
			[-1.981981304930552E-8, 8.983055099779535E-6, 0.03278182852591, 40.31678527705744, 0.65659298677277, -4.44255534477492, 0.85341911805263, 0.12923347998204, -0.04625736007561, 4482777.06],
			[3.09191371068437E-9, 8.983055096812155E-6, 6.995724062E-5, 23.10934304144901, -2.3663490511E-4, -0.6321817810242, -0.00663494467273, 0.03430082397953, -0.00466043876332, 2555164.4],
			[2.890871144776878E-9, 8.983055095805407E-6, -3.068298E-8, 7.47137025468032, -3.53937994E-6, -0.02145144861037, -1.234426596E-5, 1.0322952773E-4, -3.23890364E-6, 826088.5]
		],
		Tp: [
			[-0.0015702102444, 111320.7020616939, 1704480524535203, -10338987376042340, 26112667856603880, -35149669176653700, 26595700718403920, -10725012454188240, 1800819912950474, 82.5],
			[8.277824516172526E-4, 111320.7020463578, 6.477955746671607E8, -4.082003173641316E9, 1.077490566351142E10, -1.517187553151559E10, 1.205306533862167E10, -5.124939663577472E9, 9.133119359512032E8, 67.5],
			[0.00337398766765, 111320.7020202162, 4481351.045890365, -2.339375119931662E7, 7.968221547186455E7, -1.159649932797253E8, 9.723671115602145E7, -4.366194633752821E7, 8477230.501135234, 52.5],
			[0.00220636496208, 111320.7020209128, 51751.86112841131, 3796837.749470245, 992013.7397791013, -1221952.21711287, 1340652.697009075, -620943.6990984312, 144416.9293806241, 37.5],
			[-3.441963504368392E-4, 111320.7020576856, 278.2353980772752, 2485758.690035394, 6070.750963243378, 54821.18345352118, 9540.606633304236, -2710.55326746645, 1405.483844121726, 22.5],
			[-3.218135878613132E-4, 111320.7020701615, 0.00369383431289, 823725.6402795718, 0.46104986909093, 2351.343141331292, 1.58060784298199, 8.77738589078284, 0.37238884252424, 7.45]
		],
		lC: function(a, b) {
			if (!a || !b) return 0;
			var c, d, a = this.Na(a);
			if (!a) return 0;
			c = this.zf(a.lng);
			d = this.zf(a.lat);
			b = this.Na(b);
			return !b ? 0 : this.Pc(c, this.zf(b.lng), d, this.zf(b.lat))
		},
		uo: function(a, b) {
			if (!a || !b) return 0;
			a.lng = this.Bo(a.lng, -180, 180);
			a.lat = this.Go(a.lat, -74, 74);
			b.lng = this.Bo(b.lng, -180, 180);
			b.lat = this.Go(b.lat, -74, 74);
			return this.Pc(this.zf(a.lng), this.zf(b.lng), this.zf(a.lat), this.zf(b.lat))
		},
		Na: function(a) {
			var b, c;
			b = new K(p.abs(a.lng), p.abs(a.lat));
			for (var d = 0; d < this.Vp[w]; d++) if (b.lat >= this.Vp[d]) {
				c = this.Eu[d];
				break
			}
			a = this.Xr(a, c);
			return a = new K(a.lng.toFixed(6), a.lat.toFixed(6))
		},
		Va: function(a) {
			var b, c;
			a.lng = this.Bo(a.lng, -180, 180);
			a.lat = this.Go(a.lat, -74, 74);
			b = new K(a.lng, a.lat);
			for (var d = 0; d < this.Kj[w]; d++) if (b.lat >= this.Kj[d]) {
				c = this.Tp[d];
				break
			}
			if (!c) for (d = this.Kj[w] - 1; 0 <= d; d--) if (b.lat <= -this.Kj[d]) {
				c = this.Tp[d];
				break
			}
			a = this.Xr(a, c);
			return a = new K(a.lng.toFixed(2), a.lat.toFixed(2))
		},
		Xr: function(a, b) {
			if (a && b) {
				var c = b[0] + b[1] * p.abs(a.lng),
					d = p.abs(a.lat) / b[9],
					d = b[2] + b[3] * d + b[4] * d * d + b[5] * d * d * d + b[6] * d * d * d * d + b[7] * d * d * d * d * d + b[8] * d * d * d * d * d * d,
					c = c * (0 > a.lng ? -1 : 1),
					d = d * (0 > a.lat ? -1 : 1);
				return new K(c, d)
			}
		},
		Pc: function(a, b, c, d) {
			return this.Au * p.acos(p.sin(c) * p.sin(d) + p.cos(c) * p.cos(d) * p.cos(b - a))
		},
		zf: function(a) {
			return p.PI * a / 180
		},
		bD: function(a) {
			return 180 * a / p.PI
		},
		Go: function(a, b, c) {
			b != k && (a = p.max(a, b));
			c != k && (a = p.min(a, c));
			return a
		},
		Bo: function(a, b, c) {
			for (; a > c;) a -= c - b;
			for (; a < b;) a += c - b;
			return a
		}
	});
	C[x](T[u], {
		wg: function(a) {
			return T.Va(a)
		},
		ll: function(a) {
			a = T.Va(a);
			return new S(a.lng, a.lat)
		},
		qf: function(a) {
			return T.Na(a)
		},
		hp: function(a) {
			a = new K(a.x, a.y);
			return T.Na(a)
		},
		Za: function(a, b, c, d, e) {
			if (a) return a = this.wg(a, e), b = this.nb(b), new S(p.round((a.lng - c.lng) / b + d[y] / 2), p.round((c.lat - a.lat) / b + d[A] / 2))
		},
		La: function(a, b, c, d, e) {
			if (a) return b = this.nb(b), this.qf(new K(c.lng + b * (a.x - d[y] / 2), c.lat - b * (a.y - d[A] / 2)), e)
		},
		nb: function(a) {
			return p.pow(2, 18 - a)
		}
	});

	function Na() {
		this.Mn = "bj"
	}
	Na[u] = new T;
	C[x](Na[u], {
		wg: function(a, b) {
			return this.hv(b, T.Va(a))
		},
		qf: function(a, b) {
			return T.Na(this.iv(b, a))
		},
		lngLatToPointFor3D: function(a, b) {
			var c = this,
				d = T.Va(a);
			L.load("coordtrans", function() {
				var a = Cb.Eo(c.Mn || "bj", d),
					a = new S(a.x, a.y);
				b && b(a)
			}, g)
		},
		pointToLngLatFor3D: function(a, b) {
			var c = this,
				d = new K(a.x, a.y);
			L.load("coordtrans", function() {
				var a = Cb.Do(c.Mn || "bj", d),
					a = new K(a.lng, a.lat),
					a = T.Na(a);
				b && b(a)
			}, g)
		},
		hv: function(a, b) {
			if (L.Hi("coordtrans").Hc == L.Ye.Oh) {
				var c = Cb.Eo(a || "bj", b);
				return new K(c.x, c.y)
			}
			L.load("coordtrans", n());
			return new K(0, 0)
		},
		iv: function(a, b) {
			if (L.Hi("coordtrans").Hc == L.Ye.Oh) {
				var c = Cb.Do(a || "bj", b);
				return new K(c.lng, c.lat)
			}
			L.load("coordtrans", n());
			return new K(0, 0)
		},
		nb: function(a) {
			return p.pow(2, 20 - a)
		}
	});

	function Db() {
		this.$a = "overlay"
	}
	C.lang.da(Db, C.lang.ma, "Overlay");
	Db.$i = function(a) {
		a *= 1;
		return !a ? 0 : -1E5 * a << 1
	};
	C[x](Db[u], {
		Zc: function(a) {
			if (!this.F && Ga(this.initialize) && (this.F = this.initialize(a))) this.F.style.WebkitUserSelect = "none";
			this.draw()
		},
		initialize: function() {
			throw "initialize方法未实现";
		},
		draw: function() {
			throw "draw方法未实现";
		},
		remove: function() {
			if (this.F && this.F[ka]) this.F[ka][ga](this.F);
			this.F = k;
			this.dispatchEvent(new Q("onremove"))
		},
		H: function() {
			this.F && C.w.H(this.F)
		},
		show: function() {
			this.F && C.w.show(this.F)
		},
		Te: function() {
			return !this.F || "none" == this.F.style.display || "hidden" == this.F.style.visibility ? l : g
		}
	});
	H.Cc(function(a) {
		function b(a, b) {
			var c = M("div"),
				i = c.style;
			i[la] = "absolute";
			i.top = i.left = i[y] = i[A] = "0";
			i.zIndex = b;
			a[v](c);
			return c
		}
		var c = a.B;
		c.Vc = a.Vc = b(a.platform, 200);
		a.$b.ks = b(c.Vc, 800);
		a.$b.Yo = b(c.Vc, 700);
		a.$b.ls = b(c.Vc, 600);
		a.$b.$s = b(c.Vc, 500);
		a.$b.jt = b(c.Vc, 400);
		a.$b.kt = b(c.Vc, 300);
		a.$b.wB = b(c.Vc, 201);
		a.$b.nl = b(c.Vc, 200)
	});

	function Ma() {
		C.lang.ma.call(this);
		Db.call(this);
		this.map = k;
		this.ab = g;
		this.bb = k;
		this.rq = 0
	}
	C.lang.da(Ma, Db, "OverlayInternal");
	C[x](Ma[u], {
		initialize: function(a) {
			this.map = a;
			C.lang.ma.call(this, this.K);
			return k
		},
		Co: o("map"),
		draw: n(),
		remove: function() {
			this.map = k;
			C.lang.Qk(this.K);
			Db[u].remove.call(this)
		},
		H: function() {
			this.ab != l && (this.ab = l)
		},
		show: function() {
			this.ab != g && (this.ab = g)
		},
		Te: function() {
			return !this.F ? l : !! this.ab
		},
		Ed: o("F"),
		Ut: function(a) {
			var a = a || {},
				b;
			for (b in a) this.v[b] = a[b]
		},
		Hl: ba("zIndex"),
		lf: function() {
			this.v.lf = g
		},
		qy: function() {
			this.v.lf = l
		},
		fh: ba("Wg"),
		Dh: function() {
			this.Wg = k
		}
	});

	function Eb() {
		this.map = k;
		this.X = {};
		this.pc = []
	}
	H.Cc(function(a) {
		var b = new Eb;
		b.map = a;
		a.X = b.X;
		a.pc = b.pc;
		a[B]("load", function(a) {
			b.draw(a)
		});
		a[B]("moveend", function(a) {
			b.draw(a)
		});
		if (C.N.S && 8 > C.N.S || "BackCompat" == da.compatMode) a[B]("zoomend", function(a) {
			setTimeout(function() {
				b.draw(a)
			}, 20)
		});
		else a[B]("zoomend", function(a) {
			b.draw(a)
		});
		a[B]("maptypechange", function(a) {
			b.draw(a)
		});
		a[B]("addoverlay", function(a) {
			a = a.target;
			if (a instanceof Ma) b.X[a.K] || (b.X[a.K] = a);
			else {
				for (var d = l, e = 0, f = b.pc[w]; e < f; e++) if (b.pc[e] === a) {
					d = g;
					break
				}
				d || b.pc.push(a)
			}
		});
		a[B]("removeoverlay", function(a) {
			a = a.target;
			if (a instanceof Ma) delete b.X[a.K];
			else for (var d = 0, e = b.pc[w]; d < e; d++) if (b.pc[d] === a) {
				b.pc.splice(d, 1);
				break
			}
		});
		a[B]("clearoverlays", function() {
			this.Db();
			for (var a in b.X) b.X[a].v.lf && (b.X[a].remove(), delete b.X[a]);
			a = 0;
			for (var d = b.pc[w]; a < d; a++) b.pc[a].lf != l && (b.pc[a].remove(), b.pc[a] = k, b.pc.splice(a, 1), a--, d--)
		});
		a[B]("infowindowopen", function() {
			var a = this.bb;
			a && (C.w.H(a.jb), C.w.H(a.Sa))
		});
		a[B]("movestart", function() {
			this.Ne() && this.Ne().hx()
		});
		a[B]("moveend", function() {
			this.Ne() && this.Ne().Xw()
		})
	});
	Eb[u].draw = function() {
		for (var a in this.X) this.X[a].draw();
		C.Nb.Bd(this.pc, function(a) {
			a.draw()
		});
		this.map.B.ya && this.map.B.ya.ba();
		H.Hj && H.Hj.Ri(this.map).up()
	};

	function Fb(a) {
		Ma.call(this);
		a = a || {};
		this.v = {
			strokeColor: a.strokeColor || "#3a6bdb",
			Qd: a.strokeWeight || 5,
			sd: a.strokeOpacity || 0.65,
			strokeStyle: a.strokeStyle || "solid",
			lf: a.enableMassClear === l ? l : g,
			nf: k,
			og: k,
			ed: a.enableEditing === g ? g : l,
			vt: 5,
			rB: l,
			Jc: a.enableClicking === l ? l : g
		};
		0 >= this.v.Qd && (this.v.Qd = 5);
		if (0 > this.v.sd || 1 < this.v.sd) this.v.sd = 0.65;
		if (0 > this.v.hg || 1 < this.v.hg) this.v.hg = 0.65;
		"solid" != this.v.strokeStyle && "dashed" != this.v.strokeStyle && (this.v.strokeStyle = "solid");
		this.F = k;
		this.cm = new La(0, 0);
		this.Gc = [];
		this.Ta = [];
		this.ea = {}
	}
	C.lang.da(Fb, Ma, "Graph");
	Fb.Zk = function(a) {
		var b = [];
		if (!a) return b;
		Ha(a) && C.Nb.Bd(a.split(";"), function(a) {
			a = a.split(",");
			b.push(new K(a[0], a[1]))
		});
		"[object Array]" == Object[u].toString.apply(a) && 0 < a[w] && (b = a);
		return b
	};
	Fb.ep = [0.09, 0.005, 1.0E-4, 1.0E-5];
	C[x](Fb[u], {
		initialize: function(a) {
			this.map = a;
			return k
		},
		draw: n(),
		wi: function(a) {
			this.Gc[w] = 0;
			this.Q = Fb.Zk(a).slice(0);
			this.Sd()
		},
		lc: function(a) {
			this.wi(a)
		},
		Sd: function() {
			if (this.Q) {
				var a = this;
				a.cm = new La;
				C.Nb.Bd(this.Q, function(b) {
					a.cm[x](b)
				})
			}
		},
		cc: o("Q"),
		Hg: function(a, b) {
			b && this.Q[a] && (this.Gc[w] = 0, this.Q[a] = new K(b.lng, b.lat), this.Sd())
		},
		setStrokeColor: function(a) {
			this.v.strokeColor = a
		},
		mz: function() {
			return this.v.strokeColor
		},
		sj: function(a) {
			0 < a && (this.v.Qd = a)
		},
		Gs: function() {
			return this.v.Qd
		},
		qj: function(a) {
			a == aa || (1 < a || 0 > a) || (this.v.sd = a)
		},
		nz: function() {
			return this.v.sd
		},
		Dl: function(a) {
			1 < a || 0 > a || (this.v.hg = a)
		},
		Vy: function() {
			return this.v.hg
		},
		rj: function(a) {
			"solid" != a && "dashed" != a || (this.v.strokeStyle = a)
		},
		Fs: function() {
			return this.v.strokeStyle
		},
		setFillColor: function(a) {
			this.v.fillColor = a || ""
		},
		Uy: function() {
			return this.v.fillColor
		},
		mf: o("cm"),
		remove: function() {
			this.map && this.map.removeEventListener("onmousemove", this.bk);
			Ma[u].remove.call(this);
			this.Gc[w] = 0
		},
		ed: function() {
			if (!(2 > this.Q[w])) {
				this.v.ed = g;
				var a = this;
				L.load("poly", function() {
					a.Yf()
				}, g)
			}
		},
		py: function() {
			this.v.ed = l;
			var a = this;
			L.load("poly", function() {
				a.hf()
			}, g)
		}
	});

	function Gb(a) {
		Ma.call(this);
		this.F = this.map = k;
		this.v = {
			width: 0,
			height: 0,
			Y: new P(0, 0),
			opacity: 1,
			background: "transparent",
			kl: 1,
			bt: "#000",
			Zz: "solid",
			point: k
		};
		this.Ut(a);
		this.point = this.v.point
	}
	C.lang.da(Gb, Ma, "Division");
	C[x](Gb[u], {
		Rh: function() {
			var a = this.v,
				b = this.content,
				c = ['<div class="BMap_Division" style="position:absolute;'];
			c.push("width:" + a[y] + "px;display:block;");
			c.push("overflow:hidden;");
			"none" != a.borderColor && c.push("border:" + a.kl + "px " + a.Zz + " " + a.bt + ";");
			c.push("opacity:" + a.opacity + "; filter:(opacity=" + 100 * a.opacity + ")");
			c.push("background:" + a.background + ";");
			c.push('z-index:60;">');
			c.push(b);
			c.push("</div>");
			this.F = Za(this.map.fe().Yo, c.join(""))
		},
		initialize: function(a) {
			this.map = a;
			this.Rh();
			this.F && C.C(this.F, Da() ? "touchstart" : "mousedown", function(a) {
				pa(a)
			});
			return this.F
		},
		draw: function() {
			var a = this.map.Od(this.v.point);
			this.v.Y = new P(-p.round(this.v[y] / 2) - p.round(this.v.kl), -p.round(this.v[A] / 2) - p.round(this.v.kl));
			this.F.style.left = a.x + this.v.Y[y] + "px";
			this.F.style.top = a.y + this.v.Y[A] + "px"
		},
		T: function() {
			return this.v.point
		},
		QB: function() {
			return this.map.Za(this.T())
		},
		ba: function(a) {
			this.v.point = a;
			this.draw()
		},
		GA: function(a, b) {
			this.v[y] = p.round(a);
			this.v[A] = p.round(b);
			this.F && (this.F.style[y] = this.v[y] + "px", this.F.style[A] = this.v[A] + "px", this.draw())
		}
	});

	function Ib(a, b, c) {
		a && b && (this.imageUrl = a, this.size = b, a = new P(p.floor(b[y] / 2), p.floor(b[A] / 2)), c = c || {}, a = c.anchor || a, b = c.imageOffset || new P(0, 0), this.imageSize = c.imageSize, this.anchor = a, this.imageOffset = b, this.infoWindowAnchor = c.infoWindowAnchor || this.anchor, this.printImageUrl = c.printImageUrl || "")
	}
	C[x](Ib[u], {
		LA: function(a) {
			a && (this.imageUrl = a)
		},
		SA: function(a) {
			a && (this.printImageUrl = a)
		},
		rd: function(a) {
			a && (this.size = new P(a[y], a[A]))
		},
		vb: function(a) {
			a && (this.anchor = new P(a[y], a[A]))
		},
		nj: function(a) {
			a && (this.imageOffset = new P(a[y], a[A]))
		},
		MA: function(a) {
			a && (this.infoWindowAnchor = new P(a[y], a[A]))
		},
		KA: function(a) {
			a && (this.imageSize = new P(a[y], a[A]))
		},
		toString: ca("Icon")
	});

	function Jb(a, b) {
		C.lang.ma.call(this);
		this.content = a;
		this.map = k;
		b = b || {};
		this.v = {
			width: b.width || 0,
			height: b.height || 0,
			maxWidth: b.maxWidth || 600,
			Y: b.offset || new P(0, 0),
			title: b.title || "",
			Zo: b.maxContent || "",
			Cd: b.enableMaximize || l,
			Mi: b.enableAutoPan === l ? l : g,
			$n: b.enableCloseOnClick === l ? l : g,
			margin: [10, 10, 40, 10],
			Jk: [
				[10, 10],
				[10, 10],
				[10, 10],
				[10, 10]
			],
			Jz: l,
			HC: ca(g),
			co: b.enableMessage === l ? l : g,
			message: b.message,
			ho: b.enableSearchTool === g ? g : l
		};
		0 != this.v[y] && (220 > this.v[y] && (this.v[y] = 220), 730 < this.v[y] && (this.v[y] = 730));
		0 != this.v[A] && (60 > this.v[A] && (this.v[A] = 60), 650 < this.v[A] && (this.v[A] = 650));
		if (0 != this.v.maxWidth && (220 > this.v.maxWidth && (this.v.maxWidth = 220), 730 < this.v.maxWidth)) this.v.maxWidth = 730;
		this.Qb = l;
		this.ue = I.W;
		this.xa = k;
		var c = this;
		L.load("infowindow", function() {
			c.Mb()
		})
	}
	C.lang.da(Jb, C.lang.ma, "InfoWindow");
	C[x](Jb[u], {
		setWidth: function(a) {
			!a && 0 != a || (isNaN(a) || 0 > a) || (0 != a && (220 > a && (a = 220), 730 < a && (a = 730)), this.v[y] = a)
		},
		setHeight: function(a) {
			!a && 0 != a || (isNaN(a) || 0 > a) || (0 != a && (60 > a && (a = 60), 650 < a && (a = 650)), this.v[A] = a)
		},
		Wt: function(a) {
			!a && 0 != a || (isNaN(a) || 0 > a) || (0 != a && (220 > a && (a = 220), 730 < a && (a = 730)), this.v.maxWidth = a)
		},
		wb: function(a) {
			this.v.title = a
		},
		getTitle: function() {
			return this.v.title
		},
		Yb: ba("content"),
		ts: o("content"),
		oj: function(a) {
			this.v.Zo = a + ""
		},
		Wb: n(),
		Mi: function() {
			this.v.Mi = g
		},
		disableAutoPan: function() {
			this.v.Mi = l
		},
		enableCloseOnClick: function() {
			this.v.$n = g
		},
		disableCloseOnClick: function() {
			this.v.$n = l
		},
		Cd: function() {
			this.v.Cd = g
		},
		Sk: function() {
			this.v.Cd = l
		},
		show: function() {
			this.ab = g
		},
		H: function() {
			this.ab = l
		},
		close: function() {
			this.H()
		},
		ol: function() {
			this.Qb = g
		},
		restore: function() {
			this.Qb = l
		},
		Te: function() {
			return this.pa()
		},
		pa: ca(l),
		T: function() {
			if (this.xa && this.xa.T) return this.xa.T()
		},
		Oe: function() {
			return this.v.Y
		}
	});
	wa[u].Ub = function(a, b) {
		if (a instanceof Jb && b instanceof K) {
			var c = this.B;
			c.xg ? c.xg.ba(b) : (c.xg = new U(b, {
				icon: new Ib(I.W + "blank.gif", {
					width: 1,
					height: 1
				}),
				offset: new P(0, 0),
				clickable: l
			}), c.xg.Ev = 1);
			this.Ca(c.xg);
			c.xg.Ub(a)
		}
	};
	wa[u].Db = function() {
		var a = this.B.ya || this.B.Ff;
		a && a.xa && a.xa.Db()
	};
	Ma[u].Ub = function(a) {
		this.map && (this.map.Db(), a.ab = g, this.map.B.Ff = a, a.xa = this, C.lang.ma.call(a, a.K))
	};
	Ma[u].Db = function() {
		this.map && this.map.B.Ff && (this.map.B.Ff.ab = l, C.lang.Qk(this.map.B.Ff.K), this.map.B.Ff = k)
	};

	function Kb(a, b) {
		Ma.call(this);
		this.content = a;
		this.F = this.map = k;
		b = b || {};
		this.v = {
			width: 0,
			Y: b.offset || new P(0, 0),
			Kh: {
				backgroundColor: "#fff",
				border: "1px solid #f00",
				padding: "1px",
				whiteSpace: "nowrap",
				font: "12px " + I.fontFamily,
				zIndex: "80",
				MozUserSelect: "none"
			},
			position: b.position || k,
			lf: b.enableMassClear === l ? l : g,
			Jc: g
		};
		0 > this.v[y] && (this.v[y] = 0);
		eb(b.enableClicking) && (this.v.Jc = b.enableClicking);
		this.point = this.v[la];
		var c = this;
		L.load("marker", function() {
			c.Mb()
		})
	}
	C.lang.da(Kb, Ma, "Label");
	C[x](Kb[u], {
		T: function() {
			return this.hk ? this.hk.T() : this.point
		},
		ba: function(a) {
			a instanceof K && !this.al() && (this.point = this.v[la] = new K(a.lng, a.lat))
		},
		Yb: ba("content"),
		OA: function(a) {
			0 <= a && 1 >= a && (this.v.opacity = a)
		},
		kc: function(a) {
			a instanceof P && (this.v.Y = new P(a[y], a[A]))
		},
		Oe: function() {
			return this.v.Y
		},
		Ib: function(a) {
			a = a || {};
			this.v.Kh = C[x](this.v.Kh, a)
		},
		vf: function(a) {
			return this.Ib(a)
		},
		wb: function(a) {
			this.v.title = a || ""
		},
		getTitle: function() {
			return this.v.title
		},
		Vt: function(a) {
			this.point = (this.hk = a) ? this.v[la] = a.T() : this.v[la] = k
		},
		al: function() {
			return this.hk || k
		}
	});
	var Lb = new Ib(I.W + "marker_red_sprite.png", new P(19, 25), {
		anchor: new P(10, 25),
		infoWindowAnchor: new P(10, 0)
	}),
		Mb = new Ib(I.W + "marker_red_sprite.png", new P(20, 11), {
			anchor: new P(6, 11),
			imageOffset: new P(-19, -13)
		});

	function U(a, b) {
		Ma.call(this);
		b = b || {};
		this.point = a;
		this.Th = this.map = k;
		this.v = {
			Y: b.offset || new P(0, 0),
			Id: b.icon || Lb,
			wf: Mb,
			title: b.title || "",
			label: k,
			Hr: b.baseZIndex || 0,
			Jc: g,
			iD: l,
			Vo: l,
			lf: b.enableMassClear === l ? l : g,
			mb: l,
			Ot: b.raiseOnDrag === g ? g : l,
			Rt: l,
			zc: b.draggingCursor || I.zc
		};
		b.icon && !b.shadow && (this.v.wf = k);
		b.enableDragging && (this.v.mb = b.enableDragging);
		eb(b.enableClicking) && (this.v.Jc = b.enableClicking);
		var c = this;
		L.load("marker", function() {
			c.Mb()
		})
	}
	U.Mj = Db.$i(-90) + 1E6;
	U.Rp = U.Mj + 1E6;
	C.lang.da(U, Ma, "Marker");
	C[x](U[u], {
		se: function(a) {
			a instanceof Ib && (this.v.Id = a)
		},
		As: function() {
			return this.v.Id
		},
		Gl: function(a) {
			a instanceof Ib && (this.v.wf = a)
		},
		getShadow: function() {
			return this.v.wf
		},
		Fg: function(a) {
			this.v.label = a || k
		},
		Bs: function() {
			return this.v.label
		},
		mb: function() {
			this.v.mb = g
		},
		Tn: function() {
			this.v.mb = l
		},
		T: o("point"),
		ba: function(a) {
			a instanceof K && (this.point = new K(a.lng, a.lat))
		},
		Hh: function(a, b) {
			this.v.Vo = !! a;
			a && (this.bq = b || 0)
		},
		wb: function(a) {
			this.v.title = a + ""
		},
		getTitle: function() {
			return this.v.title
		},
		kc: function(a) {
			a instanceof P && (this.v.Y = a)
		},
		Oe: function() {
			return this.v.Y
		},
		Eg: ba("Th")
	});

	function Nb(a, b) {
		Fb.call(this, b);
		b = b || {};
		this.v.hg = b.fillOpacity ? b.fillOpacity : 0.65;
		this.v.fillColor = "" == b.fillColor ? "" : b.fillColor ? b.fillColor : "#fff";
		this.lc(a);
		var c = this;
		L.load("poly", function() {
			c.Mb()
		})
	}
	C.lang.da(Nb, Fb, "Polygon");
	C[x](Nb[u], {
		lc: function(a, b) {
			this.eh = Fb.Zk(a).slice(0);
			var c = Fb.Zk(a).slice(0);
			1 < c[w] && c.push(new K(c[0].lng, c[0].lat));
			Fb[u].lc.call(this, c, b)
		},
		Hg: function(a, b) {
			this.eh[a] && (this.eh[a] = new K(b.lng, b.lat), this.Q[a] = new K(b.lng, b.lat), 0 == a && !this.Q[0].Ob(this.Q[this.Q[w] - 1]) && (this.Q[this.Q[w] - 1] = new K(b.lng, b.lat)), this.Sd())
		},
		cc: function() {
			var a = this.eh;
			0 == a[w] && (a = this.Q);
			return a
		}
	});

	function Ob(a, b) {
		Fb.call(this, b);
		this.wi(a);
		var c = this;
		L.load("poly", function() {
			c.Mb()
		})
	}
	C.lang.da(Ob, Fb, "Polyline");

	function Pb(a, b, c) {
		this.point = a;
		this.ta = p.abs(b);
		Nb.call(this, [], c)
	}
	Pb.ep = [0.01, 1.0E-4, 1.0E-5, 4.0E-6];
	C.lang.da(Pb, Nb, "Circle");
	C[x](Pb[u], {
		initialize: function(a) {
			this.map = a;
			this.Q = this.Zj(this.point, this.ta);
			this.Sd();
			return k
		},
		Ga: o("point"),
		qd: function(a) {
			a && (this.point = a)
		},
		ez: o("ta"),
		Fl: function(a) {
			this.ta = p.abs(a)
		},
		Zj: function(a, b) {
			if (!a || !b || !this.map) return [];
			for (var c = [], d = b / 6378800, e = p.PI / 180 * a.lat, f = p.PI / 180 * a.lng, i = 0; 360 > i; i += 9) {
				var j = p.PI / 180 * i,
					m = p.asin(p.sin(e) * p.cos(d) + p.cos(e) * p.sin(d) * p.cos(j)),
					j = new K(((f - p.atan2(p.sin(j) * p.sin(d) * p.cos(e), p.cos(d) - p.sin(e) * p.sin(m)) + p.PI) % (2 * p.PI) - p.PI) * (180 / p.PI), m * (180 / p.PI));
				c.push(j)
			}
			d = c[0];
			c.push(new K(d.lng, d.lat));
			return c
		}
	});
	var Qb = {};

	function Rb(a) {
		this.map = a;
		this.yh = [];
		this.fd = [];
		this.Rx = 300;
		this.ip = 0;
		this.Ld = {};
		this.$f = {};
		this.jj = 0;
		this.Ug = this.pq(1);
		this.pi = this.pq(2);
		a.platform[v](this.Ug);
		a.platform[v](this.pi)
	}
	H.Cc(function(a) {
		(new Rb(a)).sa()
	});
	C[x](Rb[u], {
		sa: function() {
			var a = this,
				b = a.map;
			b[B]("loadcode", function() {
				a.ml()
			});
			b[B]("addtilelayer", function(b) {
				a.Ci(b)
			});
			b[B]("removetilelayer", function(b) {
				a.kj(b)
			});
			b[B]("setmaptype", function(b) {
				a.Ve(b)
			});
			b[B]("zoomstartcode", function(b) {
				a.vr(b)
			})
		},
		ml: function() {
			var a = this;
			if (C.N.S) try {
				da.execCommand("BackgroundImageCache", l, g)
			} catch (b) {}
			this.loaded || a.gl();
			a.me();
			this.loaded || (this.loaded = g, L.load("tile", function() {
				a.Su()
			}))
		},
		gl: function() {
			for (var a = this.map.aa().ni, b = 0; b < a[w]; b++) {
				var c = new Sb;
				C[x](c, a[b]);
				this.yh.push(c);
				c.sa(this.map, this.Ug)
			}
		},
		pq: function(a) {
			var b = M("div");
			b.style[la] = "absolute";
			b.style.overflow = "visible";
			b.style.left = b.style.top = "0";
			b.style.zIndex = a;
			return b
		},
		XA: function(a, b, c) {
			var d = this;
			d.bC = b;
			var e = this.map.aa(),
				f = d.Hs(a, c),
				i = e.u.We,
				j = a[0] * i + b[0],
				m = 0;
			e === ya && 15 == d.map.fa() && (m = 0.5);
			b = [j, (m - 1 - a[1]) * i + b[1]];
			(i = this.Ld[f]) && i.Ha ? (Va(i.Ha, b), i.loaded ? this.Xh() : i.Zl(function() {
				d.Xh()
			})) : (i = this.$f[f]) && i.Ha ? (c.kb.insertBefore(i.Ha, c.kb.lastChild), this.Ld[f] = i, Va(i.Ha, b), i.loaded ? this.Xh() : i.Zl(function() {
				d.Xh()
			})) : (e = 256 * p.pow(2, e.ng() - a[2]), new K(a[0] * e, a[1] * e), e = c.getTilesUrl(new S(a[0], a[1]), a[2]), i = new Tb(this, e, b, a, c), i.Zl(function() {
				d.Xh()
			}), i.qw(), this.Ld[f] = i)
		},
		Xh: function() {
			this.jj--;
			var a = this;
			0 == this.jj && (this.Rj && (clearTimeout(this.Rj), this.Rj = k), this.Rj = setTimeout(function() {
				if (a.jj == 0) {
					a.map.dispatchEvent(new Q("ontilesloaded"));
					if (va) {
						if (sa && ta && ua) {
							var b = Ia(),
								c = a.map.Pb();
							setTimeout(function() {
								za(5030, {
									load_script_time: ta - sa,
									load_tiles_time: b - ua,
									map_width: c[y],
									map_height: c[A],
									map_size: c[y] * c[A]
								})
							}, 1E4)
						}
						va = l
					}
				}
				a.Rj = k
			}, 80))
		},
		Hs: function(a, b) {
			return this.map.aa() === ya ? "TILE-" + b.K + "-" + this.map.Dn + "-" + a[0] + "-" + a[1] + "-" + a[2] : "TILE-" + b.K + "-" + a[0] + "-" + a[1] + "-" + a[2]
		},
		No: function(a) {
			var b = a.Ha;
			if (b && (Ub(b), Xa(b))) b[ka][ga](b);
			delete this.Ld[a.name];
			a.loaded || (Ub(b), a.dm(), a.Ha = k, a.zh = k)
		},
		me: function() {
			var a = this;
			a.map.aa() == ya ? L.load("coordtrans", function() {
				a.Qq()
			}, g) : a.Qq()
		},
		Qq: function() {
			for (var a = this.yh.concat(this.fd), b = a[w], c = 0; c < b; c++) {
				var d = a[c];
				if (d.Gb && e.ua < d.Gb) break;
				d.Ek && (this.kb = d.kb);
				var e = this.map,
					f = e.aa(),
					i = f.th(),
					j = e.ua,
					m = e.Fb;
				f == ya && m.Ob(new K(0, 0)) && (m = e.Fb = i.wg(e.Ad, e.hb));
				var q = f.nb(j),
					j = f.zz(j),
					i = p.ceil(m.lng / j),
					s = p.ceil(m.lat / j),
					z = f.u.We,
					j = [i, s, (m.lng - i * j) / j * z, (m.lat - s * j) / j * z],
					E = j[0] - p.ceil((e[y] / 2 - j[2]) / z),
					i = j[1] - p.ceil((e[A] / 2 - j[3]) / z),
					s = j[0] + p.ceil((e[y] / 2 + j[2]) / z),
					F = 0;
				f === ya && 15 == e.fa() && (F = 1);
				f = j[1] + p.ceil((e[A] / 2 + j[3]) / z) + F;
				this.Fr = new K(m.lng, m.lat);
				var F = this.Ld,
					z = -this.Fr.lng / q,
					J = this.Fr.lat / q,
					q = [p.ceil(z), p.ceil(J)],
					m = e.fa(),
					G;
				for (G in F) {
					var ha = F[G],
						D = ha.info;
					(D[2] != m || D[2] == m && (E > D[0] || s <= D[0] || i > D[1] || f <= D[1])) && this.No(ha)
				}
				F = -e[ja] + e[A] / 2;
				d.kb.style.left = p.ceil(z + (-e[ia] + e[y] / 2)) - q[0] + "px";
				d.kb.style.top = p.ceil(J + F) - q[1] + "px";
				z = [];
				for (e.qn = []; E < s; E++) for (F = i; F < f; F++) z.push([E, F]), e.qn.push({
					x: E,
					y: F
				});
				z.sort(function(a) {
					return function(b, c) {
						return 0.4 * p.abs(b[0] - a[0]) + 0.6 * p.abs(b[1] - a[1]) - (0.4 * p.abs(c[0] - a[0]) + 0.6 * p.abs(c[1] - a[1]))
					}
				}([j[0] - 1, j[1] - 1]));
				this.jj += z[w];
				E = 0;
				for (j = z[w]; E < j; E++) this.XA([z[E][0], z[E][1], m], q, d)
			}
		},
		Ci: function(a) {
			for (var a = a.target, b = 0; b < this.fd[w]; b++) if (this.fd[b] == a) return;
			a.sa(this.map, this.pi);
			this.fd.push(a)
		},
		kj: function(a) {
			for (var a = a.target, b = 0, c = this.fd[w]; b < c; b++) a == this.fd[b] && this.fd.splice(b, 1);
			a.remove()
		},
		Ve: function() {
			for (var a = this.yh, b = 0, c = a[w]; b < c; b++) a[b].remove();
			delete this.kb;
			this.yh = [];
			this.$f = this.Ld = {};
			this.gl();
			this.me()
		},
		vr: function() {
			var a = this;
			a.zb && C.w.H(a.zb);
			setTimeout(function() {
				a.me();
				a.map.dispatchEvent(new Q("onzoomend"))
			}, 10)
		}
	});

	function Tb(a, b, c, d, e) {
		this.zh = a;
		this[la] = c;
		this.Pj = [];
		this.name = a.Hs(d, e);
		this.info = d;
		this.rr = e.il();
		d = M("img");
		Wa(d);
		d.ns = l;
		var f = d.style,
			a = a.map.aa();
		f[la] = "absolute";
		f.border = "none";
		f[y] = a.u.We + "px";
		f[A] = a.u.We + "px";
		f.left = c[0] + "px";
		f.top = c[1] + "px";
		f.maxWidth = "none";
		this.Ha = d;
		this.src = b;
		Vb && (this.Ha.style.opacity = 0);
		var i = this;
		this.Ha.onload = function() {
			i.loaded = g;
			if (i.zh) {
				var a = i.zh,
					b = a.$f;
				if (!b[i.name]) {
					a.ip++;
					b[i.name] = i
				}
				if (i.Ha && !Xa(i.Ha) && e.kb) {
					e.kb[v](i.Ha);
					if (C.N.S <= 6 && C.N.S > 0 && i.rr) i.Ha.style.cssText = i.Ha.style.cssText + (';filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src="' + i.src + '",sizingMethod=scale);')
				}
				var c = a.ip - a.Rx,
					d;
				for (d in b) {
					if (c <= 0) break;
					if (!a.Ld[d]) {
						b[d].zh = k;
						var f = b[d].Ha;
						if (f && f[ka]) {
							f[ka][ga](f);
							Ub(f)
						}
						f = k;
						b[d].Ha = k;
						delete b[d];
						a.ip--;
						c--
					}
				}
				Vb && new Ta({
					Oc: 20,
					duration: 200,
					Ra: function(a) {
						if (i.Ha && i.Ha.style) i.Ha.style.opacity = a * 1
					},
					finish: function() {
						i.Ha && i.Ha.style && delete i.Ha.style.opacity
					}
				});
				i.dm()
			}
		};
		this.Ha.onerror = function() {
			i.dm();
			if (i.zh) {
				var a = i.zh.map.aa();
				if (a.u.io) {
					i.error = g;
					i.Ha.src = a.u.io;
					if (i.Ha && !Xa(i.Ha)) e.kb[v](i.Ha)
				}
			}
		};
		d = k
	}
	Tb[u].Zl = function(a) {
		this.Pj.push(a)
	};
	Tb[u].qw = function() {
		this.Ha.src = 0 < C.N.S && 6 >= C.N.S && this.rr ? I.W + "blank.gif" : this.src
	};
	Tb[u].dm = function() {
		for (var a = 0; a < this.Pj[w]; a++) this.Pj[a]();
		this.Pj[w] = 0
	};

	function Ub(a) {
		if (a) {
			a.onload = a.onerror = k;
			var b = a.attributes,
				c, d, e;
			if (b) {
				d = b[w];
				for (c = 0; c < d; c += 1) e = b[c].name, Ga(a[e]) && (a[e] = k)
			}
			if (b = a.children) {
				d = b[w];
				for (c = 0; c < d; c += 1) Ub(a.children[c])
			}
		}
	}
	var Vb = !C.N.S || 8 < C.N.S;

	function Sb(a) {
		this.Bh = a || {};
		this.fy = this.Bh.copyright || k;
		this.qB = this.Bh.transparentPng || l;
		this.Ek = this.Bh.baseLayer || l;
		this.zIndex = this.Bh.zIndex || 0;
		this.K = Sb.gw++
	}
	Sb.gw = 0;
	C.lang.da(Sb, C.lang.ma, "TileLayer");
	C[x](Sb[u], {
		sa: function(a, b) {
			this.Ek && (this.zIndex = -100);
			this.map = a;
			if (!this.kb) {
				var c = M("div"),
					d = c.style;
				d[la] = "absolute";
				d.overflow = "visible";
				d.zIndex = this.zIndex;
				d.left = p.ceil(-a[ia] + a[y] / 2) + "px";
				d.top = p.ceil(-a[ja] + a[A] / 2) + "px";
				b[v](c);
				this.kb = c
			}
			c = a.aa();
			a.he() && c == xa && (c.u.We = 128, d = function(a) {
				return p.pow(2, 18 - a) * 2
			}, c.nb = d, c.u.hc.nb = d)
		},
		remove: function() {
			this.kb && this.kb[ka] && (this.kb.innerHTML = "", this.kb[ka][ga](this.kb));
			delete this.kb
		},
		il: o("qB"),
		getTilesUrl: function(a, b) {
			var c = "";
			this.Bh.tileUrlTemplate && (c = this.Bh.tileUrlTemplate.replace(/\{X\}/, a.x), c = c.replace(/\{Y\}/, a.y), c = c.replace(/\{Z\}/, b));
			return c
		},
		kg: o("fy"),
		aa: function() {
			return this.cb || xa
		}
	});

	function Wb(a) {
		Sb.call(this, a);
		this.u = a || {};
		if (this.u.predictDate) {
			if (1 > this.u.predictDate.weekday || 7 < this.u.predictDate.weekday) this.u.predictDate = 1;
			if (0 > this.u.predictDate.hour || 23 < this.u.predictDate.hour) this.u.predictDate.hour = 0
		}
		this.vx = "http://its.map.baidu.com:8002/traffic/"
	}
	Wb[u] = new Sb;
	Wb[u].sa = function(a, b) {
		Sb[u].sa.call(this, a, b);
		this.z = a
	};
	Wb[u].il = ca(g);
	Wb[u].getTilesUrl = function(a, b) {
		var c = "";
		this.u.predictDate ? c = "HistoryService?day=" + (this.u.predictDate.weekday - 1) + "&hour=" + this.u.predictDate.hour + "&t=" + (new Date).getTime() + "&" : (c = "TrafficTileService?time=" + (new Date).getTime() + "&", this.z.he() || (c += "label=web2D&v=016&"));
		return (this.vx + c + "level=" + b + "&x=" + a.x + "&y=" + a.y).replace(/-(\d+)/gi, "M$1")
	};

	function Xb(a, b) {
		Sb.call(this);
		var c = this;
		fb(a) ? b = a || {} : (c.$h = a, b = b || {});
		b.geotableId && (c.vd = b.geotableId);
		b.databoxId && (c.$h = b.databoxId);
		c.Fc = {
			Iz: "http://api.map.baidu.com/georender/gss/image",
			YC: "api.map.baidu.com/geosearch/render",
			Fz: "http://api.map.baidu.com/georender/gss/data",
			Gz: "http://api.map.baidu.com/geosearch/detail/",
			Hz: "http://api.map.baidu.com/geosearch/v2/detail/",
			Dr: b.age || 36E5,
			Nt: b.q || "",
			iB: "png",
			xC: [5, 5, 5, 5],
			Yz: {
				backgroundColor: "#FFFFD5",
				borderColor: "#808080"
			},
			yn: b.ak || ra,
			Bp: b.tags || "",
			filter: b.filter || "",
			yC: b.hotspotName || "tile_md_" + (1E5 * p.random()).toFixed(0)
		};
		L.load("clayer", function() {
			c.oc()
		})
	}
	Xb[u] = new Sb;
	Xb[u].sa = function(a, b) {
		Sb[u].sa.call(this, a, b);
		this.z = a
	};
	Xb[u].getTilesUrl = function(a, b) {
		var c = this.Fc,
			c = c.Iz + "?grids=" + a.x + "_" + a.y + "_" + b + "&q=" + c.Nt + "&tags=" + c.Bp + "&filter=" + c.filter + "&ak=" + this.Fc.yn + "&age=" + c.Dr + "&format=" + c.iB;
		this.vd ? c += "&geotable_id=" + this.vd : this.$h && (c += "&databox_id=" + this.$h);
		return c
	};
	Xb.Zw = /^point\(|\)$/ig;
	Xb.$w = /\s+/;
	Xb.bx = /^[\s﻿ ]+|[\s﻿ ]+$/g;

	function Yb(a, b, c) {
		this.yw = a;
		this.ni = b instanceof Sb ? [b] : b.slice(0);
		c = c || {};
		this.u = {
			jB: c.tips || "",
			Xo: "",
			Gb: c.minZoom || 3,
			dc: c.maxZoom || 19,
			Dz: c.minZoom || 3,
			Cz: c.maxZoom || 18,
			We: 256,
			hB: c.textColor || "black",
			io: c.errorImageUrl || "",
			hc: c.projection || new T
		};
		1 <= this.ni[w] && (this.ni[0].Ek = g);
		C[x](this.u, c)
	}
	C[x](Yb[u], {
		getName: o("yw"),
		Zi: function() {
			return this.u.jB
		},
		pC: function() {
			return this.u.Xo
		},
		rz: function() {
			return this.ni[0]
		},
		vC: o("ni"),
		tz: function() {
			return this.u.We
		},
		oh: function() {
			return this.u.Gb
		},
		ng: function() {
			return this.u.dc
		},
		Yi: function() {
			return this.u.hB
		},
		th: function() {
			return this.u.hc
		},
		mC: function() {
			return this.u.io
		},
		tz: function() {
			return this.u.We
		},
		nb: function(a) {
			return p.pow(2, 18 - a)
		},
		zz: function(a) {
			return this.nb(a) * this.u.We
		}
	});
	var Zb = ["http://shangetu0.map.bdimg.com/it/", "http://shangetu1.map.bdimg.com/it/", "http://shangetu2.map.bdimg.com/it/", "http://shangetu3.map.bdimg.com/it/", "http://shangetu4.map.bdimg.com/it/"],
		$b = ["http://online0.map.bdimg.com/tile/", "http://online1.map.bdimg.com/tile/", "http://online2.map.bdimg.com/tile/", "http://online3.map.bdimg.com/tile/", "http://online4.map.bdimg.com/tile/"],
		ac = new Sb;
	ac.getTilesUrl = function(a, b) {
		var c = a.x,
			d = a.y,
			e = "pl";
		this.map.he() && (e = "ph");
		return ($b[p.abs(c + d) % $b[w]] + "?qt=tile&x=" + (c + "").replace(/-/gi, "M") + "&y=" + (d + "").replace(/-/gi, "M") + "&z=" + b + "&styles=" + e + (6 == C.N.S ? "&color_dep=32&colors=50" : "") + "&udt=20140928").replace(/-(\d+)/gi, "M$1")
	};
	var xa = new Yb("地图", ac, {
		tips: "显示普通地图"
	}),
		bc = new Sb;
	bc.hu = ["http://d0.map.baidu.com/resource/mappic/", "http://d1.map.baidu.com/resource/mappic/", "http://d2.map.baidu.com/resource/mappic/", "http://d3.map.baidu.com/resource/mappic/"];
	bc.getTilesUrl = function(a, b) {
		var c = a.x,
			d = a.y,
			e = 256 * p.pow(2, 20 - b),
			d = p.round((9998336 - e * d) / e) - 1;
		return url = this.hu[p.abs(c + d) % this.hu[w]] + this.map.hb + "/" + this.map.Dn + "/3/lv" + (21 - b) + "/" + c + "," + d + ".jpg"
	};
	var ya = new Yb("三维", bc, {
		tips: "显示三维地图",
		minZoom: 15,
		maxZoom: 20,
		textColor: "white",
		projection: new Na
	});
	ya.nb = function(a) {
		return p.pow(2, 20 - a)
	};
	ya.lh = function(a) {
		if (!a) return "";
		var b = I.Gn,
			c;
		for (c in b) if (-1 < a.search(c)) return b[c].wl;
		return ""
	};
	ya.Qy = function(a) {
		return {
			bj: 2,
			gz: 1,
			sz: 14,
			sh: 4
		}[a]
	};
	var cc = new Sb({
		Ek: g
	});
	cc.getTilesUrl = function(a, b) {
		var c = a.x,
			d = a.y;
		return (Zb[p.abs(c + d) % Zb[w]] + "u=x=" + c + ";y=" + d + ";z=" + b + ";v=009;type=sate&fm=46&udt=20141015").replace(/-(\d+)/gi, "M$1")
	};
	var Aa = new Yb("卫星", cc, {
		tips: "显示卫星影像",
		minZoom: 1,
		maxZoom: 19,
		textColor: "white"
	}),
		dc = new Sb({
			transparentPng: g
		});
	dc.getTilesUrl = function(a, b) {
		var c = a.x,
			d = a.y;
		return ($b[p.abs(c + d) % $b[w]] + "?qt=tile&x=" + (c + "").replace(/-/gi, "M") + "&y=" + (d + "").replace(/-/gi, "M") + "&z=" + b + "&styles=sl" + (6 == C.N.S ? "&color_dep=32&colors=50" : "") + "&udt=20141015").replace(/-(\d+)/gi, "M$1")
	};
	var Ba = new Yb("混合", [cc, dc], {
		tips: "显示带有街道的卫星影像",
		labelText: "路网",
		minZoom: 1,
		maxZoom: 19,
		textColor: "white"
	});
	var ec = 1,
		V = {};
	window.CB = V;

	function W(a, b) {
		C.lang.ma.call(this);
		this.Bb = {};
		this.Gg(a);
		b = b || {};
		b.R = b.renderOptions || {};
		this.u = {
			R: {
				ka: b.R.panel || k,
				map: b.R.map || k,
				zd: b.R.autoViewport || g,
				lj: b.R.selectFirstResult,
				cj: b.R.highlightMode,
				mb: b.R.enableDragging || l
			},
			ul: b.onSearchComplete || n(),
			Dt: b.onMarkersSet || n(),
			Ct: b.onInfoHtmlSet || n(),
			Et: b.onResultsHtmlSet || n(),
			Bt: b.onGetBusListComplete || n(),
			At: b.onGetBusLineComplete || n(),
			zt: b.onBusListHtmlSet || n(),
			yt: b.onBusLineHtmlSet || n(),
			bp: b.onPolylinesSet || n(),
			Eh: b.reqFrom || ""
		};
		this.u.R.zd = "undefined" != typeof b && "undefined" != typeof b.renderOptions && "undefined" != typeof b.renderOptions.autoViewport ? b.renderOptions.autoViewport : g;
		this.u.R.ka = C.Kb(this.u.R.ka)
	}
	C.da(W, C.lang.ma);
	C[x](W[u], {
		getResults: function() {
			return this.gb ? this.ye : this.M
		},
		enableAutoViewport: function() {
			this.u.R.zd = g
		},
		disableAutoViewport: function() {
			this.u.R.zd = l
		},
		Gg: function(a) {
			a && (this.Bb.src = a)
		},
		vp: function(a) {
			this.u.ul = a || n()
		},
		setMarkersSetCallback: function(a) {
			this.u.Dt = a || n()
		},
		setPolylinesSetCallback: function(a) {
			this.u.bp = a || n()
		},
		setInfoHtmlSetCallback: function(a) {
			this.u.Ct = a || n()
		},
		setResultsHtmlSetCallback: function(a) {
			this.u.Et = a || n()
		},
		pg: o("Hc")
	});
	var fc = {
		Hu: "http://api.map.baidu.com/",
		Ea: function(a, b, c, d, e) {
			var f = (1E5 * p.random()).toFixed(0);
			H._rd["_cbk" + f] = function(b) {
				c = c || {};
				a && a(b, c);
				delete H._rd["_cbk" + f]
			};
			d = d || "";
			b = c && c.qu ? db(b, encodeURI) : db(b, encodeURIComponent);
			d = this.Hu + d + "?" + b + "&ie=utf-8&oue=1&fromproduct=jsapi";
			e || (d += "&res=api");
			lb(d + ("&callback=BMap._rd._cbk" + f))
		}
	};
	window.GB = fc;
	H._rd = {};
	var R = {};
	window.FB = R;
	R.Pt = function(a) {
		return a.replace(/<\/?b>/g, "")
	};
	R.mA = function(a) {
		return a.replace(/([1-9]\d*\.\d*|0\.\d*[1-9]\d*|0?\.0+|0|[1-9]\d*),([1-9]\d*\.\d*|0\.\d*[1-9]\d*|0?\.0+|0|[1-9]\d*)(,)/g, "$1,$2;")
	};
	R.nA = function(a, b) {
		var c = new ea("(((-?\\d+)(\\.\\d+)?),((-?\\d+)(\\.\\d+)?);)(((-?\\d+)(\\.\\d+)?),((-?\\d+)(\\.\\d+)?);){" + b + "}", "ig");
		return a.replace(c, "$1")
	};
	var gc = 2,
		hc = 3,
		ic = 0,
		jc = "bt",
		kc = "nav",
		lc = "walk",
		mc = "bl",
		nc = "bsl",
		oc = 14,
		pc = 15,
		qc = 18,
		rc = 20,
		sc = 31;
	H.I = window.Instance = C.lang.Jd;

	function Ja(a, b) {
		W.call(this, a, b);
		b = b || {};
		b.renderOptions = b.renderOptions || {};
		this.Gh(b.pageCapacity);
		"undefined" != typeof b.renderOptions.selectFirstResult && !b.renderOptions.selectFirstResult ? this.Un() : this.bo();
		this.X = [];
		this.Wc = [];
		this.va = -1;
		this.oa = [];
		var c = this;
		L.load("local", function() {
			c.gm()
		}, g)
	}
	C.da(Ja, W, "LocalSearch");
	Ja.Qh = 10;
	Ja.DB = 1;
	Ja.Ng = 100;
	Ja.Qp = 2E3;
	Ja.Up = 1E5;
	C[x](Ja[u], {
		search: function(a, b) {
			this.oa.push({
				method: "search",
				arguments: [a, b]
			})
		},
		Cg: function(a, b, c) {
			this.oa.push({
				method: "searchInBounds",
				arguments: [a, b, c]
			})
		},
		Fh: function(a, b, c, d) {
			this.oa.push({
				method: "searchNearby",
				arguments: [a, b, c, d]
			})
		},
		yc: function() {
			delete this.ca;
			delete this.Hc;
			delete this.M;
			delete this.P;
			this.va = -1;
			this.Aa();
			this.u.R.ka && (this.u.R.ka.innerHTML = "")
		},
		rg: n(),
		bo: function() {
			this.u.R.lj = g
		},
		Un: function() {
			this.u.R.lj = l
		},
		Gh: function(a) {
			this.u.sf = "number" == typeof a && !isNaN(a) ? 1 > a ? Ja.Qh : a > Ja.Ng ? Ja.Qh : a : Ja.Qh
		},
		Qc: function() {
			return this.u.sf
		},
		toString: ca("LocalSearch")
	});
	var tc = Ja[u];
	Z(tc, {
		clearResults: tc.yc,
		setPageCapacity: tc.Gh,
		getPageCapacity: tc.Qc,
		gotoPage: tc.rg,
		searchNearby: tc.Fh,
		searchInBounds: tc.Cg,
		search: tc.search,
		enableFirstResultSelection: tc.bo,
		disableFirstResultSelection: tc.Un
	});

	function uc(a, b) {
		W.call(this, a, b)
	}
	C.da(uc, W, "BaseRoute");
	C[x](uc[u], {
		yc: n()
	});

	function vc(a, b) {
		W.call(this, a, b);
		b = b || {};
		this.pj(b.policy);
		this.Gh(b.pageCapacity);
		this.ve = jc;
		this.Lj = oc;
		this.Xl = ec;
		this.X = [];
		this.va = -1;
		this.oa = [];
		var c = this;
		L.load("route", function() {
			c.oc()
		})
	}
	vc.Ng = 100;
	vc.Cu = [0, 1, 0, 0, 0, 0, 0, 0, 2, 0, 0, 0, 1, 1, 1];
	C.da(vc, uc, "TransitRoute");
	C[x](vc[u], {
		pj: function(a) {
			this.u.gc = 0 <= a && 4 >= a ? a : 0
		},
		mw: function(a, b) {
			this.oa.push({
				method: "_internalSearch",
				arguments: [a, b]
			})
		},
		search: function(a, b) {
			this.oa.push({
				method: "search",
				arguments: [a, b]
			})
		},
		Gh: function(a) {
			if ("string" == typeof a && (a = t(a), isNaN(a))) {
				this.u.sf = vc.Ng;
				return
			}
			this.u.sf = "number" != typeof a ? vc.Ng : 1 <= a && a <= vc.Ng ? p.round(a) : vc.Ng
		},
		toString: ca("TransitRoute"),
		lx: function(a) {
			return a.replace(/\(.*\)/, "")
		}
	});

	function wc(a, b) {
		W.call(this, a, b);
		this.X = [];
		this.va = -1;
		this.oa = [];
		var c = this,
			d = this.u.R;
		1 != d.cj && 2 != d.cj && (d.cj = 1);
		this.rm = this.u.R.mb ? g : l;
		L.load("route", function() {
			c.oc()
		});
		this.Po && this.Po()
	}
	wc.Ku = " 环岛 无属性道路 主路 高速连接路 交叉点内路段 连接道路 停车场内部道路 服务区内部道路 桥 步行街 辅路 匝道 全封闭道路 未定义交通区域 POI连接路 隧道 步行道 公交专用道 提前右转道".split(" ");
	C.da(wc, uc, "DWRoute");
	C[x](wc[u], {
		search: function(a, b, c) {
			this.oa.push({
				method: "search",
				arguments: [a, b, c]
			})
		}
	});

	function xc(a, b) {
		wc.call(this, a, b);
		b = b || {};
		this.pj(b.policy);
		this.ve = kc;
		this.Lj = rc;
		this.Xl = hc
	}
	C.da(xc, wc, "DrivingRoute");
	C[x](xc[u], {
		pj: function(a) {
			this.u.gc = 0 <= a && 2 >= a ? a : 0
		}
	});

	function yc(a, b) {
		wc.call(this, a, b);
		this.ve = lc;
		this.Lj = sc;
		this.Xl = gc;
		this.rm = l
	}
	C.da(yc, wc, "WalkingRoute");

	function zc(a) {
		this.u = {};
		C[x](this.u, a);
		this.oa = [];
		var b = this;
		L.load("othersearch", function() {
			b.oc()
		})
	}
	C.da(zc, C.lang.ma, "Geocoder");
	C[x](zc[u], {
		Fo: function(a, b, c) {
			this.oa.push({
				method: "getPoint",
				arguments: [a, b, c]
			})
		},
		Ui: function(a, b, c) {
			this.oa.push({
				method: "getLocation",
				arguments: [a, b, c]
			})
		},
		toString: ca("Geocoder")
	});
	var Ac = zc[u];
	Z(Ac, {
		getPoint: Ac.Fo,
		getLocation: Ac.Ui
	});

	function Geolocation(a) {
		a = a || {};
		this.G = {
			timeout: a.timeout || 1E4,
			maximumAge: a.maximumAge || 6E5
		};
		this.Yg = [];
		var b = this;
		L.load("othersearch", function() {
			for (var a = 0, d; d = b.Yg[a]; a++) b[d.method].apply(b, d.arguments)
		})
	}
	C[x](Geolocation[u], {
		getCurrentPosition: function(a, b) {
			this.Yg.push({
				method: "getCurrentPosition",
				arguments: arguments
			})
		},
		getStatus: ca(2)
	});

	function Bc(a) {
		a = a || {};
		a.R = a.renderOptions || {};
		this.u = {
			R: {
				map: a.R.map || k
			}
		};
		this.oa = [];
		var b = this;
		L.load("othersearch", function() {
			b.oc()
		})
	}
	C.da(Bc, C.lang.ma, "LocalCity");
	C[x](Bc[u], {
		get: function(a) {
			this.oa.push({
				method: "get",
				arguments: [a]
			})
		},
		toString: ca("LocalCity")
	});

	function Cc() {
		this.oa = [];
		var a = this;
		L.load("othersearch", function() {
			a.oc()
		})
	}
	C.da(Cc, C.lang.ma, "Boundary");
	C[x](Cc[u], {
		get: function(a, b) {
			this.oa.push({
				method: "get",
				arguments: [a, b]
			})
		},
		toString: ca("Boundary")
	});

	function Dc(a, b) {
		W.call(this, a, b);
		this.Gu = mc;
		this.Ju = pc;
		this.Fu = nc;
		this.Iu = qc;
		this.oa = [];
		var c = this;
		L.load("buslinesearch", function() {
			c.oc()
		})
	}
	Dc.dk = I.W + "iw_plus.gif";
	Dc.jw = I.W + "iw_minus.gif";
	Dc.rx = I.W + "stop_icon.png";
	C.da(Dc, W);
	C[x](Dc[u], {
		getBusList: function(a) {
			this.oa.push({
				method: "getBusList",
				arguments: [a]
			})
		},
		getBusLine: function(a) {
			this.oa.push({
				method: "getBusLine",
				arguments: [a]
			})
		},
		setGetBusListCompleteCallback: function(a) {
			this.u.Bt = a || n()
		},
		setGetBusLineCompleteCallback: function(a) {
			this.u.At = a || n()
		},
		setBusListHtmlSetCallback: function(a) {
			this.u.zt = a || n()
		},
		setBusLineHtmlSetCallback: function(a) {
			this.u.yt = a || n()
		},
		setPolylinesSetCallback: function(a) {
			this.u.bp = a || n()
		}
	});

	function Ec(a) {
		W.call(this, a);
		a = a || {};
		this.Fc = {
			input: a.input || k,
			zn: a.baseDom || k,
			types: a.types || [],
			ul: a.onSearchComplete || n()
		};
		this.Bb.src = a.location || "全国";
		this.Ge = "";
		this.ld = k;
		this.Mq = "";
		this.Fm();
		za(5011);
		var b = this;
		L.load("autocomplete", function() {
			b.oc()
		})
	}
	C.da(Ec, W, "Autocomplete");
	C[x](Ec[u], {
		Fm: n(),
		show: n(),
		H: n(),
		wp: function(a) {
			this.Fc.types = a
		},
		Gg: function(a) {
			this.Bb.src = a
		},
		search: ba("Ge"),
		El: ba("Mq")
	});
	var Ca;
	H.Map = wa;
	H.Hotspot = Oa;
	H.MapType = Yb;
	H.Point = K;
	H.Pixel = S;
	H.Size = P;
	H.Bounds = La;
	H.TileLayer = Sb;
	H.Projection = Bb;
	H.MercatorProjection = T;
	H.PerspectiveProjection = Na;
	H.Copyright = function(a, b, c) {
		this.id = a;
		this.Ua = b;
		this.content = c
	};
	H.Overlay = Db;
	H.Label = Kb;
	H.Marker = U;
	H.Icon = Ib;
	H.Polyline = Ob;
	H.Polygon = Nb;
	H.InfoWindow = Jb;
	H.Circle = Pb;
	H.Control = nb;
	H.NavigationControl = rb;
	H.GeolocationControl = tb;
	H.OverviewMapControl = vb;
	H.CopyrightControl = ub;
	H.ScaleControl = wb;
	H.MapTypeControl = yb;
	H.TrafficLayer = Wb;
	H.CustomLayer = Xb;
	H.ContextMenu = zb;
	H.MenuItem = Ab;
	H.LocalSearch = Ja;
	H.TransitRoute = vc;
	H.DrivingRoute = xc;
	H.WalkingRoute = yc;
	H.Autocomplete = Ec;
	H.Geocoder = zc;
	H.LocalCity = Bc;
	H.Geolocation = Geolocation;
	H.BusLineSearch = Dc;
	H.Boundary = Cc;

	function Z(a, b) {
		for (var c in b) a[c] = b[c]
	}
	Z(window, {
		BMap: H,
		_jsload: function(a, b) {
			ma.Ll.Uz && ma.Ll.set(a, b);
			L.Tx(a, b)
		},
		BMAP_API_VERSION: "1.5"
	});
	var Fc = wa[u];
	Z(Fc, {
		getBounds: Fc.mf,
		getCenter: Fc.Ga,
		getMapType: Fc.aa,
		getSize: Fc.Pb,
		setSize: Fc.rd,
		getViewport: Fc.el,
		getZoom: Fc.fa,
		centerAndZoom: Fc.Ic,
		panTo: Fc.pe,
		panBy: Fc.Nd,
		setCenter: Fc.qd,
		setCurrentCity: Fc.sp,
		setMapType: Fc.Ve,
		setViewport: Fc.Ih,
		setZoom: Fc.Il,
		highResolutionEnabled: Fc.he,
		zoomTo: Fc.Rd,
		zoomIn: Fc.Ip,
		zoomOut: Fc.Jp,
		addHotspot: Fc.vn,
		removeHotspot: Fc.uA,
		clearHotspots: Fc.Hk,
		checkResize: Fc.Vx,
		addControl: Fc.un,
		removeControl: Fc.tA,
		getContainer: Fc.Ed,
		addContextMenu: Fc.fh,
		removeContextMenu: Fc.Dh,
		addOverlay: Fc.Ca,
		removeOverlay: Fc.jc,
		clearOverlays: Fc.Rr,
		openInfoWindow: Fc.Ub,
		closeInfoWindow: Fc.Db,
		pointToOverlayPixel: Fc.Od,
		overlayPixelToPoint: Fc.Gt,
		getInfoWindow: Fc.Ne,
		getOverlays: Fc.bz,
		getPanes: function() {
			return {
				floatPane: this.$b.ks,
				markerMouseTarget: this.$b.Yo,
				floatShadow: this.$b.ls,
				labelPane: this.$b.$s,
				markerPane: this.$b.jt,
				markerShadow: this.$b.kt,
				mapPane: this.$b.nl
			}
		},
		addTileLayer: Fc.Ci,
		removeTileLayer: Fc.kj,
		pixelToPoint: Fc.La,
		pointToPixel: Fc.Za
	});
	var Gc = Yb[u];
	Z(Gc, {
		getTileLayer: Gc.rz,
		getMinZoom: Gc.oh,
		getMaxZoom: Gc.ng,
		getProjection: Gc.th,
		getTextColor: Gc.Yi,
		getTips: Gc.Zi
	});
	Z(window, {
		BMAP_NORMAL_MAP: xa,
		BMAP_PERSPECTIVE_MAP: ya,
		BMAP_SATELLITE_MAP: Aa,
		BMAP_HYBRID_MAP: Ba
	});
	var Hc = T[u];
	Z(Hc, {
		lngLatToPoint: Hc.ll,
		pointToLngLat: Hc.hp
	});
	var Ic = Na[u];
	Z(Ic, {
		lngLatToPoint: Ic.ll,
		pointToLngLat: Ic.hp
	});
	var Jc = La[u];
	Z(Jc, {
		equals: Jc.Ob,
		containsPoint: Jc.dy,
		containsBounds: Jc.cy,
		intersects: Jc.Os,
		extend: Jc[x],
		getCenter: Jc.Ga,
		isEmpty: Jc.Se,
		getSouthWest: Jc.Hd,
		getNorthEast: Jc.Gd,
		toSpan: Jc.Fp
	});
	var Kc = Db[u];
	Z(Kc, {
		isVisible: Kc.Te,
		show: Kc.show,
		hide: Kc.H
	});
	Db.getZIndex = Db.$i;
	var Lc = Ma[u];
	Z(Lc, {
		openInfoWindow: Lc.Ub,
		closeInfoWindow: Lc.Db,
		enableMassClear: Lc.lf,
		disableMassClear: Lc.qy,
		show: Lc.show,
		hide: Lc.H,
		getMap: Lc.Co,
		addContextMenu: Lc.fh,
		removeContextMenu: Lc.Dh
	});
	var Mc = U[u];
	Z(Mc, {
		setIcon: Mc.se,
		getIcon: Mc.As,
		setPosition: Mc.ba,
		getPosition: Mc.T,
		setOffset: Mc.kc,
		getOffset: Mc.Oe,
		getLabel: Mc.Bs,
		setLabel: Mc.Fg,
		setTitle: Mc.wb,
		setTop: Mc.Hh,
		enableDragging: Mc.mb,
		disableDragging: Mc.Tn,
		setZIndex: Mc.Hl,
		getMap: Mc.Co,
		setAnimation: Mc.Eg,
		setShadow: Mc.Gl,
		hide: Mc.H
	});
	Z(window, {
		BMAP_ANIMATION_DROP: 1,
		BMAP_ANIMATION_BOUNCE: 2
	});
	var Nc = Kb[u];
	Z(Nc, {
		setStyle: Nc.Ib,
		setStyles: Nc.vf,
		setContent: Nc.Yb,
		setPosition: Nc.ba,
		getPosition: Nc.T,
		setOffset: Nc.kc,
		getOffset: Nc.Oe,
		setTitle: Nc.wb,
		setZIndex: Nc.Hl,
		getMap: Nc.Co
	});
	var Oc = Ib[u];
	Z(Oc, {
		setImageUrl: Oc.LA,
		setSize: Oc.rd,
		setAnchor: Oc.vb,
		setImageOffset: Oc.nj,
		setImageSize: Oc.KA,
		setInfoWindowAnchor: Oc.MA,
		setPrintImageUrl: Oc.SA
	});
	var Pc = Jb[u];
	Z(Pc, {
		redraw: Pc.Wb,
		setTitle: Pc.wb,
		setContent: Pc.Yb,
		getContent: Pc.ts,
		getPosition: Pc.T,
		enableMaximize: Pc.Cd,
		disableMaximize: Pc.Sk,
		isOpen: Pc.pa,
		setMaxContent: Pc.oj,
		maximize: Pc.ol,
		enableAutoPan: Pc.Mi
	});
	var Qc = Fb[u];
	Z(Qc, {
		getPath: Qc.cc,
		setPath: Qc.lc,
		setPositionAt: Qc.Hg,
		getStrokeColor: Qc.mz,
		setStrokeWeight: Qc.sj,
		getStrokeWeight: Qc.Gs,
		setStrokeOpacity: Qc.qj,
		getStrokeOpacity: Qc.nz,
		setFillOpacity: Qc.Dl,
		getFillOpacity: Qc.Vy,
		setStrokeStyle: Qc.rj,
		getStrokeStyle: Qc.Fs,
		getFillColor: Qc.Uy,
		getBounds: Qc.mf,
		enableEditing: Qc.ed,
		disableEditing: Qc.py
	});
	var Rc = Pb[u];
	Z(Rc, {
		setCenter: Rc.qd,
		getCenter: Rc.Ga,
		getRadius: Rc.ez,
		setRadius: Rc.Fl
	});
	var Sc = Nb[u];
	Z(Sc, {
		getPath: Sc.cc,
		setPath: Sc.lc,
		setPositionAt: Sc.Hg
	});
	var Tc = Oa[u];
	Z(Tc, {
		getPosition: Tc.T,
		setPosition: Tc.ba,
		getText: Tc.Jo,
		setText: Tc.tj
	});
	K[u].equals = K[u].Ob;
	S[u].equals = S[u].Ob;
	P[u].equals = P[u].Ob;
	Z(window, {
		BMAP_ANCHOR_TOP_LEFT: ob,
		BMAP_ANCHOR_TOP_RIGHT: pb,
		BMAP_ANCHOR_BOTTOM_LEFT: qb,
		BMAP_ANCHOR_BOTTOM_RIGHT: 3
	});
	var Uc = nb[u];
	Z(Uc, {
		setAnchor: Uc.vb,
		getAnchor: Uc.po,
		setOffset: Uc.kc,
		getOffset: Uc.Oe,
		show: Uc.show,
		hide: Uc.H,
		isVisible: Uc.Te,
		toString: Uc.toString
	});
	var Vc = rb[u];
	Z(Vc, {
		getType: Vc.vh,
		setType: Vc.Ig
	});
	Z(window, {
		BMAP_NAVIGATION_CONTROL_LARGE: 0,
		BMAP_NAVIGATION_CONTROL_SMALL: 1,
		BMAP_NAVIGATION_CONTROL_PAN: 2,
		BMAP_NAVIGATION_CONTROL_ZOOM: 3
	});
	var Wc = vb[u];
	Z(Wc, {
		changeView: Wc.ac,
		setSize: Wc.rd,
		getSize: Wc.Pb
	});
	var Xc = wb[u];
	Z(Xc, {
		getUnit: Xc.vz,
		setUnit: Xc.xp
	});
	Z(window, {
		BMAP_UNIT_METRIC: "metric",
		BMAP_UNIT_IMPERIAL: "us"
	});
	var Yc = ub[u];
	Z(Yc, {
		addCopyright: Yc.zk,
		removeCopyright: Yc.kp,
		getCopyright: Yc.kg,
		getCopyrightCollection: Yc.to
	});
	Z(window, {
		BMAP_MAPTYPE_CONTROL_HORIZONTAL: xb,
		BMAP_MAPTYPE_CONTROL_DROPDOWN: 1
	});
	var Zc = Sb[u];
	Z(Zc, {
		getMapType: Zc.aa,
		getCopyright: Zc.kg,
		isTransparentPng: Zc.il
	});
	var $c = zb[u];
	Z($c, {
		addItem: $c.Bk,
		addSeparator: $c.wn,
		removeSeparator: $c.lp
	});
	var ad = Ab[u];
	Z(ad, {
		setText: ad.tj
	});
	var bd = W[u];
	Z(bd, {
		getStatus: bd.pg,
		setSearchCompleteCallback: bd.vp,
		getPageCapacity: bd.Qc,
		setPageCapacity: bd.Gh,
		setLocation: bd.Gg,
		disableFirstResultSelection: bd.Un,
		enableFirstResultSelection: bd.bo,
		gotoPage: bd.rg,
		searchNearby: bd.Fh,
		searchInBounds: bd.Cg,
		search: bd.search
	});
	Z(window, {
		BMAP_STATUS_SUCCESS: 0,
		BMAP_STATUS_CITY_LIST: 1,
		BMAP_STATUS_UNKNOWN_LOCATION: 2,
		BMAP_STATUS_UNKNOWN_ROUTE: 3,
		BMAP_STATUS_INVALID_KEY: 4,
		BMAP_STATUS_INVALID_REQUEST: 5,
		BMAP_STATUS_PERMISSION_DENIED: 6,
		BMAP_STATUS_SERVICE_UNAVAILABLE: 7,
		BMAP_STATUS_TIMEOUT: 8
	});
	Z(window, {
		BMAP_POI_TYPE_NORMAL: 0,
		BMAP_POI_TYPE_BUSSTOP: 1,
		BMAP_POI_TYPE_BUSLINE: 2,
		BMAP_POI_TYPE_SUBSTOP: 3,
		BMAP_POI_TYPE_SUBLINE: 4
	});
	Z(window, {
		BMAP_TRANSIT_POLICY_LEAST_TIME: 0,
		BMAP_TRANSIT_POLICY_LEAST_TRANSFER: 2,
		BMAP_TRANSIT_POLICY_LEAST_WALKING: 3,
		BMAP_TRANSIT_POLICY_AVOID_SUBWAYS: 4,
		BMAP_LINE_TYPE_BUS: 0,
		BMAP_LINE_TYPE_SUBWAY: 1,
		BMAP_LINE_TYPE_FERRY: 2
	});
	var cd = uc[u];
	Z(cd, {
		clearResults: cd.yc
	});
	var dd = vc[u];
	Z(dd, {
		setPolicy: dd.pj,
		toString: dd.toString,
		setPageCapacity: dd.Gh
	});
	Z(window, {
		BMAP_DRIVING_POLICY_LEAST_TIME: 0,
		BMAP_DRIVING_POLICY_LEAST_DISTANCE: 1,
		BMAP_DRIVING_POLICY_AVOID_HIGHWAYS: 2
	});
	Z(window, {
		BMAP_HIGHLIGHT_STEP: 1,
		BMAP_HIGHLIGHT_ROUTE: 2
	});
	Z(window, {
		BMAP_ROUTE_TYPE_DRIVING: hc,
		BMAP_ROUTE_TYPE_WALKING: gc
	});
	Z(window, {
		BMAP_ROUTE_STATUS_NORMAL: ic,
		BMAP_ROUTE_STATUS_EMPTY: 1,
		BMAP_ROUTE_STATUS_ADDRESS: 2
	});
	var ed = xc[u];
	Z(ed, {
		setPolicy: ed.pj
	});
	var fd = Ec[u];
	Z(fd, {
		show: fd.show,
		hide: fd.H,
		setTypes: fd.wp,
		setLocation: fd.Gg,
		search: fd.search,
		setInputValue: fd.El
	});
	Z(Xb[u], {});
	var gd = Cc[u];
	Z(gd, {
		get: gd.get
	});
	H.Jx();
})();
var BMapLib = window.BMapLib = BMapLib || {};
(function() {
	var a = a || {
		guid: "$BAIDU$"
	};
	(function() {
		window[a.guid] = {};
		a.extend = function(h, f) {
			for (var g in f) {
				if (f.hasOwnProperty(g)) {
					h[g] = f[g]
				}
			}
			return h
		};
		a.lang = a.lang || {};
		a.lang.guid = function() {
			return "TANGRAM__" + (window[a.guid]._counter++).toString(36)
		};
		window[a.guid]._counter = window[a.guid]._counter || 1;
		window[a.guid]._instances = window[a.guid]._instances || {};
		a.lang.Class = function(f) {
			this.guid = f || a.lang.guid();
			window[a.guid]._instances[this.guid] = this
		};
		window[a.guid]._instances = window[a.guid]._instances || {};
		a.lang.isString = function(f) {
			return "[object String]" == Object.prototype.toString.call(f)
		};
		a.isString = a.lang.isString;
		a.lang.isFunction = function(f) {
			return "[object Function]" == Object.prototype.toString.call(f)
		};
		a.lang.Event = function(f, g) {
			this.type = f;
			this.returnValue = true;
			this.target = g || null;
			this.currentTarget = null
		};
		a.lang.Class.prototype.addEventListener = function(i, h, g) {
			if (!a.lang.isFunction(h)) {
				return
			}!this.__listeners && (this.__listeners = {});
			var f = this.__listeners,
				j;
			if (typeof g == "string" && g) {
				if (/[^\w\-]/.test(g)) {
					throw ("nonstandard key:" + g)
				} else {
					h.hashCode = g;
					j = g
				}
			}
			i.indexOf("on") != 0 && (i = "on" + i);
			typeof f[i] != "object" && (f[i] = {});
			j = j || a.lang.guid();
			h.hashCode = j;
			f[i][j] = h
		};
		a.lang.Class.prototype.removeEventListener = function(h, g) {
			if (a.lang.isFunction(g)) {
				g = g.hashCode
			} else {
				if (!a.lang.isString(g)) {
					return
				}
			}!this.__listeners && (this.__listeners = {});
			h.indexOf("on") != 0 && (h = "on" + h);
			var f = this.__listeners;
			if (!f[h]) {
				return
			}
			f[h][g] && delete f[h][g]
		};
		a.lang.Class.prototype.dispatchEvent = function(j, f) {
			if (a.lang.isString(j)) {
				j = new a.lang.Event(j)
			}!this.__listeners && (this.__listeners = {});
			f = f || {};
			for (var h in f) {
				j[h] = f[h]
			}
			var h, g = this.__listeners,
				k = j.type;
			j.target = j.target || this;
			j.currentTarget = this;
			k.indexOf("on") != 0 && (k = "on" + k);
			a.lang.isFunction(this[k]) && this[k].apply(this, arguments);
			if (typeof g[k] == "object") {
				for (h in g[k]) {
					g[k][h].apply(this, arguments)
				}
			}
			return j.returnValue
		};
		a.dom = a.dom || {};
		a.dom._g = function(f) {
			if (a.lang.isString(f)) {
				return document.getElementById(f)
			}
			return f
		};
		a._g = a.dom._g;
		a.event = a.event || {};
		a.event._listeners = a.event._listeners || [];
		a.event.on = function(g, j, l) {
			j = j.replace(/^on/i, "");
			g = a.dom._g(g);
			var k = function(n) {
					l.call(g, n)
				},
				f = a.event._listeners,
				i = a.event._eventFilter,
				m, h = j;
			j = j.toLowerCase();
			if (i && i[j]) {
				m = i[j](g, j, k);
				h = m.type;
				k = m.listener
			}
			if (g.addEventListener) {
				g.addEventListener(h, k, false)
			} else {
				if (g.attachEvent) {
					g.attachEvent("on" + h, k)
				}
			}
			f[f.length] = [g, j, l, k, h];
			return g
		};
		a.on = a.event.on;
		a.event.un = function(h, k, g) {
			h = a.dom._g(h);
			k = k.replace(/^on/i, "").toLowerCase();
			var n = a.event._listeners,
				i = n.length,
				j = !g,
				m, l, f;
			while (i--) {
				m = n[i];
				if (m[1] === k && m[0] === h && (j || m[2] === g)) {
					l = m[4];
					f = m[3];
					if (h.removeEventListener) {
						h.removeEventListener(l, f, false)
					} else {
						if (h.detachEvent) {
							h.detachEvent("on" + l, f)
						}
					}
					n.splice(i, 1)
				}
			}
			return h
		};
		a.un = a.event.un;
		a.preventDefault = a.event.preventDefault = function(f) {
			if (f.preventDefault) {
				f.preventDefault()
			} else {
				f.returnValue = false
			}
		}
	})();
	var e = BMapLib.RichMarker = function(h, f, g) {
			if (!h || !f || !(f instanceof BMap.Point)) {
				return
			}
			this._map = null;
			this._content = h;
			this._position = f;
			this._container = null;
			this._size = null;
			g = g || {};
			this._opts = a.extend(a.extend(this._opts || {}, {
				enableDragging: false,
				anchor: new BMap.Size(0, 0)
			}), g)
		};
	e.prototype = new BMap.Overlay();
	e.prototype.initialize = function(g) {
		var f = this,
			h = f._container = document.createElement("div");
		f._map = g;
		a.extend(h.style, {
			position: "absolute",
			zIndex: BMap.Overlay.getZIndex(f._position.lat),
			background: "none",
			cursor: "pointer"
		});
		g.getPanes().labelPane.appendChild(h);
		f._appendContent();
		f._setEventDispath();
		f._getContainerSize();
		return h
	};
	e.prototype.draw = function() {
		var h = this._map,
			g = this._opts.anchor,
			f = h.pointToOverlayPixel(this._position);
		this._container.style.left = f.x + g.width + "px";
		this._container.style.top = f.y + g.height + "px"
	};
	e.prototype.enableDragging = function() {
		this._opts.enableDragging = true
	};
	e.prototype.disableDragging = function() {
		this._opts.enableDragging = false
	};
	e.prototype.isDraggable = function() {
		return this._opts.enableDragging
	};
	e.prototype.getPosition = function() {
		return this._position
	};
	e.prototype.setPosition = function(f) {
		if (!f instanceof BMap.Point) {
			return
		}
		this._position = f;
		this.draw()
	};
	e.prototype.getAnchor = function() {
		return this._opts.anchor
	};
	e.prototype.setAnchor = function(f) {
		if (!f instanceof BMap.Size) {
			return
		}
		this._opts.anchor = f;
		this.draw()
	};
	e.prototype._appendContent = function() {
		var g = this._content;
		if (typeof g == "string") {
			var h = document.createElement("DIV");
			h.innerHTML = g;
			if (h.childNodes.length == 1) {
				g = (h.removeChild(h.firstChild))
			} else {
				var f = document.createDocumentFragment();
				while (h.firstChild) {
					f.appendChild(h.firstChild)
				}
				g = f
			}
		}
		this._container.innerHTML = "";
		this._container.appendChild(g)
	};
	e.prototype.getContent = function() {
		return this._content
	};
	e.prototype.setContent = function(f) {
		if (!f) {
			return
		}
		this._content = f;
		this._appendContent()
	};
	e.prototype._getContainerSize = function() {
		if (!this._container) {
			return
		}
		var g = this._container.offsetHeight;
		var f = this._container.offsetWidth;
		this._size = new BMap.Size(f, g)
	};
	e.prototype.getWidth = function() {
		if (!this._size) {
			return
		}
		return this._size.width
	};
	e.prototype.setWidth = function(f) {
		if (!this._container) {
			return
		}
		this._container.style.width = f + "px";
		this._getContainerSize()
	};
	e.prototype.getHeight = function() {
		if (!this._size) {
			return
		}
		return this._size.height
	};
	e.prototype.setHeight = function(f) {
		if (!this._container) {
			return
		}
		this._container.style.height = f + "px";
		this._getContainerSize()
	};
	e.prototype._setEventDispath = function() {
		var k = this,
			l = k._container,
			g = false,
			i = null;

		function j(p) {
			var p = window.event || p,
				n = p.pageX || p.clientX || 0,
				q = p.pageY || p.clientY || 0,
				o = new BMap.Pixel(n, q),
				m = k._map.pixelToPoint(o);
			return {
				pixel: o,
				point: m
			}
		}
		a.on(l, "onclick", function(m) {
			c(k, "onclick");
			d(m)
		});
		a.on(l, "ondblclick", function(n) {
			var m = j(n);
			c(k, "ondblclick", {
				point: m.point,
				pixel: m.pixel
			});
			d(n)
		});
		l.onmouseover = function(n) {
			var m = j(n);
			c(k, "onmouseover", {
				point: m.point,
				pixel: m.pixel
			});
			d(n)
		};
		l.onmouseout = function(n) {
			var m = j(n);
			c(k, "onmouseout", {
				point: m.point,
				pixel: m.pixel
			});
			d(n)
		};
		var h = function(n) {
				var m = j(n);
				c(k, "onmouseup", {
					point: m.point,
					pixel: m.pixel
				});
				if (k._container.releaseCapture) {
					a.un(l, "onmousemove", f);
					a.un(l, "onmouseup", h)
				} else {
					a.un(window, "onmousemove", f);
					a.un(window, "onmouseup", h)
				}
				if (!k._opts.enableDragging) {
					d(n);
					return
				}
				k._container.releaseCapture && k._container.releaseCapture();
				c(k, "ondragend", {
					point: m.point,
					pixel: m.pixel
				});
				g = false;
				i = null;
				k._setCursor("dragend");
				k._container.style.MozUserSelect = "";
				k._container.style.KhtmlUserSelect = "";
				k._container.style.WebkitUserSelect = "";
				k._container.unselectable = "off";
				k._container.onselectstart = function() {};
				d(n)
			};
		var f = function(o) {
				if (!k._opts.enableDragging || !g) {
					return
				}
				var n = j(o);
				var p = k._map.pointToPixel(k._position);
				var m = n.pixel.x - i.x + p.x;
				var q = n.pixel.y - i.y + p.y;
				i = n.pixel;
				k._position = k._map.pixelToPoint(new BMap.Pixel(m, q));
				k.draw();
				k._setCursor("dragging");
				c(k, "ondragging", {
					point: n.point,
					pixel: n.pixel
				});
				d(o)
			};
		a.on(l, "onmousedown", function(n) {
			var m = j(n);
			c(k, "onmousedown", {
				point: m.point,
				pixel: m.pixel
			});
			if (k._container.setCapture) {
				a.on(l, "onmousemove", f);
				a.on(l, "onmouseup", h)
			} else {
				a.on(window, "onmousemove", f);
				a.on(window, "onmouseup", h)
			}
			if (!k._opts.enableDragging) {
				d(n);
				return
			}
			i = m.pixel;
			c(k, "ondragstart", {
				point: m.point,
				pixel: m.pixel
			});
			g = true;
			k._setCursor("dragstart");
			k._container.setCapture && k._container.setCapture();
			k._container.style.MozUserSelect = "none";
			k._container.style.KhtmlUserSelect = "none";
			k._container.style.WebkitUserSelect = "none";
			k._container.unselectable = "on";
			k._container.onselectstart = function() {
				return false
			};
			d(n)
		})
	};
	e.prototype._setCursor = function(f) {
		var h = "";
		var g = {
			moz: {
				dragstart: "-moz-grab",
				dragging: "-moz-grabbing",
				dragend: "pointer"
			},
			other: {
				dragstart: "move",
				dragging: "move",
				dragend: "pointer"
			}
		};
		if (navigator.userAgent.indexOf("Gecko/") !== -1) {
			h = g.moz[f]
		} else {
			h = g.other[f]
		}
		if (this._container.style.cursor != h) {
			this._container.style.cursor = h
		}
	};
	e.prototype.remove = function() {
		c(this, "onremove");
		if (this._container) {
			b(this._container)
		}
		if (this._container && this._container.parentNode) {
			this._container.parentNode.removeChild(this._container)
		}
	};

	function c(f, g, i) {
		g.indexOf("on") != 0 && (g = "on" + g);
		var h = new a.lang.Event(g);
		if ( !! i) {
			for (var j in i) {
				h[j] = i[j]
			}
		}
		f.dispatchEvent(h)
	}
	function b(j) {
		if (!j) {
			return
		}
		var g = j.attributes,
			f = "";
		if (g) {
			for (var h = 0, l = g.length; h < l; h++) {
				f = g[h].name;
				if (typeof j[f] === "function") {
					j[f] = null
				}
			}
		}
		var k = j.childnodes;
		if (k) {
			for (var h = 0, l = k.length; h < l; h++) {
				b(j.childnodes[h])
			}
		}
	}
	function d(f) {
		var f = window.event || f;
		f.stopPropagation ? f.stopPropagation() : f.cancelBubble = true;
		return a.preventDefault(f)
	}
})();
(function() {
	var c = c || {
		guid: "$BAIDU$"
	};
	(function() {
		window[c.guid] = {};
		c.extend = function(g, e) {
			for (var f in e) {
				if (e.hasOwnProperty(f)) {
					g[f] = e[f]
				}
			}
			return g
		};
		c.lang = c.lang || {};
		c.lang.guid = function() {
			return "TANGRAM__" + (window[c.guid]._counter++).toString(36)
		};
		window[c.guid]._counter = window[c.guid]._counter || 1;
		window[c.guid]._instances = window[c.guid]._instances || {};
		c.lang.Class = function(e) {
			this.guid = e || c.lang.guid();
			window[c.guid]._instances[this.guid] = this
		};
		window[c.guid]._instances = window[c.guid]._instances || {};
		c.lang.isString = function(e) {
			return "[object String]" == Object.prototype.toString.call(e)
		};
		c.lang.isFunction = function(e) {
			return "[object Function]" == Object.prototype.toString.call(e)
		};
		c.lang.Class.prototype.toString = function() {
			return "[object " + (this._className || "Object") + "]"
		};
		c.lang.Class.prototype.dispose = function() {
			delete window[c.guid]._instances[this.guid];
			for (var e in this) {
				if (!c.lang.isFunction(this[e])) {
					delete this[e]
				}
			}
			this.disposed = true
		};
		c.lang.Event = function(e, f) {
			this.type = e;
			this.returnValue = true;
			this.target = f || null;
			this.currentTarget = null
		};
		c.lang.Class.prototype.addEventListener = function(h, g, f) {
			if (!c.lang.isFunction(g)) {
				return
			}!this.__listeners && (this.__listeners = {});
			var e = this.__listeners,
				i;
			if (typeof f == "string" && f) {
				if (/[^\w\-]/.test(f)) {
					throw ("nonstandard key:" + f)
				} else {
					g.hashCode = f;
					i = f
				}
			}
			h.indexOf("on") != 0 && (h = "on" + h);
			typeof e[h] != "object" && (e[h] = {});
			i = i || c.lang.guid();
			g.hashCode = i;
			e[h][i] = g
		};
		c.lang.Class.prototype.removeEventListener = function(g, f) {
			if (c.lang.isFunction(f)) {
				f = f.hashCode
			} else {
				if (!c.lang.isString(f)) {
					return
				}
			}!this.__listeners && (this.__listeners = {});
			g.indexOf("on") != 0 && (g = "on" + g);
			var e = this.__listeners;
			if (!e[g]) {
				return
			}
			e[g][f] && delete e[g][f]
		};
		c.lang.Class.prototype.dispatchEvent = function(h, e) {
			if (c.lang.isString(h)) {
				h = new c.lang.Event(h)
			}!this.__listeners && (this.__listeners = {});
			e = e || {};
			for (var g in e) {
				h[g] = e[g]
			}
			var g, f = this.__listeners,
				j = h.type;
			h.target = h.target || this;
			h.currentTarget = this;
			j.indexOf("on") != 0 && (j = "on" + j);
			c.lang.isFunction(this[j]) && this[j].apply(this, arguments);
			if (typeof f[j] == "object") {
				for (g in f[j]) {
					f[j][g].apply(this, arguments)
				}
			}
			return h.returnValue
		};
		c.lang.inherits = function(k, i, h) {
			var g, j, e = k.prototype,
				f = new Function();
			f.prototype = i.prototype;
			j = k.prototype = new f();
			for (g in e) {
				j[g] = e[g]
			}
			k.prototype.constructor = k;
			k.superClass = i.prototype;
			if ("string" == typeof h) {
				j._className = h
			}
		};
		c.dom = c.dom || {};
		c._g = c.dom._g = function(e) {
			if (c.lang.isString(e)) {
				return document.getElementById(e)
			}
			return e
		};
		c.g = c.dom.g = function(e) {
			if ("string" == typeof e || e instanceof String) {
				return document.getElementById(e)
			} else {
				if (e && e.nodeName && (e.nodeType == 1 || e.nodeType == 9)) {
					return e
				}
			}
			return null
		};
		c.insertHTML = c.dom.insertHTML = function(h, e, g) {
			h = c.dom.g(h);
			var f, i;
			if (h.insertAdjacentHTML) {
				h.insertAdjacentHTML(e, g)
			} else {
				f = h.ownerDocument.createRange();
				e = e.toUpperCase();
				if (e == "AFTERBEGIN" || e == "BEFOREEND") {
					f.selectNodeContents(h);
					f.collapse(e == "AFTERBEGIN")
				} else {
					i = e == "BEFOREBEGIN";
					f[i ? "setStartBefore" : "setEndAfter"](h);
					f.collapse(i)
				}
				f.insertNode(f.createContextualFragment(g))
			}
			return h
		};
		c.ac = c.dom.addClass = function(k, m) {
			k = c.dom.g(k);
			var f = m.split(/\s+/),
				e = k.className,
				j = " " + e + " ",
				h = 0,
				g = f.length;
			for (; h < g; h++) {
				if (j.indexOf(" " + f[h] + " ") < 0) {
					e += (e ? " " : "") + f[h]
				}
			}
			k.className = e;
			return k
		};
		c.event = c.event || {};
		c.event._listeners = c.event._listeners || [];
		c.on = c.event.on = function(f, i, k) {
			i = i.replace(/^on/i, "");
			f = c._g(f);
			var j = function(m) {
					k.call(f, m)
				},
				e = c.event._listeners,
				h = c.event._eventFilter,
				l, g = i;
			i = i.toLowerCase();
			if (h && h[i]) {
				l = h[i](f, i, j);
				g = l.type;
				j = l.listener
			}
			if (f.addEventListener) {
				f.addEventListener(g, j, false)
			} else {
				if (f.attachEvent) {
					f.attachEvent("on" + g, j)
				}
			}
			e[e.length] = [f, i, k, j, g];
			return f
		};
		c.un = c.event.un = function(g, j, f) {
			g = c._g(g);
			j = j.replace(/^on/i, "").toLowerCase();
			var m = c.event._listeners,
				h = m.length,
				i = !f,
				l, k, e;
			while (h--) {
				l = m[h];
				if (l[1] === j && l[0] === g && (i || l[2] === f)) {
					k = l[4];
					e = l[3];
					if (g.removeEventListener) {
						g.removeEventListener(k, e, false)
					} else {
						if (g.detachEvent) {
							g.detachEvent("on" + k, e)
						}
					}
					m.splice(h, 1)
				}
			}
			return g
		};
		c.preventDefault = c.event.preventDefault = function(e) {
			if (e.preventDefault) {
				e.preventDefault()
			} else {
				e.returnValue = false
			}
		}
	})();
	var d = BMapLib.DistanceTool = function(f, e) {
			if (!f) {
				return
			}
			this._map = f;
			e = e || {};
			this._opts = c.extend(c.extend(this._opts || {}, {
				tips: "测距",
				followText: "单击确定地点，双击结束",
				unit: "metric",
				lineColor: "#ff6319",
				lineStroke: 2,
				opacity: 0.8,
				lineStyle: "solid",
				cursor: "http://api.map.baidu.com/images/ruler.cur",
				secIcon: null,
				closeIcon: null
			}), e);
			this._followTitle = null;
			this._points = [];
			this._paths = [];
			this._dots = [];
			this._segDistance = [];
			this._overlays = [];
			this._enableMassClear = true, this._units = {
				metric: {
					name: "metric",
					conv: 1,
					incon: 1000,
					u1: "米",
					u2: "公里"
				},
				us: {
					name: "us",
					conv: 3.2808,
					incon: 5279.856,
					u1: "英尺",
					u2: "英里"
				}
			};
			this._isOpen = false;
			this._startFollowText = "单击确定起点";
			this._movingTimerId = null;
			this._styles = {
				BMapLib_diso: "height:17px;width:5px;position:absolute;background:url(http://api.map.baidu.com/images/dis_box_01.gif) no-repeat left top",
				BMapLib_disi: "color:#7a7a7a;position:absolute;left:5px;padding:0 4px 1px 0;line-height:17px;background:url(http://api.map.baidu.com/images/dis_box_01.gif) no-repeat right top",
				BMapLib_disBoxDis: "color:#ff6319;font-weight:bold"
			};
			if (this._opts.lineStroke <= 0) {
				this._opts.lineStroke = 2
			}
			if (this._opts.opacity > 1) {
				this._opts.opacity = 1
			} else {
				if (this._opts.opacity < 0) {
					this._opts.opacity = 0
				}
			}
			if (this._opts.lineStyle != "solid" && this._opts.lineStyle != "dashed") {
				this._opts.lineStyle = "solid"
			}
			if (!this._units[this._opts.unit]) {
				this._opts.unit = "metric"
			}
			this.text = "测距"
		};
	c.lang.inherits(d, c.lang.Class, "DistanceTool");
	d.prototype._bind = function() {
		this._setCursor(this._opts.cursor);
		var f = this;
		c.on(this._map.getContainer(), "mousemove", function(i) {
			if (!f._isOpen) {
				return
			}
			if (!f._followTitle) {
				return
			}
			i = window.event || i;
			var g = i.target || i.srcElement;
			if (g != a.getDom(f._map)) {
				f._followTitle.hide();
				return
			}
			if (!f._mapMoving) {
				f._followTitle.show()
			}
			var h = a.getDrawPoint(i, true);
			f._followTitle.setPosition(h)
		});
		if (this._startFollowText) {
			var e = this._followTitle = new BMap.Label(this._startFollowText, {
				offset: new BMap.Size(14, 16)
			});
			this._followTitle.setStyles({
				color: "#333",
				borderColor: "#ff0103"
			})
		}
	};
	d.prototype.open = function() {
		if (this._isOpen == true) {
			return true
		}
		if ( !! BMapLib._toolInUse) {
			return
		}
		this._isOpen = true;
		BMapLib._toolInUse = true;
		if (this._mapMoving) {
			delete this._mapMoving
		}
		var h = this;
		if (!this._binded) {
			this._binded = true;
			this._bind();
			this._map.addEventListener("moving", function() {
				h._hideCurrent()
			})
		}
		if (this._followTitle) {
			this._map.addOverlay(this._followTitle);
			this._followTitle.hide()
		}
		var g = function(q) {
				var l = h._map;
				if (!h._isOpen) {
					return
				}
				q = window.event || q;
				var n = a.getDrawPoint(q, true);
				if (!h._isPointValid(n)) {
					return
				}
				h._bind.initX = q.pageX || q.clientX || 0;
				h._bind.initY = q.pageY || q.clientY || 0;
				if (h._points.length > 0) {
					var t = l.pointToPixel(h._points[h._points.length - 1]);
					var m = l.pointToPixel(n);
					var p = Math.sqrt(Math.pow(t.x - m.x, 2) + Math.pow(t.y - m.y, 2));
					if (p < 5) {
						return
					}
				}
				h._bind.x = q.layerX || q.offsetX || 0;
				h._bind.y = q.layerY || q.offsetY || 0;
				h._points.push(n);
				h._addSecPoint(n);
				if (h._paths.length == 0) {
					h._formatTitle(1, h._opts.followText, h._getTotalDistance())
				}
				if (h._paths.length > 0) {
					h._paths[h._paths.length - 1].show();
					h._paths[h._paths.length - 1].setStrokeOpacity(h._opts.opacity)
				}
				var w = new BMap.Polyline([n, n], {
					enableMassClear: h._enableMassClear
				});
				h._map.addOverlay(w);
				h._paths.push(w);
				h._overlays.push(w);
				w.setStrokeWeight(h._opts.lineStroke);
				w.setStrokeColor(h._opts.lineColor);
				w.setStrokeOpacity(h._opts.opacity / 2);
				w.setStrokeStyle(h._opts.lineStyle);
				if (h._mapMoving) {
					w.hide()
				}
				if (h._points.length > 1) {
					var o = h._paths[h._points.length - 2];
					o.setPositionAt(1, n)
				}
				var r = "";
				if (h._points.length > 1) {
					var u = h._setSegDistance(h._points[h._points.length - 2], h._points[h._points.length - 1]);
					var s = h._getTotalDistance();
					r = h._formatDisStr(s)
				} else {
					r = "起点"
				}
				var v = new BMap.Label(r, {
					offset: new BMap.Size(10, -5),
					enableMassClear: h._enableMassClear
				});
				v.setStyles({
					color: "#333",
					borderColor: "#ff0103"
				});
				h._map.addOverlay(v);
				h._formatSegLabel(v, r);
				h._overlays.push(v);
				n.disLabel = v;
				v.setPosition(n);
				var k = new c.lang.Event("onaddpoint");
				k.point = n;
				k.pixel = h._map.pointToPixel(n);
				k.index = h._points.length - 1;
				k.distance = h._getTotalDistance().toFixed(0);
				h.dispatchEvent(k)
			};
		var f = function(p) {
				if (!h._isOpen) {
					return
				}
				if (h._paths.length > 0) {
					p = window.event || p;
					var l = p.pageX || p.clientX || 0;
					var k = p.pageY || p.clientY || 0;
					if (typeof h._bind.initX == "undefined") {
						h._bind.x = p.layerX || p.offsetX || 0;
						h._bind.y = p.layerY || p.offsetY || 0;
						h._bind.initX = l;
						h._bind.initY = k
					}
					var r = h._bind.x + l - h._bind.initX;
					var q = h._bind.y + k - h._bind.initY;
					var z = h._paths[h._paths.length - 1];
					var m = h._map.pixelToPoint(new BMap.Pixel(r, q));
					z.setPositionAt(1, m);
					if (!h._mapMoving) {
						z.show()
					}
					var A = 0;
					var u = 0;
					if (r < 10) {
						A = 8
					} else {
						if (r > h._map.getSize().width - 10) {
							A = -8
						}
					}
					if (q < 10) {
						u = 8
					} else {
						if (q > h._map.getSize().height - 10) {
							u = -8
						}
					}
					if (A != 0 || u != 0) {
						if (!f._movingTimerId) {
							h._mapMoving = true;
							h._map.panBy(A, u, {
								noAnimation: true
							});
							h._movingTimerId = f._movingTimerId = setInterval(function() {
								h._map.panBy(A, u, {
									noAnimation: true
								})
							}, 30);
							z.hide();
							h._followTitle && h._followTitle.hide()
						}
					} else {
						if (f._movingTimerId) {
							clearInterval(f._movingTimerId);
							delete f._movingTimerId;
							delete h._movingTimerId;
							var w = h._paths[h._paths.length - 1];
							var v = h._map.pixelToPoint(new BMap.Pixel(r, q));
							if (!w) {
								return
							}
							w.setPositionAt(1, v);
							w.show();
							if (h._followTitle) {
								h._followTitle.setPosition(v);
								h._followTitle.show()
							}
							h._bind.i = 0;
							h._bind.j = 0;
							delete h._mapMoving
						}
					}
					if (h._followTitle) {
						var o = h._getTotalDistance();
						var n = h._map.getDistance(h._points[h._points.length - 1], m);
						h._updateInstDis(h._followTitle, o + n)
					}
				} else {
					if (h._followTitle) {
						h._followTitle.show();
						p = window.event || p;
						var s = p.target || p.srcElement;
						if (s != a.getDom()) {
							h._followTitle.hide()
						}
					}
				}
			};
		var e = function(k) {
				if (!h._isOpen) {
					return
				}
				c.un(a.getDom(h._map), "click", g);
				c.un(document, "mousemove", f);
				c.un(a.getDom(h._map), "dblclick", e);
				c.un(document, "keydown", j);
				c.un(a.getDom(h._map), "mouseup", i);
				setTimeout(function() {
					h.close()
				}, 50)
			};
		var j = function(k) {
				k = window.event || k;
				if (k.keyCode == 27) {
					h._clearCurData();
					setTimeout(function() {
						h.close()
					}, 50)
				}
			};
		var i = function(k) {
				k = window.event || k;
				var l = 0;
				if (/msie (\d+\.\d)/i.test(navigator.userAgent)) {
					l = document.documentMode || +RegExp["$1"]
				}
				if (l && k.button != 1 || k.button == 2) {
					h.close()
				}
			};
		h._initData();
		this._formatTitle();
		a.show(this._map);
		this._setCursor(this._opts.cursor);
		c.on(a.getDom(this._map), "click", g);
		c.on(document, "mousemove", f);
		c.on(a.getDom(this._map), "dblclick", e);
		c.on(document, "keydown", j);
		c.on(a.getDom(this._map), "mouseup", i);
		this.bindFunc = [{
			elem: a.getDom(this._map),
			type: "click",
			func: g
		}, {
			elem: a.getDom(this._map),
			type: "dblclick",
			func: e
		}, {
			elem: document,
			type: "mousemove",
			func: f
		}, {
			elem: document,
			type: "keydown",
			func: j
		}, {
			elem: a.getDom(this._map),
			type: "mouseup",
			func: i
		}];
		return true
	};
	d.prototype._dispatchLastEvent = function() {
		var e = new c.lang.Event("ondrawend");
		e.points = this._points ? this._points.slice(0) : [];
		e.overlays = this._paths ? this._paths.slice(0, this._paths.length - 1) : [];
		e.distance = this._getTotalDistance().toFixed(0);
		this.dispatchEvent(e)
	};
	d.prototype.close = function() {
		if (this._isOpen == false) {
			return
		}
		this._isOpen = false;
		BMapLib._toolInUse = false;
		if (this._mapMoving) {
			delete this._mapMoving
		}
		var g = this;
		g._dispatchLastEvent();
		if (g._points.length < 2) {
			g._clearCurData()
		} else {
			g._paths[g._paths.length - 1].remove();
			g._paths[g._paths.length - 1] = null;
			g._paths.length = g._paths.length - 1;
			var h = g._points[g._points.length - 1];
			if (h.disLabel) {
				h.disLabel.remove()
			}
			g._processLastOp()
		}
		a.hide();
		for (var f = 0, e = this.bindFunc.length; f < e; f++) {
			c.un(this.bindFunc[f].elem, this.bindFunc[f].type, this.bindFunc[f].func)
		}
		if (g._movingTimerId) {
			clearInterval(g._movingTimerId);
			g._movingTimerId = null
		}
		if (this._followTitle) {
			this._followTitle.hide()
		}
	};
	d.prototype._clearCurData = function() {
		for (var f = 0, e = this._points.length; f < e; f++) {
			if (this._points[f].disLabel) {
				this._points[f].disLabel.remove()
			}
		}
		for (var f = 0, e = this._paths.length; f < e; f++) {
			this._paths[f].remove()
		}
		for (var f = 0, e = this._dots.length; f < e; f++) {
			this._dots[f].remove()
		}
		this._initData()
	};
	d.prototype._initData = function() {
		this._points.length = 0;
		this._paths.length = 0;
		this._segDistance.length = 0;
		this._dots.length = 0
	};
	d.prototype._setSegDistance = function(g, f) {
		if (!g || !f) {
			return
		}
		var e = this._map.getDistance(g, f);
		this._segDistance.push(e);
		return e
	};
	d.prototype._getTotalDistance = function() {
		var g = 0;
		for (var f = 0, e = this._segDistance.length; f < e; f++) {
			g += this._segDistance[f]
		}
		return g
	};
	d.prototype._convertUnit = function(e, f) {
		f = f || "metric";
		if (this._units[f]) {
			return e * this._units[f].conv
		}
		return e
	};
	d.prototype._addSecPoint = function(g) {
		var f = this._opts.secIcon ? this._opts.secIcon : new BMap.Icon("http://api.map.baidu.com/images/mapctrls.png", new BMap.Size(11, 11), {
			imageOffset: new BMap.Size(-26, -313)
		});
		var e = new BMap.Marker(g, {
			icon: f,
			clickable: false,
			baseZIndex: 3500000,
			zIndexFixed: true,
			enableMassClear: this._enableMassClear
		});
		this._map.addOverlay(e);
		this._dots.push(e)
	};
	d.prototype._formatDisStr = function(h) {
		var f = this._opts.unit;
		var g = this._units[f].u1;
		var e = this._convertUnit(h, f);
		if (e > this._units[f].incon) {
			e = e / this._units[f].incon;
			g = this._units[f].u2;
			e = e.toFixed(1)
		} else {
			e = e.toFixed(0)
		}
		return e + g
	};
	d.prototype._setCursor = function(f) {
		var e = /webkit/.test(navigator.userAgent.toLowerCase()) ? "url(" + this._opts.cursor + ") 3 6, crosshair" : "url(" + this._opts.cursor + "), crosshair";
		a._setCursor(e)
	};
	d.prototype._getCursor = function() {
		return this._opts.cursor
	};
	d.prototype._formatSegLabel = function(e, f) {
		e.setStyle({
			border: "none",
			padding: "0"
		});
		e.setContent("<span style='" + this._styles.BMapLib_diso + "'><span style='" + this._styles.BMapLib_disi + "'>" + f + "</span></span>")
	};
	d.prototype._processLastOp = function() {
		var i = this;
		delete i._bind.x;
		delete i._bind.y;
		delete i._bind.initX;
		delete i._bind.initY;
		if (i._paths.length > i._points.length - 1) {
			var g = i._paths.length - 1;
			i._paths[g].remove();
			i._paths[g] = null;
			i._paths.length = g
		}
		var e = {};
		e.points = i._points.slice(0);
		e.paths = i._paths.slice(0);
		e.dots = i._dots.slice(0);
		e.segDis = i._segDistance.slice(0);
		var j = i._map.pointToPixel(e.points[e.points.length - 1]);
		var h = i._map.pointToPixel(e.points[e.points.length - 2]);
		var k = [0, 0];
		var f = [0, 0];
		if (j.y - h.y >= 0) {
			f = [-5, 11]
		} else {
			f = [-5, -35]
		}
		if (j.x - h.x >= 0) {
			k = [14, 0]
		} else {
			k = [-14, 0]
		}
		var n = e.points[e.points.length - 1];
		n.disLabel = new BMap.Label("", {
			offset: new BMap.Size(-15, -40),
			enableMassClear: i._enableMassClear
		});
		n.disLabel.setStyles({
			color: "#333",
			borderColor: "#ff0103"
		});
		i._map.addOverlay(n.disLabel);
		n.disLabel.setOffset(new BMap.Size(f[0], f[1]));
		n.disLabel.setPosition(n);
		i._formatTitle(2, "", "", n.disLabel);
		var m = this._opts.closeIcon ? this._opts.closeIcon : new BMap.Icon("http://api.map.baidu.com/images/mapctrls.gif", new BMap.Size(12, 12), {
			imageOffset: new BMap.Size(0, -14)
		});
		e.closeBtn = new BMap.Marker(e.points[e.points.length - 1], {
			icon: m,
			offset: new BMap.Size(k[0], k[1]),
			baseZIndex: 3600000,
			enableMassClear: i._enableMassClear
		});
		i._map.addOverlay(e.closeBtn);
		e.closeBtn.setTitle("清除本次测距");
		e.closeBtn.addEventListener("click", function(r) {
			for (var p = 0, o = e.points.length; p < o; p++) {
				e.points[p].disLabel.remove();
				e.points[p].disLabel = null
			}
			for (var p = 0, o = e.paths.length; p < o; p++) {
				e.paths[p].remove();
				e.paths[p] = null
			}
			for (var p = 0, o = e.dots.length; p < o; p++) {
				e.dots[p].remove();
				e.dots[p] = null
			}
			e.closeBtn.remove();
			e.closeBtn = null;
			b(r);
			var q = new c.lang.Event("onremovepolyline");
			i.dispatchEvent(q)
		});
		i._initData()
	};
	d.prototype._formatTitle = function(g, l, e, i) {
		var h = i || this._followTitle;
		if (!h) {
			return
		}
		h.setStyle({
			lineHeight: "16px",
			zIndex: "85",
			padding: "3px 5px"
		});
		var n = this._startFollowText || "";
		var k = [];
		if (g == 1) {
			h.setOffset(0, 25);
			var m = this._opts.unit;
			var j = this._units[m].u1;
			var f = this._convertUnit(e, m);
			if (f > this._units[m].incon) {
				f = f / this._units[m].incon;
				j = this._units[m].u2;
				f = f.toFixed(1)
			} else {
				f = f.toFixed(0)
			}
			k.push("<span>总长：<span style='" + this._styles.BMapLib_disBoxDis + "'>" + f + "</span>" + j + "</span><br />");
			k.push("<span style='color:#7a7a7a'>" + l + "</span>")
		} else {
			if (g == 2) {
				var m = this._opts.unit;
				var j = this._units[m].u1;
				var f = this._convertUnit(this._getTotalDistance(), m);
				if (f > this._units[m].incon) {
					f = f / this._units[m].incon;
					j = this._units[m].u2;
					f = f.toFixed(1)
				} else {
					f = f.toFixed(0)
				}
				k.push("总长：<span style='" + this._styles.BMapLib_disBoxDis + "'>" + f + "</span>" + j)
			} else {
				h.setOffset(0, 25);
				k.push(n)
			}
		}
		h.setContent(k.join(""))
	};
	d.prototype._updateInstDis = function(g, e) {
		var f = this._opts.unit;
		var i = this._units[f].u1;
		if (e > this._units[f].incon) {
			e = e / this._units[f].incon;
			i = this._units[f].u2;
			e = e.toFixed(1)
		} else {
			e = e.toFixed(0)
		}
		if (g) {
			var h = [];
			h.push("<span>总长：<span style='" + this._styles.BMapLib_disBoxDis + "'>" + e + "</span>" + i + "</span><br />");
			h.push("<span style='color:#7a7a7a'>" + this._opts.followText + "</span>");
			g.setContent(h.join(""))
		}
	};
	d.prototype._hideCurrent = function() {
		if (!this._isOpen) {
			return
		}
		if (this._paths.length > 0) {
			var e = this._paths[this._paths.length - 1];
			e.hide()
		}
		this._followTitle && this._followTitle.hide()
	};
	d.prototype._isPointValid = function(h) {
		if (!h) {
			return false
		}
		var f = this._map.getBounds();
		var e = f.getSouthWest(),
			g = f.getNorthEast();
		if (h.lng < e.lng || h.lng > g.lng || h.lat < e.lat || h.lat > g.lat) {
			return false
		}
		return true
	};
	var a = {
		_map: null,
		_html: "<div style='background:transparent url(http://api.map.baidu.com/images/blank.gif);position:absolute;left:0;top:0;width:100%;height:100%;z-index:1000' unselectable='on'></div>",
		_maskElement: null,
		_cursor: "default",
		_inUse: false,
		show: function(e) {
			if (!this._map) {
				this._map = e
			}
			this._inUse = true;
			if (!this._maskElement) {
				this._createMask(e)
			}
			this._maskElement.style.display = "block"
		},
		_createMask: function(g) {
			this._map = g;
			if (!this._map) {
				return
			}
			c.insertHTML(this._map.getContainer(), "beforeEnd", this._html);
			var f = this._maskElement = this._map.getContainer().lastChild;
			var e = function(h) {
					b(h);
					return c.preventDefault(h)
				};
			c.on(f, "mouseup", function(h) {
				if (h.button == 2) {
					e(h)
				}
			});
			c.on(f, "contextmenu", e);
			f.style.display = "none"
		},
		getDrawPoint: function(h, j) {
			h = window.event || h;
			var f = h.layerX || h.offsetX || 0;
			var i = h.layerY || h.offsetY || 0;
			var g = h.target || h.srcElement;
			if (g != a.getDom(this._map) && j == true) {
				while (g && g != this._map.getContainer()) {
					if (!(g.clientWidth == 0 && g.clientHeight == 0 && g.offsetParent && g.offsetParent.nodeName.toLowerCase() == "td")) {
						f += g.offsetLeft;
						i += g.offsetTop
					}
					g = g.offsetParent
				}
			}
			if (g != a.getDom(this._map) && g != this._map.getContainer()) {
				return
			}
			if (typeof f === "undefined" || typeof i === "undefined") {
				return
			}
			if (isNaN(f) || isNaN(i)) {
				return
			}
			return this._map.pixelToPoint(new BMap.Pixel(f, i))
		},
		hide: function() {
			if (!this._map) {
				return
			}
			this._inUse = false;
			if (this._maskElement) {
				this._maskElement.style.display = "none"
			}
		},
		getDom: function(e) {
			if (!this._maskElement) {
				this._createMask(e)
			}
			return this._maskElement
		},
		_setCursor: function(e) {
			this._cursor = e || "default";
			if (this._maskElement) {
				this._maskElement.style.cursor = this._cursor
			}
		}
	};

	function b(f) {
		var f = window.event || f;
		f.stopPropagation ? f.stopPropagation() : f.cancelBubble = true
	}
})();
(function() {
	var e = 0;
	var j = 1;
	var c = BMapLib.RectangleZoom = function(n, m) {
			if (!n) {
				return
			}
			this._map = n;
			this._bounds = null;
			this._opts = {
				zoomType: e,
				followText: "",
				strokeWeight: 2,
				strokeColor: "#111",
				style: "solid",
				fillColor: "#ccc",
				opacity: 0.4,
				cursor: "crosshair",
				autoClose: false
			};
			this._setOptions(m);
			this._opts.strokeWeight = this._opts.strokeWeight <= 0 ? 1 : this._opts.strokeWeight;
			this._opts.opacity = this._opts.opacity < 0 ? 0 : this._opts.opacity > 1 ? 1 : this._opts.opacity;
			if (this._opts.zoomType < e || this._opts.zoomType > j) {
				this._opts.zoomType = e
			}
			this._isOpen = false;
			this._fDiv = null;
			this._followTitle = null
		};
	c.prototype._setOptions = function(m) {
		if (!m) {
			return
		}
		for (var n in m) {
			if (typeof(m[n]) != "undefined") {
				this._opts[n] = m[n]
			}
		}
	};
	c.prototype.setStrokeColor = function(m) {
		if (typeof m == "string") {
			this._opts.strokeColor = m;
			this._updateStyle()
		}
	};
	c.prototype.setLineStroke = function(m) {
		if (typeof m == "number" && Math.round(m) > 0) {
			this._opts.strokeWeight = Math.round(m);
			this._updateStyle()
		}
	};
	c.prototype.setLineStyle = function(m) {
		if (m == "solid" || m == "dashed") {
			this._opts.style = m;
			this._updateStyle()
		}
	};
	c.prototype.setOpacity = function(m) {
		if (typeof m == "number" && m >= 0 && m <= 1) {
			this._opts.opacity = m;
			this._updateStyle()
		}
	};
	c.prototype.setFillColor = function(m) {
		this._opts.fillColor = m;
		this._updateStyle()
	};
	c.prototype.setCursor = function(m) {
		this._opts.cursor = m;
		f.setCursor(this._opts.cursor)
	};
	c.prototype._updateStyle = function() {
		if (this._fDiv) {
			this._fDiv.style.border = [this._opts.strokeWeight, "px ", this._opts.style, " ", this._opts.color].join("");
			var m = this._fDiv.style,
				n = this._opts.opacity;
			m.opacity = n;
			m.MozOpacity = n;
			m.KhtmlOpacity = n;
			m.filter = "alpha(opacity=" + (n * 100) + ")"
		}
	};
	c.prototype.getBounds = function() {
		return this._bounds
	};
	c.prototype.getCursor = function() {
		return this._opts.cursor
	};
	c.prototype._bind = function() {
		this.setCursor(this._opts.cursor);
		var n = this;
		d(this._map.getContainer(), "mousemove", function(q) {
			if (!n._isOpen) {
				return
			}
			if (!n._followTitle) {
				return
			}
			q = window.event || q;
			var o = q.target || q.srcElement;
			if (o != f.getDom(n._map)) {
				n._followTitle.hide();
				return
			}
			if (!n._mapMoving) {
				n._followTitle.show()
			}
			var p = f.getDrawPoint(q, true);
			n._followTitle.setPosition(p)
		});
		if (this._opts.followText) {
			var m = this._followTitle = new BMap.Label(this._opts.followText, {
				offset: new BMap.Size(14, 16)
			});
			this._followTitle.setStyles({
				color: "#333",
				borderColor: "#ff0103"
			})
		}
	};
	c.prototype.open = function() {
		if (this._isOpen == true) {
			return true
		}
		if ( !! BMapLib._toolInUse) {
			return
		}
		this._isOpen = true;
		BMapLib._toolInUse = true;
		if (!this.binded) {
			this._bind();
			this.binded = true
		}
		if (this._followTitle) {
			this._map.addOverlay(this._followTitle);
			this._followTitle.hide()
		}
		var o = this;
		var p = this._map;
		var q = 0;
		if (/msie (\d+\.\d)/i.test(navigator.userAgent)) {
			q = document.documentMode || +RegExp["$1"]
		}
		var n = function(s) {
				s = window.event || s;
				if (s.button != 0 && !q || q && s.button != 1) {
					return
				}
				if ( !! q && f.getDom(p).setCapture) {
					f.getDom(p).setCapture()
				}
				if (!o._isOpen) {
					return
				}
				o._bind.isZooming = true;
				d(document, "mousemove", m);
				d(document, "mouseup", r);
				o._bind.mx = s.layerX || s.offsetX || 0;
				o._bind.my = s.layerY || s.offsetY || 0;
				o._bind.ix = s.pageX || s.clientX || 0;
				o._bind.iy = s.pageY || s.clientY || 0;
				a(f.getDom(p), "beforeBegin", o._generateHTML());
				o._fDiv = f.getDom(p).previousSibling;
				o._fDiv.style.width = "0";
				o._fDiv.style.height = "0";
				o._fDiv.style.left = o._bind.mx + "px";
				o._fDiv.style.top = o._bind.my + "px";
				b(s);
				return h(s)
			};
		var m = function(z) {
				if (o._isOpen == true && o._bind.isZooming == true) {
					var z = window.event || z;
					var u = z.pageX || z.clientX || 0;
					var s = z.pageY || z.clientY || 0;
					var w = o._bind.dx = u - o._bind.ix;
					var t = o._bind.dy = s - o._bind.iy;
					var v = Math.abs(w) - o._opts.strokeWeight;
					var y = Math.abs(t) - o._opts.strokeWeight;
					o._fDiv.style.width = (v < 0 ? 0 : v) + "px";
					o._fDiv.style.height = (y < 0 ? 0 : y) + "px";
					var x = [p.getSize().width, p.getSize().height];
					if (w >= 0) {
						o._fDiv.style.right = "auto";
						o._fDiv.style.left = o._bind.mx + "px";
						if (o._bind.mx + w >= x[0] - 2 * o._opts.strokeWeight) {
							o._fDiv.style.width = x[0] - o._bind.mx - 2 * o._opts.strokeWeight + "px";
							o._followTitle && o._followTitle.hide()
						}
					} else {
						o._fDiv.style.left = "auto";
						o._fDiv.style.right = x[0] - o._bind.mx + "px";
						if (o._bind.mx + w <= 2 * o._opts.strokeWeight) {
							o._fDiv.style.width = o._bind.mx - 2 * o._opts.strokeWeight + "px";
							o._followTitle && o._followTitle.hide()
						}
					}
					if (t >= 0) {
						o._fDiv.style.bottom = "auto";
						o._fDiv.style.top = o._bind.my + "px";
						if (o._bind.my + t >= x[1] - 2 * o._opts.strokeWeight) {
							o._fDiv.style.height = x[1] - o._bind.my - 2 * o._opts.strokeWeight + "px";
							o._followTitle && o._followTitle.hide()
						}
					} else {
						o._fDiv.style.top = "auto";
						o._fDiv.style.bottom = x[1] - o._bind.my + "px";
						if (o._bind.my + t <= 2 * o._opts.strokeWeight) {
							o._fDiv.style.height = o._bind.my - 2 * o._opts.strokeWeight + "px";
							o._followTitle && o._followTitle.hide()
						}
					}
					b(z);
					return h(z)
				}
			};
		var r = function(A) {
				if (o._isOpen == true) {
					i(document, "mousemove", m);
					i(document, "mouseup", r);
					if ( !! q && f.getDom(p).releaseCapture) {
						f.getDom(p).releaseCapture()
					}
					var v = parseInt(o._fDiv.style.left) + parseInt(o._fDiv.style.width) / 2;
					var u = parseInt(o._fDiv.style.top) + parseInt(o._fDiv.style.height) / 2;
					var z = [p.getSize().width, p.getSize().height];
					if (isNaN(v)) {
						v = z[0] - parseInt(o._fDiv.style.right) - parseInt(o._fDiv.style.width) / 2
					}
					if (isNaN(u)) {
						u = z[1] - parseInt(o._fDiv.style.bottom) - parseInt(o._fDiv.style.height) / 2
					}
					var C = Math.min(z[0] / Math.abs(o._bind.dx), z[1] / Math.abs(o._bind.dy));
					C = Math.floor(C);
					var x = new BMap.Pixel(v - parseInt(o._fDiv.style.width) / 2, u - parseInt(o._fDiv.style.height) / 2);
					var w = new BMap.Pixel(v + parseInt(o._fDiv.style.width) / 2, u + parseInt(o._fDiv.style.height) / 2);
					var F = p.pixelToPoint(x);
					var E = p.pixelToPoint(w);
					var y = new BMap.Bounds(F, E);
					o._bounds = y;
					delete o._bind.dx;
					delete o._bind.dy;
					delete o._bind.ix;
					delete o._bind.iy;
					if (!isNaN(C)) {
						if (o._opts.zoomType == e) {
							targetZoomLv = Math.round(p.getZoom() + (Math.log(C) / Math.log(2)));
							if (targetZoomLv < p.getZoom()) {
								targetZoomLv = p.getZoom()
							}
						} else {
							targetZoomLv = Math.round(p.getZoom() + (Math.log(1 / C) / Math.log(2)));
							if (targetZoomLv > p.getZoom()) {
								targetZoomLv = p.getZoom()
							}
						}
					} else {
						targetZoomLv = p.getZoom() + (o._opts.zoomType == e ? 1 : -1)
					}
					var s = p.pixelToPoint({
						x: v,
						y: u
					}, p.getZoom());
					p.centerAndZoom(s, targetZoomLv);
					var I = f.getDrawPoint(A);
					if (o._followTitle) {
						o._followTitle.setPosition(I);
						o._followTitle.show()
					}
					o._bind.isZooming = false;
					o._fDiv.parentNode.removeChild(o._fDiv);
					o._fDiv = null
				}
				var t = y.getSouthWest(),
					B = y.getNorthEast(),
					G = new BMap.Point(B.lng, t.lat),
					H = new BMap.Point(t.lng, B.lat),
					D = new BMap.Polygon([t, H, B, G]);
				D.setStrokeWeight(2);
				D.setStrokeOpacity(0.3);
				D.setStrokeColor("#111");
				D.setFillColor("");
				p.addOverlay(D);
				new g({
					duration: 240,
					fps: 20,
					delay: 500,
					render: function(K) {
						var J = 0.3 * (1 - K);
						D.setStrokeOpacity(J)
					},
					finish: function() {
						p.removeOverlay(D);
						D.dispose();
						D = null
					}
				});
				if (o._opts.autoClose) {
					setTimeout(function() {
						if (o._isOpen == true) {
							o.close()
						}
					}, 70)
				}
				b(A);
				return h(A)
			};
		f.show(this._map);
		this.setCursor(this._opts.cursor);
		if (!this._isBeginDrawBinded) {
			d(f.getDom(this._map), "mousedown", n);
			this._isBeginDrawBinded = true
		}
		return true
	};
	c.prototype.close = function() {
		this._bounds = null;
		if (!this._isOpen) {
			return
		}
		this._isOpen = false;
		BMapLib._toolInUse = false;
		this._followTitle && this._followTitle.hide();
		f.hide()
	};
	c.prototype._generateHTML = function() {
		return ["<div style='position:absolute;z-index:300;border:", this._opts.strokeWeight, "px ", this._opts.style, " ", this._opts.strokeColor, "; opacity:", this._opts.opacity, "; background: ", this._opts.fillColor, "; filter:alpha(opacity=", Math.round(this._opts.opacity * 100), "); width:0; height:0; font-size:0'></div>"].join("")
	};

	function a(p, m, o) {
		var n, q;
		if (p.insertAdjacentHTML) {
			p.insertAdjacentHTML(m, o)
		} else {
			n = p.ownerDocument.createRange();
			m = m.toUpperCase();
			if (m == "AFTERBEGIN" || m == "BEFOREEND") {
				n.selectNodeContents(p);
				n.collapse(m == "AFTERBEGIN")
			} else {
				q = m == "BEFOREBEGIN";
				n[q ? "setStartBefore" : "setEndAfter"](p);
				n.collapse(q)
			}
			n.insertNode(n.createContextualFragment(o))
		}
		return p
	}
	function k(n, m) {
		a(n, "beforeEnd", m);
		return n.lastChild
	}
	function b(m) {
		var m = window.event || m;
		m.stopPropagation ? m.stopPropagation() : m.cancelBubble = true
	}
	function h(m) {
		var m = window.event || m;
		m.preventDefault ? m.preventDefault() : m.returnValue = false;
		return false
	}
	function d(m, n, o) {
		if (!m) {
			return
		}
		n = n.replace(/^on/i, "").toLowerCase();
		if (m.addEventListener) {
			m.addEventListener(n, o, false)
		} else {
			if (m.attachEvent) {
				m.attachEvent("on" + n, o)
			}
		}
	}
	function i(m, n, o) {
		if (!m) {
			return
		}
		n = n.replace(/^on/i, "").toLowerCase();
		if (m.removeEventListener) {
			m.removeEventListener(n, o, false)
		} else {
			if (m.detachEvent) {
				m.detachEvent("on" + n, o)
			}
		}
	}
	var f = {
		_map: null,
		_html: "<div style='background:transparent url(http://api.map.baidu.com/images/blank.gif);position:absolute;left:0;top:0;width:100%;height:100%;z-index:1000' unselectable='on'></div>",
		_maskElement: null,
		_cursor: "default",
		_inUse: false,
		show: function(m) {
			if (!this._map) {
				this._map = m
			}
			this._inUse = true;
			if (!this._maskElement) {
				this._createMask(m)
			}
			this._maskElement.style.display = "block"
		},
		_createMask: function(o) {
			this._map = o;
			if (!this._map) {
				return
			}
			var n = this._maskElement = k(this._map.getContainer(), this._html);
			var m = function(p) {
					b(p);
					return h(p)
				};
			d(n, "mouseup", function(p) {
				if (p.button == 2) {
					m(p)
				}
			});
			d(n, "contextmenu", m);
			n.style.display = "none"
		},
		getDrawPoint: function(p, r) {
			p = window.event || p;
			var m = p.layerX || p.offsetX || 0;
			var q = p.layerY || p.offsetY || 0;
			var o = p.target || p.srcElement;
			if (o != f.getDom(this._map) && r == true) {
				while (o && o != this._map.getContainer()) {
					if (!(o.clientWidth == 0 && o.clientHeight == 0 && o.offsetParent && o.offsetParent.nodeName.toLowerCase() == "td")) {
						m += o.offsetLeft;
						q += o.offsetTop
					}
					o = o.offsetParent
				}
			}
			if (o != f.getDom(this._map) && o != this._map.getContainer()) {
				return
			}
			if (typeof m === "undefined" || typeof q === "undefined") {
				return
			}
			if (isNaN(m) || isNaN(q)) {
				return
			}
			return this._map.pixelToPoint(new BMap.Pixel(m, q))
		},
		hide: function() {
			if (!this._map) {
				return
			}
			this._inUse = false;
			if (this._maskElement) {
				this._maskElement.style.display = "none"
			}
		},
		getDom: function(m) {
			if (!this._maskElement) {
				this._createMask(m)
			}
			return this._maskElement
		},
		setCursor: function(m) {
			this._cursor = m || "default";
			if (this._maskElement) {
				this._maskElement.style.cursor = this._cursor
			}
		}
	};

	function g(p) {
		var m = {
			duration: 1000,
			fps: 30,
			delay: 0,
			transition: l.linear,
			onStop: function() {}
		};
		if (p) {
			for (var n in p) {
				m[n] = p[n]
			}
		}
		this._opts = m;
		if (m.delay) {
			var o = this;
			setTimeout(function() {
				o._beginTime = new Date().getTime();
				o._endTime = o._beginTime + o._opts.duration;
				o._launch()
			}, m.delay)
		} else {
			this._beginTime = new Date().getTime();
			this._endTime = this._beginTime + this._opts.duration;
			this._launch()
		}
	}
	g.prototype._launch = function() {
		var n = this;
		var m = new Date().getTime();
		if (m >= n._endTime) {
			if (typeof n._opts.render == "function") {
				n._opts.render(n._opts.transition(1))
			}
			if (typeof n._opts.finish == "function") {
				n._opts.finish()
			}
			return
		}
		n.schedule = n._opts.transition((m - n._beginTime) / n._opts.duration);
		if (typeof n._opts.render == "function") {
			n._opts.render(n.schedule)
		}
		if (!n.terminative) {
			n._timer = setTimeout(function() {
				n._launch()
			}, 1000 / n._opts.fps)
		}
	};
	var l = {
		linear: function(m) {
			return m
		},
		reverse: function(m) {
			return 1 - m
		},
		easeInQuad: function(m) {
			return m * m
		},
		easeInCubic: function(m) {
			return Math.pow(m, 3)
		},
		easeOutQuad: function(m) {
			return -(m * (m - 2))
		},
		easeOutCubic: function(m) {
			return Math.pow((m - 1), 3) + 1
		},
		easeInOutQuad: function(m) {
			if (m < 0.5) {
				return m * m * 2
			} else {
				return -2 * (m - 2) * m - 1
			}
			return
		},
		easeInOutCubic: function(m) {
			if (m < 0.5) {
				return Math.pow(m, 3) * 4
			} else {
				return Math.pow(m - 1, 3) * 4 + 1
			}
		},
		easeInOutSine: function(m) {
			return (1 - Math.cos(Math.PI * m)) / 2
		}
	}
})();
(function() {
	BMapLib.CurveLine = CurveLine;

	function CurveLine(points, opts) {
		var self = this;
		var curvePoints = getCurvePoints(points);
		var polyline = new BMap.Polyline(curvePoints, opts);
		polyline.addEventListener("lineupdate", function() {
			if (this.isEditing) {
				this.enableEditing()
			}
		});
		polyline.cornerPoints = points;
		polyline.editMarkers = [];
		polyline.enableEditing = function() {
			var self = this;
			if (self.map) {
				self.disableEditing();
				for (var i = 0; i < self.cornerPoints.length; i++) {
					var marker = new BMap.Marker(self.cornerPoints[i], {
						icon: new BMap.Icon("http://api.map.baidu.com/library/CurveLine/1.5/src/circle.png", new BMap.Size(16, 16)),
						enableDragging: true,
						raiseOnDrag: true
					});
					marker.addEventListener("dragend", function() {
						self.cornerPoints.length = 0;
						for (var i = 0; i < self.editMarkers.length; i++) {
							self.cornerPoints.push(self.editMarkers[i].getPosition())
						}
						var curvePoints = getCurvePoints(self.cornerPoints);
						self.setPath(curvePoints)
					});
					marker.index = i;
					self.editMarkers.push(marker);
					self.map.addOverlay(marker)
				}
			}
			self.isEditing = true
		};
		polyline.disableEditing = function() {
			this.isEditing = false;
			for (var i = 0; i < this.editMarkers.length; i++) {
				this.map.removeOverlay(this.editMarkers[i]);
				this.editMarkers[i] = null
			}
			this.editMarkers.length = 0
		};
		polyline.getPath = function() {
			return curvePoints
		};
		return polyline
	}
	function extend(child, parent) {
		for (var p in parent) {
			if (parent.hasOwnProperty(p)) {
				child[p] = parent[p]
			}
		}
		return child
	}
	function getCurvePoints(points) {
		var curvePoints = [];
		for (var i = 0; i < points.length - 1; i++) {
			var p = getCurveByTwoPoints(points[i], points[i + 1]);
			if (p && p.length > 0) {
				curvePoints = curvePoints.concat(p)
			}
		}
		return curvePoints
	}
	function getCurveByTwoPoints(obj1, obj2) {
		if (!obj1 || !obj2 || !(obj1 instanceof BMap.Point) || !(obj2 instanceof BMap.Point)) {
			return null
		}
		var B1 = function(x) {
				return 1 - 2 * x + x * x
			};
		var B2 = function(x) {
				return 2 * x - 2 * x * x
			};
		var B3 = function(x) {
				return x * x
			};
		curveCoordinates = [];
		var count = 30;
		var isFuture = false;
		var t, h, h2, lat3, lng3, j, t2;
		var LnArray = [];
		var i = 0;
		var inc = 0;
		if (typeof(obj2) == "undefined") {
			if (typeof(curveCoordinates) != "undefined") {
				curveCoordinates = []
			}
			return
		}
		var lat1 = parseFloat(obj1.lat);
		var lat2 = parseFloat(obj2.lat);
		var lng1 = parseFloat(obj1.lng);
		var lng2 = parseFloat(obj2.lng);
		if (lng2 > lng1) {
			if (parseFloat(lng2 - lng1) > 180) {
				if (lng1 < 0) {
					lng1 = parseFloat(180 + 180 + lng1)
				}
			}
		}
		if (lng1 > lng2) {
			if (parseFloat(lng1 - lng2) > 180) {
				if (lng2 < 0) {
					lng2 = parseFloat(180 + 180 + lng2)
				}
			}
		}
		j = 0;
		t2 = 0;
		if (lat2 == lat1) {
			t = 0;
			h = lng1 - lng2
		} else {
			if (lng2 == lng1) {
				t = Math.PI / 2;
				h = lat1 - lat2
			} else {
				t = Math.atan((lat2 - lat1) / (lng2 - lng1));
				h = (lat2 - lat1) / Math.sin(t)
			}
		}
		if (t2 == 0) {
			t2 = (t + (Math.PI / 5))
		}
		h2 = h / 2;
		lng3 = h2 * Math.cos(t2) + lng1;
		lat3 = h2 * Math.sin(t2) + lat1;
		for (i = 0; i < count + 1; i++) {
			curveCoordinates.push(new BMap.Point((lng1 * B1(inc) + lng3 * B2(inc)) + lng2 * B3(inc), (lat1 * B1(inc) + lat3 * B2(inc) + lat2 * B3(inc))));
			inc = inc + (1 / count)
		}
		return curveCoordinates
	}
})();
var BMapLib = window.BMapLib = BMapLib || {};
(function() {
	var a = a || {
		guid: "$BAIDU$"
	};
	(function() {
		window[a.guid] = {};
		a.extend = function(h, f) {
			for (var g in f) {
				if (f.hasOwnProperty(g)) {
					h[g] = f[g]
				}
			}
			return h
		};
		a.lang = a.lang || {};
		a.lang.guid = function() {
			return "TANGRAM__" + (window[a.guid]._counter++).toString(36)
		};
		window[a.guid]._counter = window[a.guid]._counter || 1;
		window[a.guid]._instances = window[a.guid]._instances || {};
		a.lang.Class = function(f) {
			this.guid = f || a.lang.guid();
			window[a.guid]._instances[this.guid] = this
		};
		window[a.guid]._instances = window[a.guid]._instances || {};
		a.lang.isString = function(f) {
			return "[object String]" == Object.prototype.toString.call(f)
		};
		a.isString = a.lang.isString;
		a.lang.isFunction = function(f) {
			return "[object Function]" == Object.prototype.toString.call(f)
		};
		a.lang.Event = function(f, g) {
			this.type = f;
			this.returnValue = true;
			this.target = g || null;
			this.currentTarget = null
		};
		a.lang.Class.prototype.addEventListener = function(i, h, g) {
			if (!a.lang.isFunction(h)) {
				return
			}!this.__listeners && (this.__listeners = {});
			var f = this.__listeners,
				j;
			if (typeof g == "string" && g) {
				if (/[^\w\-]/.test(g)) {
					throw ("nonstandard key:" + g)
				} else {
					h.hashCode = g;
					j = g
				}
			}
			i.indexOf("on") != 0 && (i = "on" + i);
			typeof f[i] != "object" && (f[i] = {});
			j = j || a.lang.guid();
			h.hashCode = j;
			f[i][j] = h
		};
		a.lang.Class.prototype.removeEventListener = function(h, g) {
			if (a.lang.isFunction(g)) {
				g = g.hashCode
			} else {
				if (!a.lang.isString(g)) {
					return
				}
			}!this.__listeners && (this.__listeners = {});
			h.indexOf("on") != 0 && (h = "on" + h);
			var f = this.__listeners;
			if (!f[h]) {
				return
			}
			f[h][g] && delete f[h][g]
		};
		a.lang.Class.prototype.dispatchEvent = function(j, f) {
			if (a.lang.isString(j)) {
				j = new a.lang.Event(j)
			}!this.__listeners && (this.__listeners = {});
			f = f || {};
			for (var h in f) {
				j[h] = f[h]
			}
			var h, g = this.__listeners,
				k = j.type;
			j.target = j.target || this;
			j.currentTarget = this;
			k.indexOf("on") != 0 && (k = "on" + k);
			a.lang.isFunction(this[k]) && this[k].apply(this, arguments);
			if (typeof g[k] == "object") {
				for (h in g[k]) {
					g[k][h].apply(this, arguments)
				}
			}
			return j.returnValue
		};
		a.dom = a.dom || {};
		a.dom._g = function(f) {
			if (a.lang.isString(f)) {
				return document.getElementById(f)
			}
			return f
		};
		a._g = a.dom._g;
		a.event = a.event || {};
		a.event._listeners = a.event._listeners || [];
		a.event.on = function(g, j, l) {
			j = j.replace(/^on/i, "");
			g = a.dom._g(g);
			var k = function(n) {
					l.call(g, n)
				},
				f = a.event._listeners,
				i = a.event._eventFilter,
				m, h = j;
			j = j.toLowerCase();
			if (i && i[j]) {
				m = i[j](g, j, k);
				h = m.type;
				k = m.listener
			}
			if (g.addEventListener) {
				g.addEventListener(h, k, false)
			} else {
				if (g.attachEvent) {
					g.attachEvent("on" + h, k)
				}
			}
			f[f.length] = [g, j, l, k, h];
			return g
		};
		a.on = a.event.on;
		a.event.un = function(h, k, g) {
			h = a.dom._g(h);
			k = k.replace(/^on/i, "").toLowerCase();
			var n = a.event._listeners,
				i = n.length,
				j = !g,
				m, l, f;
			while (i--) {
				m = n[i];
				if (m[1] === k && m[0] === h && (j || m[2] === g)) {
					l = m[4];
					f = m[3];
					if (h.removeEventListener) {
						h.removeEventListener(l, f, false)
					} else {
						if (h.detachEvent) {
							h.detachEvent("on" + l, f)
						}
					}
					n.splice(i, 1)
				}
			}
			return h
		};
		a.un = a.event.un;
		a.preventDefault = a.event.preventDefault = function(f) {
			if (f.preventDefault) {
				f.preventDefault()
			} else {
				f.returnValue = false
			}
		}
	})();
	var e = BMapLib.RichMarker = function(h, f, g) {
			if (!h || !f || !(f instanceof BMap.Point)) {
				return
			}
			this._map = null;
			this._content = h;
			this._position = f;
			this._container = null;
			this._size = null;
			g = g || {};
			this._opts = a.extend(a.extend(this._opts || {}, {
				enableDragging: false,
				anchor: new BMap.Size(0, 0)
			}), g)
		};
	e.prototype = new BMap.Overlay();
	e.prototype.initialize = function(g) {
		var f = this,
			h = f._container = document.createElement("div");
		f._map = g;
		a.extend(h.style, {
			position: "absolute",
			zIndex: BMap.Overlay.getZIndex(f._position.lat),
			background: "none",
			cursor: "pointer"
		});
		g.getPanes().labelPane.appendChild(h);
		f._appendContent();
		f._setEventDispath();
		f._getContainerSize();
		return h
	};
	e.prototype.draw = function() {
		var h = this._map,
			g = this._opts.anchor,
			f = h.pointToOverlayPixel(this._position);
		this._container.style.left = f.x + g.width + "px";
		this._container.style.top = f.y + g.height + "px"
	};
	e.prototype.enableDragging = function() {
		this._opts.enableDragging = true
	};
	e.prototype.disableDragging = function() {
		this._opts.enableDragging = false
	};
	e.prototype.isDraggable = function() {
		return this._opts.enableDragging
	};
	e.prototype.getPosition = function() {
		return this._position
	};
	e.prototype.setPosition = function(f) {
		if (!f instanceof BMap.Point) {
			return
		}
		this._position = f;
		this.draw()
	};
	e.prototype.getAnchor = function() {
		return this._opts.anchor
	};
	e.prototype.setAnchor = function(f) {
		if (!f instanceof BMap.Size) {
			return
		}
		this._opts.anchor = f;
		this.draw()
	};
	e.prototype._appendContent = function() {
		var g = this._content;
		if (typeof g == "string") {
			var h = document.createElement("DIV");
			h.innerHTML = g;
			if (h.childNodes.length == 1) {
				g = (h.removeChild(h.firstChild))
			} else {
				var f = document.createDocumentFragment();
				while (h.firstChild) {
					f.appendChild(h.firstChild)
				}
				g = f
			}
		}
		this._container.innerHTML = "";
		this._container.appendChild(g)
	};
	e.prototype.getContent = function() {
		return this._content
	};
	e.prototype.setContent = function(f) {
		if (!f) {
			return
		}
		this._content = f;
		this._appendContent()
	};
	e.prototype._getContainerSize = function() {
		if (!this._container) {
			return
		}
		var g = this._container.offsetHeight;
		var f = this._container.offsetWidth;
		this._size = new BMap.Size(f, g)
	};
	e.prototype.getWidth = function() {
		if (!this._size) {
			return
		}
		return this._size.width
	};
	e.prototype.setWidth = function(f) {
		if (!this._container) {
			return
		}
		this._container.style.width = f + "px";
		this._getContainerSize()
	};
	e.prototype.getHeight = function() {
		if (!this._size) {
			return
		}
		return this._size.height
	};
	e.prototype.setHeight = function(f) {
		if (!this._container) {
			return
		}
		this._container.style.height = f + "px";
		this._getContainerSize()
	};
	e.prototype._setEventDispath = function() {
		var k = this,
			l = k._container,
			g = false,
			i = null;

		function j(p) {
			var p = window.event || p,
				n = p.pageX || p.clientX || 0,
				q = p.pageY || p.clientY || 0,
				o = new BMap.Pixel(n, q),
				m = k._map.pixelToPoint(o);
			return {
				pixel: o,
				point: m
			}
		}
		a.on(l, "onclick", function(m) {
			c(k, "onclick");
			d(m)
		});
		a.on(l, "ondblclick", function(n) {
			var m = j(n);
			c(k, "ondblclick", {
				point: m.point,
				pixel: m.pixel
			});
			d(n)
		});
		l.onmouseover = function(n) {
			var m = j(n);
			c(k, "onmouseover", {
				point: m.point,
				pixel: m.pixel
			});
			d(n)
		};
		l.onmouseout = function(n) {
			var m = j(n);
			c(k, "onmouseout", {
				point: m.point,
				pixel: m.pixel
			});
			d(n)
		};
		var h = function(n) {
				var m = j(n);
				c(k, "onmouseup", {
					point: m.point,
					pixel: m.pixel
				});
				if (k._container.releaseCapture) {
					a.un(l, "onmousemove", f);
					a.un(l, "onmouseup", h)
				} else {
					a.un(window, "onmousemove", f);
					a.un(window, "onmouseup", h)
				}
				if (!k._opts.enableDragging) {
					d(n);
					return
				}
				k._container.releaseCapture && k._container.releaseCapture();
				c(k, "ondragend", {
					point: m.point,
					pixel: m.pixel
				});
				g = false;
				i = null;
				k._setCursor("dragend");
				k._container.style.MozUserSelect = "";
				k._container.style.KhtmlUserSelect = "";
				k._container.style.WebkitUserSelect = "";
				k._container.unselectable = "off";
				k._container.onselectstart = function() {};
				d(n)
			};
		var f = function(o) {
				if (!k._opts.enableDragging || !g) {
					return
				}
				var n = j(o);
				var p = k._map.pointToPixel(k._position);
				var m = n.pixel.x - i.x + p.x;
				var q = n.pixel.y - i.y + p.y;
				i = n.pixel;
				k._position = k._map.pixelToPoint(new BMap.Pixel(m, q));
				k.draw();
				k._setCursor("dragging");
				c(k, "ondragging", {
					point: n.point,
					pixel: n.pixel
				});
				d(o)
			};
		a.on(l, "onmousedown", function(n) {
			var m = j(n);
			c(k, "onmousedown", {
				point: m.point,
				pixel: m.pixel
			});
			if (k._container.setCapture) {
				a.on(l, "onmousemove", f);
				a.on(l, "onmouseup", h)
			} else {
				a.on(window, "onmousemove", f);
				a.on(window, "onmouseup", h)
			}
			if (!k._opts.enableDragging) {
				d(n);
				return
			}
			i = m.pixel;
			c(k, "ondragstart", {
				point: m.point,
				pixel: m.pixel
			});
			g = true;
			k._setCursor("dragstart");
			k._container.setCapture && k._container.setCapture();
			k._container.style.MozUserSelect = "none";
			k._container.style.KhtmlUserSelect = "none";
			k._container.style.WebkitUserSelect = "none";
			k._container.unselectable = "on";
			k._container.onselectstart = function() {
				return false
			};
			d(n)
		})
	};
	e.prototype._setCursor = function(f) {
		var h = "";
		var g = {
			moz: {
				dragstart: "-moz-grab",
				dragging: "-moz-grabbing",
				dragend: "pointer"
			},
			other: {
				dragstart: "move",
				dragging: "move",
				dragend: "pointer"
			}
		};
		if (navigator.userAgent.indexOf("Gecko/") !== -1) {
			h = g.moz[f]
		} else {
			h = g.other[f]
		}
		if (this._container.style.cursor != h) {
			this._container.style.cursor = h
		}
	};
	e.prototype.remove = function() {
		c(this, "onremove");
		if (this._container) {
			b(this._container)
		}
		if (this._container && this._container.parentNode) {
			this._container.parentNode.removeChild(this._container)
		}
	};

	function c(f, g, i) {
		g.indexOf("on") != 0 && (g = "on" + g);
		var h = new a.lang.Event(g);
		if ( !! i) {
			for (var j in i) {
				h[j] = i[j]
			}
		}
		f.dispatchEvent(h)
	}
	function b(j) {
		if (!j) {
			return
		}
		var g = j.attributes,
			f = "";
		if (g) {
			for (var h = 0, l = g.length; h < l; h++) {
				f = g[h].name;
				if (typeof j[f] === "function") {
					j[f] = null
				}
			}
		}
		var k = j.childnodes;
		if (k) {
			for (var h = 0, l = k.length; h < l; h++) {
				b(j.childnodes[h])
			}
		}
	}
	function d(f) {
		var f = window.event || f;
		f.stopPropagation ? f.stopPropagation() : f.cancelBubble = true;
		return a.preventDefault(f)
	}
})();
(function() {
	var c = c || {
		guid: "$BAIDU$"
	};
	(function() {
		window[c.guid] = {};
		c.extend = function(g, e) {
			for (var f in e) {
				if (e.hasOwnProperty(f)) {
					g[f] = e[f]
				}
			}
			return g
		};
		c.lang = c.lang || {};
		c.lang.guid = function() {
			return "TANGRAM__" + (window[c.guid]._counter++).toString(36)
		};
		window[c.guid]._counter = window[c.guid]._counter || 1;
		window[c.guid]._instances = window[c.guid]._instances || {};
		c.lang.Class = function(e) {
			this.guid = e || c.lang.guid();
			window[c.guid]._instances[this.guid] = this
		};
		window[c.guid]._instances = window[c.guid]._instances || {};
		c.lang.isString = function(e) {
			return "[object String]" == Object.prototype.toString.call(e)
		};
		c.lang.isFunction = function(e) {
			return "[object Function]" == Object.prototype.toString.call(e)
		};
		c.lang.Class.prototype.toString = function() {
			return "[object " + (this._className || "Object") + "]"
		};
		c.lang.Class.prototype.dispose = function() {
			delete window[c.guid]._instances[this.guid];
			for (var e in this) {
				if (!c.lang.isFunction(this[e])) {
					delete this[e]
				}
			}
			this.disposed = true
		};
		c.lang.Event = function(e, f) {
			this.type = e;
			this.returnValue = true;
			this.target = f || null;
			this.currentTarget = null
		};
		c.lang.Class.prototype.addEventListener = function(h, g, f) {
			if (!c.lang.isFunction(g)) {
				return
			}!this.__listeners && (this.__listeners = {});
			var e = this.__listeners,
				i;
			if (typeof f == "string" && f) {
				if (/[^\w\-]/.test(f)) {
					throw ("nonstandard key:" + f)
				} else {
					g.hashCode = f;
					i = f
				}
			}
			h.indexOf("on") != 0 && (h = "on" + h);
			typeof e[h] != "object" && (e[h] = {});
			i = i || c.lang.guid();
			g.hashCode = i;
			e[h][i] = g
		};
		c.lang.Class.prototype.removeEventListener = function(g, f) {
			if (c.lang.isFunction(f)) {
				f = f.hashCode
			} else {
				if (!c.lang.isString(f)) {
					return
				}
			}!this.__listeners && (this.__listeners = {});
			g.indexOf("on") != 0 && (g = "on" + g);
			var e = this.__listeners;
			if (!e[g]) {
				return
			}
			e[g][f] && delete e[g][f]
		};
		c.lang.Class.prototype.dispatchEvent = function(h, e) {
			if (c.lang.isString(h)) {
				h = new c.lang.Event(h)
			}!this.__listeners && (this.__listeners = {});
			e = e || {};
			for (var g in e) {
				h[g] = e[g]
			}
			var g, f = this.__listeners,
				j = h.type;
			h.target = h.target || this;
			h.currentTarget = this;
			j.indexOf("on") != 0 && (j = "on" + j);
			c.lang.isFunction(this[j]) && this[j].apply(this, arguments);
			if (typeof f[j] == "object") {
				for (g in f[j]) {
					f[j][g].apply(this, arguments)
				}
			}
			return h.returnValue
		};
		c.lang.inherits = function(k, i, h) {
			var g, j, e = k.prototype,
				f = new Function();
			f.prototype = i.prototype;
			j = k.prototype = new f();
			for (g in e) {
				j[g] = e[g]
			}
			k.prototype.constructor = k;
			k.superClass = i.prototype;
			if ("string" == typeof h) {
				j._className = h
			}
		};
		c.dom = c.dom || {};
		c._g = c.dom._g = function(e) {
			if (c.lang.isString(e)) {
				return document.getElementById(e)
			}
			return e
		};
		c.g = c.dom.g = function(e) {
			if ("string" == typeof e || e instanceof String) {
				return document.getElementById(e)
			} else {
				if (e && e.nodeName && (e.nodeType == 1 || e.nodeType == 9)) {
					return e
				}
			}
			return null
		};
		c.insertHTML = c.dom.insertHTML = function(h, e, g) {
			h = c.dom.g(h);
			var f, i;
			if (h.insertAdjacentHTML) {
				h.insertAdjacentHTML(e, g)
			} else {
				f = h.ownerDocument.createRange();
				e = e.toUpperCase();
				if (e == "AFTERBEGIN" || e == "BEFOREEND") {
					f.selectNodeContents(h);
					f.collapse(e == "AFTERBEGIN")
				} else {
					i = e == "BEFOREBEGIN";
					f[i ? "setStartBefore" : "setEndAfter"](h);
					f.collapse(i)
				}
				f.insertNode(f.createContextualFragment(g))
			}
			return h
		};
		c.ac = c.dom.addClass = function(k, m) {
			k = c.dom.g(k);
			var f = m.split(/\s+/),
				e = k.className,
				j = " " + e + " ",
				h = 0,
				g = f.length;
			for (; h < g; h++) {
				if (j.indexOf(" " + f[h] + " ") < 0) {
					e += (e ? " " : "") + f[h]
				}
			}
			k.className = e;
			return k
		};
		c.event = c.event || {};
		c.event._listeners = c.event._listeners || [];
		c.on = c.event.on = function(f, i, k) {
			i = i.replace(/^on/i, "");
			f = c._g(f);
			var j = function(m) {
					k.call(f, m)
				},
				e = c.event._listeners,
				h = c.event._eventFilter,
				l, g = i;
			i = i.toLowerCase();
			if (h && h[i]) {
				l = h[i](f, i, j);
				g = l.type;
				j = l.listener
			}
			if (f.addEventListener) {
				f.addEventListener(g, j, false)
			} else {
				if (f.attachEvent) {
					f.attachEvent("on" + g, j)
				}
			}
			e[e.length] = [f, i, k, j, g];
			return f
		};
		c.un = c.event.un = function(g, j, f) {
			g = c._g(g);
			j = j.replace(/^on/i, "").toLowerCase();
			var m = c.event._listeners,
				h = m.length,
				i = !f,
				l, k, e;
			while (h--) {
				l = m[h];
				if (l[1] === j && l[0] === g && (i || l[2] === f)) {
					k = l[4];
					e = l[3];
					if (g.removeEventListener) {
						g.removeEventListener(k, e, false)
					} else {
						if (g.detachEvent) {
							g.detachEvent("on" + k, e)
						}
					}
					m.splice(h, 1)
				}
			}
			return g
		};
		c.preventDefault = c.event.preventDefault = function(e) {
			if (e.preventDefault) {
				e.preventDefault()
			} else {
				e.returnValue = false
			}
		}
	})();
	var d = BMapLib.DistanceTool = function(f, e) {
			if (!f) {
				return
			}
			this._map = f;
			e = e || {};
			this._opts = c.extend(c.extend(this._opts || {}, {
				tips: "测距",
				followText: "单击确定地点，双击结束",
				unit: "metric",
				lineColor: "#ff6319",
				lineStroke: 2,
				opacity: 0.8,
				lineStyle: "solid",
				cursor: "http://api.map.baidu.com/images/ruler.cur",
				secIcon: null,
				closeIcon: null
			}), e);
			this._followTitle = null;
			this._points = [];
			this._paths = [];
			this._dots = [];
			this._segDistance = [];
			this._overlays = [];
			this._enableMassClear = true, this._units = {
				metric: {
					name: "metric",
					conv: 1,
					incon: 1000,
					u1: "米",
					u2: "公里"
				},
				us: {
					name: "us",
					conv: 3.2808,
					incon: 5279.856,
					u1: "英尺",
					u2: "英里"
				}
			};
			this._isOpen = false;
			this._startFollowText = "单击确定起点";
			this._movingTimerId = null;
			this._styles = {
				BMapLib_diso: "height:17px;width:5px;position:absolute;background:url(http://api.map.baidu.com/images/dis_box_01.gif) no-repeat left top",
				BMapLib_disi: "color:#7a7a7a;position:absolute;left:5px;padding:0 4px 1px 0;line-height:17px;background:url(http://api.map.baidu.com/images/dis_box_01.gif) no-repeat right top",
				BMapLib_disBoxDis: "color:#ff6319;font-weight:bold"
			};
			if (this._opts.lineStroke <= 0) {
				this._opts.lineStroke = 2
			}
			if (this._opts.opacity > 1) {
				this._opts.opacity = 1
			} else {
				if (this._opts.opacity < 0) {
					this._opts.opacity = 0
				}
			}
			if (this._opts.lineStyle != "solid" && this._opts.lineStyle != "dashed") {
				this._opts.lineStyle = "solid"
			}
			if (!this._units[this._opts.unit]) {
				this._opts.unit = "metric"
			}
			this.text = "测距"
		};
	c.lang.inherits(d, c.lang.Class, "DistanceTool");
	d.prototype._bind = function() {
		this._setCursor(this._opts.cursor);
		var f = this;
		c.on(this._map.getContainer(), "mousemove", function(i) {
			if (!f._isOpen) {
				return
			}
			if (!f._followTitle) {
				return
			}
			i = window.event || i;
			var g = i.target || i.srcElement;
			if (g != a.getDom(f._map)) {
				f._followTitle.hide();
				return
			}
			if (!f._mapMoving) {
				f._followTitle.show()
			}
			var h = a.getDrawPoint(i, true);
			f._followTitle.setPosition(h)
		});
		if (this._startFollowText) {
			var e = this._followTitle = new BMap.Label(this._startFollowText, {
				offset: new BMap.Size(14, 16)
			});
			this._followTitle.setStyles({
				color: "#333",
				borderColor: "#ff0103"
			})
		}
	};
	d.prototype.open = function() {
		if (this._isOpen == true) {
			return true
		}
		if ( !! BMapLib._toolInUse) {
			return
		}
		this._isOpen = true;
		BMapLib._toolInUse = true;
		if (this._mapMoving) {
			delete this._mapMoving
		}
		var h = this;
		if (!this._binded) {
			this._binded = true;
			this._bind();
			this._map.addEventListener("moving", function() {
				h._hideCurrent()
			})
		}
		if (this._followTitle) {
			this._map.addOverlay(this._followTitle);
			this._followTitle.hide()
		}
		var g = function(q) {
				var l = h._map;
				if (!h._isOpen) {
					return
				}
				q = window.event || q;
				var n = a.getDrawPoint(q, true);
				if (!h._isPointValid(n)) {
					return
				}
				h._bind.initX = q.pageX || q.clientX || 0;
				h._bind.initY = q.pageY || q.clientY || 0;
				if (h._points.length > 0) {
					var t = l.pointToPixel(h._points[h._points.length - 1]);
					var m = l.pointToPixel(n);
					var p = Math.sqrt(Math.pow(t.x - m.x, 2) + Math.pow(t.y - m.y, 2));
					if (p < 5) {
						return
					}
				}
				h._bind.x = q.layerX || q.offsetX || 0;
				h._bind.y = q.layerY || q.offsetY || 0;
				h._points.push(n);
				h._addSecPoint(n);
				if (h._paths.length == 0) {
					h._formatTitle(1, h._opts.followText, h._getTotalDistance())
				}
				if (h._paths.length > 0) {
					h._paths[h._paths.length - 1].show();
					h._paths[h._paths.length - 1].setStrokeOpacity(h._opts.opacity)
				}
				var w = new BMap.Polyline([n, n], {
					enableMassClear: h._enableMassClear
				});
				h._map.addOverlay(w);
				h._paths.push(w);
				h._overlays.push(w);
				w.setStrokeWeight(h._opts.lineStroke);
				w.setStrokeColor(h._opts.lineColor);
				w.setStrokeOpacity(h._opts.opacity / 2);
				w.setStrokeStyle(h._opts.lineStyle);
				if (h._mapMoving) {
					w.hide()
				}
				if (h._points.length > 1) {
					var o = h._paths[h._points.length - 2];
					o.setPositionAt(1, n)
				}
				var r = "";
				if (h._points.length > 1) {
					var u = h._setSegDistance(h._points[h._points.length - 2], h._points[h._points.length - 1]);
					var s = h._getTotalDistance();
					r = h._formatDisStr(s)
				} else {
					r = "起点"
				}
				var v = new BMap.Label(r, {
					offset: new BMap.Size(10, -5),
					enableMassClear: h._enableMassClear
				});
				v.setStyles({
					color: "#333",
					borderColor: "#ff0103"
				});
				h._map.addOverlay(v);
				h._formatSegLabel(v, r);
				h._overlays.push(v);
				n.disLabel = v;
				v.setPosition(n);
				var k = new c.lang.Event("onaddpoint");
				k.point = n;
				k.pixel = h._map.pointToPixel(n);
				k.index = h._points.length - 1;
				k.distance = h._getTotalDistance().toFixed(0);
				h.dispatchEvent(k)
			};
		var f = function(p) {
				if (!h._isOpen) {
					return
				}
				if (h._paths.length > 0) {
					p = window.event || p;
					var l = p.pageX || p.clientX || 0;
					var k = p.pageY || p.clientY || 0;
					if (typeof h._bind.initX == "undefined") {
						h._bind.x = p.layerX || p.offsetX || 0;
						h._bind.y = p.layerY || p.offsetY || 0;
						h._bind.initX = l;
						h._bind.initY = k
					}
					var r = h._bind.x + l - h._bind.initX;
					var q = h._bind.y + k - h._bind.initY;
					var z = h._paths[h._paths.length - 1];
					var m = h._map.pixelToPoint(new BMap.Pixel(r, q));
					z.setPositionAt(1, m);
					if (!h._mapMoving) {
						z.show()
					}
					var A = 0;
					var u = 0;
					if (r < 10) {
						A = 8
					} else {
						if (r > h._map.getSize().width - 10) {
							A = -8
						}
					}
					if (q < 10) {
						u = 8
					} else {
						if (q > h._map.getSize().height - 10) {
							u = -8
						}
					}
					if (A != 0 || u != 0) {
						if (!f._movingTimerId) {
							h._mapMoving = true;
							h._map.panBy(A, u, {
								noAnimation: true
							});
							h._movingTimerId = f._movingTimerId = setInterval(function() {
								h._map.panBy(A, u, {
									noAnimation: true
								})
							}, 30);
							z.hide();
							h._followTitle && h._followTitle.hide()
						}
					} else {
						if (f._movingTimerId) {
							clearInterval(f._movingTimerId);
							delete f._movingTimerId;
							delete h._movingTimerId;
							var w = h._paths[h._paths.length - 1];
							var v = h._map.pixelToPoint(new BMap.Pixel(r, q));
							if (!w) {
								return
							}
							w.setPositionAt(1, v);
							w.show();
							if (h._followTitle) {
								h._followTitle.setPosition(v);
								h._followTitle.show()
							}
							h._bind.i = 0;
							h._bind.j = 0;
							delete h._mapMoving
						}
					}
					if (h._followTitle) {
						var o = h._getTotalDistance();
						var n = h._map.getDistance(h._points[h._points.length - 1], m);
						h._updateInstDis(h._followTitle, o + n)
					}
				} else {
					if (h._followTitle) {
						h._followTitle.show();
						p = window.event || p;
						var s = p.target || p.srcElement;
						if (s != a.getDom()) {
							h._followTitle.hide()
						}
					}
				}
			};
		var e = function(k) {
				if (!h._isOpen) {
					return
				}
				c.un(a.getDom(h._map), "click", g);
				c.un(document, "mousemove", f);
				c.un(a.getDom(h._map), "dblclick", e);
				c.un(document, "keydown", j);
				c.un(a.getDom(h._map), "mouseup", i);
				setTimeout(function() {
					h.close()
				}, 50)
			};
		var j = function(k) {
				k = window.event || k;
				if (k.keyCode == 27) {
					h._clearCurData();
					setTimeout(function() {
						h.close()
					}, 50)
				}
			};
		var i = function(k) {
				k = window.event || k;
				var l = 0;
				if (/msie (\d+\.\d)/i.test(navigator.userAgent)) {
					l = document.documentMode || +RegExp["$1"]
				}
				if (l && k.button != 1 || k.button == 2) {
					h.close()
				}
			};
		h._initData();
		this._formatTitle();
		a.show(this._map);
		this._setCursor(this._opts.cursor);
		c.on(a.getDom(this._map), "click", g);
		c.on(document, "mousemove", f);
		c.on(a.getDom(this._map), "dblclick", e);
		c.on(document, "keydown", j);
		c.on(a.getDom(this._map), "mouseup", i);
		this.bindFunc = [{
			elem: a.getDom(this._map),
			type: "click",
			func: g
		}, {
			elem: a.getDom(this._map),
			type: "dblclick",
			func: e
		}, {
			elem: document,
			type: "mousemove",
			func: f
		}, {
			elem: document,
			type: "keydown",
			func: j
		}, {
			elem: a.getDom(this._map),
			type: "mouseup",
			func: i
		}];
		return true
	};
	d.prototype._dispatchLastEvent = function() {
		var e = new c.lang.Event("ondrawend");
		e.points = this._points ? this._points.slice(0) : [];
		e.overlays = this._paths ? this._paths.slice(0, this._paths.length - 1) : [];
		e.distance = this._getTotalDistance().toFixed(0);
		this.dispatchEvent(e)
	};
	d.prototype.close = function() {
		if (this._isOpen == false) {
			return
		}
		this._isOpen = false;
		BMapLib._toolInUse = false;
		if (this._mapMoving) {
			delete this._mapMoving
		}
		var g = this;
		g._dispatchLastEvent();
		if (g._points.length < 2) {
			g._clearCurData()
		} else {
			g._paths[g._paths.length - 1].remove();
			g._paths[g._paths.length - 1] = null;
			g._paths.length = g._paths.length - 1;
			var h = g._points[g._points.length - 1];
			if (h.disLabel) {
				h.disLabel.remove()
			}
			g._processLastOp()
		}
		a.hide();
		for (var f = 0, e = this.bindFunc.length; f < e; f++) {
			c.un(this.bindFunc[f].elem, this.bindFunc[f].type, this.bindFunc[f].func)
		}
		if (g._movingTimerId) {
			clearInterval(g._movingTimerId);
			g._movingTimerId = null
		}
		if (this._followTitle) {
			this._followTitle.hide()
		}
	};
	d.prototype._clearCurData = function() {
		for (var f = 0, e = this._points.length; f < e; f++) {
			if (this._points[f].disLabel) {
				this._points[f].disLabel.remove()
			}
		}
		for (var f = 0, e = this._paths.length; f < e; f++) {
			this._paths[f].remove()
		}
		for (var f = 0, e = this._dots.length; f < e; f++) {
			this._dots[f].remove()
		}
		this._initData()
	};
	d.prototype._initData = function() {
		this._points.length = 0;
		this._paths.length = 0;
		this._segDistance.length = 0;
		this._dots.length = 0
	};
	d.prototype._setSegDistance = function(g, f) {
		if (!g || !f) {
			return
		}
		var e = this._map.getDistance(g, f);
		this._segDistance.push(e);
		return e
	};
	d.prototype._getTotalDistance = function() {
		var g = 0;
		for (var f = 0, e = this._segDistance.length; f < e; f++) {
			g += this._segDistance[f]
		}
		return g
	};
	d.prototype._convertUnit = function(e, f) {
		f = f || "metric";
		if (this._units[f]) {
			return e * this._units[f].conv
		}
		return e
	};
	d.prototype._addSecPoint = function(g) {
		var f = this._opts.secIcon ? this._opts.secIcon : new BMap.Icon("http://api.map.baidu.com/images/mapctrls.png", new BMap.Size(11, 11), {
			imageOffset: new BMap.Size(-26, -313)
		});
		var e = new BMap.Marker(g, {
			icon: f,
			clickable: false,
			baseZIndex: 3500000,
			zIndexFixed: true,
			enableMassClear: this._enableMassClear
		});
		this._map.addOverlay(e);
		this._dots.push(e)
	};
	d.prototype._formatDisStr = function(h) {
		var f = this._opts.unit;
		var g = this._units[f].u1;
		var e = this._convertUnit(h, f);
		if (e > this._units[f].incon) {
			e = e / this._units[f].incon;
			g = this._units[f].u2;
			e = e.toFixed(1)
		} else {
			e = e.toFixed(0)
		}
		return e + g
	};
	d.prototype._setCursor = function(f) {
		var e = /webkit/.test(navigator.userAgent.toLowerCase()) ? "url(" + this._opts.cursor + ") 3 6, crosshair" : "url(" + this._opts.cursor + "), crosshair";
		a._setCursor(e)
	};
	d.prototype._getCursor = function() {
		return this._opts.cursor
	};
	d.prototype._formatSegLabel = function(e, f) {
		e.setStyle({
			border: "none",
			padding: "0"
		});
		e.setContent("<span style='" + this._styles.BMapLib_diso + "'><span style='" + this._styles.BMapLib_disi + "'>" + f + "</span></span>")
	};
	d.prototype._processLastOp = function() {
		var i = this;
		delete i._bind.x;
		delete i._bind.y;
		delete i._bind.initX;
		delete i._bind.initY;
		if (i._paths.length > i._points.length - 1) {
			var g = i._paths.length - 1;
			i._paths[g].remove();
			i._paths[g] = null;
			i._paths.length = g
		}
		var e = {};
		e.points = i._points.slice(0);
		e.paths = i._paths.slice(0);
		e.dots = i._dots.slice(0);
		e.segDis = i._segDistance.slice(0);
		var j = i._map.pointToPixel(e.points[e.points.length - 1]);
		var h = i._map.pointToPixel(e.points[e.points.length - 2]);
		var k = [0, 0];
		var f = [0, 0];
		if (j.y - h.y >= 0) {
			f = [-5, 11]
		} else {
			f = [-5, -35]
		}
		if (j.x - h.x >= 0) {
			k = [14, 0]
		} else {
			k = [-14, 0]
		}
		var n = e.points[e.points.length - 1];
		n.disLabel = new BMap.Label("", {
			offset: new BMap.Size(-15, -40),
			enableMassClear: i._enableMassClear
		});
		n.disLabel.setStyles({
			color: "#333",
			borderColor: "#ff0103"
		});
		i._map.addOverlay(n.disLabel);
		n.disLabel.setOffset(new BMap.Size(f[0], f[1]));
		n.disLabel.setPosition(n);
		i._formatTitle(2, "", "", n.disLabel);
		var m = this._opts.closeIcon ? this._opts.closeIcon : new BMap.Icon("http://api.map.baidu.com/images/mapctrls.gif", new BMap.Size(12, 12), {
			imageOffset: new BMap.Size(0, -14)
		});
		e.closeBtn = new BMap.Marker(e.points[e.points.length - 1], {
			icon: m,
			offset: new BMap.Size(k[0], k[1]),
			baseZIndex: 3600000,
			enableMassClear: i._enableMassClear
		});
		i._map.addOverlay(e.closeBtn);
		e.closeBtn.setTitle("清除本次测距");
		e.closeBtn.addEventListener("click", function(r) {
			for (var p = 0, o = e.points.length; p < o; p++) {
				e.points[p].disLabel.remove();
				e.points[p].disLabel = null
			}
			for (var p = 0, o = e.paths.length; p < o; p++) {
				e.paths[p].remove();
				e.paths[p] = null
			}
			for (var p = 0, o = e.dots.length; p < o; p++) {
				e.dots[p].remove();
				e.dots[p] = null
			}
			e.closeBtn.remove();
			e.closeBtn = null;
			b(r);
			var q = new c.lang.Event("onremovepolyline");
			i.dispatchEvent(q)
		});
		i._initData()
	};
	d.prototype._formatTitle = function(g, l, e, i) {
		var h = i || this._followTitle;
		if (!h) {
			return
		}
		h.setStyle({
			lineHeight: "16px",
			zIndex: "85",
			padding: "3px 5px"
		});
		var n = this._startFollowText || "";
		var k = [];
		if (g == 1) {
			h.setOffset(0, 25);
			var m = this._opts.unit;
			var j = this._units[m].u1;
			var f = this._convertUnit(e, m);
			if (f > this._units[m].incon) {
				f = f / this._units[m].incon;
				j = this._units[m].u2;
				f = f.toFixed(1)
			} else {
				f = f.toFixed(0)
			}
			k.push("<span>总长：<span style='" + this._styles.BMapLib_disBoxDis + "'>" + f + "</span>" + j + "</span><br />");
			k.push("<span style='color:#7a7a7a'>" + l + "</span>")
		} else {
			if (g == 2) {
				var m = this._opts.unit;
				var j = this._units[m].u1;
				var f = this._convertUnit(this._getTotalDistance(), m);
				if (f > this._units[m].incon) {
					f = f / this._units[m].incon;
					j = this._units[m].u2;
					f = f.toFixed(1)
				} else {
					f = f.toFixed(0)
				}
				k.push("总长：<span style='" + this._styles.BMapLib_disBoxDis + "'>" + f + "</span>" + j)
			} else {
				h.setOffset(0, 25);
				k.push(n)
			}
		}
		h.setContent(k.join(""))
	};
	d.prototype._updateInstDis = function(g, e) {
		var f = this._opts.unit;
		var i = this._units[f].u1;
		if (e > this._units[f].incon) {
			e = e / this._units[f].incon;
			i = this._units[f].u2;
			e = e.toFixed(1)
		} else {
			e = e.toFixed(0)
		}
		if (g) {
			var h = [];
			h.push("<span>总长：<span style='" + this._styles.BMapLib_disBoxDis + "'>" + e + "</span>" + i + "</span><br />");
			h.push("<span style='color:#7a7a7a'>" + this._opts.followText + "</span>");
			g.setContent(h.join(""))
		}
	};
	d.prototype._hideCurrent = function() {
		if (!this._isOpen) {
			return
		}
		if (this._paths.length > 0) {
			var e = this._paths[this._paths.length - 1];
			e.hide()
		}
		this._followTitle && this._followTitle.hide()
	};
	d.prototype._isPointValid = function(h) {
		if (!h) {
			return false
		}
		var f = this._map.getBounds();
		var e = f.getSouthWest(),
			g = f.getNorthEast();
		if (h.lng < e.lng || h.lng > g.lng || h.lat < e.lat || h.lat > g.lat) {
			return false
		}
		return true
	};
	var a = {
		_map: null,
		_html: "<div style='background:transparent url(http://api.map.baidu.com/images/blank.gif);position:absolute;left:0;top:0;width:100%;height:100%;z-index:1000' unselectable='on'></div>",
		_maskElement: null,
		_cursor: "default",
		_inUse: false,
		show: function(e) {
			if (!this._map) {
				this._map = e
			}
			this._inUse = true;
			if (!this._maskElement) {
				this._createMask(e)
			}
			this._maskElement.style.display = "block"
		},
		_createMask: function(g) {
			this._map = g;
			if (!this._map) {
				return
			}
			c.insertHTML(this._map.getContainer(), "beforeEnd", this._html);
			var f = this._maskElement = this._map.getContainer().lastChild;
			var e = function(h) {
					b(h);
					return c.preventDefault(h)
				};
			c.on(f, "mouseup", function(h) {
				if (h.button == 2) {
					e(h)
				}
			});
			c.on(f, "contextmenu", e);
			f.style.display = "none"
		},
		getDrawPoint: function(h, j) {
			h = window.event || h;
			var f = h.layerX || h.offsetX || 0;
			var i = h.layerY || h.offsetY || 0;
			var g = h.target || h.srcElement;
			if (g != a.getDom(this._map) && j == true) {
				while (g && g != this._map.getContainer()) {
					if (!(g.clientWidth == 0 && g.clientHeight == 0 && g.offsetParent && g.offsetParent.nodeName.toLowerCase() == "td")) {
						f += g.offsetLeft;
						i += g.offsetTop
					}
					g = g.offsetParent
				}
			}
			if (g != a.getDom(this._map) && g != this._map.getContainer()) {
				return
			}
			if (typeof f === "undefined" || typeof i === "undefined") {
				return
			}
			if (isNaN(f) || isNaN(i)) {
				return
			}
			return this._map.pixelToPoint(new BMap.Pixel(f, i))
		},
		hide: function() {
			if (!this._map) {
				return
			}
			this._inUse = false;
			if (this._maskElement) {
				this._maskElement.style.display = "none"
			}
		},
		getDom: function(e) {
			if (!this._maskElement) {
				this._createMask(e)
			}
			return this._maskElement
		},
		_setCursor: function(e) {
			this._cursor = e || "default";
			if (this._maskElement) {
				this._maskElement.style.cursor = this._cursor
			}
		}
	};

	function b(f) {
		var f = window.event || f;
		f.stopPropagation ? f.stopPropagation() : f.cancelBubble = true
	}
})();
(function() {
	var e = 0;
	var j = 1;
	var c = BMapLib.RectangleZoom = function(n, m) {
			if (!n) {
				return
			}
			this._map = n;
			this._bounds = null;
			this._opts = {
				zoomType: e,
				followText: "",
				strokeWeight: 2,
				strokeColor: "#111",
				style: "solid",
				fillColor: "#ccc",
				opacity: 0.4,
				cursor: "crosshair",
				autoClose: false
			};
			this._setOptions(m);
			this._opts.strokeWeight = this._opts.strokeWeight <= 0 ? 1 : this._opts.strokeWeight;
			this._opts.opacity = this._opts.opacity < 0 ? 0 : this._opts.opacity > 1 ? 1 : this._opts.opacity;
			if (this._opts.zoomType < e || this._opts.zoomType > j) {
				this._opts.zoomType = e
			}
			this._isOpen = false;
			this._fDiv = null;
			this._followTitle = null
		};
	c.prototype._setOptions = function(m) {
		if (!m) {
			return
		}
		for (var n in m) {
			if (typeof(m[n]) != "undefined") {
				this._opts[n] = m[n]
			}
		}
	};
	c.prototype.setStrokeColor = function(m) {
		if (typeof m == "string") {
			this._opts.strokeColor = m;
			this._updateStyle()
		}
	};
	c.prototype.setLineStroke = function(m) {
		if (typeof m == "number" && Math.round(m) > 0) {
			this._opts.strokeWeight = Math.round(m);
			this._updateStyle()
		}
	};
	c.prototype.setLineStyle = function(m) {
		if (m == "solid" || m == "dashed") {
			this._opts.style = m;
			this._updateStyle()
		}
	};
	c.prototype.setOpacity = function(m) {
		if (typeof m == "number" && m >= 0 && m <= 1) {
			this._opts.opacity = m;
			this._updateStyle()
		}
	};
	c.prototype.setFillColor = function(m) {
		this._opts.fillColor = m;
		this._updateStyle()
	};
	c.prototype.setCursor = function(m) {
		this._opts.cursor = m;
		f.setCursor(this._opts.cursor)
	};
	c.prototype._updateStyle = function() {
		if (this._fDiv) {
			this._fDiv.style.border = [this._opts.strokeWeight, "px ", this._opts.style, " ", this._opts.color].join("");
			var m = this._fDiv.style,
				n = this._opts.opacity;
			m.opacity = n;
			m.MozOpacity = n;
			m.KhtmlOpacity = n;
			m.filter = "alpha(opacity=" + (n * 100) + ")"
		}
	};
	c.prototype.getBounds = function() {
		return this._bounds
	};
	c.prototype.getCursor = function() {
		return this._opts.cursor
	};
	c.prototype._bind = function() {
		this.setCursor(this._opts.cursor);
		var n = this;
		d(this._map.getContainer(), "mousemove", function(q) {
			if (!n._isOpen) {
				return
			}
			if (!n._followTitle) {
				return
			}
			q = window.event || q;
			var o = q.target || q.srcElement;
			if (o != f.getDom(n._map)) {
				n._followTitle.hide();
				return
			}
			if (!n._mapMoving) {
				n._followTitle.show()
			}
			var p = f.getDrawPoint(q, true);
			n._followTitle.setPosition(p)
		});
		if (this._opts.followText) {
			var m = this._followTitle = new BMap.Label(this._opts.followText, {
				offset: new BMap.Size(14, 16)
			});
			this._followTitle.setStyles({
				color: "#333",
				borderColor: "#ff0103"
			})
		}
	};
	c.prototype.open = function() {
		if (this._isOpen == true) {
			return true
		}
		if ( !! BMapLib._toolInUse) {
			return
		}
		this._isOpen = true;
		BMapLib._toolInUse = true;
		if (!this.binded) {
			this._bind();
			this.binded = true
		}
		if (this._followTitle) {
			this._map.addOverlay(this._followTitle);
			this._followTitle.hide()
		}
		var o = this;
		var p = this._map;
		var q = 0;
		if (/msie (\d+\.\d)/i.test(navigator.userAgent)) {
			q = document.documentMode || +RegExp["$1"]
		}
		var n = function(s) {
				s = window.event || s;
				if (s.button != 0 && !q || q && s.button != 1) {
					return
				}
				if ( !! q && f.getDom(p).setCapture) {
					f.getDom(p).setCapture()
				}
				if (!o._isOpen) {
					return
				}
				o._bind.isZooming = true;
				d(document, "mousemove", m);
				d(document, "mouseup", r);
				o._bind.mx = s.layerX || s.offsetX || 0;
				o._bind.my = s.layerY || s.offsetY || 0;
				o._bind.ix = s.pageX || s.clientX || 0;
				o._bind.iy = s.pageY || s.clientY || 0;
				a(f.getDom(p), "beforeBegin", o._generateHTML());
				o._fDiv = f.getDom(p).previousSibling;
				o._fDiv.style.width = "0";
				o._fDiv.style.height = "0";
				o._fDiv.style.left = o._bind.mx + "px";
				o._fDiv.style.top = o._bind.my + "px";
				b(s);
				return h(s)
			};
		var m = function(z) {
				if (o._isOpen == true && o._bind.isZooming == true) {
					var z = window.event || z;
					var u = z.pageX || z.clientX || 0;
					var s = z.pageY || z.clientY || 0;
					var w = o._bind.dx = u - o._bind.ix;
					var t = o._bind.dy = s - o._bind.iy;
					var v = Math.abs(w) - o._opts.strokeWeight;
					var y = Math.abs(t) - o._opts.strokeWeight;
					o._fDiv.style.width = (v < 0 ? 0 : v) + "px";
					o._fDiv.style.height = (y < 0 ? 0 : y) + "px";
					var x = [p.getSize().width, p.getSize().height];
					if (w >= 0) {
						o._fDiv.style.right = "auto";
						o._fDiv.style.left = o._bind.mx + "px";
						if (o._bind.mx + w >= x[0] - 2 * o._opts.strokeWeight) {
							o._fDiv.style.width = x[0] - o._bind.mx - 2 * o._opts.strokeWeight + "px";
							o._followTitle && o._followTitle.hide()
						}
					} else {
						o._fDiv.style.left = "auto";
						o._fDiv.style.right = x[0] - o._bind.mx + "px";
						if (o._bind.mx + w <= 2 * o._opts.strokeWeight) {
							o._fDiv.style.width = o._bind.mx - 2 * o._opts.strokeWeight + "px";
							o._followTitle && o._followTitle.hide()
						}
					}
					if (t >= 0) {
						o._fDiv.style.bottom = "auto";
						o._fDiv.style.top = o._bind.my + "px";
						if (o._bind.my + t >= x[1] - 2 * o._opts.strokeWeight) {
							o._fDiv.style.height = x[1] - o._bind.my - 2 * o._opts.strokeWeight + "px";
							o._followTitle && o._followTitle.hide()
						}
					} else {
						o._fDiv.style.top = "auto";
						o._fDiv.style.bottom = x[1] - o._bind.my + "px";
						if (o._bind.my + t <= 2 * o._opts.strokeWeight) {
							o._fDiv.style.height = o._bind.my - 2 * o._opts.strokeWeight + "px";
							o._followTitle && o._followTitle.hide()
						}
					}
					b(z);
					return h(z)
				}
			};
		var r = function(A) {
				if (o._isOpen == true) {
					i(document, "mousemove", m);
					i(document, "mouseup", r);
					if ( !! q && f.getDom(p).releaseCapture) {
						f.getDom(p).releaseCapture()
					}
					var v = parseInt(o._fDiv.style.left) + parseInt(o._fDiv.style.width) / 2;
					var u = parseInt(o._fDiv.style.top) + parseInt(o._fDiv.style.height) / 2;
					var z = [p.getSize().width, p.getSize().height];
					if (isNaN(v)) {
						v = z[0] - parseInt(o._fDiv.style.right) - parseInt(o._fDiv.style.width) / 2
					}
					if (isNaN(u)) {
						u = z[1] - parseInt(o._fDiv.style.bottom) - parseInt(o._fDiv.style.height) / 2
					}
					var C = Math.min(z[0] / Math.abs(o._bind.dx), z[1] / Math.abs(o._bind.dy));
					C = Math.floor(C);
					var x = new BMap.Pixel(v - parseInt(o._fDiv.style.width) / 2, u - parseInt(o._fDiv.style.height) / 2);
					var w = new BMap.Pixel(v + parseInt(o._fDiv.style.width) / 2, u + parseInt(o._fDiv.style.height) / 2);
					var F = p.pixelToPoint(x);
					var E = p.pixelToPoint(w);
					var y = new BMap.Bounds(F, E);
					o._bounds = y;
					delete o._bind.dx;
					delete o._bind.dy;
					delete o._bind.ix;
					delete o._bind.iy;
					if (!isNaN(C)) {
						if (o._opts.zoomType == e) {
							targetZoomLv = Math.round(p.getZoom() + (Math.log(C) / Math.log(2)));
							if (targetZoomLv < p.getZoom()) {
								targetZoomLv = p.getZoom()
							}
						} else {
							targetZoomLv = Math.round(p.getZoom() + (Math.log(1 / C) / Math.log(2)));
							if (targetZoomLv > p.getZoom()) {
								targetZoomLv = p.getZoom()
							}
						}
					} else {
						targetZoomLv = p.getZoom() + (o._opts.zoomType == e ? 1 : -1)
					}
					var s = p.pixelToPoint({
						x: v,
						y: u
					}, p.getZoom());
					p.centerAndZoom(s, targetZoomLv);
					var I = f.getDrawPoint(A);
					if (o._followTitle) {
						o._followTitle.setPosition(I);
						o._followTitle.show()
					}
					o._bind.isZooming = false;
					o._fDiv.parentNode.removeChild(o._fDiv);
					o._fDiv = null
				}
				var t = y.getSouthWest(),
					B = y.getNorthEast(),
					G = new BMap.Point(B.lng, t.lat),
					H = new BMap.Point(t.lng, B.lat),
					D = new BMap.Polygon([t, H, B, G]);
				D.setStrokeWeight(2);
				D.setStrokeOpacity(0.3);
				D.setStrokeColor("#111");
				D.setFillColor("");
				p.addOverlay(D);
				new g({
					duration: 240,
					fps: 20,
					delay: 500,
					render: function(K) {
						var J = 0.3 * (1 - K);
						D.setStrokeOpacity(J)
					},
					finish: function() {
						p.removeOverlay(D);
						D.dispose();
						D = null
					}
				});
				if (o._opts.autoClose) {
					setTimeout(function() {
						if (o._isOpen == true) {
							o.close()
						}
					}, 70)
				}
				b(A);
				return h(A)
			};
		f.show(this._map);
		this.setCursor(this._opts.cursor);
		if (!this._isBeginDrawBinded) {
			d(f.getDom(this._map), "mousedown", n);
			this._isBeginDrawBinded = true
		}
		return true
	};
	c.prototype.close = function() {
		this._bounds = null;
		if (!this._isOpen) {
			return
		}
		this._isOpen = false;
		BMapLib._toolInUse = false;
		this._followTitle && this._followTitle.hide();
		f.hide()
	};
	c.prototype._generateHTML = function() {
		return ["<div style='position:absolute;z-index:300;border:", this._opts.strokeWeight, "px ", this._opts.style, " ", this._opts.strokeColor, "; opacity:", this._opts.opacity, "; background: ", this._opts.fillColor, "; filter:alpha(opacity=", Math.round(this._opts.opacity * 100), "); width:0; height:0; font-size:0'></div>"].join("")
	};

	function a(p, m, o) {
		var n, q;
		if (p.insertAdjacentHTML) {
			p.insertAdjacentHTML(m, o)
		} else {
			n = p.ownerDocument.createRange();
			m = m.toUpperCase();
			if (m == "AFTERBEGIN" || m == "BEFOREEND") {
				n.selectNodeContents(p);
				n.collapse(m == "AFTERBEGIN")
			} else {
				q = m == "BEFOREBEGIN";
				n[q ? "setStartBefore" : "setEndAfter"](p);
				n.collapse(q)
			}
			n.insertNode(n.createContextualFragment(o))
		}
		return p
	}
	function k(n, m) {
		a(n, "beforeEnd", m);
		return n.lastChild
	}
	function b(m) {
		var m = window.event || m;
		m.stopPropagation ? m.stopPropagation() : m.cancelBubble = true
	}
	function h(m) {
		var m = window.event || m;
		m.preventDefault ? m.preventDefault() : m.returnValue = false;
		return false
	}
	function d(m, n, o) {
		if (!m) {
			return
		}
		n = n.replace(/^on/i, "").toLowerCase();
		if (m.addEventListener) {
			m.addEventListener(n, o, false)
		} else {
			if (m.attachEvent) {
				m.attachEvent("on" + n, o)
			}
		}
	}
	function i(m, n, o) {
		if (!m) {
			return
		}
		n = n.replace(/^on/i, "").toLowerCase();
		if (m.removeEventListener) {
			m.removeEventListener(n, o, false)
		} else {
			if (m.detachEvent) {
				m.detachEvent("on" + n, o)
			}
		}
	}
	var f = {
		_map: null,
		_html: "<div style='background:transparent url(http://api.map.baidu.com/images/blank.gif);position:absolute;left:0;top:0;width:100%;height:100%;z-index:1000' unselectable='on'></div>",
		_maskElement: null,
		_cursor: "default",
		_inUse: false,
		show: function(m) {
			if (!this._map) {
				this._map = m
			}
			this._inUse = true;
			if (!this._maskElement) {
				this._createMask(m)
			}
			this._maskElement.style.display = "block"
		},
		_createMask: function(o) {
			this._map = o;
			if (!this._map) {
				return
			}
			var n = this._maskElement = k(this._map.getContainer(), this._html);
			var m = function(p) {
					b(p);
					return h(p)
				};
			d(n, "mouseup", function(p) {
				if (p.button == 2) {
					m(p)
				}
			});
			d(n, "contextmenu", m);
			n.style.display = "none"
		},
		getDrawPoint: function(p, r) {
			p = window.event || p;
			var m = p.layerX || p.offsetX || 0;
			var q = p.layerY || p.offsetY || 0;
			var o = p.target || p.srcElement;
			if (o != f.getDom(this._map) && r == true) {
				while (o && o != this._map.getContainer()) {
					if (!(o.clientWidth == 0 && o.clientHeight == 0 && o.offsetParent && o.offsetParent.nodeName.toLowerCase() == "td")) {
						m += o.offsetLeft;
						q += o.offsetTop
					}
					o = o.offsetParent
				}
			}
			if (o != f.getDom(this._map) && o != this._map.getContainer()) {
				return
			}
			if (typeof m === "undefined" || typeof q === "undefined") {
				return
			}
			if (isNaN(m) || isNaN(q)) {
				return
			}
			return this._map.pixelToPoint(new BMap.Pixel(m, q))
		},
		hide: function() {
			if (!this._map) {
				return
			}
			this._inUse = false;
			if (this._maskElement) {
				this._maskElement.style.display = "none"
			}
		},
		getDom: function(m) {
			if (!this._maskElement) {
				this._createMask(m)
			}
			return this._maskElement
		},
		setCursor: function(m) {
			this._cursor = m || "default";
			if (this._maskElement) {
				this._maskElement.style.cursor = this._cursor
			}
		}
	};

	function g(p) {
		var m = {
			duration: 1000,
			fps: 30,
			delay: 0,
			transition: l.linear,
			onStop: function() {}
		};
		if (p) {
			for (var n in p) {
				m[n] = p[n]
			}
		}
		this._opts = m;
		if (m.delay) {
			var o = this;
			setTimeout(function() {
				o._beginTime = new Date().getTime();
				o._endTime = o._beginTime + o._opts.duration;
				o._launch()
			}, m.delay)
		} else {
			this._beginTime = new Date().getTime();
			this._endTime = this._beginTime + this._opts.duration;
			this._launch()
		}
	}
	g.prototype._launch = function() {
		var n = this;
		var m = new Date().getTime();
		if (m >= n._endTime) {
			if (typeof n._opts.render == "function") {
				n._opts.render(n._opts.transition(1))
			}
			if (typeof n._opts.finish == "function") {
				n._opts.finish()
			}
			return
		}
		n.schedule = n._opts.transition((m - n._beginTime) / n._opts.duration);
		if (typeof n._opts.render == "function") {
			n._opts.render(n.schedule)
		}
		if (!n.terminative) {
			n._timer = setTimeout(function() {
				n._launch()
			}, 1000 / n._opts.fps)
		}
	};
	var l = {
		linear: function(m) {
			return m
		},
		reverse: function(m) {
			return 1 - m
		},
		easeInQuad: function(m) {
			return m * m
		},
		easeInCubic: function(m) {
			return Math.pow(m, 3)
		},
		easeOutQuad: function(m) {
			return -(m * (m - 2))
		},
		easeOutCubic: function(m) {
			return Math.pow((m - 1), 3) + 1
		},
		easeInOutQuad: function(m) {
			if (m < 0.5) {
				return m * m * 2
			} else {
				return -2 * (m - 2) * m - 1
			}
			return
		},
		easeInOutCubic: function(m) {
			if (m < 0.5) {
				return Math.pow(m, 3) * 4
			} else {
				return Math.pow(m - 1, 3) * 4 + 1
			}
		},
		easeInOutSine: function(m) {
			return (1 - Math.cos(Math.PI * m)) / 2
		}
	}
})();
var BMapLib = window.BMapLib = BMapLib || {};
(function() {
	BMapLib.COORD_TYPE_GPS = 0;
	BMapLib.COORD_TYPE_GOOGLE = 2;
	var c = 4;
	var a = 20;
	var b = BMapLib.MapWrapper = function(e, d) {
			this._map = e;
			this._coordType = d;
			this._hashObject = {};
			this._arrCache = []
		};
	b.prototype._uiqueNum = 0;
	b.prototype._canSendRequest = true;
	b.prototype.addOverlay = function(d) {
		if (!d || !(d instanceof BMap.Marker)) {
			return
		}
		var f = {
			overlay: d,
			uiqueNum: this._uiqueNum
		};
		this._arrCache.push(f);
		this._hashObject["guid" + this._uiqueNum] = d;
		this._uiqueNum++;
		var e = this;
		if (e._canSendRequest) {
			e._canSendRequest = false;
			window.setTimeout(function() {
				e._canSendRequest = true;
				e._delayRequest()
			}, 50)
		}
	};
	b.prototype._delayRequest = function() {
		var f = this._arrCache;
		var t = Math.ceil(f.length / a);
		for (var l = 0; l < t; l++) {
			var v = [],
				u = [],
				p = [];
			var m = l * a;
			var g = -1;
			if (f.length - (l + 1) * a > 0) {
				g = (l + 1) * a
			} else {
				g = f.length
			}
			for (var h = m; h < g; h++) {
				var k = f[h];
				var s = k.overlay.getPosition().lng;
				var r = k.overlay.getPosition().lat;
				v.push(s);
				u.push(r);
				p.push("guid" + k.uiqueNum)
			}
			var q = v.join(",");
			var o = u.join(",");
			var e = {
				x: q,
				y: o,
				from: this._coordType,
				to: c,
				mode: 1
			};
			var d = {
				guids: p
			};
			var n = this;
			b._SearchRequestMgr.request(function(j, i) {
				n._requestCbk(j, i)
			}, e, d)
		}
		this._arrCache.length = 0
	};
	b.prototype._requestCbk = function(m, f) {
		if (m && m instanceof Array) {
			var j = f.guids;
			for (var g = 0; g < m.length; g++) {
				var l = m[g];
				if (l && l.error == 0) {
					var d = l.x,
						n = l.y;
					var e = new BMap.Point(d, n);
					var k = j[g];
					if (this._hashObject[k]) {
						var h = this._hashObject[k];
						h.setPosition(e);
						this._map.addOverlay(h);
						delete this._hashObject[k]
					}
				}
			}
		}
	};
	b._jsonToQuery = function(d, f) {
		var e = [];
		f = f ||
		function(h) {
			return h
		};
		for (var g in d) {
			e.push(g + "=" + f(d[g]))
		}
		return e.join("&")
	};
	b._SearchRequestMgr = {
		COORD_CONVERT_URL: "http://api.map.baidu.com/ag/coord/convert",
		request: function(e, k, d, j) {
			var f = (Math.random() * 100000).toFixed(0);
			b._cbkMount["_cbk" + f] = function(l) {
				d = d || {};
				e && e(l, d);
				delete b._cbkMount["_cbk" + f]
			};
			j = j || "";
			var h = b._jsonToQuery(k, encodeURIComponent);
			var i = this,
				g = i.COORD_CONVERT_URL + j + "?" + h + "&ie=utf-8&oue=1&res=api&callback=BMapLib.MapWrapper._cbkMount._cbk" + f;
			i.createScript(g)
		},
		createScript: function(e) {
			var d = document.createElement("script");
			d.src = e;
			d.setAttribute("type", "text/javascript");
			d.setAttribute("charset", "utf-8");
			if (d.addEventListener) {
				d.addEventListener("load", function(g) {
					var f = g.target;
					f.parentNode.removeChild(f)
				}, false)
			} else {
				if (d.attachEvent) {
					d.attachEvent("onreadystatechange", function(g) {
						var f = window.event.srcElement;
						if (f && (f.readyState == "loaded" || f.readyState == "complete")) {
							f.parentNode.removeChild(f)
						}
					})
				}
			}
			setTimeout(function() {
				document.getElementsByTagName("head")[0].appendChild(d);
				d = null
			}, 1)
		}
	};
	b._cbkMount = {};
	window._BMapLibStatImg = "http://api.map.baidu.com/images/";
	window._addStat = function(j, h) {
		if (!j) {
			return
		}
		h = h || {};
		var g = "";
		for (var e in h) {
			g = g + "&" + e + "=" + encodeURIComponent(h[e])
		}
		var k = function(i) {
				if (!i) {
					return
				}
				_addStat._sending = true;
				setTimeout(function() {
					_addStat._img.src = window._BMapLibStatImg + "blank.gif?" + i.src
				}, 50)
			};
		var d = function() {
				var i = _addStat._reqQueue.shift();
				if (i) {
					k(i)
				}
			};
		var f = (Math.random() * 100000000).toFixed(0);
		if (_addStat._sending) {
			_addStat._reqQueue.push({
				src: "t=" + f + "&code=" + j + g
			})
		} else {
			k({
				src: "t=" + f + "&code=" + j + g
			})
		}
		if (!_addStat._binded) {
			_addStat._img.onload = function() {
				_addStat._sending = false;
				d()
			};
			_addStat._img.onerror = function() {
				_addStat._sending = false;
				d()
			};
			_addStat._binded = true
		}
	};
	window._addStat._reqQueue = [];
	window._addStat._img = new Image();
	window._addStat(5015)
})();