function getRadioGroupValue(radioGroupObj) {
  for (var i=0; i < radioGroupObj.length; i++)
    if (radioGroupObj[i].checked) return radioGroupObj[i].value;
  return null;
}

function inc_view_param_new_window(url,width,height,resizable,status,scrollbars,toolbar,location,directories,menubar) {
	params = "width="+width+",height="+height+",resizable="+resizable+",status="+status+",scrollbars="+scrollbars+",toolbar="+toolbar+",location="+location+",directories="+directories+",menubar="+menubar;
	window.open(url, "_blank", params);
}

function replaceString(aSearch, aFind, aReplace) {
    var result = new String;
	result = aSearch;
    if (result != null && result.length > 0) {
        var a = 0;
        var b = 0;
        while (true) {
            a = result.indexOf(aFind, b);
            if (a != -1) {
                result = result.substring(0, a) + aReplace + result.substring(a + aFind.length);
                b = a + aReplace.length;
            } else
                break;
        }
    }
    return result;
}

function kvit_load_page(){

	document.getElementById('kvit_vid_plat').focus();
	document.getElementById('kvit_vid_plat').select();

	var ua = navigator.userAgent.toLowerCase();
	// Определим Internet Explorer
	isIE = (ua.indexOf("msie") != -1 && ua.indexOf("opera") == -1);
	// Opera
	isOpera = (ua.indexOf("opera") != -1);
	// Gecko = Mozilla + Firefox + Netscape
	isGecko = (ua.indexOf("gecko") != -1);
	// Safari, используется в MAC OS
	isSafari = (ua.indexOf("safari") != -1);
	// Konqueror, используется в UNIX-системах
	isKonqueror = (ua.indexOf("konqueror") != -1);
	// Простая проверка с помощью document.write

	if (isIE) 			{};
	if (isOpera) 		{};
	if (isGecko && !isSafari) 	{};
	if (isSafari) 		{};
	if (isKonqueror) 	{};

	//if (isIE) {document.attachEvent("onkeydown",my_keydown);}
	//	else if (isOpera) {document.attachEvent("onkeypress",my_keydown);}
	//	else if (isGecko || isSafari) {document.addEventListener("keypress",my_keydown,true);}

}

function in_array(needle, haystack, argStrict) {
	var key = '', strict = !!argStrict;
	if (strict) {
		for (key in haystack) {
			if (haystack[key] === needle) {
				return true;
			}
		}
	} else {
		for (key in haystack) {
			if (haystack[key] == needle) {
				return true;
			}
		}
	}
	return false;
}

function my_keydown(){
	/*
	//if(event.ctrlKey && event.keyCode==67) alert('Ctrl+C');
	//alert(event.keyCode);

	if (window.event) { // для IE
      	if (window.event.ctrlKey && window.event.keyCode == 66) { // Ctrl + b
              	event.returnValue = false; // Отмена стандартной реакции на горячие клавиши
     			alert('Ctrl + b');
      	}
      	else if (window.event.ctrlKey && window.event.keyCode == 73) { // Ctrl + i
     			event.returnValue = false; // Отмена стандартной реакции на горячие клавиши
     			alert('Ctrl + b');
     	}
     	else if (window.event.ctrlKey && window.event.keyCode == 85) { // Ctrl + u
    			event.returnValue = false; // Отмена стандартной реакции на горячие клавиши
     			alert('Ctrl + b');
     	}
     	else if (window.event.altKey && window.event.keyCode == 112) { // Ctrl + F1
    			event.returnValue = false; // Отмена стандартной реакции на горячие клавиши
     			alert('ALT + F1');
     	}
     	else if (window.event.ctrlKey && window.event.keyCode == 8) { // Ctrl + BackSpace
    			event.returnValue = false; // Отмена стандартной реакции на горячие клавиши
     			alert('Ctrl + BackSpace');
     	}
     	//else if (window.event.keyCode == 8) { // BackSpace
    	//		event.returnValue = false; // Отмена стандартной реакции на горячие клавиши
     	//		alert('BackSpace');
     	//}
     	else if (window.event.shiftKey && window.event.keyCode == 112) { // SHIFT + F1
    			event.returnValue = false; // Отмена стандартной реакции на горячие клавиши
     			alert('SHIFT + F1');
     	}
   }
   */
}

function clear_kvit_form(){
	document.getElementById('kvit_kn').value = '';
	document.getElementById('kvit_nom').value = '';
	document.getElementById('kvit_uch').value = '1';
	document.getElementById('kvit_client_data').innerHTML = '';
	document.getElementById('kvit_form').innerHTML = '';
}

