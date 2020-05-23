var var1 = 1;
var var2 = 1;
var waitAction = '';
var mess_several_time = '';

/* ****************************AJAX FUNCTION********************************* */
function loadContent(url, containerid, sinchro){

    // Отключение асинхронности запроса, если надо
    if (sinchro == undefined) {
        sinchro=true;
    }
	var page_request = false
	if (window.XMLHttpRequest)
		page_request = new XMLHttpRequest()
	else if (window.ActiveXObject){
		try { page_request = new ActiveXObject("Msxml2.XMLHTTP") }
		catch (e){
			try { page_request = new ActiveXObject("Microsoft.XMLHTTP") }
			catch (e){}
		}
	}
	else
		return false;

	page_request.onreadystatechange=function() { se_load_page_content(page_request, containerid); }
	page_request.open('GET', url, sinchro);
	page_request.send(null);
}

function se_load_page_content(page_request, containerid){
        /* если статус ошибка, то в форму вываливается содержание ошибки*/
        if (page_request.status>200) {updateHtmlContent(containerid, page_request.responseText);}
	if (page_request.readyState == 4 && (page_request.status==200 || window.location.href.indexOf("http")==-1)){
		updateHtmlContent(containerid, page_request.responseText);
		if (waitAction != ''){
			if (waitAction == 'bank')
				bank_reload();
			if (waitAction == 'pachka_reg_year')
				pachka_reg_year_funct_2();
			if (waitAction == 'pachka_reg_true_redirect')
				pachka_reg_true_redirect();
			if (waitAction == 'vvod_kvit_true_redirect')
				vvod_kvit_true_redirect();
			if (waitAction == 'kvit_load_client_data_neyasn_kvit')
				kvit_load_client_data_neyasn_kvit();
			if (waitAction == 'kvit_pereschet_pachka_redirect')
				kvit_pereschet_pachka_redirect();
			if (waitAction == 'kvit_raznoska_pachka_redirect')
				kvit_raznoska_pachka_redirect();

			page_back = 0;
		}
	}
}

function updateHtmlContent(elementName, content){
	document.getElementById(elementName).innerHTML = content;
}

/* ********************************************************************************************* */
/*          Отчеты (формы, ведомости, реестры):Отчеты (формы, ведомости, реестры)   			 */
/*          Запускает на выполнение формирование отчета FORMA_1                                  */
/*              "Ведомость оплаты за период с . . . по . . ."                                    */
/* ********************************************************************************************* */
function report_01(){
	document.getElementById('report_ready_f_01').style.display = 'block';
	document.getElementById('report_ready_f_01').innerHTML = messs + '<br><img src="/images/updateLoader.gif" border="0"><br>';
	waitAction = '';
	request = '';
	request = "include/otchet_function.php?a=f_01";
	request = request+'&f_01_dateb='+document.getElementById('f_01_dateb').value ;
	request = request+'&f_01_datee='+document.getElementById('f_01_datee').value ;
	loadContent(request,"report_ready_f_01");
}
/* ********************************************************************************************* */
/*          Отчеты (формы, ведомости, реестры):Отчеты (формы, ведомости, реестры)                */
/*          Запускает на выполнение формирование общего отчёта                                   */
/*              "Ведомость оплаты за период с . . . по . . ."                                    */
/* ********************************************************************************************* */
function common_report(){
    document.getElementById('report_ready_common_report').style.display = 'block';
    document.getElementById('report_ready_common_report').innerHTML = messs + '<br><img src="/images/updateLoader.gif" border="0"><br>';
    waitAction = '';
    request = '';
    request = "include/otchet_function.php?a=common_report";
    request = request+'&common_report_dateb='+document.getElementById('common_report_dateb').value ;
    request = request+'&common_report_datee='+document.getElementById('common_report_datee').value ;
    if (document.getElementById('kvt_recount').checked == true) {
        request = request + "&kvt_recount=1";
    }
    else {
        request = request + "&kvt_recount=0";
    }
    /* Выбор РЭС */
    var spisok = document.getElementById('f_res');
    for (var i=0; i < spisok.options.length; i++){
      if (spisok.options[i].selected == true)
          request = request + '&nres=' + spisok.options[i].value;
    }
    loadContent(request,"report_ready_common_report");
}

/* ********************************************************************************************* */
/*          Отчеты (формы, ведомости, реестры):Отчеты (формы, ведомости, реестры)                */
/*                       Отправка запроса на выполнение отчета по задолженностям                 */
/* ********************************************************************************************* */
function f_04_exec(){
    document.getElementById('report_ready_f_04').style.display = 'block';
    document.getElementById('report_ready_f_04').innerHTML = messs + '<br><img src="/images/updateLoader.gif" border="0"><br>';
    waitAction = '';
    request = '';
    request = "include/otchet_function.php?a=f_04";
    request = request+'&f_04_dateb='+document.getElementById('f_04_dateb').value ;
    request = request+'&f_04_datee='+document.getElementById('f_04_datee').value ;
    request = request+'&f_04_raznica='+document.getElementById('f_04_raznica').value ;
    if (document.getElementById('with_askue').checked == true) {request = request + "&with_askue=1";}
        else{request = request + "&with_askue=0";};
    /* Выбор РЭС */
    var spisok = document.getElementById('f_04_res');
    for (var i=0; i < spisok.options.length; i++){
      if (spisok.options[i].selected == true)
          request = request + '&nres=' + spisok.options[i].value;
    }
    loadContent(request,"report_ready_f_04");
}

/* ********************************************************************************************* */
/*          Отчеты (формы, ведомости, реестры):Отчеты (формы, ведомости, реестры)                */
/*                       Акт сверки поступления платежей от населения                            */
/* ********************************************************************************************* */
function f_05_exec(){
    document.getElementById('report_ready_f_05').style.display = 'block';
    document.getElementById('report_ready_f_05').innerHTML = messs + '<br><img src="/images/updateLoader.gif" border="0"><br>';
    waitAction = '';
    request = '';
    request = "include/otchet_function.php?a=f_05";
    request = request+'&f_05_dateb='+document.getElementById('f_05_dateb').value ;
    request = request+'&f_05_datee='+document.getElementById('f_05_datee').value ;
    var spisok = document.getElementById('f_05_plpr');
    for (var i=0; i < spisok.options.length; i++){
      if (spisok.options[i].selected == true)
          request = request + '&plpr=' + spisok.options[i].value;
    }
    loadContent(request,"report_ready_f_05");
}

/* ********************************************************************************************* */
/*       Отчеты (формы, ведомости, реестры):Отчеты (формы, ведомости, реестры)                   */
/*       Запускает на выполнение формирование отчета FORMA_6                                     */
/*       "Отчёт по предоставлению отдельным категориям граждан льгот по оплате электроэнергии"   */
/* ********************************************************************************************* */
function f_06(){
    document.getElementById('report_ready_f_06').style.display = 'block';
    document.getElementById('report_ready_f_06').innerHTML = messs + '<br><img src="/images/updateLoader.gif" border="0"><br>';
    waitAction = '';
    request = '';
    request = 'include/otchet_function.php?a=f_06';
    request = request + "&dateb="+document.getElementById('f_06_dateb').value;
    request = request + "&datee="+document.getElementById('f_06_datee').value;
    /* Выбор РЭС */
    var spisok = document.getElementById('f_06_res');
    for (var i=0; i < spisok.options.length; i++){
      if (spisok.options[i].selected == true)
          request = request + '&nres=' + spisok.options[i].value;
    }
    loadContent(request,"report_ready_f_06");
}

/* ********************************************************************************************* */
/*                               Отчеты (формы, ведомости, реестры):                             */
/*                             Учет движения счетчиков в разрезе тарифов                         */
/* ********************************************************************************************* */
function f_07_exec(){
    document.getElementById('report_ready_f_07').style.display = 'block';
    document.getElementById('report_ready_f_07').innerHTML = messs + '<br><img src="/images/updateLoader.gif" border="0"><br>';
    waitAction = '';
    request = '';
    request = "include/otchet_function.php?a=f_07";
    request = request+'&f_07_dateb='+document.getElementById('f_07_dateb').value ;
    request = request+'&f_07_datee='+document.getElementById('f_07_datee').value ;
    /* Выбор РЭС */
    var spisok = document.getElementById('f_07_res');
    for (var i=0; i < spisok.options.length; i++){
      if (spisok.options[i].selected == true)
          request = request + '&nres=' + spisok.options[i].value;
    }
    loadContent(request,"report_ready_f_07");
}

