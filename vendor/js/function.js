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
			  (charCode >= 48 && charCode <= 58) 
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
function ValidNumberPhone(inputtxt)		{      if (inputtxt.match(/^[0-9\_\-\+]+$/))    {return true;  }   else     {return false;} }
 
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
function ShowAndHidden(idcontent1, idcontent2, idcontent3,value3) {    
	document.getElementById(idcontent1).style.display = 'block'; 	
	document.getElementById(idcontent2).style.display = 'none'; 
	document.getElementById(idcontent3).readOnly = value3; 	
}

function Show(idcontent) {    
	document.getElementById(idcontent).style.visibility = 'visible'; 	
}
function Hidden(idcontent) {    
	document.getElementById(idcontent).style.visibility = 'hidden'; 	
}

function Show2(idcontent) {    
	document.getElementById(idcontent).style.display = 'block'; 	
}
function Hidden2(idcontent) {    
	document.getElementById(idcontent).style.display = 'none'; 	
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

// تبدیل تاریخ میلادی به شمسی
function gregorian_to_jalali2(gy,gm,gd){
	 var g_d_m,jy,jm,jd,gy2,days;
	 g_d_m=[0,31,59,90,120,151,181,212,243,273,304,334];
	 if(gy > 1600){
		  jy=979;
		  gy-=1600;
	 }else{
		  jy=0;
		  gy-=621;
	 }
	 gy2=(gm > 2)?(gy+1):gy;
	 days=(365*gy) +(parseInt((gy2+3)/4)) -(parseInt((gy2+99)/100)) +(parseInt((gy2+399)/400)) -80 +gd +g_d_m[gm-1];
	 jy+=33*(parseInt(days/12053)); 
	 days%=12053;
	 jy+=4*(parseInt(days/1461));
	 days%=1461;
	 if(days > 365){
		  jy+=parseInt((days-1)/365);
		  days=(days-1)%365;
	 }
	 jm=(days < 186)?1+parseInt(days/31):7+parseInt((days-186)/30);
	 jd=1+((days < 186)?(days%31):((days-186)%30));
	 return [jy,jm,jd];
}
//تبدیل تاریخ شمسی به میلادی
function jalali_to_gregorian2(jy,jm,jd){
	 var sal_a,gy,gm,gd,days,v;
	 if(jy > 979){
		  gy=1600;
		  jy-=979;
	 }else{
		gy=621;
	 }
	 days=(365*jy) +((parseInt(jy/33))*8) +(parseInt(((jy%33)+3)/4)) +78 +jd +((jm<7)?(jm-1)*31:((jm-7)*30)+186);
	 gy+=400*(parseInt(days/146097));
	 days%=146097;
	 if(days > 36524){
		gy+=100*(parseInt(--days/36524));
		days%=36524;
		if(days >= 365)days++;
	 }
	 gy+=4*(parseInt(days/1461));
	 days%=1461;
	 if(days > 365){
		gy+=parseInt((days-1)/365);
		days=(days-1)%365;
	 }
	 gd=days+1;
	 sal_a=[0,31,((gy%4===0 && gy%100!==0) || (gy%400===0))?29:28,31,30,31,30,31,31,30,31,30,31];
	 for(gm=0;gm<13;gm++){
		v=sal_a[gm];
		if(gd <= v)break;
		gd-=v;
	 }
	 return [gy,gm,gd]; 
}

function date_jalali_to_day(inputdate) {
	if (typeof inputdate != 'string') {		return 0;	}
	if ( (inputdate=='')  || (inputdate.length != 10) ){ 		return 0; 	}
	var dateArr = inputdate.split('/');
	if (dateArr.length != 3) { return 0; }
	jy = parseInt(dateArr[0]);
	jm = parseInt(dateArr[1]);
	jd = parseInt(dateArr[2]);
	days=(365*jy) +((parseInt(jy/33))*8) +(parseInt(((jy%33)+3)/4)) +78 +jd +((jm<7)?(jm-1)*31:((jm-7)*30)+186);
	return days;
}

function ActivePage(PageId) {
	var PageIdClose = PageId + '_close';
	var VideoId     = PageId + '_video';
	var NewPage = document.getElementById(PageId);
	NewPage.style.display = "block";
	var CloseIcon = document.getElementById(PageIdClose);
	CloseIcon.onclick = function() {
	  NewPage.style.display = "none";
		var VideoTag = document.getElementById(VideoId);
    	if(typeof(VideoTag) != 'undefined' && VideoTag != null){		
			VideoTag.pause();
		}
	}
	// When the user clicks anywhere outside of the modal, close it
	window.onclick = function(event) {
	  if (event.target == NewPage) {
		NewPage.style.display = "none";
		var VideoTag = document.getElementById(VideoId);
    	if(typeof(VideoTag) != 'undefined' && VideoTag != null){		
			VideoTag.pause();
		}
	  }
	} 
}

function ChangeHeight(ObjectId1, ObjectId2, Min,Max,) {
	var Object1 = document.getElementById(ObjectId1);
	var Object2 = document.getElementById(ObjectId2);
	var OHeight = Object2.style.height;
	var Min = Min+'px';
	var Max = Max+'px';
	if (OHeight==Min)  { 
		Object2.style.height = Max;  
		Object1.innerHTML=' موارد کمتر ▲ ';
	} else {  
		Object2.style.height = Min;  
		Object1.innerHTML=' موارد بیشتر ▼ ';
	} 
	
}



function sendAjaxComment(codenews,idnazar) {
	if (idnazar==0) {
		var message = document.getElementById("comment_name");  
	} else {
		var message = document.getElementById("comment_name2");  
	}

	if (message.value == "") {
		ShowAlert('لطفا نظر خود را وارد نمایید');
		message.focus();
		return false;
	}
	parameter = 'idnews=' + codenews +'&idnazar='+idnazar+'&message='+message.value;

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
	xmlhttp.open("POST","/ajaxsavecomment.php",true);  
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");  
	xmlhttp.send(parameter);
	xmlhttp.onreadystatechange=function() {
		var idanswernazarold1  = 'answer_container_'+ idnazar;
		var idanswernazarold2 = 'answer_icon_'+ idnazar;
		if (xmlhttp.readyState==4) {
			var content = xmlhttp.responseText;
			if( content ){
				document.getElementById(idanswernazarold1).innerHTML= content ;
				var SendIcon = document.getElementById(idanswernazarold2);  
				if(SendIcon){
					SendIcon.outerHTML='';
				}
			}
		}
	}
	return false;
}

function VoteCommentFilm(idnews, vote) {
	var idnazar=0;
	parameter = 'idnews=' + idnews +'&idnazar='+idnazar+'&vote='+vote;
	var id_down 	= 'VoteDown';
	var id_data 	= 'VoteResult';
	var id_up   	= 'VoteUp';
	var id_down_data= 'VoteDownData';
	var id_up_data  = 'VoteUpData';


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
	xmlhttp.open("POST","/ajaxsavevote.php",true);  
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");  
	xmlhttp.send(parameter);
	xmlhttp.onreadystatechange=function() {
	if (xmlhttp.readyState==4) {
		var content = xmlhttp.responseText;
		if( content ){
			if (content.indexOf("خطا") > -1) 	{ 
				ShowAlert(content);	
			}else { 
				
				var ary=content.split(',');
				var StatusDown = ary[0];
				var ScoreDown  = ary[1];
				var StatusUp   = ary[2];
				var ScoreUp    = ary[3];
				var up_data   = document.getElementById(id_up_data);
				var down_data = document.getElementById(id_down_data);

				var down_data_val = parseInt(down_data.innerHTML);  
				var up_data_val   = parseInt(up_data.innerHTML);  
				
				if (StatusDown>0) {
					var temp1 = document.getElementById(id_down);  
					if (ScoreDown==1) {
						temp1.style.opacity = "1";
						down_data_val = down_data_val + 1;
					}else {
						temp1.style.opacity = "0.5";
						down_data_val = down_data_val - 1;
					}
				}
				if (StatusUp>0) {
					var temp1 = document.getElementById(id_up);  
					if (ScoreUp==1) {
						temp1.style.opacity = "1";
						up_data_val = up_data_val + 1;
					}else {
						temp1.style.opacity = "0.5";
						up_data_val = up_data_val - 1;
					}
				}
				var CountVote = up_data_val  + down_data_val;
				var VoteResult = 100 - parseInt(down_data_val*100/CountVote);
//				alert('VoteResult='+VoteResult+'\r\n CountVote='+CountVote);
				document.getElementById(id_data).innerHTML= VoteResult + '%' ;
				up_data.innerHTML   = up_data_val;
				down_data.innerHTML = down_data_val;
			}
		}
	}
	}
	return false;
}


function VoteComment(idnews,idnazar, vote) {
	parameter = 'idnews=' + idnews +'&idnazar='+idnazar+'&vote='+vote;
	var id_down 		= 'vote'+idnews+'-'+idnazar+'-down';
	var id_down_data 	= 'vote'+idnews+'-'+idnazar+'-down-data';
	var id_up   		= 'vote'+idnews+'-'+idnazar+'-up';
	var id_up_data   	= 'vote'+idnews+'-'+idnazar+'-up-data';


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
	xmlhttp.open("POST","/ajaxsavevote.php",true);  
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");  
	xmlhttp.send(parameter);
	xmlhttp.onreadystatechange=function() {
	if (xmlhttp.readyState==4) {
		var content = xmlhttp.responseText;
		if( content ){
			if (content.indexOf("خطا") > -1) 	{ 
				ShowAlert(content);	
			}else { 
				var ary=content.split(',');
				var StatusDown = ary[0];
				var ScoreDown  = ary[1];
				var StatusUp   = ary[2];
				var ScoreUp    = ary[3];
//				alert('content='+content+'\r\n StatusDown='+StatusDown);
				if (StatusDown>0) {
					var temp1 = document.getElementById(id_down);  
					var temp2 = document.getElementById(id_down_data);  
					if (ScoreDown==1) {
					temp1.innerHTML = "<i class='CommentIcon2 fa fa-thumbs-down'></i>";
					temp2.innerHTML = parseInt(temp2.innerText)+1;
					}else {
					temp1.innerHTML = "<i class='CommentIcon fa fa-thumbs-down'></i>";
					temp2.innerHTML = parseInt(temp2.innerText)-1;
					}
				}
				if (StatusUp>0) {
					var temp1 = document.getElementById(id_up);  
					var temp2 = document.getElementById(id_up_data);  
					if (ScoreUp==1) {
					temp1.innerHTML = "<i class='CommentIcon2 fa fa-thumbs-up'></i>";
					temp2.innerHTML = parseInt(temp2.innerText)+1;
					}else {
					temp1.innerHTML = "<i class='CommentIcon fa fa-thumbs-up'></i>";
					temp2.innerHTML = parseInt(temp2.innerText)-1;
					}
				}
//				document.getElementById(idspan).innerHTML= content ;
			}
		}
	}
	}
	return false;
}

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

function ShowAlert(Message) {
	var PageMain = '#AlertMessage';
	var PageId   = 'AlertMessageContent';
	var NewPage = document.getElementById(PageMain);
	document.getElementById(PageId).innerHTML=Message;
	$(PageMain).modal('show')	

}

 