/* ************************************************************************************************************** */
/*        Общая функция для проверки введенного в поле значения с/без переходом на сл. элемент                    */
/*        obj - объект, который вызвал функцию                                                                    */
/*        next_obj - объект, которому передаётся действие                                                         */
/*        next_do  - что надо сделать соследующим объектом: выделить-1; навести фокус-1,2; submit-3;              */
/*        check_type  - характер проверки: фильтр-1, тип-2, функция-3                                             */
/*        filter  - фильтр-1, название функции-2, тип данных-3                                                    */
/*        error_message  - error_message                                                                          */
/*                    (this,'pachka_reg_month',1,1,'/^[0-9]/','{mess_wrong_day}')                                 */
/* ************************************************************************************************************** */
function general_check(obj,next_obj,next_do,check_type,filter_str,error_message){
	if (window.event.keyCode == 13) {
		if (check_type == 1){
			var filter = filter_str;
			if (! filter.test(obj.value)) {
				alert(error_message);
				obj.focus();
				obj.select();
				return 0;
			}
		}
		if (check_type == 2){

		}
		if (check_type == 3){

		}

		if (next_do == 1) {
			document.getElementById(next_obj).focus();
			document.getElementById(next_obj).select();
		}
		if (next_do == 2) {
			document.getElementById(next_obj).focus();
		}
	}
}

/* *********************************************************************************************** */
/*     Функция передает фокус следующему элементу если введена максимальная длина поля             */
/* *********************************************************************************************** */
function next_prev_max_change(event, pres_obj,next_obj,prev_obj){
	var event = event || window.event;
	if (event.keyCode == 8 && pres_obj.value.length == 0){
		if (prev_obj.length > 1){
			document.getElementById(prev_obj).focus();
			document.getElementById(prev_obj).select();
		}
		event.returnValue = false;
	}
	if (event.keyCode == 13){
		document.getElementById(next_obj).focus();
		document.getElementById(next_obj).select();
	}
	else {
		obj = pres_obj;
		obj_value = new String ("");
		obj_value = obj.value;
		if (obj.maxLength == obj_value.length && !in_array(event.keyCode,[8,27,35,36,37,38,39,40,46,110])){
			if (next_obj.length > 1){
				document.getElementById(next_obj).focus();
				document.getElementById(next_obj).select();
				return 1;
			}
		}
	}
}

function next_obj(event, pres_obj,next_obj){
	var event = event || window.event;
	var obj = pres_obj;
	obj_value = new String ("");
	obj_value = obj.value;
	if (obj.maxLength == obj_value.length && event.keyCode != 8){
		if (next_obj.length > 1){
			document.getElementById(next_obj).focus();
			document.getElementById(next_obj).select();
			return 1;
		}
	}
}

function next_obj_shot_se(event, pres_obj,next_obj){
	var event = event || window.event;
	var obj = pres_obj;
	if (event.keyCode == 8 && obj.value.length == 0){
		event.returnValue = false;
	}
	else{
		obj_value = new String ("");
		obj_value = obj.value;
		if (obj.maxLength == obj_value.length && event.keyCode != 8){
			if (next_obj.length > 1){
				document.getElementById(next_obj).focus();
				document.getElementById(next_obj).select();
				return 1;
			}
		}
	}
}

function simple_next_obj(event, next_obj){
	var event = event || window.event;
	if (event.keyCode == 13){
		if (next_obj.length > 1){
			document.getElementById(next_obj).focus();
			document.getElementById(next_obj).select();
			return 1;
		}
	}
}

function simple_next_obj_focus_only(event,next_obj){
	var event = event || window.event;
	if (event.keyCode == 13){
		if (next_obj.length > 1){
			document.getElementById(next_obj).focus();
			return 1;
		}
	}
}

// Осуществляет переход по объектам формы при ВВОДЕ данных по квитанции
function next_back_obj(event, obj,next_obj,prev_obj){
	var event = event || window.event;
	if (event.keyCode == 13){
		if (next_obj.length > 1){
			document.getElementById(next_obj).focus();
			document.getElementById(next_obj).select();
			return 1;
		}
	}
	if (event.keyCode == 8){
		if (obj.value.length == 0){
			if (prev_obj.length > 1) {
				document.getElementById(prev_obj).focus();
				document.getElementById(prev_obj).select();
				event.returnValue = false;
				return 1;
			}
		}
	}
}

 // Отправляем запрос на Подгрузить форму для ввода данных по квитанции
 // Используется при ВВОДЕ квитанций