/* ********************************************************************************************* */
/*          Отчеты (формы, ведомости, реестры):Отчеты (формы, ведомости, реестры)                */
/*                       Отправка запроса на выполнение отчета по неплательщикам                */
/* ********************************************************************************************* */
function f_08_exec(){
    document.getElementById('report_ready_f_08').style.display = 'block';
    document.getElementById('report_ready_f_08').innerHTML = messs + '<br><img src="/images/updateLoader.gif" border="0"><br>';
    waitAction = '';
    request = '';
    request = "include/otchet_function.php?a=f_08";
    request = request+'&f_08_dateb='+document.getElementById('f_08_dateb').value ;
    request = request+'&f_08_datee='+document.getElementById('f_08_datee').value ;
    /* Выбор РЭС */
    var spisok = document.getElementById('f_08_res');
    for (var i=0; i < spisok.options.length; i++){
      if (spisok.options[i].selected == true)
          request = request + '&nres=' + spisok.options[i].value;
    }
    loadContent(request,"report_ready_f_08");
}

/* ********************************************************************************************* */
/*          Отчеты (формы, ведомости, реестры):Отчеты (формы, ведомости, реестры)                */
/*          Запускает на выполнение формирование отчета о потреблении за период                  */
/* ********************************************************************************************* */
function report_potreb(){
    document.getElementById('report_ready_f_potreb').style.display = 'block';
    document.getElementById('report_ready_f_potreb').innerHTML = messs + '<br><img src="/images/updateLoader.gif" border="0"><br>';
    waitAction = '';
    request = '';
    request = "include/otchet_function.php?a=f_potreb";
    request = request+'&f_dateb='+document.getElementById('f_dateb').value ;
    request = request+'&f_datee='+document.getElementById('f_datee').value ;
    loadContent(request,"report_ready_f_potreb");
}

/* ********************************************************************************************* */
/*          Отчеты (формы, ведомости, реестры):Отчеты (формы, ведомости, реестры)                */
/*          Запускает на выполнение формирование отчета о потреблении свыше 1000кВт за период    */
/* ********************************************************************************************* */
function report_potreb_1000kvt(){
    document.getElementById('report_ready_f_potreb_1000kvt').style.display = 'block';
    document.getElementById('report_ready_f_potreb_1000kvt').innerHTML = messs + '<br><img src="/images/updateLoader.gif" border="0"><br>';
    waitAction = '';
    request = '';
    request = "include/otchet_function.php?a=f_potreb_1000kvt";
    request = request+'&f_dateb='+document.getElementById('f_dateb').value ;
    request = request+'&f_datee='+document.getElementById('f_datee').value ;
    request = request+'&f_1000_raznica='+document.getElementById('f_1000_raznica').value ;
    loadContent(request,"report_ready_f_potreb_1000kvt");
}

/* ********************************************************************************************* */
/*          Отчеты (формы, ведомости, реестры):Отчеты (формы, ведомости, реестры)   			 */
/*               		Реестр возврата квитанций (за услуги)                        			 */
/* ********************************************************************************************* */
function reestr_vozv_exec(){
	document.getElementById('report_ready_reestr_vozv').style.display = 'block';
	document.getElementById('report_ready_reestr_vozv').innerHTML = messs + '<br><img src="/images/updateLoader.gif" border="0"><br>';
	waitAction = '';
	request = '';
	request = "include/otchet_function.php?a=reestr_vozv";
	request = request+'&reestr_vozv_dateb='+document.getElementById('reestr_vozv_dateb').value ;
	request = request+'&reestr_vozv_datee='+document.getElementById('reestr_vozv_datee').value ;
    var spisok = document.getElementById('reestr_vozv_type');
    for (var i=0; i < spisok.options.length; i++){
      if (spisok.options[i].selected == true)
          request = request + '&reestr_vozv_type=' + spisok.options[i].value;
    }
	loadContent(request,"report_ready_reestr_vozv");
}

/* ********************************************************************************************* */
/*          Отчеты (формы, ведомости, реестры):Отчеты (формы, ведомости, реестры)                */
/*                       Акт сверки поступления платежей от населения                            */
/* ********************************************************************************************* */
function kontr_nak_ved_exec(){
    document.getElementById('report_ready_f_knv').style.display = 'block';
    document.getElementById('report_ready_f_knv').innerHTML = messs + '<br><img src="/images/updateLoader.gif" border="0"><br>';
    waitAction = '';
    request = '';
    request = "include/otchet_function.php?a=kontr_nak_ved";
    request = request+'&f_knv_dateb='+document.getElementById('f_knv_dateb').value ;
    request = request+'&f_knv_datee='+document.getElementById('f_knv_datee').value ;
    loadContent(request,"report_ready_f_knv");
}

/* ************************************************************************************* */
/*          Отчеты (формы, ведомости, реестры):Отчеты (формы, ведомости, реестры)   	 */
/*               		Отправка запроса на выполнение отчета       					 */
/*               		Абоненты в разрезе типов построек       						 */
/* ************************************************************************************* */
function tipy_postroek_exec(){
	document.getElementById('report_ready_tipy_postroek').style.display = 'block';
	document.getElementById('report_ready_tipy_postroek').innerHTML = messs + '<br><img src="/images/updateLoader.gif" border="0"><br>';
	var waitAction = '';
	var request = '';
	request = "include/otchet_function.php?a=tipy_postroek";
	loadContent(request,"report_ready_tipy_postroek");
}

/* ************************************************************************************* */
/*          Отчеты (формы, ведомости, реестры):Отчеты (формы, ведомости, реестры)   	 */
/*               		Отправка запроса на выполнение отчета                   		 */
/*               		Абоненты в разрезе привязки		      		                     */
/* ************************************************************************************* */
function priviazka_exec(){
	document.getElementById('report_ready_f_priviazka').style.display = 'block';
	document.getElementById('report_ready_f_priviazka').innerHTML = messs + '<br><img src="/images/updateLoader.gif" border="0"><br>';
	var waitAction = '';
	var request = '';
	request = "include/otchet_function.php?a=priviazka";
	loadContent(request,"report_ready_f_priviazka");
}

/* ********************************************************************************************* */
/*          Отчеты (формы, ведомости, реестры):Отчеты (формы, ведомости, реестры)                */
/*          Запускает на выполнение формирование отчета FORMA_2                                  */
/*            "Ведомость потери льготников  за период с . . . по . . ."                          */
/* ********************************************************************************************* */
function report_lose_lg(){
    document.getElementById('report_ready_lose_lg').style.display = 'block';
    document.getElementById('report_ready_lose_lg').innerHTML = messs + '<br><img src="/images/updateLoader.gif" border="0"><br>';
    waitAction = '';
    request = '';
    request = "include/otchet_function.php?a=lose_lg";
    request = request+'&lose_lg_dateb='+document.getElementById('lose_lg_dateb').value ;
    request = request+'&lose_lg_datee='+document.getElementById('lose_lg_datee').value ;
    loadContent(request,"report_ready_lose_lg");
}

/* ********************************************************************************************* */
/*          Отчеты (формы, ведомости, реестры): Циклический вызов отчета в банк                     */
/* ********************************************************************************************* */
function bank_reload(){
    var is_end=0;
    is_end = document.getElementById('is_end').value;
    if (is_end == 0) {
        bank();
    }
    else{
        return 0;
    }
}

/* ********************************************************************************************* */
/*       Отчеты (формы, ведомости, реестры):Список для обхода                                    */
/*       Производит выборку улиц для населенного пункта                                          */
/* ********************************************************************************************* */
function f_get_streets_for_np(){
	waitAction = '';
	var request = 'include/otchet_function.php?a=get_streets_for_np&id_np=';
	var spisok_np = document.getElementById('np');
	for (var i=0; i < spisok_np.options.length; i++){
      if (spisok_np.options[i].selected == true)
	  	request = request + spisok_np.options[i].value;
  	}
	loadContent(request,"f_obhod_streets");
}

