function isNormalText(evt)      {
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		var ListSafeKey  = [37, 39, 36, 35, 46]; // left arrow - right arrow  - home - end  - delete
		var ListSafeKey2 = [97, 99, 118, 120]; // ctrl+a/ ctrl+v/ ctrl+c // ctrl+x
         if ( (charCode < 32)  ||
			  (ListSafeKey.indexOf(charCode) !== -1 ) ||
			  ( (evt.altKey || evt.ctrlKey || evt.shiftKey) && (ListSafeKey2.indexOf(charCode) !== -1 )) ||
			  (charCode >= 48 && charCode <= 57) ||
			  (charCode >= 65 && charCode <= 90) || 
			  (charCode >= 97 && charCode <= 122) 
			) {
            return true;
		 }
         return false;
}
function isNormalNumber(evt)      {
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		var ListSafeKey  = [37, 39, 36, 35, 46]; // left arrow - right arrow  - home - end  - delete
		var ListSafeKey2 = [97, 99, 118, 120]; // ctrl+a/ ctrl+v/ ctrl+c // ctrl+x
         if ( (charCode < 32)  ||
			  (ListSafeKey.indexOf(charCode) !== -1 ) ||
			  ( (evt.altKey || evt.ctrlKey || evt.shiftKey) && (ListSafeKey2.indexOf(charCode) !== -1 )) ||
			  (charCode >= 48 && charCode <= 57) 
			) {
            return true;
		 }
         return false;
}

function validateNormalNumber(evt) {
  var theEvent = evt || window.event;
  var key = theEvent.keyCode || theEvent.which;
  key = String.fromCharCode( key );
  var regex = /[0-9]|\./;
  if( !regex.test(key) ) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
  }
}
function checkEmail(inputvalue){	
    var pattern=/^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;
    if(pattern.test(inputvalue)){         
		return (true);
    }else{   
		return (false);
    }
}

function ValidURL(str) {
  var regex = /(http|https):\/\/(\w+:{0,1}\w*)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%!\-\/]))?/;
  if(!regex.test(str)) {
    return false;
  } else {
    return true;
  }
}
function isEmpty(str) {    return (!str || 0 === str.length);	}

function ValidateDate(inputtxt){	
	var date = inputtxt.trim();
	if ( isEmpty(date) )  return true; 
	if (inputtxt.match(/^\d{4}\/\d{2}\/\d{2}$/))    {return true;  }   else     {return false;}  
}

function validatePasswordHigh(text_password){
  //#must contain 8 characters, 1 uppercase, 1 lowercase and 1 number
	if (inputtxt.match(/^(?=^.{8,}$)((?=.*[A-Za-z0-9])(?=.*[A-Z])(?=.*[a-z]))^.*$/))    {return true;  }   else     {return false;}  
}

function validateTime(inputTime) {
        var isValid = /^([0-1]?[0-9]|2[0-4]):([0-5][0-9])(:[0-5][0-9])?$/.test(inputTime);
        return isValid;
}


function ValidNumber(inputtxt)			{      if (inputtxt.match(/^[0-9\.]+$/))    {return true;  }   else     {return false;} }
function ValidNumberPhone(inputtxt)		{      if (inputtxt.match(/^[0-9\:\_\-\+]+$/))    {return true;  }   else     {return false;} }
 
function ValidEnglish(inputtxt)			{      if (inputtxt.match(/^[A-Za-z]+$/))     		{return true;  }   else     {return false;} }
function ValidEnglish_Number2(inputtxt)	{      if (inputtxt.match(/^[A-Za-z0-9\.\@\_\-]+$/))    {return true;  }   else     {return false;} }
function ValidEnglish_Number(inputtxt)	{      if (inputtxt.match(/^[A-Za-z0-9\_]+$/))     	{return true;  }   else     {return false;} }
function ValidFarsi_Number(inputtxt)	{      if (inputtxt.match(/^[0-9أ-یيء]+$/))     	{return true;  }   else     {return false;} }
function ShowHidden(idcontent) {    
	if (document.getElementById(idcontent).style.display == 'none') {  		
		document.getElementById(idcontent).style.display = 'block'; 	
	} else {			
		document.getElementById(idcontent).style.display = 'none';	
	}
}
function FormatNumberBy3(num, decpoint, sep) {
  // check for missing parameters and use defaults if so
  if (arguments.length == 2) {
    sep = ",";
  }
  if (arguments.length == 1) {
    sep = ",";
    decpoint = ".";
  }
  // need a string for operations
  num = num.toString();
  // separate the whole number and the fraction if possible
  a = num.split(decpoint);
  x = a[0]; // decimal
  y = a[1]; // fraction
  z = "";


  if (typeof(x) != "undefined") {
    // reverse the digits. regexp works from left to right.
    for (i=x.length-1;i>=0;i--)
      z += x.charAt(i);
    // add seperators. but undo the trailing one, if there
    z = z.replace(/(\d{3})/g, "$1" + sep);
    if (z.slice(-sep.length) == sep)
      z = z.slice(0, -sep.length);
    x = "";
    // reverse again to get back the number
    for (i=z.length-1;i>=0;i--)
      x += z.charAt(i);
    // add the fraction back in, if it was there
    if (typeof(y) != "undefined" && y.length > 0)
      x += decpoint + y;
  }
  return x;
}