function kvit_vid_plat_kewdown(event,obj,x){
	var event = event || window.event;
	waitAction = '';
	if (event.keyCode == 13 || x==1){
		var filter= /^[1-8]{1,}?$/;
		if (! filter.test(obj.value)) {
			alert(mess_wrong_vid_pl);
			document.getElementById('kvit_vid_plat').focus();
			document.getElementById('kvit_vid_plat').select();
			return 0;
		}

		//clear_kvit_form();

		document.getElementById('kvit_kn').focus();
		document.getElementById('kvit_kn').select();
		// Подгрузить форму для ввода данных по квитанции
		var request = '';
		var form = document.getElementById('kvit_vid_plat').value;
		request = "include/kvit_function.php?a=load_kvit_forms&form="+form;
		loadContent(request,"kvit_form");
		return 0;
	}
}

function kvit_vid_plat_kewdown_simple(){
    document.getElementById('kvit_kn').focus();
    document.getElementById('kvit_kn').select();
    // Подгрузить форму для ввода данных по квитанции
    var request = '';
    var form = document.getElementById('kvit_vid_plat').value;
    request = "include/kvit_function.php?a=load_kvit_forms&form="+form;
    loadContent(request,'kvit_form');
    return 0;
}

 // Отправляем запрос на Подгрузить форму для ввода данных по квитанции
 // Используется при редактировании квитанций
function kvit_vid_plat_kewdown_with_data(event,obj,x,npachkv,numkv){
    var event = event || window.event;
    waitAction = '';
    if (event.keyCode == 13 || x==1){
        var filter= /^[1-8]{1,}?$/;
        if (! filter.test(obj.value)) {
            alert(mess_wrong_vid_pl);
            document.getElementById('kvit_vid_plat').focus();
            document.getElementById('kvit_vid_plat').select();
            return 0;
        }
        else{
            document.getElementById('kvit_kn').focus();
            document.getElementById('kvit_kn').select();
            // Отправляем запрос на Подгрузить форму для ввода данных по квитанции
            var request = '';
            var form = document.getElementById('kvit_vid_plat').value;
            request = "include/kvit_function.php?a=load_kvit_forms_with_data&new_form="+form+"&npachkv="+npachkv+"&numkv="+numkv;
            loadContent(request,"kvit_form");
            return 0;
        }
    }
}

function kvit_knnom_keydown(event, obj){
	var event = event || window.event;
	if (obj.name == 'kvit_knnom'){
		if (event.keyCode == 13){
			var filter= /^[0-9]{7,}?$/;
			if (! filter.test(obj.value)) {
				alert(mess_wrong_knnom);
				document.getElementById('kvit_knnom').focus();
				document.getElementById('kvit_knnom').select();
				return 0;
			}
			document.getElementById('kvit_uch').focus();
			document.getElementById('kvit_uch').select();
			return 0;
		}
		if (event.keyCode == 8 && obj.value == ''){
			document.getElementById('kvit_vid_plat').focus();
			event.returnValue = false;
		}

	}
}

function kvit_uch_keydown(event){
		var event = event || window.event;
		if (event.keyCode == 13){
			kvit_uch_keydown_onblur();
			switch (document.getElementById('kvit_vid_plat').value){
				case '1':{
					document.getElementById('kvit_form1_month').focus();
					document.getElementById('kvit_form1_month').select();
					break;
				}
				case '2':{
					document.getElementById('kvit_form2_kalc').focus();
					break;
				}
				case '3':{
					document.getElementById('kvit_form3_8_summ').focus();
					break;
				}
				case '4':{
					document.getElementById('kvit_form3_8_summ').focus();
					break;
				}
				case '5':{
					document.getElementById('kvit_form58_kvt').focus();
					break;
				}
				case '6':{
					document.getElementById('kvit_form3_8_summ').focus();
					break;
				}
				case '7':{
					document.getElementById('kvit_form3_8_summ').focus();
					break;
				}
				case '8':{
					document.getElementById('kvit_form58_col').focus();
					break;
				}
			}
		}
		if (event.keyCode == 8 && obj.value == ''){
			document.getElementById('kvit_nom').focus();
			document.getElementById('kvit_nom').select();
			event.returnValue = false;
		}
}

function kvit_uch_keydown_onblur(){
    if ( (document.getElementById('kvit_uch').value != '1') && (document.getElementById('kvit_uch').value != '2') ) {
                alert(mess_wrong_uch);
                document.getElementById('kvit_uch').value='1';
                document.getElementById('kvit_uch').focus();
                document.getElementById('kvit_uch').select();
                return 0;
    }
    kvit_load_client_data();
}