function f_get_streets_for_np_copy(){
    waitAction = '';
    var request = 'include/otchet_function.php?a=get_streets_for_np_copy&id_np=';
    var spisok_np = document.getElementById('filter_np');
    for (var i=0; i < spisok_np.options.length; i++){
      if (spisok_np.options[i].selected == true)
          request = request + spisok_np.options[i].value;
      }
    loadContent(request,"filter_streets");
}

function f_get_streets_for_np_copy2(){
    waitAction = '';

    var request = 'include/otchet_function.php?a=get_streets_for_np_copy2&id_np=';
    var spisok_np = document.getElementById('nps');
    for (var i=0; i < spisok_np.options.length; i++){
      if (spisok_np.options[i].selected == true)
          request = request + spisok_np.options[i].value;
      }
    loadContent(request,"div_streets");
}

function set_res_for_np(){
    waitAction = '';
    var request = 'include/otchet_function.php?a=set_res_for_np&id_np=';
    var spisok_np = document.getElementById('nps');
    for (var i=0; i < spisok_np.options.length; i++){
      if (spisok_np.options[i].selected == true)
          request = request + spisok_np.options[i].value;
      }
    loadContent(request,"div_ress", false);
}

/* ********************************************************************************************* */
/*       Отчеты (формы, ведомости, реестры):Список для обхода                   			     */
/*       Запуск на выполнение формирования отчета                                                */
/* ********************************************************************************************* */
function f_obhod_exec(){
	waitAction = '';
	var request = 'include/otchet_function.php?a=f_obhod';
	if (document.getElementById('with_kn').checked == true) {request = request + "&with_kn=1";}else{request = request + "&with_kn=0";};
	if (document.getElementById('with_address').checked == true) {request = request + "&with_address=1";} else{request = request + "&with_address=0";};
	request = request + "&f_obhod_knbeg="+document.getElementById('f_obhod_knbeg').value;
	request = request + "&f_obhod_knend="+document.getElementById('f_obhod_knend').value;
	request = request + "&f_obhod_np=";
	var spisok_np = document.getElementById('np');
	for (var i=0; i < spisok_np.options.length; i++){
      if (spisok_np.options[i].selected == true)
	  	request = request + spisok_np.options[i].value;
  	}
	request = request + "&f_obhod_street=";
	var spisok_street = document.getElementById('f_obhod_street');
	for (var i=0; i < spisok_street.options.length; i++){
      if (spisok_street.options[i].selected == true)
	  	request = request + spisok_street.options[i].value;
  	}
	request = request + "&f_obhod_dombeg="+document.getElementById('f_obhod_dombeg').value;
	request = request + "&f_obhod_domend="+document.getElementById('f_obhod_domend').value;
	loadContent(request,"report_ready_f_obhod");
}

/* ********************************************************************************************* */
/*       Отчеты (формы, ведомости, реестры):Отчеты (формы, ведомости, реестры)   			     */
/*       Запускает на выполнение формирование отчета lgotniki_prinadl "Списки льготников"        */
/* ********************************************************************************************* */
function lgotniki_prinadl(){
	waitAction = '';
	var request_option = '';
	var spisok_object = '';
	var request = 'include/otchet_function.php?a=report_lgotniki_prinadl';
	document.getElementById('report_ready_lgotniki_prinadl').style.display = 'block';
	document.getElementById('report_ready_lgotniki_prinadl').innerHTML = messs + '<br><img src="/images/updateLoader.gif" border="0"><br>';

	if (document.getElementById('spisok_tp_dot').checked == true) {
		spisok_object = document.getElementById('spisok_tp');
		request_option = '&tp=';
		for (var i = 0; i < spisok_object.options.length; i++) {
			if (spisok_object.options[i].selected) request_option = request_option + spisok_object.options[i].value + '|';
		}
		request = request + request_option;
	}

	if (document.getElementById('spisok_ss_dot').checked == true) {
		spisok_object = document.getElementById('spisok_ss');
		request_option = '&ss=';
		for (var i = 0; i < spisok_object.options.length; i++) {
			if (spisok_object.options[i].selected)
				request_option = request_option + i + ',';
		}
		request = request + request_option;
	}
	if (document.getElementById('sort_by_knnom').checked == true)
        request = request + '&sort_by_knnom=1';
	if (document.getElementById('sort_by_fio').checked == true)
		request = request + '&sort_by_fio=1';
	if (document.getElementById('sort_by_adr').checked == true)
		request = request + '&sort_by_adr=1';
	if (document.getElementById('sort_by_lg').checked == true)
        request = request + '&sort_by_lg=1';
    if (document.getElementById('full_form').checked == true)
        request = request + '&full_form=1';
    if (document.getElementById('shot_form').checked == true)
		request = request + '&shot_form=1';

    if (document.getElementById('chastnyj_sektor').checked == true)
        request = request + '&chastnyj_sektor=1';

	if (document.getElementById('without_reg').checked == true) {
		request = request + "&without_reg=1&without_reg_date="+document.getElementById('without_reg_date').value;
	}
    else{
        request = request + "&without_reg=0&without_reg_date="+document.getElementById('without_reg_date').value;
    }

    if (document.getElementById('lgotnikov_bolshe').checked == true)
        request = request + '&lgotnikov_bolshe=1';

	loadContent(request,"report_ready_lgotniki_prinadl");
}

/* ********************************************************************************************* */
/*          Отчеты (формы, ведомости, реестры):Отчеты (формы, ведомости, реестры)   			 */
/*          Отменяет весь выбор в списке Принадлежность счетчика                                 */
/* ********************************************************************************************* */
function spisok_clear(obj){
	var spisok_object = document.getElementById(obj);
	for (var i=0; i < spisok_object.options.length; i++){
      if (spisok_object.options[i].selected) spisok_object.options[i].selected = false;
  	}
}

function spisok_select_all(obj){
	var spisok_object = document.getElementById(obj);
	for (var i=0; i < spisok_object.options.length; i++){
      if (spisok_object.options[i].selected == false) spisok_object.options[i].selected = true;
  	}
}

function change_panel(){
	var spisok_tp = document.getElementById('spisok_tp');
	var spisok_ss = document.getElementById('spisok_ss');
	var spisok_tp_dot = document.getElementById('spisok_tp_dot');
	var spisok_ss_dot = document.getElementById('spisok_ss_dot');

	if (spisok_tp_dot.checked==true){
		spisok_tp.disabled = false;
		spisok_ss.disabled = true;
		spisok_clear('spisok_ss');
	}
	else{
		spisok_tp.disabled = true;
		spisok_ss.disabled = false;
		spisok_clear('spisok_tp');
		spisok_ss.click();
	}
}