function convertDigitIn(enDigit){ // PERSIAN, ARABIC, URDO
    var newValue="";
    for (var i=0;i<enDigit.length;i++)    {
        var ch=enDigit.charCodeAt(i);
        if (ch>=48 && ch<=57)        {
            // european digit range
            var newChar=ch+1584;
            newValue=newValue+String.fromCharCode(newChar);
        }
        else
            newValue=newValue+String.fromCharCode(ch);
    }
    return newValue;
}
//بدست آوردن فاصله بین دو ساعت
function DifferntTime(start, end) {
    start = start.split(":");
    end = end.split(":");
    var startDate = new Date(0, 0, 0, start[0], start[1], 0);
    var endDate = new Date(0, 0, 0, end[0], end[1], 0);
    var diff = endDate.getTime() - startDate.getTime();
    var hours = Math.floor(diff / 1000 / 60 / 60);
    diff -= hours * 1000 * 60 * 60;
    var minutes = Math.floor(diff / 1000 / 60);

    // If using time pickers with 24 hours format, add the below line get exact hours
    if (hours < 0)
       hours = hours + 24;

    return (hours <= 9 ? "0" : "") + hours + ":" + (minutes <= 9 ? "0" : "") + minutes;
}

function DeleteRecord(id,page) {
	if(confirm('آیا نسبت به حذف اطلاعات اطمینان دارید؟')) {
		
	/*    $("<form action='' method='post'>	<input type='hidden' name='id_delete' value='"+id+"'><input type='hidden' name='page' value='"+page+"'></form>").appendTo('body').submit();	
		return
		*/
	  var newForm = $('<form>', {'method': 'post'})
		.append($('<input>', {'name': 'id_delete','value': id,'type': 'hidden'}))
		.append($('<input>', {'name': 'page','value': page ,'type': 'hidden'}))	;
		newForm.appendTo(document.body)	
		$(newForm).submit();

	}
}


function DeleteRecord2(id,page,id_main) {
	if(confirm('آیا نسبت به حذف اطلاعات اطمینان دارید؟')) {
	  var newForm = $('<form>', {'method': 'post'})
		.append($('<input>', {'name': 'id_main','value': id_main ,'type': 'hidden'}))	
		.append($('<input>', {'name': 'id_delete','value': id,'type': 'hidden'}))
		.append($('<input>', {'name': 'page','value': page ,'type': 'hidden'}))	;
		newForm.appendTo(document.body)	
		$(newForm).submit();

	}
}

function itpro(Number)       {
	Number+= '';
	Number= Number.replace(',', ''); Number= Number.replace(',', ''); Number= Number.replace(',', '');
	Number= Number.replace(',', ''); Number= Number.replace(',', ''); Number= Number.replace(',', '');
	x = Number.split('.');
	y = x[0];
	z= x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(y))
		y= y.replace(rgx, '$1' + ',' + '$2');
	return y+ z;
}



function DeleteRecordModal(page, id, Msg='') {
	//بالای صفحه ای که این تابع خوانده می شود باید سطر زیر باشد
	//<div  class="modal fade"  id="DeleteRec"  tabindex="-1"  aria-hidden="true"></div>
	if (Msg=='') Msg = "آیا نسبت به حذف این اطلاعات اطمینان دارید؟";
	var content = '';
	content = 
	"<div class='modal-dialog modal-dialog-centered'>"+
		"<div class='modal-content' style='border-color:#ffa101;border-block-width: thin;'>"+
	  		"<div class='modal-header' style='border-bottom-color: #ffa101;border-block-width: thin;'>"+
				"<h5 class='modal-title' id='SharePopupDelete' style='font-size: 20px;  margin-bottom: 10px;'>"+
				"<i class='fa fa-trash' style='color:red;'> </i> حذف صفحه</h5>"+
				"<span style='cursor:pointer;'>"+
				"<i class='fas fa-times' data-dismiss='modal'  aria-label='Close'></i>"+
				"</span>"+
			"</div>"+
	  		"<div class='modal-body share-icon-modal' >"+
				"<div class='row'>"+
					"<p style='margin:auto;padding:20px;'>"+Msg+"</p>"+
					"<div class='col-xs-6 col-sm-6'><div class='btn btn-block btn-secondary' data-dismiss='modal' style='cursor:pointer;'>انصراف</div></div>"+
					"<div class='col-xs-6 col-sm-6'><div class='btn btn-block btn-danger' onclick='DeleteRecordModalEnjine("+id+", "+page+");' style='cursor:pointer;'>حذف اطلاعات</div></div>"+
				"</div>"+
			"</div>"+
		"</div>"+
	"</div>	";
	$("#DeleteRec").append(content);	
	$("#DeleteRec").modal('show');	
}
function DeleteRecordModalEnjine(idrec,page) {
	$("#DeleteRec").modal('hide');	
  var newForm = $('<form>', {'method': 'post'})
		.append($('<input>', {'name': 'deleted','value': idrec,'type': 'hidden'}))
		.append($('<input>', {'name': 'page','value': page ,'type': 'hidden'}))	;
		newForm.appendTo(document.body)	
	$(newForm).submit();

}
function formatBytes(bytes, decimals = 2) {
    if (!+bytes) return '0 Bytes'

    const k = 1024
    const dm = decimals < 0 ? 0 : decimals
    const sizes = ['Bytes', 'Kb', 'Mb', 'Gb', 'Tb', 'Pb', 'Eb', 'Zb', 'Yb']

    const i = Math.floor(Math.log(bytes) / Math.log(k))

    return `${parseFloat((bytes / Math.pow(k, i)).toFixed(dm))} ${sizes[i]}`
}

function AnyToNumber(input) {
    if (input===undefined || input==null || input==''){ return 0; }
	input = input.replaceAll(',','');
	input = Number(input);
	input = parseInt(input);
	if (input==NaN) return 0;
	return input;
}