function check_day(value){
		if ((value < '01') || (value > '31')) {
			return 0;
		}
		else{
			return 1;
		}
}

function check_month(value){
		if ((value < '01') || (value > '12')) {
			return 0;
		}
		else{
			return 1;
		}
}

function check_year(value){
		if ((value < '1990') || (value > '2020')) {
			return 0;
		}
		else{
			return 1;
		}
}

function conf_exit_1(){
	if (confirm("Завершить работу?")) window.close();
}

function conf_exit_2(){
	if (confirm('Прекратить ввод квитанций?'))
		document.location='kvit.php?action=vvod';
}

function conf_exit_full(){
	if (confirm("Завершить работу программы?")) {
		window.opener.close();
		window.close();
	}
}

function switch_on_off(obj,switch_obj){
	if (document.getElementById(obj).checked == true){
		document.getElementById(switch_obj).value = 1;
	}
	else{
		document.getElementById(switch_obj).value = 0;
	}
}

/* ********************************************************************************** */
/*                    КВИТАНЦИИ: ФОРМА 1                                              */
/* ********************************************************************************** */
function kvit_form1_month(event, obj){
	var event = event || window.event;
	if (event.keyCode == 13){
		if ( !check_month(obj.value) ) {
			alert(mess_wrong_month);
			document.getElementById('kvit_form1_month').focus();
			document.getElementById('kvit_form1_month').select();
			return 0;
		}
		else {
			document.getElementById('kvit_form1_year').focus();
			document.getElementById('kvit_form1_year').select();
			return 0;
		}
	}
}

function kvit_form1_year(event, obj){
	var event = event || window.event;
	if (event.keyCode == 13){
		if ( !check_year(obj.value) ) {
			alert(mess_wrong_year);
			document.getElementById('kvit_form1_year').focus();
			document.getElementById('kvit_form1_year').select();
			return 0;
		}
		else {
			document.getElementById('kvit_form1_pos_pok').focus();
			document.getElementById('kvit_form1_pos_pok').select();
			return 0;
		}
	}
}

function kvit_form1_pos_pok(event, obj){
	var event = event || window.event;
	if (event.keyCode == 13){
		if ( obj.value <1 ) {
				alert(mess_wrong_pos_pok);
				document.getElementById('kvit_form1_pos_pok').focus();
				document.getElementById('kvit_form1_pos_pok').select();
				return 0;
		}
		else {
			document.getElementById('kvit_form1_penya').focus();
			document.getElementById('kvit_form1_penya').select();
			return 0;
		}
	}
}

function kvit_form1_penya(event, obj){
	var event = event || window.event;
	if (event.keyCode == 13){
		if ( obj.value > '' ) {
			var filter= /^[0-9]/;
			if (!filter.test(obj.value)) {
				alert(mess_wrong_penya);
				document.getElementById('kvit_form1_penya').focus();
				document.getElementById('kvit_form1_penya').select();
				return 0;
			}
		}
		document.getElementById('kvit_form1_summ_gen').focus();
		document.getElementById('kvit_form1_summ_gen').select();
		return 0;
	}
}

function kvit_form1_summ_gen(event, obj){
	var event = event || window.event;
	if (event.keyCode == 13){
		if ( obj.value > '' ) {
			var filter= /^[0-9]/;
			if (!filter.test(obj.value)) {
				alert(mess_wrong_summ_gen);
				document.getElementById('kvit_form1_summ_gen').focus();
				document.getElementById('kvit_form1_summ_gen').select();
				return 0;
			}
		}
		var v1 = document.getElementById('kvit_form1_summ_gen').value;
		var v2 = document.getElementById('kvit_form1_penya').value;

		document.getElementById('kvit_form1_summ_kv').value = v1-v2;
		document.getElementById('kvit_form1_opl_day').focus();
		document.getElementById('kvit_form1_opl_day').select();
		return 0;
	}
}

function kvit_form1_opl_day(event, obj){
	var event = event || window.event;
	if (event.keyCode == 13){
		if ( !check_day(obj.value) ) {
			alert(mess_wrong_day);
			document.getElementById('kvit_form1_opl_day').focus();
			document.getElementById('kvit_form1_opl_day').select();
			return 0;
		}
		else {
			document.getElementById('kvit_form1_opl_month').focus();
			document.getElementById('kvit_form1_opl_month').select();
			return 0;
		}
	}
}