/* ********************************************************************************************* */
/*       Отчеты (формы, ведомости, реестры): КОНСТРУКТОР ОТЧЕТОВ                   			     */
/*       Запуск на выполнение формирования отчета                                                */
/* ********************************************************************************************* */
function f_constructor(){
	waitAction = '';
	var request = 'include/otchet_function.php?a=f_constructor';

    /* Выбор РЭС */
    var spisok = document.getElementById('f_res');
    for (var i=0; i < spisok.options.length; i++){
      if (spisok.options[i].selected == true)
          request = request + '&nres=' + spisok.options[i].value;
    }

	/* ***********************    Временные интервалы   *********************** */
    //Период расчета with_date
    if (document.getElementById('with_date').checked == true) {request = request + "&with_date=1";}else{request = request + "&with_date=0";};
    request = request + "&f_c_dateb="+document.getElementById('f_c_dateb').value;
    request = request + "&f_c_datee="+document.getElementById('f_c_datee').value;
    //ПЕРИОД. СЧЕТЧИК: установлен with_date_sc_ust
    if (document.getElementById('with_date_sc_ust').checked == true) {request = request + "&with_date_sc_ust=1";}else{request = request + "&with_date_sc_ust=0";};
    request = request + "&f_c_dateb_sc_ust="+document.getElementById('f_c_dateb_sc_ust').value;
    request = request + "&f_c_datee_sc_ust="+document.getElementById('f_c_datee_sc_ust').value;
    //ПЕРИОД. СЧЕТЧИК: снят with_date_sc_sn
    if (document.getElementById('with_date_sc_sn').checked == true) {request = request + "&with_date_sc_sn=1";}else{request = request + "&with_date_sc_sn=0";};
    request = request + "&f_c_dateb_sc_sn="+document.getElementById('f_c_dateb_sc_sn').value;
    request = request + "&f_c_datee_sc_sn="+document.getElementById('f_c_datee_sc_sn').value;
    //СЧЕТЧИК: просроченные на год
    if (document.getElementById('with_date_sc_prosr').checked == true) {request = request + "&with_date_sc_prosr=1";}else{request = request + "&with_date_sc_prosr=0";};
    var spisok = document.getElementById('f_c_date_sc_prosr');
    for (var i=0; i < spisok.options.length; i++){
      if (spisok.options[i].selected == true) request = request + '&f_c_date_sc_prosr=' + spisok.options[i].value;
    }
   //НЕПЛАТЕЛЬЩИКИ С:
    if (document.getElementById('with_date_neplat_s').checked == true) {request = request + "&with_date_neplat_s=1";}else{request = request + "&with_date_neplat_s=0";};
    request = request + "&f_c_dateb_neplat_s="+document.getElementById('f_c_dateb_neplat_s').value;
    //ДОГОВОР ЗАКЛЮЧЕН
     if (document.getElementById('with_date_dogovor').checked == true) {request = request + "&with_date_dogovor=1";}else{request = request + "&with_date_dogovor=0";};
    request = request + "&f_c_dateb_dogovor="+document.getElementById('f_c_dateb_dogovor').value;
    request = request + "&f_c_datee_dogovor="+document.getElementById('f_c_datee_dogovor').value;
    //БЕЗ ОБХОДОВ С:
    if (document.getElementById('with_date_bezobhod_s').checked == true) {request = request + "&with_date_bezobhod_s=1";}else{request = request + "&with_date_bezobhod_s=0";};
    request = request + "&f_c_dateb_bezobhod_s="+document.getElementById('f_c_dateb_bezobhod_s').value;
    //ДАТА ПОСЛЕДНЕГО ОБХОДА С.. ПО..
     if (document.getElementById('with_date_obhod').checked == true) {request = request + "&with_date_obhod=1";}else{request = request + "&with_date_obhod=0";};
    request = request + "&f_c_dateb_obhod="+document.getElementById('f_c_dateb_obhod').value;
    request = request + "&f_c_datee_obhod="+document.getElementById('f_c_datee_obhod').value;
        //разница оплаты и обхода
    request = request + "&f_c_raznica_obhod="+document.getElementById('f_c_raznica_obhod').value;
    //ДАТА ОТКЛЮЧЕНИЯ С.. ПО..
     if (document.getElementById('with_date_otkl').checked == true) {request = request + "&with_date_otkl=1";}else{request = request + "&with_date_otkl=0";};
    request = request + "&f_c_dateb_otkl="+document.getElementById('f_c_dateb_otkl').value;
    request = request + "&f_c_datee_otkl="+document.getElementById('f_c_datee_otkl').value;

    /* ***********************   Общие условия выборки  *********************** */
    //ПО ВСЕЙ БАЗЕ
    if (document.getElementById('with_alldb').checked == true) {request = request + "&with_alldb=1";}else{request = request + "&with_alldb=0";};
    //КНИГА with_kn
	if (document.getElementById('with_kn').checked == true) {request = request + "&with_kn=1";}else{request = request + "&with_kn=0";};
	request = request + "&f_c_knbeg="+document.getElementById('f_c_knbeg').value;
	request = request + "&f_c_knend="+document.getElementById('f_c_knend').value;

	//АДРЕС with_address
	if (document.getElementById('with_address').checked == true) {request = request + "&with_address=1";}else{request = request + "&with_address=0";};
	request = request + '&nps=';
	var spisok = document.getElementById('constructor_np');
	for (var i=0; i < spisok.options.length; i++) {
      if (spisok.options[i].selected == true) request = request + spisok.options[i].value + ',';
  	}
	request = request + '&streets=';
	var spisok = document.getElementById('streets');
	for (var i=0; i < spisok.options.length; i++) {
      if (spisok.options[i].selected == true) request = request + spisok.options[i].value + ',';
  	}
	request = request + '&doms=';
    var spisok = document.getElementById('doms');
    for (var i=0; i < spisok.options.length; i++) {
      if (spisok.options[i].selected == true) request = request + spisok.options[i].value + ',';
    }
    //РЕГИОН with_region
    if (document.getElementById('with_region').checked == true) {request = request + "&with_region=1";}else{request = request + "&with_region=0";};
    request = request + '&regions=';
    var spisok = document.getElementById('region');
    for (var i=0; i < spisok.options.length; i++) {
      if (spisok.options[i].selected == true) request = request + spisok.options[i].value + ',';
    }

    //Контролёр для Мобильного приложения
    request = request + '&persona=';
    var spisok = document.getElementById('persona');
    for (var i=0; i < spisok.options.length; i++) {
      if (spisok.options[i].selected == true) request = request + spisok.options[i].value;
    }

    /* ***********************   Дополнительные ограничения  *********************** */
    // СЧЕТЧИК: учитывать в том числе и снятые
    if (document.getElementById('with_sn_sc').checked == true) {request = request + "&with_sn_sc=1";}else{request = request + "&with_sn_sc=0";};
    //  ДОГОВОР НЕ ЗАКЛЮЧЕН:включить в отчет только абонентов, с которыми не заключен договор;
    if (document.getElementById('with_without_dog').checked == true) {request = request + "&with_without_dog=1";}else{request = request + "&with_without_dog=0";};
    //  БЕЗ ОТКЛЮЧЕННЫХ АБОНЕНТОВ:не выбирать отключенных на данный момент времени абонентов;
    if (document.getElementById('with_without_otkl').checked == true) {request = request + "&with_without_otkl=1";}else{request = request + "&with_without_otkl=0";};
    //  АРХИВНЫЕ АБОНЕНТЫ:включить в отчет архивных абонентов;
    if (document.getElementById('with_with_arch').checked == true) {request = request + "&with_with_arch=1";}else{request = request + "&with_with_arch=0";};
    // МНОГОТАРИФНЫЕ: учитывать каждый тариф отдельно
    if (document.getElementById('with_uch2').checked == true) {request = request + "&with_uch2=1";}else{request = request + "&with_uch2=0";};
    // ДИФТАРИФНЫЕ: учитывать только абонентов с несколькими тарифами
    if (document.getElementById('with_diftarif').checked == true) {request = request + "&with_diftarif=1";}else{request = request + "&with_diftarif=0";};
    // АСКУЭ: учитывать только дома с АСКУЭ
    if (document.getElementById('with_askue').checked == true) {request = request + "&with_askue=1";}else{request = request + "&with_askue=0";};
    // ЛИМИТ: превышен лимит потребления
	if (document.getElementById('with_limit').checked == true) {request = request + "&with_limit=1";}else{request = request + "&with_limit=0";};

    /* ***********************   Фильтры  *********************** */
	// ТИП СЧЕТЧИКА with_tip_sc
	if (document.getElementById('with_tip_sc').checked == true) {request = request + "&with_tip_sc=1";}
		else{request = request + "&with_tip_sc=0";};
	request = request + '&tip_scs=';
	var spisok = document.getElementById('tip_sc');
	for (var i=0; i < spisok.options.length; i++) {
      if (spisok.options[i].selected == true) request = request + spisok.options[i].value;
  	}
    // ВИД СЧЕТЧИКА with_vid_sc // Электронный/эл.механический/индукционный
    if (document.getElementById('with_vid_sc').checked == true) {request = request + "&with_vid_sc=1";}
        else{request = request + "&with_vid_sc=0";};
    request = request + '&vid_scs=';
    var spisok = document.getElementById('vid_sc');
    for (var i=0; i < spisok.options.length; i++) {
      if (spisok.options[i].selected == true) request = request + spisok.options[i].value;
    }
    // СЧЕТЧИКИ: класс точности with_klass_toch // 0.5 1.0 1.5 2.0 2.5
    if (document.getElementById('with_klass_toch').checked == true) {request = request + "&with_klass_toch=1";}
        else{request = request + "&with_klass_toch=0";};
    request = request + '&klass_tochs=';
    var spisok = document.getElementById('klass_toch');
    for (var i=0; i < spisok.options.length; i++) {
        if (spisok.options[i].selected == true) request = request + spisok.options[i].value+'|';
    }
    // СЧЕТЧИКИ: год выпуска with_sc_yearvyp
    if (document.getElementById('with_sc_yearvyp').checked == true) {request = request + "&with_sc_yearvyp=1";}
        else{request = request + "&with_sc_yearvyp=0";};
    request = request + '&sc_yearvyps=';
    var spisok = document.getElementById('sc_yearvyp');
    for (var i=0; i < spisok.options.length; i++) {
        if (spisok.options[i].selected == true) request = request + spisok.options[i].value+'|';
    }
    // СЧЕТЧИКИ: ПРОИЗВОДИТЕЛЬ with_sc_izg
    if (document.getElementById('with_sc_izg').checked == true) {request = request + "&with_sc_izg=1";}
        else{request = request + "&with_sc_izg=0";};
    request = request + '&sc_izgs=';
    var spisok = document.getElementById('sc_izg');
    for (var i=0; i < spisok.options.length; i++) {
        if (spisok.options[i].selected == true) request = request + spisok.options[i].value+'|';
    }
	// КОНКРЕТНЫЙ СЧЕТЧИК with_sc
	if (document.getElementById('with_sc').checked == true) {request = request + "&with_sc=1";}
		else{request = request + "&with_sc=0";};
	request = request + '&scs=';
	var spisok = document.getElementById('sc');
	for (var i=0; i < spisok.options.length; i++) {
      if (spisok.options[i].selected == true) request = request + spisok.options[i].value+',';
  	}
	// ПРИНАДЛЕЖНОСТЬ СЧЕТЧИКОВ with_tip_prinadl_sc
	if (document.getElementById('with_tip_prinadl_sc').checked == true) {request = request + "&with_tip_prinadl_sc=1";}
		else{request = request + "&with_tip_prinadl_sc=0"; };
	request = request + '&tip_prinadls_sc=';
	var spisok = document.getElementById('tip_prinadl_sc');
	for (var i=0; i < spisok.options.length; i++) {
      if (spisok.options[i].selected == true) request = request + spisok.options[i].value+',';
  	}
    // ПРИНАДЛЕЖНОСТЬ ДОМОВ with_tip_prinadl_dom
    if (document.getElementById('with_tip_prinadl_dom').checked == true) {request = request + "&with_tip_prinadl_dom=1";}
        else{request = request + "&with_tip_prinadl_dom=0"; };
    request = request + '&tip_prinadls_dom=';
    var spisok = document.getElementById('tip_prinadl_dom');
    for (var i=0; i < spisok.options.length; i++){
      if (spisok.options[i].selected == true) request = request + spisok.options[i].value+',';
    }
	// ТИП ПОСТРОЙКИ
	if (document.getElementById('with_tip_postr').checked == true) {request = request + "&with_tip_postr=1";}
		else{request = request + "&with_tip_postr=0"; };
	request = request + '&tip_postrs=';
	var spisok = document.getElementById('tip_postr');
	for (var i=0; i < spisok.options.length; i++) {
      if (spisok.options[i].selected == true) request = request + spisok.options[i].value+',';
  	}
	// ВИД ЛЬГОТЫ
	if (document.getElementById('with_vidlg').checked == true) {request = request + "&with_vidlg=1";}
		else{request = request + "&with_vidlg=0"; };
	request = request + '&vidlgs=';
	var spisok = document.getElementById('vidlg');
	for (var i=0; i < spisok.options.length; i++) {
      if (spisok.options[i].selected == true) request = request + spisok.options[i].value+',';
  	}
	// ТАРИФ with_vidtar
	if (document.getElementById('with_vidtar').checked == true) {request = request + "&with_vidtar=1";}
		else{request = request + "&with_vidtar=0"; };
	request = request + '&vidtars=';
	var spisok = document.getElementById('vidtar');
	for (var i=0; i < spisok.options.length; i++) {
      if (spisok.options[i].selected == true) request = request + spisok.options[i].value + ',';
  	}
    // ТАРИФ: состав семьи with_sostav_semji
    if (document.getElementById('with_sostav_semji').checked == true) {request = request + "&with_sostav_semji=1";}
        else{request = request + "&with_sostav_semji=0";};
    request = request + '&sostav_semjis=';
    var spisok = document.getElementById('sostav_semji');
    for (var i=0; i < spisok.options.length; i++) {
        if (spisok.options[i].selected == true) request = request + spisok.options[i].value+'|';
    }
    // КТП with_ktp
    if (document.getElementById('with_ktp').checked == true) {request = request + "&with_ktp=1";}
        else{request = request + "&with_ktp=0"; };
    request = request + '&ktp=';
    var spisok = document.getElementById('ktp');
    for (var i=0; i < spisok.options.length; i++) {
        if (spisok.options[i].selected == true) request = request + "'" +spisok.options[i].value + "',";
    }
    // NOMR with_nomr
    if (document.getElementById('with_nomr').checked == true) {request = request + "&with_nomr=1";}
        else{request = request + "&with_nomr=0"; };
    request = request + '&nomr=';
    var spisok = document.getElementById('nomr');
    for (var i=0; i < spisok.options.length; i++) {
        if (spisok.options[i].selected == true) request = request + spisok.options[i].value + "|";
    }
    // otkl  with_otkl (Отключения)
    if (document.getElementById('with_otkl').checked == true) {request = request + "&with_otkl=1";}
        else {request = request + "&with_otkl=0"; };
    request = request + '&otkl=';
    var spisok = document.getElementById('otkl');
    for (var i=0; i < spisok.options.length; i++) {
        if (spisok.options[i].selected == true) request = request + spisok.options[i].value + "|";
    }

	/* ********************* ПОЛЯ ДЛЯ ВЫВОДА В ОТЧЕТ ******************** */
    // СПОСОБ ВЫВОДА ОТЧЕТА: КОНСТРУКТОР ИЛИ СТАНДАРТНЫЕ ФОРМЫ ОТЧЕТНОСТИ
    var out_use = getRadioGroupValue(document.forma_constructor.out_use);
    request = request + "&out_use="+out_use;


    //ВЫБОР СТАНДАРТНОЙ ФОРМЫ ОТЧЕТНОСТИ
    var out_use_standart_form = getRadioGroupValue(document.forma_constructor.out_use_standart_form);
    request = request + "&out_use_standart_form="+out_use_standart_form;
    //Не выводить ошибочные квитанции
    if (document.getElementById('without_osh_kvit').checked == true) {request = request + "&without_osh_kvit=1";}
        else{request = request + "&without_osh_kvit=0";};
    //Не выводить служебные отметки
    if (document.getElementById('without_sl_otm').checked == true) {request = request + "&without_sl_otm=1";}
        else{request = request + "&without_sl_otm=0";};
    //Не выводить оплаченное показание
    if (document.getElementById('without_opl_pok').checked == true) {request = request + "&without_opl_pok=1";}
        else{request = request + "&without_opl_pok=0";};
    //Не выводить тип постройки
    if (document.getElementById('without_tip_postr').checked == true) {request = request + "&without_tip_postr=1";}
        else{request = request + "&without_tip_postr=0";};
    //Не выводить ошибочные квитанции (шаблон с тремя строками)
    if (document.getElementById('without_osh_kvit_3str').checked == true) {request = request + "&without_osh_kvit_3str=1";}
        else{request = request + "&without_osh_kvit_3str=0";};
    //Не выводить служебные отметки (шаблон с тремя строками)
    if (document.getElementById('without_sl_otm_3str').checked == true) {request = request + "&without_sl_otm_3str=1";}
        else{request = request + "&without_sl_otm_3str=0";};
    //Не выводить оплаченное показание (шаблон с тремя строками)
    if (document.getElementById('without_opl_pok_3str').checked == true) {request = request + "&without_opl_pok_3str=1";}
        else{request = request + "&without_opl_pok_3str=0";};
    //Не выводить тип постройки (шаблон с тремя строками)
    if (document.getElementById('without_tip_postr_3str').checked == true) {request = request + "&without_tip_postr_3str=1";}
        else{request = request + "&without_tip_postr_3str=0";};
    // НОМЕР ПО ПОРЯДКУ out_number
	if (document.getElementById('out_number').checked == true) request = request + "&out_number=1"; else request = request + "&out_number=0";
	request = request + "&out_number_col="+document.getElementById('out_number_col').value;
	request = request + "&out_number_width="+document.getElementById('out_number_width').value;
	// ДАННЫЕ АБОНЕНТА: НОМЕР АБОНЕНТА
	if (document.getElementById('out_knnom').checked==true) request=request+"&out_knnom=1"; else request=request+"&out_knnom=0";
	request = request + "&out_knnom_col="+document.getElementById('out_knnom_col').value;
	request = request + "&out_knnom_width="+document.getElementById('out_knnom_width').value;
	// ДАННЫЕ АБОНЕНТА: ФИО АБОНЕНТА
	if (document.getElementById('out_fio').checked==true) request=request+"&out_fio=1"; else request=request+"&out_fio=0";
	request = request + "&out_fio_col="+document.getElementById('out_fio_col').value;
	request = request + "&out_fio_width="+document.getElementById('out_fio_width').value;
	// ДАННЫЕ АБОНЕНТА: АДРЕС АБОНЕНТА
	if (document.getElementById('out_address').checked==true) request=request+"&out_address=1"; else request=request+"&out_address=0";
	request = request + "&out_address_col="+document.getElementById('out_address_col').value;
	request = request + "&out_address_width="+document.getElementById('out_address_width').value;
	// ДАННЫЕ АБОНЕНТА: ТЕЛЕФОН
	if (document.getElementById('out_tel').checked==true) request=request+"&out_tel=1"; else request=request+"&out_tel=0";
	request = request + "&out_tel_col="+document.getElementById('out_tel_col').value;
	request = request + "&out_tel_width="+document.getElementById('out_tel_width').value;
	// ДАННЫЕ АБОНЕНТА: ТЕЛЕФОН МОБИЛЬНЫЙ
    if (document.getElementById('out_mobtel').checked==true) request=request+"&out_mobtel=1"; else request=request+"&out_mobtel=0";
    request = request + "&out_mobtel_col="+document.getElementById('out_mobtel_col').value;
    request = request + "&out_mobtel_width="+document.getElementById('out_mobtel_width').value;
    // ДАННЫЕ АБОНЕНТА: ДАТА ДОГОВОРА
    if (document.getElementById('out_data_dog').checked==true) request=request+"&out_data_dog=1"; else request=request+"&out_data_dog=0";
    request = request + "&out_data_dog_col="+document.getElementById('out_data_dog_col').value;
    request = request + "&out_data_dog_width="+document.getElementById('out_data_dog_width').value;
    // ДАННЫЕ АБОНЕНТА: Буквенная ДопИнформация
	if (document.getElementById('out_nomr').checked==true) request=request+"&out_nomr=1"; else request=request+"&out_nomr=0";
	request = request + "&out_nomr_col="+document.getElementById('out_nomr_col').value;
	request = request + "&out_nomr_width="+document.getElementById('out_nomr_width').value;
    // ДАННЫЕ АБОНЕНТА: Пломба вв.устр.1
    if (document.getElementById('out_plmbvu1').checked==true) request=request+"&out_plmbvu1=1"; else request=request+"&out_plmbvu1=0";
    request = request + "&out_plmbvu1_col="+document.getElementById('out_plmbvu1_col').value;
    request = request + "&out_plmbvu1_width="+document.getElementById('out_plmbvu1_width').value;
    // ДАННЫЕ АБОНЕНТА: Пломба вв.устр.2
    if (document.getElementById('out_plmbvu2').checked==true) request=request+"&out_plmbvu2=1"; else request=request+"&out_plmbvu2=0";
    request = request + "&out_plmbvu2_col="+document.getElementById('out_plmbvu2_col').value;
    request = request + "&out_plmbvu2_width="+document.getElementById('out_plmbvu2_width').value;
    // ДАННЫЕ АБОНЕНТА: Пломба Ш0
    if (document.getElementById('out_plmbs0').checked==true) request=request+"&out_plmbs0=1"; else request=request+"&out_plmbs0=0";
    request = request + "&out_plmbs0_col="+document.getElementById('out_plmbs0_col').value;
    request = request + "&out_plmbs0_width="+document.getElementById('out_plmbs0_width').value;

    // **************************** ДАННЫЕ ПО ДОМУ *****************************************//
    // ДАННЫЕ ПО ДОМУ: Регион
    if (document.getElementById('out_region').checked==true) request=request+"&out_region=1"; else request=request+"&out_region=0";
    request = request + "&out_region_col="+document.getElementById('out_region_col').value;
    request = request + "&out_region_width="+document.getElementById('out_region_width').value;
    // ДАННЫЕ ПО ДОМУ: КТП
    if (document.getElementById('out_ktp').checked==true) request=request+"&out_ktp=1"; else request=request+"&out_ktp=0";
    request = request + "&out_ktp_col="+document.getElementById('out_ktp_col').value;
    request = request + "&out_ktp_width="+document.getElementById('out_ktp_width').value;
    // ДАННЫЕ ПО ДОМУ: ВЛ
    if (document.getElementById('out_vl').checked==true) request=request+"&out_vl=1"; else request=request+"&out_vl=0";
    request = request + "&out_vl_col="+document.getElementById('out_vl_col').value;
    request = request + "&out_vl_width="+document.getElementById('out_vl_width').value;
     // ДАННЫЕ ПО ДОМУ: ОПОРА
    if (document.getElementById('out_opora').checked==true) request=request+"&out_opora=1"; else request=request+"&out_opora=0";
    request = request + "&out_opora_col="+document.getElementById('out_opora_col').value;
    request = request + "&out_opora_width="+document.getElementById('out_opora_width').value;

    // **************************************  ТАРИФ  **************************** //
    // ТАРИФ
    if (document.getElementById('out_tarif').checked==true) request=request+"&out_tarif=1"; else request=request+"&out_tarif=0";
    request = request + "&out_tarif_col="+document.getElementById('out_tarif_col').value;
    request = request + "&out_tarif_width="+document.getElementById('out_tarif_width').value;
    // СОСТАВ СЕМЬИ
    if (document.getElementById('out_semya').checked==true) request=request+"&out_semya=1"; else request=request+"&out_semya=0";
    request = request + "&out_semya_col="+document.getElementById('out_semya_col').value;
    request = request + "&out_semya_width="+document.getElementById('out_semya_width').value;
    // СОСТАВ СЕМЬИ: КОЛ-ВО ЛЬГОТНИКОВ
    if (document.getElementById('out_semlg').checked==true) request=request+"&out_semlg=1"; else request=request+"&out_semlg=0";
    request = request + "&out_semlg_col="+document.getElementById('out_semlg_col').value;
    request = request + "&out_semlg_width="+document.getElementById('out_semlg_width').value;

    // ************************************      ПОСЛ. КВИТАНЦИЯ      **************************** //
    // ПОСЛ.ОПЛАЧЕННОЕ ПОКАЗАНИЕ КВт
    if (document.getElementById('out_last_kvt').checked==true) request=request+"&out_last_kvt=1"; else request=request+"&out_last_kvt=0";
    request = request + "&out_last_kvt_col="+document.getElementById('out_last_kvt_col').value;
    request = request + "&out_last_kvt_width="+document.getElementById('out_last_kvt_width').value;
    // ПОСЛ.ОПЛАЧЕННОЕ: ДАТА ОПЛАТЫ
    if (document.getElementById('out_last_date').checked==true) request=request+"&out_last_date=1"; else request=request+"&out_last_date=0";
    request = request + "&out_last_date_col="+document.getElementById('out_last_date_col').value;
    request = request + "&out_last_date_width="+document.getElementById('out_last_date_width').value;
    // ПОСЛ.ОПЛАЧЕННОЕ: ПОКАЗАНИЕ АБОНЕНТА
    if (document.getElementById('out_last_pokkv').checked==true) request=request+"&out_last_pokkv=1"; else request=request+"&out_last_pokkv=0";
    request = request + "&out_last_pokkv_col="+document.getElementById('out_last_pokkv_col').value;
    request = request + "&out_last_pokkv_width="+document.getElementById('out_last_pokkv_width').value;
    // СРЕД.МЕС. ПОТРЕБЛЕНИЕ ЗА ПЕРИОД КВт.
    if (document.getElementById('out_avg_mon_kvt').checked==true) request=request+"&out_avg_mon_kvt=1"; else request=request+"&out_avg_mon_kvt=0";
    request = request + "&out_avg_mon_kvt_col="+document.getElementById('out_avg_mon_kvt_col').value;
    request = request + "&out_avg_mon_kvt_width="+document.getElementById('out_avg_mon_kvt_width').value;
    // ПОТРЕБЛЕНИЕ: оплаченные КВт. за период, указанный в "ПЕРИОД. ОПЛАТА"
	if (document.getElementById('out_period_kvts').checked==true) request=request+"&out_period_kvts=1"; else request=request+"&out_period_kvts=0";
	request = request + "&out_period_kvts_col="+document.getElementById('out_period_kvts_col').value;
	request = request + "&out_period_kvts_width="+document.getElementById('out_period_kvts_width').value;
    // ПОТРЕБЛЕНИЕ: оплаченная сумма в руб. за период, указанный в "ПЕРИОД. ОПЛАТА"
	if (document.getElementById('out_period_sum').checked==true) request=request+"&out_period_sum=1"; else request=request+"&out_period_sum=0";
	request = request + "&out_period_sum_col="+document.getElementById('out_period_sum_col').value;
	request = request + "&out_period_sum_width="+document.getElementById('out_period_sum_width').value;

    // *******************    ПОСЛЕДНИЙ ОБХОД  *******************************************//
    // ПОСЛ.ОБХОД.: ДАТА
    if (document.getElementById('out_last_obh_date').checked==true) request=request+"&out_last_obh_date=1"; else request=request+"&out_last_obh_date=0";
    request = request + "&out_last_obh_date_col="+document.getElementById('out_last_obh_date_col').value;
    request = request + "&out_last_obh_date_width="+document.getElementById('out_last_obh_date_width').value;
    // ПОСЛ.ОБХОД.: ПОКАЗАНИЕ
    if (document.getElementById('out_last_obh_pok').checked==true) request=request+"&out_last_obh_pok=1"; else request=request+"&out_last_obh_pok=0";
    request = request + "&out_last_obh_pok_col="+document.getElementById('out_last_obh_pok_col').value;
    request = request + "&out_last_obh_pok_width="+document.getElementById('out_last_obh_pok_width').value;
    // ОБХОДЫ: СРЕД.МЕС.ПОТРЕБЛЕНИЕ ЗА ПЕРИОД
    if (document.getElementById('out_obh_avg_mon').checked==true) request=request+"&out_obh_avg_mon=1"; else request=request+"&out_obh_avg_mon=0";
    request = request + "&out_obh_avg_mon_col="+document.getElementById('out_obh_avg_mon_col').value;
    request = request + "&out_obh_avg_mon_width="+document.getElementById('out_obh_avg_mon_width').value;

    // *************************************   СЧЕТЧИК     **************************** //
    // СЧЕТЧИК: КОД
    if (document.getElementById('out_sc_kod').checked==true) request=request+"&out_sc_kod=1"; else request=request+"&out_sc_kod=0";
    request = request + "&out_sc_kod_col="+document.getElementById('out_sc_kod_col').value;
    request = request + "&out_sc_kod_width="+document.getElementById('out_sc_kod_width').value;
    // СЧЕТЧИК: ТИП
    if (document.getElementById('out_sc_tip').checked==true) request=request+"&out_sc_tip=1"; else request=request+"&out_sc_tip=0";
    request = request + "&out_sc_tip_col="+document.getElementById('out_sc_tip_col').value;
    request = request + "&out_sc_tip_width="+document.getElementById('out_sc_tip_width').value;
    // СЧЕТЧИК: ГОД ВЫПУСКА
    if (document.getElementById('out_sc_gv').checked==true) request=request+"&out_sc_gv=1"; else request=request+"&out_sc_gv=0";
    request = request + "&out_sc_gv_col="+document.getElementById('out_sc_gv_col').value;
    request = request + "&out_sc_gv_width="+document.getElementById('out_sc_gv_width').value;
    // СЧЕТЧИК: ГОД ПОВЕРКИ
     if (document.getElementById('out_sc_gpr').checked==true) request=request+"&out_sc_gpr=1"; else request=request+"&out_sc_gpr=0";
    request = request + "&out_sc_gpr_col="+document.getElementById('out_sc_gpr_col').value;
    request = request + "&out_sc_gpr_width="+document.getElementById('out_sc_gpr_width').value;
    // СЧЕТЧИК: ДАТА УСТАНОВКИ
     if (document.getElementById('out_sc_dateust').checked==true) request=request+"&out_sc_dateust=1"; else request=request+"&out_sc_dateust=0";
    request = request + "&out_sc_dateust_col="+document.getElementById('out_sc_dateust_col').value;
    request = request + "&out_sc_dateust_width="+document.getElementById('out_sc_dateust_width').value;
    // СЧЕТЧИК: ДАТА СНЯТИЯ
     if (document.getElementById('out_sc_datesn').checked==true) request=request+"&out_sc_datesn=1"; else request=request+"&out_sc_datesn=0";
    request = request + "&out_sc_datesn_col="+document.getElementById('out_sc_datesn_col').value;
    request = request + "&out_sc_datesn_width="+document.getElementById('out_sc_datesn_width').value;
    // СЧЕТЧИК: ПОКАЗАНИЕ УСТАНОВКИ
     if (document.getElementById('out_sc_pokusc').checked==true) request=request+"&out_sc_pokusc=1"; else request=request+"&out_sc_pokusc=0";
    request = request + "&out_sc_pokusc_col="+document.getElementById('out_sc_pokusc_col').value;
    request = request + "&out_sc_pokusc_width="+document.getElementById('out_sc_pokusc_width').value;
    // СЧЕТЧИК: ЗАВОДСКОЙ НОМЕР
     if (document.getElementById('out_sc_zn').checked==true) request=request+"&out_sc_zn=1"; else request=request+"&out_sc_zn=0";
    request = request + "&out_sc_zn_col="+document.getElementById('out_sc_zn_col').value;
    request = request + "&out_sc_zn_width="+document.getElementById('out_sc_zn_width').value;
    // СЧЕТЧИК: ПРИНАДЛЕЖНОСТЬ
     if (document.getElementById('out_sc_ps').checked==true) request=request+"&out_sc_ps=1"; else request=request+"&out_sc_ps=0";
    request = request + "&out_sc_ps_col="+document.getElementById('out_sc_ps_col').value;
    request = request + "&out_sc_ps_width="+document.getElementById('out_sc_ps_width').value;
    // СЧЕТЧИК: одно/трёх фазный
     if (document.getElementById('out_sc_1_3').checked==true) request=request+"&out_sc_1_3=1"; else request=request+"&out_sc_1_3=0";
    request = request + "&out_sc_1_3_col="+document.getElementById('out_sc_1_3_col').value;
    request = request + "&out_sc_1_3_width="+document.getElementById('out_sc_1_3_width').value;
    // СЧЕТЧИК: НОМЕР ПЛОМБИРА
     if (document.getElementById('out_sc_plmb').checked==true) request=request+"&out_sc_plmb=1"; else request=request+"&out_sc_plmb=0";
    request = request + "&out_sc_plmb_col="+document.getElementById('out_sc_plmb_col').value;
    request = request + "&out_sc_plmb_width="+document.getElementById('out_sc_plmb_width').value;

	//dati dlya filtracii v vedomosti dlya proverki mobilnogo kontrolera:
	request = request + "&f_c_dateb_contr_check="+document.getElementById('f_c_dateb_contr_check').value;
	request = request + "&f_c_datee_contr_check="+document.getElementById('f_c_datee_contr_check').value;
	//выборка списка контролёров:
	//Контролёр для Мобильного приложения
    request = request + '&persona2=';
    var spisok = document.getElementById('persona_obhod2');
    for (var i=0; i < spisok.options.length; i++){
      if (spisok.options[i].selected == true) request = request + spisok.options[i].value;
    }


    //request = request + "&persona2="+document.getElementById('persona_obhod2').value;
	console.dir('req122');

	document.getElementById('report_ready_f_constructor').innerHTML = "";
	document.getElementById('report_ready_f_constructor').innerHTML = mess_several_time + '<br><img src="/images/updateLoader.gif" border="0"><br>';
	loadContent(request,"report_ready_f_constructor");
}