function DelRecordModal(page, id, Msg='', Tetr='', Menu2='',Icon='') {
	//بالای صفحه ای که این تابع خوانده می شود باید سطر زیر باشد
	//<div  class="modal fade"  id="DelWindow"  tabindex="-1"  aria-hidden="true"></div>
	if (Msg=='') {var Msg = "آیا نسبت به حذف این اطلاعات اطمینان دارید؟";}
	if (Tetr=='') Tetr='حذف رکورد';
	if (Menu2=='') Menu2='حذف';
	if (Icon=='') Icon='fa fa-trash';
	var content = '';
	content = 
	"<div class='modal-dialog modal-dialog-centered'>"+
		"<div class='modal-content' style='border-color:#ffa101;border-block-width: thin;'>"+
	  		"<div class='modal-header' style='border-bottom-color: #ffa101;border-block-width: thin;'>"+
				"<h5 class='modal-title' id='SharePopupDelete' style='font-size: 20px;  margin-bottom: 10px;'>"+
				"<i class='"+Icon+"' > </i> "+Tetr+" </h5>"+
				"<span style='cursor:pointer;'>"+
				"<i class='fas fa-times' data-dismiss='modal'  aria-label='Close'></i>"+
				"</span>"+
			"</div>"+
	  		"<div class='modal-body share-icon-modal' >"+
				"<div class='row'>"+
					"<p style='margin:auto;padding:20px;'>"+Msg+"</p>"+
					"<div class='col-xs-6 col-sm-6'><div class='btn btn-block btn-secondary' data-dismiss='modal' style='cursor:pointer;'>انصراف</div></div>"+
					"<div class='col-xs-6 col-sm-6'><div class='btn btn-block btn-danger' onclick='RecordModalEnjine("+id+", "+page+");' style='cursor:pointer;'>"+Menu2+" </div></div>"+
				"</div>"+
			"</div>"+
		"</div>"+
	"</div>	";
	$("#DelWindow").append(content);	
	$("#DelWindow").modal('show');	
}
function RecordModalEnjine(idrec,page) {
	$("#DelWindow").modal('hide');	
  var newForm = $('<form>', {'method': 'post'})
		.append($('<input>', {'name': 'DelRec','value': idrec,'type': 'hidden'}))
		.append($('<input>', {'name': 'page','value': page ,'type': 'hidden'}))	;
		newForm.appendTo(document.body)	
	$(newForm).submit();

}
//--------------------------------------------
function FormatNumberBy3(num, decpoint, sep) {
  // check for missing parameters and use defaults if so
  if (arguments.length == 2) {
    sep = ",";
  }
  if (arguments.length == 1) {
    sep = ",";
    decpoint = ".";
  }
  // need a string for operations
  num = num.toString();
  // separate the whole number and the fraction if possible
  a = num.split(decpoint);
  x = a[0]; // decimal
  y = a[1]; // fraction
  z = "";


  if (typeof(x) != "undefined") {
    // reverse the digits. regexp works from left to right.
    for (i=x.length-1;i>=0;i--)
      z += x.charAt(i);
    // add seperators. but undo the trailing one, if there
    z = z.replace(/(\d{3})/g, "$1" + sep);
    if (z.slice(-sep.length) == sep)
      z = z.slice(0, -sep.length);
    x = "";
    // reverse again to get back the number
    for (i=z.length-1;i>=0;i--)
      x += z.charAt(i);
    // add the fraction back in, if it was there
    if (typeof(y) != "undefined" && y.length > 0)
      x += decpoint + y;
  }
  return x;
}
//--------------------------
function printContent(el){
	var restorepage = $('body').html();
	var printcontent = $('#' + el).clone();
	$('body').empty().html(printcontent);
	window.print();
	$('body').html(restorepage);
}
//--------------------------
function ReadComment(StartRec,idnews) {
	IdContinue = 'ContinueComment';
	parameter = 'startrec=' + StartRec + '&idnews='+idnews;
	
	var xmlhttp=false;
	try {
		xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
	} catch (e) {
		try {
			xmlhttp = new  ActiveXObject('Microsoft.XMLHTTP');
		} catch (E) {
			xmlhttp = false;
		}
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
			xmlhttp = new XMLHttpRequest();
	}
	xmlhttp.open("POST","/ajaxreadcomment.php",true);  
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");  
	xmlhttp.send(parameter);
	xmlhttp.onreadystatechange=function() {
	if (xmlhttp.readyState==4) {
		var content = xmlhttp.responseText;
		if( content ){
			document.getElementById(IdContinue).outerHTML= content ;
		}
	}
	}
	return false;
}
function RegisterAccount(idtype) {
	var reg_type = document.getElementById("reg_type").value;  
	if (reg_type==0) {
		var Register = document.getElementById("RegisterMobile");
		var final_link = '/login-mobile';
	}else{
		var Register = document.getElementById("RegisterEmail");
		var final_link = '/login-email';
	}
	RegisterValue = Register.value;
	if (RegisterValue=='') { Register.focus(); return; 	}
	parameter = 'idtype='+idtype+'&reg_type='+reg_type+'&reg_value=' + RegisterValue;
	var ActiveCode =0;
	if (idtype==1) {
		document.getElementById("submit1").style.pointerEvents='none';	
	}
	if (idtype==2) {
		 ActiveCode = document.getElementById("ActiveCode").value;
		 if (ActiveCode=='') {return;}
		 parameter = parameter+'&ActiveCode='+ActiveCode;
		 document.getElementById("submit2").style.pointerEvents='none';	
	}
	if (idtype==3) {
		 ActiveCode = document.getElementById("ActiveCode").value;
		 family 		= document.getElementById("NameFamily").value;
		 password1  = document.getElementById("PassWord1").value;
		 password2  = document.getElementById("PassWord2").value;
		 if ((family=='') || (password1=='') || (password2=='') ) {return;}
		 parameter = parameter+'&ActiveCode='+ActiveCode+'&family='+family+'&password1='+password1+'&password2='+password2;
		 document.getElementById("submit3").style.pointerEvents='none';	
		 
	}
	var xmlhttp=false;
	try {
		xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
	} catch (e) {
		try {
			xmlhttp = new  ActiveXObject('Microsoft.XMLHTTP');
		} catch (E) {
			xmlhttp = false;
		}
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
			xmlhttp = new XMLHttpRequest();
	}
	xmlhttp.open("POST","/ajaxregister.php",true);  
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");  
	xmlhttp.send(parameter);
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4) {
			var content = xmlhttp.responseText;
			if( content ){
//				alert(content);
				document.getElementById("submit1").style.pointerEvents='auto';	
				document.getElementById("submit2").style.pointerEvents='auto';	
				document.getElementById("submit3").style.pointerEvents='auto';	
				var ary=content.split(';;;');
				var Status      = ary[0];
				var NewContent  = ary[1];
				if (Status < 0) 	{ 
					document.getElementById('ShowError1').style.visibility='visible';
					document.getElementById('ShowError1').innerHTML= NewContent ;
				}else { 
					document.getElementById('ShowError1').style.visibility='hidden';
					if (Status==1) {
						document.getElementById('panel1').style.display='none';
						document.getElementById('panel2').style.display='block';
						document.getElementById('tetr2').innerHTML = NewContent;
					}
					if (Status==2) {
						document.getElementById('panel1').style.display='none';
						document.getElementById('panel2').style.display='none';
						document.getElementById('panel3').style.display='block';
					}
					if (Status==3) {
						document.getElementById('panel1').style.display='none';
						document.getElementById('panel2').style.display='none';
						document.getElementById('panel3').style.display='none';
						window.location= '/'; //final_link;
					}
				
				}
			}
		}
	}
	return false;
}
//--------------------------
function LoginAccount() {
	var reg_type = document.getElementById("reg_type").value;  
	if (reg_type==0) {
		var LoginUser = document.getElementById("LoginMobile");
	}else{
		var LoginUser = document.getElementById("LoginEmail");
	}
	LoginPassword = document.getElementById("LoginPassword");
	if (LoginUser.value=='') { LoginUser.focus(); return; 	}
	if (LoginPassword.value=='') { LoginPassword.focus(); return; 	}
	parameter = 'reg_type='+reg_type+'&reg_value=' + LoginUser.value + '&reg_pass='+LoginPassword.value;
	document.getElementById("submit1").style.pointerEvents='none';	

	var xmlhttp=false;
	try {
		xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
	} catch (e) {
		try {
			xmlhttp = new  ActiveXObject('Microsoft.XMLHTTP');
		} catch (E) {
			xmlhttp = false;
		}
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
			xmlhttp = new XMLHttpRequest();
	}
	xmlhttp.open("POST","/ajaxlogin.php",true);  
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");  
	xmlhttp.send(parameter);
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4) {
			var content = xmlhttp.responseText;
			if( content ){
//				alert(content);
				document.getElementById("submit1").style.pointerEvents='auto';	
				var ary=content.split(';;;');
				var Status      = ary[0];
				var NewContent  = ary[1];
				if (Status < 0) 	{ 
					document.getElementById('ShowError1').style.visibility='visible';
					document.getElementById('ShowError1').innerHTML= NewContent ;
				}else { 
					window.location= '/'; //final_link;
				}
			}
		}
	}
	return false;
}
function RecoverAccount(idtype) {
	var reg_type = document.getElementById("reg_type").value;  
	if (reg_type==0) {
		var Register = document.getElementById("RecoverMobile");
		var final_link = '/login-mobile';
	}else{
		var Register = document.getElementById("RecoverEmail");
		var final_link = '/login-email';
	}
	RegisterValue = Register.value;
	if (RegisterValue=='') { Register.focus(); return; 	}
	parameter = 'idtype='+idtype+'&reg_type='+reg_type+'&reg_value=' + RegisterValue;
	var ActiveCode =0;
	if (idtype==1) {
		document.getElementById("submit1").style.pointerEvents='none';	
	}
	if (idtype==2) {
		 ActiveCode = document.getElementById("ActiveCode").value;
		 if (ActiveCode=='') {return;}
		 parameter = parameter+'&ActiveCode='+ActiveCode;
		 document.getElementById("submit2").style.pointerEvents='none';	
	}
	if (idtype==3) {
		 ActiveCode = document.getElementById("ActiveCode").value;
		 password1  = document.getElementById("PassWord1").value;
		 password2  = document.getElementById("PassWord2").value;
		 if ( (password1=='') || (password2=='') ) {return;}
		 parameter = parameter+'&ActiveCode='+ActiveCode+'&password1='+password1+'&password2='+password2;
		 document.getElementById("submit3").style.pointerEvents='none';	
		 
	}
	var xmlhttp=false;
	try {
		xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
	} catch (e) {
		try {
			xmlhttp = new  ActiveXObject('Microsoft.XMLHTTP');
		} catch (E) {
			xmlhttp = false;
		}
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
			xmlhttp = new XMLHttpRequest();
	}
	xmlhttp.open("POST","/ajaxrecover.php",true);  
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");  
	xmlhttp.send(parameter);
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4) {
			var content = xmlhttp.responseText;
			if( content ){
//				alert(content);
				document.getElementById("submit1").style.pointerEvents='auto';	
				document.getElementById("submit2").style.pointerEvents='auto';	
				document.getElementById("submit3").style.pointerEvents='auto';	
				var ary=content.split(';;;');
				var Status      = ary[0];
				var NewContent  = ary[1];
				if (Status < 0) 	{ 
					document.getElementById('ShowError1').style.visibility='visible';
					document.getElementById('ShowError1').innerHTML= NewContent ;
				}else { 
					document.getElementById('ShowError1').style.visibility='hidden';
					if (Status==1) {
						document.getElementById('panel1').style.display='none';
						document.getElementById('panel2').style.display='block';
						document.getElementById('tetr2').innerHTML = NewContent;
					}
					if (Status==2) {
						document.getElementById('panel1').style.display='none';
						document.getElementById('panel2').style.display='none';
						document.getElementById('panel3').style.display='block';
					}
					if (Status==3) {
						document.getElementById('panel1').style.display='none';
						document.getElementById('panel2').style.display='none';
						document.getElementById('panel3').style.display='none';
						window.location= '/'; //final_link;
					}
				
				}
			}
		}
	}
	return false;
}