function kvit_form1_opl_month(event, obj){
	var event = event || window.event;
	if (event.keyCode == 13){
		if ( !check_month(obj.value) ) {
			alert(mess_wrong_month);
			document.getElementById('kvit_form1_opl_month').focus();
			document.getElementById('kvit_form1_opl_month').select();
			return 0;
		}
		else {
			document.getElementById('kvit_form1_opl_year').focus();
			document.getElementById('kvit_form1_opl_year').select();
			return 0;
		}
	}
}

function kvit_form1_opl_year(event, obj){
	var event = event || window.event;
	if (event.keyCode == 13){
		if ( !check_year(obj.value) ) {
			alert(mess_wrong_year);
			document.getElementById('kvit_form1_opl_year').focus();
			document.getElementById('kvit_form1_opl_year').select();
			return 0;
		}
		else {
			confirm('Уверены в правильности ввода?');
			return 0;
		}
	}
}

function kvit_form1_year_2(event){
	var event = event || window.event;
	if (event.keyCode == 13) {
		document.getElementById('kvit_form1_pos_pok').focus();
		document.getElementById('kvit_form1_pos_pok').select();
	}
}

function enum_vvod_kvit_summ_kv(){
	waitAction = '';
	request = '';
	request = "include/kvit_function.php?a=check_function&step=enum_vvod_kvit_summ_kv&penya="+document.getElementById('kvit_form1_penya').value+"&summ_gen="+ document.getElementById('kvit_form1_summ_gen').value;
	//alert(request);
	loadContent(request,'kvit_form1_summ_kv');
}

function kvit_form1_opl_year_keydown(event, obj){
	var event = event || window.event;
	if (event.keyCode == 8 && obj.value.length == 0) {
		document.getElementById('kvit_form1_opl_month').focus();
		document.getElementById('kvit_form1_opl_month').select();
		event.returnValue = false;
		return 0;
	}
	if (event.keyCode == 13 ) {
		vvod_kvit_form_submit();
	}
}

function pachka_reg_qqq(event){
	var event=event || window.event;
	if (event.keyCode == 8 && document.getElementById('pachka_reg_pl_por').value == '') {
		event.returnValue = false;
		//document.getElementById('pachka_reg_plpr').focus();
		return 0;
	}
	if (event.keyCode == 13){
		if (next_obj.length > 1){
			document.getElementById('pachka_reg_col_kv').focus();
			document.getElementById('pachka_reg_col_kv').select();
			return 1;
		}
	}
}

function kvit_form2_opl_year_keydown(event, obj){
	var event = event || window.event;
	if (event.keyCode == 8 && obj.value.length == 0) {
		document.getElementById('kvit_form2_opl_month').focus();
		document.getElementById('kvit_form2_opl_month').select();
		event.returnValue = false;
		return 0;
	}
	if (event.keyCode == 13 ) {
		vvod_kvit_form_submit();
	}
}

function kvit_form3_8_opl_year_keydown(event, obj) {
	var event = event || window.event;
	if (event.keyCode == 8 && obj.value.length == 0) {
		document.getElementById('kvit_form3_8_opl_month').focus();
		document.getElementById('kvit_form3_8_opl_month').select();
		event.returnValue = false;
		return 0;
	}
	if (event.keyCode == 13 ) {
		vvod_kvit_form_submit();
	}
}