/* ********************************************************************************************* */
/*          Отчеты (формы, ведомости, реестры): Вызов отчета в Брестэнерго                       */
/* ********************************************************************************************* */
function sql2txt_exec(){
    document.getElementById('report_ready_sql2txt').style.display = 'block';
    document.getElementById('report_ready_sql2txt').innerHTML = '<br><img src="/images/updateLoader.gif" border="0"><br>';
    var waitAction = '';
    var request = '';
    request = "sql2txt.php?act=send";
    loadContent(request,"report_ready_sql2txt");
}

/* ********************************************************************************************* */
/*          Отчеты (формы, ведомости, реестры): Вызов отчета по привязке                         */
/* ********************************************************************************************* */
function sql2privyazka_exec(){
    document.getElementById('report_ready_sql2privyazka').style.display = 'block';
    document.getElementById('report_ready_sql2privyazka').innerHTML = '<br><img src="/images/updateLoader.gif" border="0"><br>';
    var waitAction = '';
    var request = '';
    request = "sql2privyazka.php?act=send";
    loadContent(request,"report_ready_sql2privyazka");
}

/* ********************************************************************************************** */
/*        Загрузка данных о клиенте при вводе квитанций									          */
/* ********************************************************************************************** */
function kvit_load_client_data(){
	request = '';
	waitAction = '';
	request = "include/kvit_function.php?a=get_client_data&kn="+document.getElementById('kvit_kn').value+"&nom="+document.getElementById('kvit_nom').value+"&uch="+document.getElementById('kvit_uch').value+"&kvit_type="+document.getElementById('kvit_vid_plat').value;
	waitAction = 'kvit_load_client_data_neyasn_kvit';
	loadContent(request,"kvit_client_data");
}