function CopyToClipBoard2(id_input) {
  var copyText = document.getElementById(id_input);
  copyText.select();
  copyText.setSelectionRange(0, 99999)
  document.execCommand("copy");
}

function CopyToClipBoard(Content) {
	var copyText = document.createElement('input');
	copyText.value = Content;
	document.body.appendChild(copyText);
	copyText.select();
	copyText.setSelectionRange(0, 99999);
	document.execCommand('copy');
	copyText.hidden=true
}

function SetBookmark(idnews,SetBookmark) {
	parameter = 'idnews=' + idnews +'&SetBookmark='+SetBookmark;

	var xmlhttp=false;
	try {
		xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
	} catch (e) {
		try {
			xmlhttp = new  ActiveXObject('Microsoft.XMLHTTP');
		} catch (E) {
			xmlhttp = false;
		}
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
			xmlhttp = new XMLHttpRequest();
	}
	xmlhttp.open("POST","/ajaxbookmark.php",true);  
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");  
	xmlhttp.send(parameter);
	xmlhttp.onreadystatechange=function() {
	if (xmlhttp.readyState==4) {
		var content = xmlhttp.responseText;
		if( content ){
			var ary=content.split(';;;');
			var Status      = ary[0];
			var NewContent  = ary[1];
			if (Status < 0) 	{ 
//				alert(NewContent);	
				ShowAlert(NewContent);
			}else { 
				var BookmarkLink = document.getElementById('BookmarkLink');  
				if (Status==1) {
					BookmarkLink.outerHTML = "<button id='BookmarkLink' class='action-btn action-bookmark' data-favorite='true' title='حذف از علاقه مندی ها' onclick='SetBookmark("+idnews+",0);'><i class='fas fa-bookmark'></i>لیست من</button>";
					
				}else{
					BookmarkLink.outerHTML = "<button id='BookmarkLink' class='action-btn' data-favorite='true' title='افزودن به علاقه مندی ها' onclick='SetBookmark("+idnews+",1);'><i class='fas fa-bookmark'></i>لیست من</button>";
				}
			}
		}
	}
	}
	return false;
}

