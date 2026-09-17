function ajaxObject(url, callbackFunction) {
  var that=this;      
  this.updating = false;
  this.abort = function() {
    if (that.updating) {
      that.updating=false;
      that.AJAX.abort();
      that.AJAX=null;
    }
  }
  this.update = function(passData,postMethod) { 
    if (that.updating) { return false; }
    that.AJAX = null;                          
    if (window.XMLHttpRequest) {              
      that.AJAX=new XMLHttpRequest();              
    } else {                                  
      that.AJAX=new ActiveXObject("Microsoft.XMLHTTP");
    }                                             
    if (that.AJAX==null) {                             
      return false;                               
    } else {
      that.AJAX.onreadystatechange = function() {  
        if (that.AJAX.readyState==4) {             
          that.updating=false;                
          that.callback(that.AJAX.responseText,that.AJAX.status,that.AJAX.responseXML);        
          that.AJAX=null;                                         
        }                                                      
      }                                                        
      that.updating = new Date();                              
      if (/post/i.test(postMethod)) {
        var uri=urlCall+'?'+that.updating.getTime();
        that.AJAX.open("POST", uri, true);
        that.AJAX.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        that.AJAX.setRequestHeader("Content-Length", passData.length);
        that.AJAX.send(passData);
      } else {
        var uri=urlCall+'?'+passData+'&timestamp='+(that.updating.getTime()); 
        that.AJAX.open("GET", uri, true);                             
        that.AJAX.send(null);                                         
      }              
      return true;                                             
    }                                                                           
  }
  var urlCall = url;        
  this.callback = callbackFunction || function () { };
}

/*
 *	designed by Iman Sayyed Azizy     iman [dot] sazizy [at] yahoo [dot] com
 *	All rights Reserved For Iman Sayyed Azizy
 *	You can use this code without any change and without deleting this comments
 *	© copyright 2013
 */
var _allVotes={};