/* ********************************************************************************************** */
/*        Загрузка данных о клиенте при выяснении неясной квитанции в ЭЭ                          */
/* ********************************************************************************************** */
function find_abonent_data(){
    request = '';
    waitAction = '';
    request = "include/kvit_function.php?a=get_client_data&kn="+document.getElementById('kvit_kn').value+"&nom="+document.getElementById('kvit_nom').value+"&uch="+document.getElementById('kvit_uch').value+"&kvit_type=2";
    loadContent(request,"kvit_client_data");
}

/* ************************************************************************************************************ */
/*         Если пришел ответ что абонента с таким номером нет, то ставим галочку что квитанция НЕЯСНАЯ          */
/* ************************************************************************************************************ */
function kvit_load_client_data_neyasn_kvit(){
	if (document.getElementById('kvit_is_neyasn_2').value == 1) {
		document.getElementById('kvit_is_neyasn').checked = true;
	}
	else{
		document.getElementById('kvit_is_neyasn').checked = false;
	}
}

/* ********************************************************************************************* */
/*          Квитанции: функции проверки                                                 		 */
/* ********************************************************************************************* */
function pachka_reg_year(obj){
	waitAction = '';
	if (window.event.keyCode == 8){
		return 1;
	}
	if (obj.value.length == 4) {
		request = '';
		request = "include/kvit_function.php?a=check_function&step=checkdate_1&day="+document.getElementById('pachka_reg_day').value+"&month="+document.getElementById('pachka_reg_month').value+"&year="+document.getElementById('pachka_reg_year').value;
		waitAction = 'pachka_reg_year';
		loadContent(request,'pachka_reg_year_mess');
	}
}