function SaveFilmLink2() {
	IdContinue = 'SaveFilmLink';
	SaveIdTag  = document.getElementById(IdContinue);
	if (!SaveIdTag) return;
	var idnews2 = SaveIdTag.innerHTML;
	if (idnews2=='') return;
	idnews = idnews2;
	SaveIdTag.innerHTML= '' ;
	parameter = 'idnews='+idnews;
//	alert('idnews='+idnews2 + 'nowtime=' + nowtime + 'endtime='+endtime );

	var xmlhttp=false;
	try {
		xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
	} catch (e) {
		try {
			xmlhttp = new  ActiveXObject('Microsoft.XMLHTTP');
		} catch (E) {
			xmlhttp = false;
		}
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
			xmlhttp = new XMLHttpRequest();
	}
	xmlhttp.open("POST","/ajaxvisit.php",true);  
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");  
	xmlhttp.send(parameter);
	xmlhttp.onreadystatechange=function() {
	if (xmlhttp.readyState==4) {
		var content = xmlhttp.responseText;
		if( content ){
//			alert(content);
			if (content!='ok') {
				alert(content);
			}
		}
	}
	}
	return false;
	
}

function SaveFilmLink() {
	IdContinue = 'SaveFilmLink';
	IdVideo    = 'VideoMain';
	SaveIdTag  = document.getElementById(IdContinue);
	VideoIdTag = document.getElementById(IdVideo);
	if (!SaveIdTag) return;
	if (!VideoIdTag) return;
	var idnews2 = SaveIdTag.innerHTML;
	if (idnews2=='') return;
	idnews = idnews2;
    var nowtime = VideoIdTag.player.currentTime();
    var endtime = VideoIdTag.player.duration();
    if ( nowtime< (endtime/2) ) return;
	SaveIdTag.innerHTML= '' ;
	parameter = 'idnews='+idnews;
//	alert('idnews='+idnews2 + 'nowtime=' + nowtime + 'endtime='+endtime );

	var xmlhttp=false;
	try {
		xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
	} catch (e) {
		try {
			xmlhttp = new  ActiveXObject('Microsoft.XMLHTTP');
		} catch (E) {
			xmlhttp = false;
		}
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
			xmlhttp = new XMLHttpRequest();
	}
	xmlhttp.open("POST","/ajaxvisit.php",true);  
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");  
	xmlhttp.send(parameter);
	xmlhttp.onreadystatechange=function() {
	if (xmlhttp.readyState==4) {
		var content = xmlhttp.responseText;
		if( content ){
//			alert(content);
			if (content!='ok') {
				alert(content);
			}
		}
	}
	}
	return false;
	
}