function starVote(datas) {
	this.uniqueId=Math.floor(Math.random()*1000000);
	while(typeof _allVotes[this.uniqueId]!='undefined') {
		this.uniqueId=Math.floor(Math.random()*1000000);
	}
	_allVotes[this.uniqueId]=this;
	var tmpok=0;
	if(typeof datas.score=='undefined') {
		this.score=0;
	} else {
		this.score=datas.score;
		tmpok++;
	}
	if(typeof datas.vote=='undefined') {
		this.vote=0;
	} else {
		this.vote=datas.vote;
		tmpok++;
	}
	if(typeof datas.curStars=='undefined') {
		this.curStars=0;
	} else {
		this.curStars=datas.curStars;
		tmpok++;
	}

	//Check Cookie is Enable
	function _areCookiesEnabled() {
		document.cookie = "__verifyx234x=1";
		var supportsCookies = document.cookie.length > 1 && document.cookie.indexOf("__verifyx234x=1") > -1;
		var thePast = new Date(1976, 8, 16);
		document.cookie = "__verifyx234x=1;max-age=0";
		return supportsCookies;
	}
	
	//Object Peroperties
	this.isCookieEnable=_areCookiesEnabled();
	this.apiUrl='/starvote.php';
	this.apiSet='action=set';
	this.apiGet='action=get';
	this.apiKey='key';
	this.sendMethod='post';
	this.key=datas.key;
	if(typeof datas.containerId=='undefined') {
		this.containerId='';
	} else {
		this.containerId=datas.containerId;
	}
	if(typeof datas.extraDatas=='undefined') {
		this.extraDatas='';
	} else {
		this.extraDatas=datas.extraDatas;
	}
	if(typeof datas.descriptionId=='undefined') {
		this.descriptionId='';
	} else {
		this.descriptionId=datas.descriptionId;
	}
	if(typeof datas.stars=='undefined') {
		this.stars=5;
	} else {
		this.stars=datas.stars;
	}
	if(this.isCookieEnable) {
		if(typeof datas.expire=='undefined') {
			this.expire=3600*12;
		} else {
			this.expire=datas.expire;
		}
	} else {
		this.expire=0;
	}
	
	//Getting Score
	_getScore=function(responseText, status, responseXML){
		if(200 == status){
			var ary=responseText.split(';');
			_allVotes[ary[0]].refreshScore(parseFloat(ary[1]),parseInt(ary[2]),parseFloat(ary[3]));
		}
	}
	//setting Score
	_setScore=function(responseText, status, responseXML){
		if(200 == status){
			var ary=responseText.split(';');
			_allVotes[ary[0]].isVoted=true;
			var cnt=document.getElementById(_allVotes[ary[0]].containerId);
			cnt.className=cnt.className.replace(/[ ]*x_notvoted[ ]*/,'');
			cnt.className+=' x_disabled';
			document.cookie = 'voteKey_'+_allVotes[ary[0]].key+'=true;max-age=' + _allVotes[ary[0]].expire+';path=/';
			_allVotes[ary[0]].refreshScore(parseFloat(ary[1]),parseInt(ary[2]),parseFloat(ary[3]));
		}
	}
	
	this.sendVote=function(vote) {
		if(!this.isVoted) {
			var _myRequest = new ajaxObject(this.apiUrl,_setScore);
			_myRequest.update(this.apiSet+'&'+this.apiKey+'='+this.key+'&uid='+this.uniqueId+'&vote='+vote+(this.extraDatas==''?'':'&'+this.extraDatas), 'Post');
		}
	}
	
	this.refreshScore=function(score,vote,stars) {
		if(typeof score!='undefined') {
			this.score=score;
		} else {
			score=0;
		}
		if(typeof stars!='undefined') {
			var newstars = score / stars;
//			alert('score='+score+' ---vote='+vote+' ---stars='+stars+'---newstars='+newstars);
			this.curStars=newstars;
		} else {
			stars=0;
		}
		if(typeof vote!='undefined') {
			this.vote=vote;
		} else {
			vote=0;
		}

		if(this.isCookieEnable && this.containerId!='') {
			var i,cnt,dsc,tmp,scr,tmp2;
			cnt=document.getElementById(this.containerId);
			cnt.setAttribute('onmouseout','_allVotes[\''+this.uniqueId+'\'].refreshScore('+score+','+vote+','+stars+');');
			cnt.innerHTML='';
			if(this.descriptionId!='') {
				dsc=document.getElementById(this.descriptionId);
				//dsc.innerHTML='امتیاز کسب شده '+score+' (از '+this.stars+' امتیاز) با '+vote+' رای';
				dsc.innerHTML=score;
			}
			if(!this.isVoted) {
				var reg=new RegExp('.*x_notvoted.*','');
				if(!reg.test(cnt.className)) {
					cnt.className=cnt.className+' x_notvoted';
				}
			} else {
				cnt.className=cnt.className+' x_disabled';
			}
			for(i=0;i<this.stars;i++) {
				tmp=document.createElement('div');
				if(stars>=1) {
					scr='x_100percent';
					stars--;
				} else {
					scr='x_'+(Math.floor(stars*10)*10)+'percent';
					stars=0;
				}
				
				tmp.setAttribute('class','x_starcontainer '+scr);
				tmp.setAttribute('title',(i+1)+' امتیاز');
				if(!this.isVoted) {
					tmp.setAttribute('id','x_'+this.containerId+'_star_'+(i+1));
					tmp.setAttribute('onmouseover','overStarHandler(\''+this.containerId+'\','+(i+1)+','+this.stars+');');
				}
				tmp.setAttribute('onclick','_allVotes[\''+this.uniqueId+'\'].sendVote('+(i+1)+');');
				cnt.appendChild(tmp);
			}

		}
	}
	
	if(document.cookie.search(new RegExp('[ ]*(^|;)[ ]*voteKey_'+this.key+'=','')) > -1) {
		this.isVoted=true;
	} else {
		this.isVoted=false;
	}
	if(this.containerId!='' && tmpok<3) {
		var _myRequest = new ajaxObject(this.apiUrl,_getScore);
		_myRequest.update(this.apiGet+'&'+this.apiKey+'='+this.key+'&uid='+this.uniqueId+(this.extraDatas==''?'':'&'+this.extraDatas), 'Post');
	} else {
		this.refreshScore(this.score,this.vote,this.curStars);
	}
	
	//this.refreshScore();
	//Check Cookie is Exist

}

function overStarHandler(cid,num,cnt) {
	var i,tmp;
	for(i=1;i<=cnt;i++) {
		tmp=document.getElementById('x_'+cid+'_star_'+i);
		tmp.className=tmp.className.replace(/[ ]*x_[0-9]+percent[ ]*/,'');
		if(i<=num) {
			tmp.className=tmp.className+' x_full'
		} else {
			tmp.className=tmp.className+' x_empty'
		}
	}
}