function pachka_reg_year_funct_2(){
	if (document.getElementById('pachka_reg_year_error').value == '1') {
		document.getElementById('pachka_reg_day').value = '';
		document.getElementById('pachka_reg_day').focus();

	}
	if (document.getElementById('pachka_reg_year_error').value == '0') {
		document.getElementById('pachka_reg_plpr').focus();
		document.getElementById('pachka_reg_plpr').size = 3;
	}
}

function pachka_reg_pl_por(obj){
	waitAction = '';
	request = '';
	request = "include/kvit_function.php?a=check_function&step=check_pl_por="+document.getElementById('pachka_reg_pl_por');
	loadContent(request,'pachka_reg_pl_por_mess');
}

// поиск подходящей пачки в другом РЭСе
function search_pachka_in_other_res(){
    waitAction = '';
    request = '';
    document.getElementById('search_pachka_in_other_res_result').innerHTML = '<img src="/images/updateLoader.gif" border="0">';
    request = "include/kvit_function.php?a=functions&step=search_pachka_in_other_res&";
    var spisok = document.getElementById('res_list');
    for (var i=0; i < spisok.options.length; i++){
        if (spisok.options[i].selected == true) {
            request = request + "&res_list=" +spisok.options[i].value;
            if (spisok.options[i].value != 0){
                document.getElementById("do_move_other_res_submit").disabled=false;
            }
            else{
                document.getElementById("do_move_other_res_submit").disabled=true;
            }
        }
    }
    request = request + '&kvit_id=' + document.getElementById('kvit_id').value;
    loadContent(request,'search_pachka_in_other_res_result');
}