function ChangeData(idtype) {
	parameter = 'idtype='+idtype;
	
	if (idtype==1) {
		var NameFamily = document.getElementById("NameFamily").value;  
		if ( (NameFamily.length<3) || (NameFamily.length>50) ) {
			document.getElementById("NameFamily").focus();
			document.getElementById('ShowError1').style.display='block';
			document.getElementById('ShowError1').innerHTML= 'نام و نام خانوادگی صحیح نمی باشد' ;
			return;
		}
		document.getElementById('ShowError1').style.display='none';
		parameter = 'idtype='+idtype + '&name='+NameFamily;
	}
	if (idtype==2) {
		var Mobile = document.getElementById("Mobile").value;  
		if ( (Mobile.length<10) || (Mobile.length>15) ) {
			document.getElementById("Mobile").focus();
			document.getElementById('ShowError2').style.display='block';
			document.getElementById('ShowError2').innerHTML= 'شماره موبایل صحیح نمی باشد';
			return;
		}
		var display1 = document.getElementById("ActiveCodeMobilePanel").style.display;
		if (display1== 'none') {
			var ActiveCode = 0;
		} else {
			var ActiveCode = document.getElementById("ActiveCodeMobile").value;  
		}
		document.getElementById("submit2").style.pointerEvents='none';	
		parameter = 'idtype='+idtype + '&Mobile='+Mobile+'&ActiveCode='+ActiveCode;
	}
	if (idtype==3) {
		var Email = document.getElementById("Email").value;  
		if ( (Email.length<5) || (Email.length>50) ) {
			document.getElementById("Email").focus();
			document.getElementById('ShowError3').style.display='block';
			document.getElementById('ShowError3').innerHTML= 'ایمیل صحیح نمی باشد';
			return;
		}
		var display1 = document.getElementById("ActiveCodeEmailPanel").style.display;
		if (display1== 'none') {
			var ActiveCode = 0;
		} else {
			var ActiveCode = document.getElementById("ActiveCodeEmail").value;  
		}
		parameter = 'idtype='+idtype + '&Email='+Email+'&ActiveCode='+ActiveCode;
	}
	if (idtype==4) {
		var OldPassword = document.getElementById("OldPassword").value;  
		var Password1 = document.getElementById("Password1").value;  
		var Password2 = document.getElementById("Password2").value; 
		if ( (OldPassword.length<6) || (Password1.length<6) || (Password2.length<6) ) {
			document.getElementById('ShowError4').style.display='block';
			document.getElementById('ShowError4').innerHTML= 'لطفا اطلاعات را به درستی وارد نمایید';
			return;
		}
		parameter = 'idtype='+idtype +'&OldPassword='+OldPassword + '&Password1='+Password1+'&Password2='+Password2;
	}
	var ShowError = 'ShowError'+idtype;
	var SubmitId  = 'submit'+idtype;
	var xmlhttp=false;
	try {
		xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
	} catch (e) {
		try {
			xmlhttp = new  ActiveXObject('Microsoft.XMLHTTP');
		} catch (E) {
			xmlhttp = false;
		}
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
			xmlhttp = new XMLHttpRequest();
	}
	xmlhttp.open("POST","/ajaxedit.php",true);  
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");  
	xmlhttp.send(parameter);
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4) {
			var content = xmlhttp.responseText;
			if( content ){
//				alert(content);
				var ary=content.split(';;;');
				var Status      = ary[0];
				var NewContent  = ary[1];
				if (Status < 0) 	{ 
					document.getElementById(ShowError).style.display='block';
					document.getElementById(ShowError).innerHTML= NewContent ;
				}else { 
					if (idtype==1) {
						document.getElementById(ShowError).style.display='block';
						document.getElementById(ShowError).innerHTML= NewContent ;
					}
					if (idtype==2) {
						document.getElementById(ShowError).style.display='block';
						document.getElementById(ShowError).innerHTML= NewContent ;
						if (Status==1) {
							document.getElementById('ActiveCodeMobilePanel').style.display='block';
							document.getElementById('Mobile').readOnly=true;
						}
						if (Status==2) {
							document.getElementById('ActiveCodeMobilePanel').style.display='none';
							ShowAndHidden('edit_mobile','button_mobile' , 'Mobile',true);
							alert('شماره موبایل با موفقیت تغییر یافت');
						}
					}
					if (idtype==3) {
						document.getElementById(ShowError).style.display='block';
						document.getElementById(ShowError).innerHTML= NewContent ;
						if (Status==1) {
							document.getElementById('ActiveCodeEmailPanel').style.display='block';
							document.getElementById('Email').readOnly=true;
						}
						if (Status==2) {
							document.getElementById('ActiveCodeEmailPanel').style.display='none';
							ShowAndHidden('edit_email','button_email', 'Email',true);
							alert('ایمیل با موفقیت تغییر یافت');
						}
					}
					if (idtype==4) {
						document.getElementById(ShowError).style.display='block';
						document.getElementById(ShowError).innerHTML= NewContent ;
					}
				
				}
				document.getElementById(SubmitId).style.pointerEvents='auto';	
				
			}
		}
	}
	return false;
}

function JamPrice() {
	var JamPrice=0;
	oElement1 = document.listManager.elements["idaccount[]"];
	oElement2 = document.listManager.elements["priceaccount[]"];
	for(i = 0; i < oElement1.length; i++) {
		if (oElement1[i].checked) {
			
			JamPrice = JamPrice + parseInt(oElement2[i].value);
		}
	}
	document.getElementById('JamPrice').innerHTML = FormatNumberBy3(JamPrice);
	return JamPrice;
//	alert(JamPrice);
}

function invoicing(id,price) {
	if (id==0) { //خرید دوره
		var JamPrice=0;
		var ListId = '';
		oElement1 = document.listManager.elements["idaccount[]"];
		oElement2 = document.listManager.elements["priceaccount[]"];
		for(i = 0; i < oElement1.length; i++) {
			if (oElement1[i].checked) {
				JamPrice = JamPrice + parseInt(oElement2[i].value);
				ListId = ListId + oElement1[i].value + ',';
			}
		}
		document.getElementById('JamPrice').innerHTML = FormatNumberBy3(JamPrice);
		if (JamPrice==0) {
			alert('دوره ای جهت خرید انتخاب نشده است');
			return;
		}
		parameter = 'ListId='+ListId+'&JamPrice='+JamPrice;
	}else {
		JamPrice = price;
		parameter = 'ShareId='+id+'&JamPrice='+JamPrice;
	}
		 document.getElementById("submit1").style.pointerEvents='none';	

	var xmlhttp=false;
	try {
		xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
	} catch (e) {
		try {
			xmlhttp = new  ActiveXObject('Microsoft.XMLHTTP');
		} catch (E) {
			xmlhttp = false;
		}
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
			xmlhttp = new XMLHttpRequest();
	}
	xmlhttp.open("POST","/ajaxpayment.php",true);  
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");  
	xmlhttp.send(parameter);
	xmlhttp.onreadystatechange=function() {
	if (xmlhttp.readyState==4) {
		var content = xmlhttp.responseText;
		if( content ) {
			 document.getElementById("submit1").style.pointerEvents='auto';	
			
			var ary=content.split(';;;');
			var Status      = ary[0];
			var NewContent  = ary[1];
			if (Status<0) {
				alert(content);
			}else {
				window.location = NewContent;
			}
		}
	}
	}
	return false;
	
}