// Отправка запроса на регистрацию или редактирование квитанции
// Если будут ошибки то они выведуться в div-е vvod_kvit_general_mess
function vvod_kvit_form_submit(){
	waitAction = '';
	request = '';
    step = document.getElementById('step').value;

    if (step == 'edit'){
        request = "include/kvit_function.php?a=vvod_kvit&step=kvit_edit&numkv=";
        request = request + document.getElementById('numkv').value;
    }
    else{
        request = "include/kvit_function.php?a=vvod_kvit&step=kvit_reg";
    }

    request = request + "&kvit_kn="				+document.getElementById('kvit_kn').value;
	request = request + "&kvit_nom="			+document.getElementById('kvit_nom').value;
	request = request + "&kvit_uch="			+document.getElementById('kvit_uch').value;
	request = request + "&kvit_vid_plat="		+document.getElementById('kvit_vid_plat').value;
	request = request + "&pachka_nom="			+document.getElementById('pachka_nom').value;
	if (document.getElementById('kvit_is_neyasn').checked == true) request = request + "&kvit_is_neyasn=1";
		else request = request + "&kvit_is_neyasn=0";
	request = request + "&all_is_true="	+document.getElementById('vvod_kvit_all_is_true').value;

	var kvit_vid_plat = document.getElementById('kvit_vid_plat').value;

	if (kvit_vid_plat == '1') {
		request = request + "&month="		+document.getElementById('kvit_form1_month').value;
		request = request + "&year="		+document.getElementById('kvit_form1_year').value;
		request = request + "&pos_pok="		+document.getElementById('kvit_form1_pos_pok').value;
		request = request + "&penya="		+document.getElementById('kvit_form1_penya').value;
		request = request + "&summ_gen="	+document.getElementById('kvit_form1_summ_gen').value;
		request = request + "&opl_day="		+document.getElementById('kvit_form1_opl_day').value;
		request = request + "&opl_month="	+document.getElementById('kvit_form1_opl_month').value;
		request = request + "&opl_year="	+document.getElementById('kvit_form1_opl_year').value;
	}
	if (kvit_vid_plat == '2') {
		request = request + "&kalc="		+document.getElementById('kvit_form2_kalc').value;
		request = request + "&vid_uslug="	+document.getElementById('kvit_form2_vid_uslug').value;
		request = request + "&summ="		+document.getElementById('kvit_form2_summ').value;
		request = request + "&opl_day="		+document.getElementById('kvit_form2_opl_day').value;
		request = request + "&opl_month="	+document.getElementById('kvit_form2_opl_month').value;
		request = request + "&opl_year="	+document.getElementById('kvit_form2_opl_year').value;
		request = request + "&kalkul_summ="	+document.getElementById('kvit_form2_kalkul_summ').value;
	}
	if (kvit_vid_plat >= '3' && kvit_vid_plat <= '8') {
		request = request + "&summ="		+document.getElementById('kvit_form3_8_summ').value;
		request = request + "&opl_day="		+document.getElementById('kvit_form3_8_opl_day').value;
		request = request + "&opl_month="	+document.getElementById('kvit_form3_8_opl_month').value;
		request = request + "&opl_year="	+document.getElementById('kvit_form3_8_opl_year').value;
	}
	if (kvit_vid_plat == '5' || kvit_vid_plat == '8'){
	    request = request + "&kvt="         +document.getElementById('kvit_form58_kvt').value;
	}

	 if (step == 'edit'){
	    if (confirm('Сохранить изменения в квитанции?')) {
		    waitAction = 'vvod_kvit_true_redirect';
		    //alert (request);
		    loadContent(request,'vvod_kvit_general_mess');
	    }
     }
     else{
         if (confirm('Зарегистрировать квитанцию?')) {
            waitAction = 'vvod_kvit_true_redirect';
            //alert (request);
            loadContent(request,'vvod_kvit_general_mess');
        }
     }
}

function vvod_kvit_true_redirect(){
	if (document.getElementById('vvod_kvit_true_redirect').value == 1) {
		mess = mess_kvit_reg_redirect;
		if (confirm(mess))
			window.location.reload();
			else document.location = 'kvit.php';
		return 1;
	}
	else{
		return 0;
	}
}

/* ****************************************************************************************** */
/*                        КВИТАНЦИИ: ФОРМА 2                                                  */
/* ****************************************************************************************** */
function personal_1_next_back_obj(event, obj,next_obj,prev_obj){
	var event = event || window.event;
	if (event.keyCode == 13){
		if (next_obj.length > 1){
			document.getElementById(next_obj).focus();
			document.getElementById(next_obj).select();
			return 1;
		}
	}
	if (event.keyCode == 8){
		if (obj.value.length == 0){
			if (prev_obj.length > 1) {
				document.getElementById(prev_obj).focus();
				event.returnValue = false;
				return 1;
			}
		}
	}
}

function personal_2_next_back_obj(event, obj,next_obj,prev_obj){
	var event = event || window.event;
	if (event.keyCode == 13){
		if (next_obj.length > 1){
			document.getElementById(next_obj).focus();
			document.getElementById(next_obj).select();
			return 1;
		}
	}
	if (event.keyCode == 8){
		if (prev_obj.length > 1) {
			document.getElementById(prev_obj).focus();
			document.getElementById(prev_obj).select();
			event.returnValue = false;
			return 1;
		}
	}
}

function enum_vvod_kvit_form2_kalkul(){
	waitAction = '';
	request = '';
	request = "include/kvit_function.php?a=enum_function&step=enum_vvod_kvit_form2_kalkul";
	request = request + "&kalc="	+document.getElementById('kvit_form2_kalc').value;
	request = request + "&kn="		+document.getElementById('kvit_kn').value;
	request = request + "&nom="		+document.getElementById('kvit_nom').value;
	request = request + "&uch="		+document.getElementById('kvit_uch').value;
	loadContent(request,'kvit_form2_kalc_summ');
}