/* ********************************************************************************************* */
/*          ОБЩЕЕ: подгрузка РЭСов для выбраннонго ФЭСА в форме аутентификации                   */
/* ********************************************************************************************* */
function getResforFes(){
    waitAction = '';
    var request = '';
    request = "auth.php?act=gen_func&step=getResforFes&fes=";
    var i=0;
    var spisok = document.getElementById('fess');
    for (var i=0; i < spisok.options.length; i++){
      if (spisok.options[i].selected == true)
        fes = spisok.options[i].value;
    }
    request = request + fes;
    loadContent(request,'ress_select_form');

    request = "auth.php?act=gen_func&step=getUsersforRes&res=first&fes=" + fes;
    loadContent(request,'users_select_form');
}

/* ********************************************************************************************* */
/*          ОБЩЕЕ: подгрузка пользователей для выбраннонго РЭСА в форме аутентификации           */
/* ********************************************************************************************* */
function getUsersforRes(){
    waitAction = '';
    var request = '';
    request = "auth.php?act=gen_func&step=getUsersforRes&res=";
    var i=0;
    var spisok = document.getElementById('ress');
    for (var i=0; i < spisok.options.length; i++){
      if (spisok.options[i].selected == true)
        request = request + spisok.options[i].value;
    }
    loadContent(request,'users_select_form');
}

/* ********************************************************************************************* */
/*          ОБЩЕЕ: подгрузка cписка улиц для выбранного населенного типа                */
/* ********************************************************************************************* */
function getStreetsforNP(){
    waitAction = '';
    var request = '';
    request = "include/otchet_function.php?a=gen_func&step=getStreetsforNP&np=";
    var i=0;
    var spisok = document.getElementById('constructor_np');
    for (var i=0; i < spisok.options.length; i++){
      if (spisok.options[i].selected == true)
          request = request + spisok.options[i].value+',';
      }
    document.getElementById('div_streets').innerHTML = 'Загрузка данных. Подождите<br><img src="/images/updateLoader.gif" border="0"><br>';
    loadContent(request,'div_streets');
    setTimeout("getDomsforStreet()", 1000);
}

/* ********************************************************************************************* */
/*          ОБЩЕЕ: подгрузка cписка домов для выбранной улицы                              		 */
/* ********************************************************************************************* */
function getDomsforStreet(){
	waitAction = '';
	var request = '';
	request = "include/otchet_function.php?a=gen_func&step=getDomsforStreet&streets=";
	var i=0;
	var spisok = document.getElementById('streets');
	for (var i=0; i < spisok.options.length; i++){
      if (spisok.options[i].selected == true)
	  	request = request + spisok.options[i].value + ',';
  	}
    document.getElementById('div_doms').innerHTML = 'Загрузка данных. Подождите<br><img src="/images/updateLoader.gif" border="0"><br>';
	loadContent(request,'div_doms');
}

/* ********************************************************************************************* */
/*          ОБЩЕЕ: Отправляется запрос на изготовление удостоверения для персонала               */
/* ********************************************************************************************* */
function do_udost_for_personal(id){
    waitAction = '';
    var request = '';
    request = "include/otchet_function.php?a=gen_func&step=do_udost_for_personal&id="+id;
    request = request + "&fio="+document.getElementById('fio').value;
    request = request + "&tbn="+document.getElementById('tbn').value;
    document.getElementById('div_personal').innerHTML = 'Загрузка данных. Подождите<br><img src="/images/updateLoader.gif" border="0"><br>';
    loadContent(request,'div_personal');
}