function toEnglishNum( num, dontTrim ) {
    var i = 0,
        j = 0,
        dontTrim = dontTrim || false,
        num = dontTrim ? num.toString() : num.toString().trim(),
        len = num.length,
        res = '',
        pos,
        persianNumbers = typeof persianNumber == 'undefined' ?
            [ '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹' ] :
            persianNumbers;

    for ( ; i < len; i++ )
        if ( ~( pos = persianNumbers.indexOf( num.charAt( i ) ) ) )
            res += pos;
        else
            res += num.charAt( i );
    return res;
};


function checkListCheckForm(Form1, ElementName1) { 
	CounterChecked = 0;
	if (Form1 instanceof Object) {
		var ElementName2 = ElementName1 + '[]';
		var form2 = Form1.elements[ElementName2];
		if (form2 instanceof Object)  {
    		for (i=0; i<form2.length; i++) { 
        		if (form2[i].checked == true) { 
            		CounterChecked++;
        		} 
    		} 
		}else{
			CounterChecked = -1;
		}
	}else{
		CounterChecked = -2;
	}
    return CounterChecked; 
}
//----------------------------------------------
function ShowAlert(Message) {
	var PageMain = '#AlertMessage';
	var PageId   = 'AlertMessageContent';
	var NewPage = document.getElementById(PageMain);
	document.getElementById(PageId).innerHTML=Message;
	$(PageMain).modal('show')	

}
//----------------------------------------------
function AddWish(id) {
	$.ajax({
		type: 'POST',
		url: '/data_ajax.php',
		data: { type: 9, id: id},
		dataType: 'json',
		success: function (data, status, xhr) {
			if (data[0]<0) { message(data[1], 'error') }
			if (data[0]==1) { message(data[1], 'success')  }
			if (data[0]==2) { message(data[1], 'error')  }
		},
		error: function (jqXhr, textStatus, errorMessage) {alert(' textStatus = '+textStatus+' Error: ' + errorMessage);}
	});		
}
//---------------------------------
function AddToCart(id, idprice, number) {
	$.ajax({
		type: 'POST',
		url: '/data_ajax.php',
		data: { type: 10, id: id, idprice:idprice, number:number},
		dataType: 'json',
		success: function (data, status, xhr) {
			if (data[0]<0) { message(data[1], 'error') }
			if (data[0]==1) {
				message(data[1], 'success');
				updateCartItems();
			}
		},
		error: function (jqXhr, textStatus, errorMessage) {alert(' textStatus = '+textStatus+' Error: ' + errorMessage);}
	});		
}
//---------------------------------
function Compare(id, okopen=0) {
	$.ajax({
		type: 'POST',
		url: '/data_ajax.php',
		data: { type: 11, id: id},
		dataType: 'json',
		success: function (data, status, xhr) {
			if (data[0]<0) { message(data[1], 'error') }
			if (data[0]==1) { 
				message(data[1], 'success');
				if (okopen==1) { 
					window.location='/compare/';
				}
			}
			if (data[0]==2) { 
				message(data[1], 'error');
				if (okopen==1) { 
					window.location='/compare/';
				}
			}
		},
		error: function (jqXhr, textStatus, errorMessage) {alert(' textStatus = '+textStatus+' Error: ' + errorMessage);}
	});		
}
//------------------------------------------
function updateCartItems() {
	$.ajax({
		type: 'POST',
		url: '/data_ajax.php',
		data: { type: 12},
		dataType: 'json',
		success: function (data, status, xhr) {
			if (data[0]<0) { message(data[1], 'error') }
			if (data[0]==1) { 
				var content = data[1];
				var count 	= data[2];
				var price 	= data[3];
				var content2= data[4];
				var priceall= data[5];
			  	$('#style-2').empty(); 		
				if (count==0) { 
					$('#style-0').css('display','none');
					$('#style-1').css('height','0px');
				}else{
					$('#style-0').css('display','block');
				}
				if (count==1) { 
					$('#style-1').css('height','120px');
				}
				if (count>1) { 
					$('#style-1').css('height','250px');
				}
				$('#price-1').html(price);
				$('#price-2').html(price);
				$('#count-1').html(count);
				$('#count-basket-bottom').html(count);
			  	$('#style-2').html(content); 
				if ($('#TableOrder').length) {
			  		$('#TableOrder').html(content2); 
			  		$('#PriceAll1').html(price); 
			  		$('#PriceAll2').html(price); 
				}
				
			}
		},
		error: function (jqXhr, textStatus, errorMessage) {alert(' textStatus = '+textStatus+' Error: ' + errorMessage);}
    });
}
//-------------------------------
function DelShopping(id) {
	$.ajax({
		type: 'POST',
		url: '/data_ajax.php',
		data: { type: 13, id: id},
		dataType: 'json',
		success: function (data, status, xhr) {
			if (data[0]<0) { message(data[1], 'error') }
			if (data[0]==1) {
				updateCartItems();
				message(data[1], 'success');
			}
		},
		error: function (jqXhr, textStatus, errorMessage) {alert(' textStatus = '+textStatus+' Error: ' + errorMessage);}
	});		
}
//--------------------------------------
function UpDown(idobject, num, iid) {
	var input = $(idobject);
	min = input.attr('min'),
	max = input.attr('max');
	var oldValue = parseFloat(input.val());
	if (num==1){
    	if (oldValue >= max) {var newVal = oldValue;} else {var newVal = oldValue + 1;}
	}
	if (num==-1){
    	if (oldValue <= min) {
//			var newVal = oldValue;
			DelShopping(iid);
			return;
		} 
		else {var newVal = oldValue - 1;}

	}
	input.val(newVal);
	input.trigger("change");
}
//--------------------------------------
function ChangeCount(id, number) {
	$.ajax({
		type: 'POST',
		url: '/data_ajax.php',
		data: { type: 14, id: id, number:number},
		dataType: 'json',
		success: function (data, status, xhr) {
			if (data[0]<0) { message(data[1], 'error') }
			if (data[0]==1) {
				updateCartItems();
//				message(data[1], 'success');
			}
		},
		error: function (jqXhr, textStatus, errorMessage) {alert(' textStatus = '+textStatus+' Error: ' + errorMessage);}
	});		
	
}
//---------------------------- پیست کردن فقط اعداد
function PasteonlyNumbers(event) {
	event.preventDefault();
	const pastedData = event.clipboardData.getData('text');
	const cleanedData = pastedData.replace(/[^0-9]/g, '');
	event.target.value = cleanedData;
}
//---------------------------- پیست کردن اعداد و خط تیره و کاما و نقطه
function PasteonlyNumbers2(event) {
	event.preventDefault();
	const pastedData = event.clipboardData.getData('text');
    const cleanedData = pastedData.replace(/[^0-9.,_+-]/g, '');
	event.target.value = cleanedData;
}
//---------------------------- تایپ اعداد و خط تیره و کاما و نقطه با onkeypress
function KeypressAllowNumber(event) {
	const key = event.key;
	
	// کاراکترهای مجاز: اعداد (0-9)، انگلیسی و فارسی
	const isAllowedCharacter = /^[0-9]$/.test(key);
    const isFarsiNumber = /^[\u06F0-\u06F9]$/.test(key);

	// کلیدهای ناوبری و کنترلی: Backspace, Delete, Arrow keys, Tab, Enter
	const isControlKey = event.metaKey || event.ctrlKey;
	const isNavigationKey = ['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab', 'Home', 'End', 'Enter'].includes(key);

	// اگر کاراکتر مجاز نبود و کلید ناوبری یا کنترلی هم نبود، از عملکرد پیش‌فرض جلوگیری کن
	if (!isAllowedCharacter && !isNavigationKey && !isControlKey && !isFarsiNumber) {
		event.preventDefault();
	}
}