/* **************************************************************************************** */
/*                     Регистрация пачки квитанций                                          */
/* **************************************************************************************** */
function pachka_reg_plpr(event){
	var event = event || window.event;
	if (event.keyCode == 13) {
		document.getElementById('pachka_reg_plpr').size = 1;
		document.getElementById('pachka_reg_pl_por').focus();
		document.getElementById('pachka_reg_pl_por').select();
		event.returnValue = false;
		return 1;
	}
	if (event.keyCode == 8) {
		document.getElementById('pachka_reg_plpr').size = 1;
		document.getElementById('pachka_reg_year').focus();
		document.getElementById('pachka_reg_year').select();
		event.returnValue = false;
	}
}

function pachka_reg_year_2(event){
	var event = event || window.event;
	if (event.keyCode == 13) {
		document.getElementById('pachka_reg_plpr').focus();
		document.getElementById('pachka_reg_plpr').size = 3;
	}
}

function enum_pachka_reg_summ_kas(){
	waitAction = '';
	request = '';
	request = "include/kvit_function.php?a=check_function&step=enum_pachka_reg_summ_kas&summ_pl="+document.getElementById('pachka_reg_summ_pl').value+"&summ_per="+ document.getElementById('pachka_reg_summ_per').value;
	loadContent(request,'pachka_reg_summ_kas');
}

function pachka_reg_form_submit(){
	waitAction = '';
	request = '';
	request = "include/kvit_function.php?a=pachka_reg&step=pachka_reg";
	request = request + "&pachka_reg_day="		+document.getElementById('pachka_reg_day').value;
	request = request + "&pachka_reg_month="	+document.getElementById('pachka_reg_month').value;
	request = request + "&pachka_reg_year="		+document.getElementById('pachka_reg_year').value;
	request = request + "&pachka_reg_plpr="		+document.getElementById('pachka_reg_plpr').value;
	request = request + "&pachka_reg_pl_por="	+document.getElementById('pachka_reg_pl_por').value;
	request = request + "&pachka_reg_col_kv="	+document.getElementById('pachka_reg_col_kv').value;
	request = request + "&pachka_reg_summ_pl="	+document.getElementById('pachka_reg_summ_pl').value;
	request = request + "&pachka_reg_summ_per="	+document.getElementById('pachka_reg_summ_per').value;
	request = request + "&pachka_reg_all_is_true="+document.getElementById('pachka_reg_all_is_true').value;
    request = request + "&step="				+document.getElementById('step').value;
	request = request + "&file_name="			+document.getElementById('file_name').value;
	request = request + "&type="				+document.getElementById('type').value;

	if (document.getElementById('step').value == 'import_pachka_read_head'){
		if (confirm('Зарегистрировать пачку и импортировать квитанции?')) {
			document.getElementById('pachka_reg_loader').style.display = 'block';
			waitAction = 'pachka_reg_true_redirect';
			loadContent(request,'pachka_reg_general_mess');
		}
	}
	else{
		if (confirm('Зарегистрировать пачку?')) {
			document.getElementById('pachka_reg_loader').style.display = 'block';
			waitAction = 'pachka_reg_true_redirect';
			loadContent(request,'pachka_reg_general_mess');
		}
	}
}

function pachka_reg_all_is_true_change(){
	if (document.getElementById('pachka_reg_all_is_true_change').checked == true){
		document.getElementById('pachka_reg_all_is_true').value = 1;
	}
	else{
		document.getElementById('pachka_reg_all_is_true').value = 0;
	}
}

function pachka_reg_true_redirect(){
	document.getElementById('pachka_reg_loader').style.display = 'none';
	if (document.getElementById('pachka_reg_true_redirect').value == 1) {
		var pachka_nom = new String;
		var mess = new String;
		pachka_nom = document.getElementById('pachka_reg_pachka_nom').value;
		mess = mess_pachka_reg_redirect;
		mess = replaceString(mess,'%nom%',pachka_nom);

		if (confirm(mess))
			document.location = 'kvit.php?a=vvod_kvit&step=new_kvit&pachka_nom='+pachka_nom;
			else document.location = 'kvit.php';
		return 1;
	}
	else{
		return 0;
	}
}

