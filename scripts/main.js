var tab_arr=new Array('tab1','tab2','tab3','tab4','tab5');

// Проверка даты на корректность /////////////////////////////////////////////////////////////////////////////////////////////////////////
function CheckErrorDate(date)
{
	if (date.length!=10) {return true;}
	if (isNaN(date.substr(6,4))) {return true;}
	if (isNaN(date.substr(3,2))) {return true;}
	if (isNaN(date.substr(0,2))) {return true;}

	if (date.substr(2,1)!=".") {return true;}
	if (date.substr(5,1)!=".") {return true;}

	return false;
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function initmain() {
	trid='';
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function showedit(id, kn, nom) {
		if (trid!='') { document.getElementById("tr"+trid).style.background = "#FFFFFF";
						document.getElementById("ed"+trid).style.display = "none";
		}
		document.getElementById("tr"+id).style.background = "#FFCC66";
		document.getElementById("ed"+id).style.display = "block";
		trid=id;
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function delab1(id, kn, nom) {
		document.getElementById("tr2_"+id).style.display = "table-raw";
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function set_informer(n)
{
	var _i=null;
	var _selected=0;
	for (i=1;i<=tab_arr.length;i++) {
		if (_i=document.getElementById(tab_arr[i-1])) {
			if (i!=n)
			{
				_i.style.display='none';
				document.getElementById("tabfast_"+i).className=document.getElementById("tabfast_"+i).className.replace('active','');
			}
			else {
				_i.style.display='block';
				_selected=i;
				document.getElementById("tabfast_"+i).className=document.getElementById("tabfast_"+i).className+' active';
			}
		}
	}
	if (!_selected) _selected=1;
}
//////////////////////////////////////////////////////////////////////////////////
function spis1()
{
	var rpage = eval("document.filter.rpage");
	rpage.value="np";
	var log = $('nas_punkt_c').empty().addClass('ajax-loading');
	$('filter').set('send',
	{
		onComplete: function(response)
		{
			log.removeClass('ajax-loading');
			log.set('html', response);
		}
	});

	$('filter').send();
}
//////////////////////////////////////////////////////////////////////////////////
function spis2()
{
	var rpage = eval("document.filter.rpage");
	rpage.value="street";
	var log = $('street_c').empty().addClass('ajax-loading');
	$('filter').set('send',
	{
		onComplete: function(response)
		{
			log.removeClass('ajax-loading');
			log.set('html', response);
		}
	});
	$('filter').send();
}
//////////////////////////////////////////////////////////////////////////////////
function espis1()
{
	var rpage = eval("document.frm_abonent.rpage");
	rpage.value="enp";
	clearstreet();
	cleardom();
	show_save();
	var log = $('enas_punkt_c').empty().addClass('ajax-loading');
	$('frm_abonent').set('send',
	{
		onComplete: function(response)
		{
			log.removeClass('ajax-loading');
			log.set('html', response);
		}
	});
	$('frm_abonent').send();
}
//////////////////////////////////////////////////////////////////////////////////
function espis2()
{
	cleardom();
	show_save();
	var rpage = eval("document.frm_abonent.rpage");
	rpage.value="estreet";
	var log = $('estreet_c').empty().addClass('ajax-loading');
	$('frm_abonent').set('send',
	{
		onComplete: function(response)
		{
			log.removeClass('ajax-loading');
			log.set('html', response);
		}
	});
	$('frm_abonent').send();
}
//////////////////////////////////////////////////////////////////////////////////
function espis3()
{
	var rpage = eval("document.frm_abonent.rpage");
	rpage.value="edom";
	var log = $('edom_c').empty().addClass('ajax-loading');
	$('frm_abonent').set('send',
	{
		onComplete: function(response)
		{
			log.removeClass('ajax-loading');
			log.set('html', response);
		}
	});
	$('frm_abonent').send();
}
//////////////////////////////////////////////////////////////////////////////////
function clearstreet()
{
	var obj = $('ab[nu1][]');
	for(i=obj.options.length;i>=0;i--)
	{
		obj.options[i]=null;
	}
	$('ab[nu1][]').options.clear;
	return false;
}
//////////////////////////////////////////////////////////////////////////////////
function cleardom()
{
	var obj1 = $('ab[id_dom][]');
	for(i=obj1.options.length;i>=0;i--)
	{
		obj1.options[i]=null;
	}

	$('ab[id_dom][]').options.clear;
	return 0;
}
//////////////////////////////////////////////////////////////////////////////////
function gosearch()
{
	var rpage = eval("document.filter.rpage");
	rpage.value="";
	var pnum = eval("document.filter.page_num");
	pnum.value="1";
	var onum = eval("document.filter.order_num");
	onum.value="0";
}
//////////////////////////////////////////////////////////////////////////////////
function sendfilter(pn, on)
{
	var pnum = eval("document.filter.page_num");
	pnum.value=pn;
	var onum = eval("document.filter.order_num");
	onum.value=on;
	$('filter').submit();
}
//////////////////////////////////////////////////////////////////////////////////
function editab(kn, nom, act)
{
	var pnum = eval("document.filter.ekn");
	pnum.value=kn;
	var onum = eval("document.filter.enom");
	onum.value=nom;
	var anum = eval("document.filter.act");
	anum.value=act;
	$('filter').submit();
}
//////////////////////////////////////////////////////////////////////////////////
function dublab(kn, nom, act)
{
	var str = "scrollbars=yes,left=0,top=0,width=" + screen.availWidth + "," + "height=" + screen.availHeight;
	newWin = window.open('dublab.php?act='+act+'&kn='+kn+'&nom='+nom+'', "myWindow", str);
}
//////////////////////////////////////////////////////////////////////////////////
function dom1()
{
	var rpage = eval("document.abonent.rpage");
	rpage.value="dom";

	var log = $('nas_punkt_c').empty().addClass('ajax-loading');
	$('abonent').set('send',
	{
		onComplete: function(response)
		{
			log.removeClass('ajax-loading');
			log.set('html', response);
		}
	});
	$('abonent').send();
}
//------------------------------------------------------------------
function show_save()
{
	var obj = $('ab[id_dom][]');
	var but = eval("document.frm_abonent.sbmit1");
	if (obj.options.selectedIndex>=0) {
		but.value = "Сохранить карточку абонента";
		but.disabled=false;
		var rpage = eval("document.frm_abonent.rpage");
		rpage.value="";
		after_dom_ch(obj.value);
	}
	else {
		but.value = " Введите точный адрес ";
		but.disabled=true;
	}
}
//------------------------------------------------------------------
function after_dom_ch(id)
{
    request = "ajax.php?act=after_dom_change";
    request = request + "&id="+id;
    loadContent(request,'after_dom_change');
}


/* -----------| Попапчики |----------- */
function popclose (popid)
{
	document.getElementById(popid).style.display='none';
}
/* ------------------------------------- */
function popopen(popid, id, knnom)
{
	var pnum = eval("document.form_del.id");
	pnum.value=id;
	document.getElementById(popid).style.display='block';
	document.getElementById("prdel").innerHTML = '<b>Удалить абонента '+knnom+' ?</b>';
}
/* ------------------------------------- */
function popclose2 (popid)
{
	document.getElementById(popid).style.display='none';
}
/* ------------------------------------- */
function popopen2(popid, id, knnom)
{
	var pnum = eval("document.form_restore.id");
	pnum.value=id;
	document.getElementById(popid).style.display='block';
	document.getElementById("prdel").innerHTML = '<b>Восстановить абонента '+knnom+' ?</b>';
}
/* ------------------------------------- */
////////////////////////////////////////////////////////////////////////////////////////////////////////
function jsHover()
{
	var hEls = document.getElementById("nav").getElementsByTagName("LI");
	for (var i=0, len=hEls.length; i<len; i++) {
		hEls[i].onmouseover=function() { this.className+=" jshover"; }
		hEls[i].onmouseout=function() { this.className=this.className.replace(" jshover", ""); }
	}
}

if (window.attachEvent && navigator.userAgent.indexOf("Opera")==-1) window.attachEvent("onload", jsHover);

////////////////////////////////////////////////////////////
// Функция вызова окна архива
function archive(act, kn, nom, uch)
{
	var str = "scrollbars=yes,left=0,top=0,width=" + screen.availWidth + "," + "height=" + screen.availHeight;
	newWin = window.open('archive.php?act='+act+'&kn='+kn+'&nom='+nom+'&uch='+uch+'', "myWindow", str);
}

///////////////////////////////
// Функция проверки на Int
function CheckInt(inp) {
    if (parseInt(inp.value) != inp.value) {
        inp.value = inp.value.replace(new RegExp('[^\\d]|[\\s]', 'g'), '');
    }
}

///////////////////////////////
// Функция проверки на Float
function CheckFloat(inp) {
    if (parseFloat(inp.value) != inp.value) {
        inp.value = inp.value.replace(new RegExp('[^\\d\.]|[\\s]', 'g'), '');
    }
}

////////////////////////////////////////////////////////
// Проверка даты на корректный формат dd.mm.YYYY
function check_date(dd)
{
    var a= dd.value;
    var error = 0;
    if (a.length!=10) {error = 1;}
    if ((a.charAt(0)<'0') || (a.charAt(0)>'9')) {error = 1;}
    if ((a.charAt(1)<'0') || (a.charAt(1)>'9')) {error = 1;}
    if (a.charAt(2)!='.') {error = 1;}
    if ((a.charAt(3)<'0') || (a.charAt(3)>'9')) {error = 1;}
    if ((a.charAt(4)<'0') || (a.charAt(4)>'9')) {error = 1;}
    if (a.charAt(5)!='.') {error = 1;}
    if ((a.charAt(6)<'0') || (a.charAt(6)>'9')) {error = 1;}
    if ((a.charAt(7)<'0') || (a.charAt(7)>'9')) {error = 1;}
    if ((a.charAt(8)<'0') || (a.charAt(8)>'8')) {error = 1;}
    if ((a.charAt(9)<'0') || (a.charAt(9)>'9')) {error = 1;}

    var d = date("Y.m.d");
    stdate = a.substr(6,4)+'.'+a.substr(3,2)+'.'+a.substr(0,2);
    if ((stdate>d) || (stdate<"1960.01.01")) {error=1;}

    if (error!=0) {
        dd.style.border="2px solid #f00";
        //alert(error);
    } else {dd.style.border="1px solid #AAA";}

}

////////////////////////////////////////////////////////
// Проверка даты на корректный формат dd.mm.YYYY
function check_date_simple(dd)
{
    var a= dd.value;
    var error = 0;
    if (a.length!=10) {error = 1;}
    if ((a.charAt(0)<'0') || (a.charAt(0)>'9')) {error = 1;}
    if ((a.charAt(1)<'0') || (a.charAt(1)>'9')) {error = 1;}
    if (a.charAt(2)!='.') {error = 1;}
    if ((a.charAt(3)<'0') || (a.charAt(3)>'9')) {error = 1;}
    if ((a.charAt(4)<'0') || (a.charAt(4)>'9')) {error = 1;}
    if (a.charAt(5)!='.') {error = 1;}
    if ((a.charAt(6)<'0') || (a.charAt(6)>'9')) {error = 1;}
    if ((a.charAt(7)<'0') || (a.charAt(7)>'9')) {error = 1;}
    if ((a.charAt(8)<'0') || (a.charAt(8)>'8')) {error = 1;}
    if ((a.charAt(9)<'0') || (a.charAt(9)>'9')) {error = 1;}

    if (error!=0) {
        dd.style.border="2px solid #f00";
        //alert(error);
    } else {dd.style.border="1px solid #AAA";}

}

////////////////////////////////////////////////////////
// Проверка поля на x символов длины
function check_len(dd, x)
{
    var a= dd.value;
    var error = 0;
	if (a.length<x) {error = 1;}
    if (error!=0) {
        dd.style.border="2px solid #f00";
        //alert(error);
    } else {dd.style.border="1px solid #AAA";}

}

/////////////////////////////////////////////////////////////////////////////////////////
function date(format, timestamp) {
    var that = this,
        jsdate, f, formatChr = /\\?([a-z])/gi, formatChrCb,
        // Keep this here (works, but for code commented-out
        // below for file size reasons)
        //, tal= [],
        _pad = function (n, c) {
            if ((n = n + "").length < c) {
                return new Array((++c) - n.length).join("0") + n;
            } else {
                return n;
            }
        },
        txt_words = ["Sun", "Mon", "Tues", "Wednes", "Thurs", "Fri", "Satur",
        "January", "February", "March", "April", "May", "June", "July",
        "August", "September", "October", "November", "December"],
        txt_ordin = {
            1: "st",
            2: "nd",
            3: "rd",
            21: "st",
            22: "nd",
            23: "rd",
            31: "st"
        };
    formatChrCb = function (t, s) {
        return f[t] ? f[t]() : s;
    };
    f = {
    // Day
        d: function () { // Day of month w/leading 0; 01..31
            return _pad(f.j(), 2);
        },
        D: function () { // Shorthand day name; Mon...Sun
            return f.l().slice(0, 3);
        },
        j: function () { // Day of month; 1..31
            return jsdate.getDate();
        },
        l: function () { // Full day name; Monday...Sunday
            return txt_words[f.w()] + 'day';
        },
        N: function () { // ISO-8601 day of week; 1[Mon]..7[Sun]
            return f.w() || 7;
        },
        S: function () { // Ordinal suffix for day of month; st, nd, rd, th
            return txt_ordin[f.j()] || 'th';
        },
        w: function () { // Day of week; 0[Sun]..6[Sat]
            return jsdate.getDay();
        },
        z: function () { // Day of year; 0..365
            var a = new Date(f.Y(), f.n() - 1, f.j()),
                b = new Date(f.Y(), 0, 1);
            return Math.round((a - b) / 864e5) + 1;
        },

    // Week
        W: function () { // ISO-8601 week number
            var a = new Date(f.Y(), f.n() - 1, f.j() - f.N() + 3),
                b = new Date(a.getFullYear(), 0, 4);
            return 1 + Math.round((a - b) / 864e5 / 7);
        },

    // Month
        F: function () { // Full month name; January...December
            return txt_words[6 + f.n()];
        },
        m: function () { // Month w/leading 0; 01...12
            return _pad(f.n(), 2);
        },
        M: function () { // Shorthand month name; Jan...Dec
            return f.F().slice(0, 3);
        },
        n: function () { // Month; 1...12
            return jsdate.getMonth() + 1;
        },
        t: function () { // Days in month; 28...31
            return (new Date(f.Y(), f.n(), 0)).getDate();
        },

    // Year
        L: function () { // Is leap year?; 0 or 1
            return new Date(f.Y(), 1, 29).getMonth() === 1 | 0;
        },
        o: function () { // ISO-8601 year
            var n = f.n(), W = f.W(), Y = f.Y();
            return Y + (n === 12 && W < 9 ? -1 : n === 1 && W > 9);
        },
        Y: function () { // Full year; e.g. 1980...2010
            return jsdate.getFullYear();
        },
        y: function () { // Last two digits of year; 00...99
            return (f.Y() + "").slice(-2);
        },

    // Time
        a: function () { // am or pm
            return jsdate.getHours() > 11 ? "pm" : "am";
        },
        A: function () { // AM or PM
            return f.a().toUpperCase();
        },
        B: function () { // Swatch Internet time; 000..999
            var H = jsdate.getUTCHours() * 36e2, // Hours
                i = jsdate.getUTCMinutes() * 60, // Minutes
                s = jsdate.getUTCSeconds(); // Seconds
            return _pad(Math.floor((H + i + s + 36e2) / 86.4) % 1e3, 3);
        },
        g: function () { // 12-Hours; 1..12
            return f.G() % 12 || 12;
        },
        G: function () { // 24-Hours; 0..23
            return jsdate.getHours();
        },
        h: function () { // 12-Hours w/leading 0; 01..12
            return _pad(f.g(), 2);
        },
        H: function () { // 24-Hours w/leading 0; 00..23
            return _pad(f.G(), 2);
        },
        i: function () { // Minutes w/leading 0; 00..59
            return _pad(jsdate.getMinutes(), 2);
        },
        s: function () { // Seconds w/leading 0; 00..59
            return _pad(jsdate.getSeconds(), 2);
        },
        u: function () { // Microseconds; 000000-999000
            return _pad(jsdate.getMilliseconds() * 1000, 6);
        },

    // Timezone
        e: function () { // Timezone identifier; e.g. Atlantic/Azores, ...
// The following works, but requires inclusion of the very large
// timezone_abbreviations_list() function.
/*              return this.date_default_timezone_get();
*/
            throw 'Not supported (see source code of date() for timezone on how to add support)';
        },
        I: function () { // DST observed?; 0 or 1
            // Compares Jan 1 minus Jan 1 UTC to Jul 1 minus Jul 1 UTC.
            // If they are not equal, then DST is observed.
            var a = new Date(f.Y(), 0), // Jan 1
                c = Date.UTC(f.Y(), 0), // Jan 1 UTC
                b = new Date(f.Y(), 6), // Jul 1
                d = Date.UTC(f.Y(), 6); // Jul 1 UTC
            return 0 + ((a - c) !== (b - d));
        },
        O: function () { // Difference to GMT in hour format; e.g. +0200
            var a = jsdate.getTimezoneOffset();
            return (a > 0 ? "-" : "+") + _pad(Math.abs(a / 60 * 100), 4);
        },
        P: function () { // Difference to GMT w/colon; e.g. +02:00
            var O = f.O();
            return (O.substr(0, 3) + ":" + O.substr(3, 2));
        },
        T: function () { // Timezone abbreviation; e.g. EST, MDT, ...
// The following works, but requires inclusion of the very
// large timezone_abbreviations_list() function.
/*              var abbr = '', i = 0, os = 0, default = 0;
            if (!tal.length) {
                tal = that.timezone_abbreviations_list();
            }
            if (that.php_js && that.php_js.default_timezone) {
                default = that.php_js.default_timezone;
                for (abbr in tal) {
                    for (i=0; i < tal[abbr].length; i++) {
                        if (tal[abbr][i].timezone_id === default) {
                            return abbr.toUpperCase();
                        }
                    }
                }
            }
            for (abbr in tal) {
                for (i = 0; i < tal[abbr].length; i++) {
                    os = -jsdate.getTimezoneOffset() * 60;
                    if (tal[abbr][i].offset === os) {
                        return abbr.toUpperCase();
                    }
                }
            }
*/
            return 'UTC';
        },
        Z: function () { // Timezone offset in seconds (-43200...50400)
            return -jsdate.getTimezoneOffset() * 60;
        },

    // Full Date/Time
        c: function () { // ISO-8601 date.
            return 'Y-m-d\\Th:i:sP'.replace(formatChr, formatChrCb);
        },
        r: function () { // RFC 2822
            return 'D, d M Y H:i:s O'.replace(formatChr, formatChrCb);
        },
        U: function () { // Seconds since UNIX epoch
            return jsdate.getTime() / 1000 | 0;
        }
    };
    this.date = function (format, timestamp) {
        that = this;
        jsdate = (
            (typeof timestamp === 'undefined') ? new Date() : // Not provided
            (timestamp instanceof Date) ? new Date(timestamp) : // JS Date()
            new Date(timestamp * 1000) // UNIX timestamp (auto-convert to int)
        );
        return format.replace(formatChr, formatChrCb);
    };
    return this.date(format, timestamp);
}