//---------------------------- تایپ اعداد و خط تیره و کاما و نقطه با onkeypress
function KeypressAllowNumber2(event) {
	const key = event.key;
	
	// کاراکترهای مجاز: اعداد (0-9)، نقطه، کاما، خط تیره، پلاس، آندرلاین
	const isAllowedCharacter = /^[0-9.,_+-]$/.test(key);
    const isFarsiNumber = /^[\u06F0-\u06F9]$/.test(key);

	// کلیدهای ناوبری و کنترلی: Backspace, Delete, Arrow keys, Tab, Enter
	const isControlKey = event.metaKey || event.ctrlKey;
	const isNavigationKey = ['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab', 'Home', 'End', 'Enter'].includes(key);

	// اگر کاراکتر مجاز نبود و کلید ناوبری یا کنترلی هم نبود، از عملکرد پیش‌فرض جلوگیری کن
	if (!isAllowedCharacter && !isNavigationKey && !isControlKey && !isFarsiNumber) {
		event.preventDefault();
	}
}
//-----------------------
function ChangeSelectFile(fileInputId,maxFileSizeMB,SpanFileInfo) {
    const $fileInput = $(fileInputId);
	const $fileInfo = $(SpanFileInfo);	
	const files = $fileInput[0].files;
    const maxFileSize = maxFileSizeMB * 1024 * 1024; // تبدیل به بایت
            
	if (files.length > 0) {
		// بررسی حجم فایل‌ها
		let isFileSizeValid = true;
		for (const file of files) {
			if (file.size > maxFileSize) {
				isFileSizeValid = false;
				largeFile = file;
				break;
			}
		}
		
		if (isFileSizeValid) {
			// اگر حجم معتبر بود، اطلاعات را نمایش بده
			if (files.length === 1) {
				$fileInfo.text(`فایل: ${files[0].name}`);
			} else {
				$fileInfo.text(`${files.length} فایل انتخاب شد.`);
			}
		} else {
			const fileSizeKB = (largeFile.size / 1024).toFixed(2);
            var MsgError = `
                فایل ${largeFile.name} بیش از حد مجاز است.
                <br>
                حجم فایل: ${fileSizeKB} کیلوبایت | حداکثر مجاز: ${maxFileSizeMB * 1024} کیلوبایت
            `;
			message(MsgError,'error',0,'');
            $fileInput.val(''); // پاک کردن فایل‌های انتخاب شده		}
		}
	} else {
		// اگر هیچ فایلی انتخاب نشده بود
		$fileInfo.text('هیچ فایلی انتخاب نشده است.');
	}	
}
//---------------------- بررسی اینکه آیا این رگولار (اعتبارسنجی) صحیح است یا خیر؟
// عبارت منظم معتبر --> const validRegex = '^[a-zA-Z0-9_]+$';
// عبارت منظم نامعتبر (پرانتز اضافی) --> const invalidRegex = '^[a-zA-Z0-9_]+($';
function isValidRegex(regexString) {
    try {
        new RegExp(regexString);
        return true;
    } catch (e) {
        return false;
    }
}