/* *************************************************************************************************** */
/*                     Подгрузка данных по выбранной пачке                                             */
/* *************************************************************************************************** */
function load_pachka_data(){
	waitAction = '';
	request = '';
	request = "include/kvit_function.php?a=pachka_choose&step=load_pachka_data&pachka_nom=";
	var i=0;
	var spisok_pachka = document.getElementById('spisok_pachka');
	for (var i=0; i < spisok_pachka.options.length; i++){
      if (spisok_pachka.options[i].selected == true)
	  	request = request + spisok_pachka.options[i].value;
  	}
	loadContent(request,'pachka_data');
}

function vvod_kvit_start_do_submit(){
	if (window.event.keyCode == 13) {
		document.getElementById('vvod_kvit_start').submit();
	}
}

/* *************************************************************************************************** */
/*                      Отправка запроса на пересчет пачки                                             */
/* *************************************************************************************************** */
function kvit_pereschet_pachka(pachka_nom){
	var content = document.getElementById('kvit_form').innerHTML;
	var ans = 0;

	if (content > " "){
		if (confirm(mess_is_do_pereschet_full_form)) ans = 1;
	}
	else{
		if (confirm(mess_is_do_pereschet)) ans = 1;
	}

	if (ans == 1 ){
		waitAction = 'kvit_pereschet_pachka_redirect';
		request = '';
		request = "include/kvit_function.php?a=functions&step=do_pereschet_pachka&pachka_nom="+pachka_nom;
		document.getElementById('kvit_form').innerHTML = "";
		document.getElementById('kvit_form').innerHTML = mess_several_time + '<br><img src="/images/updateLoader.gif" border="0"><br>';
		loadContent(request,'kvit_form');
	}
}

function kvit_pereschet_pachka_redirect(){
	var redirect = document.getElementById('pachka_pereschet_true_redirect').value;
	if (redirect == 1){
		alert (mess_pereschet_end);
		window.location.reload();
	}
}

/* **************************************************************************************************** */
/*                      Отправка запроса на РАЗНОСКУ пачки                                              */
/* **************************************************************************************************** */
function kvit_raznoska_pachka(pachka_nom){
	if (confirm(mess_is_do_raznoska)){
		waitAction = 'kvit_raznoska_pachka_redirect';
		request = '';
		request = "include/kvit_function.php?a=functions&step=do_raznoska_pachka&pachka_nom="+pachka_nom;
		document.getElementById('kvit_form').innerHTML = "";
		document.getElementById('kvit_form').innerHTML = mess_several_time + '<br><img src="/images/updateLoader.gif" border="0"><br>';
		loadContent(request,'kvit_form');
	}
}

function kvit_raznoska_pachka_redirect(){
	var redirect = document.getElementById('pachka_raznoska_true_redirect').value;
	if (redirect > 1){
		alert (mess_raznoska_end);
		var temp = 'include/kvit_function.php?a=view&step=logs&module=kvit_import&npachka='+redirect;
        document.location = temp;
	}
}

/* ********************************************************************************************* */
/*          ОБЩЕЕ: подгрузка списка регионов для выбранного населенного пункта, улицы            */
/* ********************************************************************************************* */
function regions_for_np(){
    waitAction = '';
    var request = '';
    request = "sprav.php?act=enum_func&step=regions_for_np&np=";
    var i=0;
    var spisok = document.getElementById('nps');
    for (var i=0; i < spisok.options.length; i++){
      if (spisok.options[i].selected == true)
          request = request + spisok.options[i].value;
    }
    loadContent(request,'div_regions');
}

function regions_for_street(){
    waitAction = '';
    var request = '';
    request = "sprav.php?act=enum_func&step=regions_for_street&np=";
    var i=0;
    var spisok = document.getElementById('nps');
    for (var i=0; i < spisok.options.length; i++){
      if (spisok.options[i].selected == true)
          request = request + spisok.options[i].value;
    }
    request = request + "&street=";
    var i=0;
    var spisok = document.getElementById('streets');
    for (var i=0; i < spisok.options.length; i++){
      if (spisok.options[i].selected == true)
          request = request + spisok.options[i].value;
    }
    loadContent(request,'div_regions');
}

function lep_for_ps(id){
    waitAction = '';
    var request = '';
    request = "sprav.php?act=enum_func&step=lep_for_ps&id="+id;
    loadContent(request,'div_lep');
}

function ps_for_res() {
    var request = '';
    request = "sprav.php?act=enum_func&step=ps_for_res&id=";
    var i=0;
    var spisok = document.getElementById('res');
    for (var i=0; i < spisok.options.length; i++){
      if (spisok.options[i].selected == true)
          request = request + spisok.options[i].value;
    }
    loadContent(request,'div_ps');
}