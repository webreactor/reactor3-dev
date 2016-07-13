/*******************************************************************************
Reactor tools for JavaScript Version 2.0.0.3
Developed by Maxim Popov http://ecto.ru/
I like JQuery syntactic but it's too huge.
*******************************************************************************/


(function (){

function reactor_tools_start(mask,context)
{
	return new reactor_tools(mask,context);
}

var _rt = window._rt=reactor_tools_start;
var userAgent = navigator.userAgent.toLowerCase();


function reactor_tools(mask,context)
{
	this.objects = [];
	this.add(mask,context);
}

reactor_tools.prototype.browser = {
		version: (userAgent.match( /.+(?:rv|it|ra|ie)[\/: ]([\d.]+)/ ) || [])[1],
		safari: /webkit/.test( userAgent ),
		opera: /opera/.test( userAgent ),
		msie: /msie/.test( userAgent ) && !/opera/.test( userAgent ),
		mozilla: /mozilla/.test( userAgent ) && !/(compatible|webkit)/.test( userAgent )
	};

reactor_tools.prototype.clone = function(obj1,obj2)
{
	if(obj2 == null)
	{
		if(obj1 == null) return null;
		obj2 = (obj1 instanceof Array) ? [] : {};
		return this.clone(obj2, obj1);
	}

	for(var i in obj2)
	{
		if(typeof obj2[i] == "object")
		{
			if(obj1[i] == null)
			obj1[i] = this.clone(obj2[i]);
			else
			this.clone(obj1[i], obj2[i]);
		}
		else
			obj1[i] = obj2[i];
	}
	return obj1;
}

reactor_tools.prototype.each=function(callback,arg)
{
	for(var i=0;i<this.objects.length;i++)
		callback.call(this,this.objects[i],i);
	return this;
}

reactor_tools.prototype.set=function(prop)
{
	if(prop.style)
		if(prop.style.opacity != null)
				prop.style.filter = "alpha(opacity:" + parseInt(prop.style.opacity * 100) + ")";
	this.each(function _set(object){
		this.clone(object, prop);
		});
	return this;
}

reactor_tools.prototype.get = function(i)
{
	if(i == null)return this.objects;
	if(this.objects[i]) return this.objects[i];
	return false;
}

reactor_tools.prototype.first = function()
{
	if(this.objects[0]) return this.objects[0];
	return false;
}

function htmlToElements(html)
{
	var rez = [];
	var obj = document.createElement('div');;
	if(/^\s*<tr.+<\/tr>\s*$/i.test(html))
	{
		obj.innerHTML = '<table><tbody>' + html + '</tbody></table>';
		obj = obj.firstChild.firstChild;
	}
	else
	{

		obj.innerHTML = html;
 	}
	for(var i = 0; i < obj.childNodes.length; i++)
		rez.push(obj.childNodes[i]);

 	return rez;
}

function getElementsByClassName(obj,mask)
{
	if(obj.getElementsByClassName)
	return obj.getElementsByClassName(mask);

	var rez = [];
	var el = obj.getElementsByTagName('*');
	var testName='';
	mask=' '+mask+' ';
	for(var i = 0; i < el.length ; i++)
	{
		testName=' '+el[i].className+' ';
		if(testName.indexOf(mask) != -1)
			rez.push(el[i]);
	}
	return rez;
}

reactor_tools.prototype.add=function(mask,context)
{
	var i;
	if(!mask) return this;
	if(mask.objects)
	{
		this.objects = mask.objects;
		return this;
	}

	if(mask.ctrlKey != null)
	{
		this.objects = [this.getTarget(mask)];
		return this;
	}

	if(typeof mask == "object")
	{
		this.objects.push(mask);
		return this;
	}

	if(/<.*>/.test(mask))
	{
		this.objects = htmlToElements(mask);
		return this;
	}
	mask=mask.split(' ');
	var rez;
	if(!context) context = document.body;
	if(!context.objects)
		rez = [context];
	else
		rez = context.objects
	for(i=0; i < mask.length; i++)
	{
		if(mask[i] != '')
			rez = _add(mask[i], rez);
	}
	this.objects=this.objects.concat(rez);
	return this;

	function _add(mask, context)
	{
		var obj, tobj, i, j, tag, filterClass;
		var rez = [];

		if(mask.substr(0, 1) == '#')
		{
			mask = mask.substr(1);
			obj = document.getElementById(mask);
			if(obj) rez.push(obj);
			return rez;
		}

		if(mask.substr(0,1) == '.')
		{
			mask = mask.substr(1);
			for(i = 0; i<context.length; i++)
			{
				tobj=getElementsByClassName(context[i],mask);
				for(j = 0; j < tobj.length; j++)
					rez.push(tobj[j]);
			}
			return rez;
		}

		i = mask.indexOf('.');
		if(i == -1)
		{
			tag = mask;
			filterClass = '';
		}
		else
		{
			tag = mask.substr(0, i);
			filterClass = mask.substr(i + 1);
		}

		obj = [];
		for(i = 0; i < context.length; i++)
		{
			tobj=context[i].getElementsByTagName(tag);
			for(j = 0; j < tobj.length; j++)
				obj.push(tobj[j]);
		}

		if(filterClass != '')
		{
			for(i = 0; i < obj.length; i++)
				if(obj[i].className == filterClass)
					rez.push(obj[i]);
		}
		else
			rez = obj;

		return rez;
	}
}

//EVENTS------------------------------------------------------------------------

reactor_tools.prototype.bind = function (eventName, callback, msg)
{
	trace('RegisterEventHandler '+eventName, msg);

	this.each(function(object)
	{
		if (object.addEventListener)
		{
			if(eventName == 'mousewheel')
				object.addEventListener('DOMMouseScroll', callback, false);
			object.addEventListener (eventName, callback, false);
		}
		else if (object.attachEvent)object.attachEvent ('on' + eventName, callback);
	});
	return this;
}

reactor_tools.prototype.unbind = function (eventName, callback,msg)
{
	trace('DeleteEventHandler '+eventName,msg);
	this.each(function(object){
		if (object.detachEvent)
			object.detachEvent ('on' + eventName, callback);
		else if (object.removeEventListener)
			object.removeEventListener (eventName, callback, false);
	});
	return this;
}

reactor_tools.prototype.trigger = function (eventName)
{
	var evt;
	this.each(function(object)
	{
		if (document.createEvent)
		{
			evt = document.createEvent("HTMLEvents");
			evt.initEvent(eventName, true, true );
			object.dispatchEvent(evt);
		}
		else
		{
			evt = document.createEventObject();
			object.fireEvent('on'+eventName,evt);
		}
	});
	return this;
}

//COOKIES-------------------------------------------------------------------------

reactor_tools.prototype.cookie = function (name,value,days)
{
	if(typeof value == 'undefined')	return readCookie(name);
	if(value == '')	return eraseCookie(name);
	return createCookie(name,value,days);
}

reactor_tools.prototype.ifCookie = function (name,def_value)
{
	var t;
	if(!(t=readCookie(name)))return def_value;
	return t;
}

function createCookie(name,value,days)
{
	var expires='';
	if (days)
	{
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		expires = "; expires="+date.toGMTString();
	}
	document.cookie = name+"="+encodeURIComponent(value)+expires+"; path=/";
	return true;
}

function readCookie(name)
{
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	var c;
	for(var i=0;i < ca.length;i++)
	{
		c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return decodeURIComponent(c.substring(nameEQ.length,c.length));
	}
	return null;
}

function eraseCookie(name)
{
	createCookie(name,"",-1);
	return true;
}






//ETC HELPS-------------------------------------------------------------------------
reactor_tools.prototype.stopEvent = function (event,msg)
{
	trace('stopEvent '+event, msg);
	if(!event) var event = window.event;
	if(event.preventDefault) event.preventDefault();
	if(event.stopPropagation) event.stopPropagation();
	event.cancelBubble = true;
    event.returnValue = false;
	event.cancel = true;
return false;
}

reactor_tools.prototype.getEvent = function (event)
{
	if (!event) var event = window.event;
	return event;
}

reactor_tools.prototype.getTarget = function (event)
{
	if (!event) var event = window.event;
	var targ;
	if (event.target) targ = event.target;
	else if (event.srcElement) targ = event.srcElement;
	if (targ.nodeType == 3)	targ = targ.parentNode;
	return targ;
}

reactor_tools.prototype.getMouseX = function (event) {
	var xMousePos = event.clientX
	if(document.documentElement.scrollLeft != 0)
		xMousePos += document.documentElement.scrollLeft;
	else
		xMousePos += document.body.scrollLeft;
	if (xMousePos < 0)xMousePos = 0;
	return xMousePos;
}

reactor_tools.prototype.getMouseY = function (event) {
	var yMousePos = event.clientY
	if(document.documentElement.scrollTop != 0)
		yMousePos += document.documentElement.scrollTop;
	else
		yMousePos += document.body.scrollTop;
	if (yMousePos < 0)yMousePos = 0;
	return yMousePos;
}

reactor_tools.prototype.getMouseWheel=function(event){
	var wheelDelta = 0;
	if (event.wheelDelta)
	{
 		wheelDelta = event.wheelDelta / 120;
 		//if (window.opera)wheelDelta = -wheelDelta;
 	}
    else if (event.detail)
    wheelDelta = -event.detail / 3;
	return wheelDelta;
}

reactor_tools.prototype.value = function (value)
{
	if(!this.objects[0])return;

	var _object=this.objects[0];

	if(_object.tagName=='select')
	{
		var objs = _object.getElementsByTagName('option');
		if(!objs)return;

		if(typeof value == 'undefined')
		{
			for(var i = 0; i < objs.length; i++)
			{
				if(objs[i].selected == true) return objs[i].value;
			}
		}
		else
		{
			for(var i = 0; i < objs.length; i++)
			{
				if(objs[i].value==value) return objs[i].selected=true;
			}

		}
		return;
	}

	if(typeof value == 'undefined')
	return _object.value;
	else
	{
		_object.value=value;
		return true;
	}
}

reactor_tools.prototype.isIE = function()
{return navigator.userAgent.toLowerCase().indexOf("msie") != -1 && navigator.userAgent.toLowerCase().indexOf("opera") == -1;}

reactor_tools.prototype.isIE6 =function ()
{return (navigator.appName == "Microsoft Internet Explorer") && (parseFloat(navigator.appVersion) <= 6);}

reactor_tools.prototype.isOpera = function()
{return navigator.userAgent.toLowerCase().indexOf("opera") != -1;}

reactor_tools.prototype.isGecko = function()
{return navigator.userAgent.toLowerCase().indexOf("gecko") != -1;}

reactor_tools.prototype.isSafari = function()
{return navigator.userAgent.toLowerCase().indexOf("safari") != -1;}

reactor_tools.prototype.isKonqueror = function()
{return navigator.userAgent.toLowerCase().indexOf("konqueror") != -1;}



//HTML BUILD--------------------------------------------------------------------

reactor_tools.prototype.html = function(code)
{
	if(code == null)
	{
		if(!this.objects[0]) return false;
		return this.objects[0].innerHTML;
	}

	this.each(function(object){
			object.innerHTML=code;
	});
	return this;
}


reactor_tools.prototype.create = function(tagNameOrCode,props)
{
 	if(props == null)props={};
	if(tagNameOrCode=='#text')
	return document.createTextNode(props);
	else
	return this.clone(document.createElement(tagNameOrCode), props);
}

reactor_tools.prototype.prepend = function(mask)
{
	this.each(function(object){
		var objects=_rt(mask).get();
		for(var i = 0 ; i < objects.length; i++)
		{
			if(object.firstChild)
				object.insertBefore(objects[i],object.firstChild);
			else
				object.append(objects[i]);
		}
	});
	return this;
}


reactor_tools.prototype.append = function(mask)
{
	this.each(function(object){
		var objects=_rt(mask).get();
		for(var i = 0 ; i < objects.length; i++)
			object.appendChild(objects[i]);
	});
	return this;
}

reactor_tools.prototype.after = function(mask)
{
	this.each(function(object){
		var parent=object.parentNode;
		var objects=_rt(mask).get();
		for(var i = 0 ; i < objects.length; i++)
		{
			if(object.nextSibling)
				parent.insertBefore(objects[i], object.nextSibling);
			else
				parent.appendChild(objects[i]);
		}
	});
	return this;
}

reactor_tools.prototype.before = function(mask)
{
	this.each(function(object){
		var parent=object.parentNode;
		var objects=_rt(mask).get();
			for(var i = 0; i < objects.length; i++)
				parent.insertBefore(objects[i], object);
	});
	return this;
}


//EFFECTS--------------------------------------------------------------------


reactor_tools.prototype.fx = function (callback,time,type,speed,callback_stop)
{
	if(this.stop_animation == null) this.stop_animation=0;
	var time_frame = 20;
	var time_now = 0;
	var _this=this;
	if(time == null)time = 0.5;
	if(type == null)type = -1;
	if(type == 'start')type = 1;
	if(type == 'startstop')type = 0;
	if(type == 'stop')type = -1;
	if(speed == null)speed = 5;
	if(speed < 0.1)speed = 0.1;
	if(speed > 10)speed = 10;
	var time_total=parseInt(time * 1000 / time_frame);
	var predel = Math.atan(speed);
	if(type == 0) predel *= 2;

	var x,f;
	var iterator=setInterval(fx_runner,time_frame);

	function fx_mover_tan(x)
	{
		x = x / time_total;
		if(type == 0) return Math.atan( speed * 2 * x - speed )/ predel + 0.5;
		if(type == -1) return Math.atan(speed * x )/ predel;
		if(type == 1) return 1 - Math.atan(speed * ( 1 - x ) )/ predel;
	}

	function fx_runner()
	{
		f = callback(fx_mover_tan(time_now));
		time_now++;
		if(time_now >= time_total || f == false || _this.stop_animation!=0)
		{
			if(_this.stop_animation==0)callback(1);
			if( _this.stop_animation==1) _this.stop_animation=0;
			clearTimeout(iterator);
		}
	}
}

reactor_tools.prototype.animate=function(params,time,type,speed)
{
	this.stop_animation=0;
	if(!this.animations)this.animations=[];
	this.animations.push({'params':params,'time':time,'speed':speed,'status':'ready'});
	this.animate_manager();
	return this;
}

reactor_tools.prototype.stop=function(force_all)
{
if(!force_all)this.stop_animation=1; else this.stop_animation=2;
}

reactor_tools.prototype.prepare_animation=function()
{
	this.stop_animation=0;
}

reactor_tools.prototype.animate_manager = function ()
{
	for(var i = 0; i < this.animations.length; i++)
	{
		if(this.animations[i].status == 'process')return;
		if(this.animations[i].status == 'ready'){this.animate_init(i);  return;}
	}
}

reactor_tools.prototype.animate_init=function(an_idx)
{
	var params=this.animations[an_idx];
	this.animations[an_idx].status='process';
	var prm = [];
	var _this=this;
	this.each(function animate_getNow(object,idx)
		{
			prm[idx] = {};
			var t;
			for(var k in params.params)
			{
				t=null;
				if(k == 'opacity')
				{
					if(this.isIE())
					{
						if(object.style.filter) t=object.style.filter.match(/opacity\s*[=:]\s*(\d+)/);
						if(!t) t = [0,1,'']; else t[1] = parseInt(t[1]) / 100;

					}
					else
					{
						if(object.style[k])	t = object.style[k].match(/(\d+(\.\d+)?)(\w*)/);
						if(!t) t=[0,1,'']; else t[1] = parseFloat(t[1]);
					}
				}
				else
				{
					if(object.style[k])
					t = object.style[k].match(/(\d+(\.\d+)?)(\w*)/);
					else
					t = [0,0];
					t[1] = parseFloat(t[1]);

				}
				if(!t[2]) t[2] = '';
				if(!t[3]) t[3] = '';
				prm[idx][k] = {from: t[1], delta: (params.params[k] - t[1]), item: t[3] };

			}
		}
	);





	function runner(k)
	{
		_this.each(function(object,idx){
			var link, value;
			for(var prop in prm[idx])
			{
				link = prm[idx][prop];
				value = link.from + link.delta * k;
				if(prop != 'opacity')
				object.style[prop] = value + link.item;
				else
				{
					object.style.opacity = value ;
					object.style.filter = "alpha(opacity:" + parseInt(value * 100) + ")";
				}
			}
		});
		if(k==1) {
			_this.animations[an_idx].status='stop';
			_this.animate_manager();
			}
	}

	this.fx(runner,params.time,params.type,params.speed);
}

//REQUESTER---------------------------------------------------------------------

var requestCnt=0;
var requestUrl='';

reactor_tools.prototype.requestUrl = function(url)
{
	requestUrl = url;
}

reactor_tools.prototype.request = function(get_data,callback,post_data,msg)
{
	requestCnt++;
	if(post_data == null)
	trace('Server GET request (' + requestCnt + ')', msg);
	else
	trace('Server POST request (' + requestCnt + ')', msg);
	var hd = new rt_requester(requestUrl, get_data, callback, post_data, requestCnt);
}


function rt_requester(requestUrl,get_data, callback, post_data, nmbr)
{
	this.connector = this.init();
	var _this = this;

	function act()
	{
		if(_this.connector.readyState == 4)
		{
			if (_this.connector.status == 200)
			{
				trace('Server answer (' + nmbr + ')');
				if(callback)
				callback(get_data, _this.connector.responseText, post_data);
			}
			else
			{
				//error handle
			}
		}
	}

	this.connector.onreadystatechange = act;
	var get_str = requestUrl + this.encodeUrl(get_data);
	trace(get_str);

	if(post_data == null)
	{
		this.connector.open("GET", get_str, true);
		this.connector.send(null);
	}
	else
	{
		var post_str = this.encodeUrl(post_data);
		trace(post_str);

		this.connector.open("POST", get_str, true);
		this.connector.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		this.connector.send(post_str);
	}
}

rt_requester.prototype.encodeUrl = function(data)
{
	var rez='';
	for(i in data)
	rez += i + '=' + encodeURIComponent(data[i]) + '&';
return rez;
}

rt_requester.prototype.init = function()
{
	var ret;
	if (window.XMLHttpRequest)
	ret = new window.XMLHttpRequest();
	else if (window.ActiveXObject)
	{
		ret = new ActiveXObject("Msxml2.XMLHTTP");
		if (!ret)
		ret = new ActiveXObject("Microsoft.XMLHTTP");
	}
return ret;
}



//JS fixes----------------------------------------------------------------------

if(!Array.indexOf)
{
	Array.prototype.indexOf = function(obj)
	{
		for(var i = 0; i < this.length; i++)
		{
			if(this[i]==obj)return i;
		}
		return -1;
	}
}




})();//and of reactor tools namespace

var trace=function(){};

//CONSOLE-----------------------------------------------------------------------

(function(){

	if(typeof _rtConsole == 'undefined') _rtConsole=false;
	if(!_rtConsole)return;

	var console_obj=_rt('<div id="_rt_console" style="height:200px; width:100%; overflow:scroll; position:fixed; bottom:0; left:0; background:black; color:#00FF00; textalign:left; font:normal 12px Verdana; display:none; zIndex:100000;"></div>');

	var isConsoleVisible=0;

	trace = window.trace = function (msg, add)
	{
		if(!add) add = ''; else add = ' - ' + add;
		if(typeof msg !='object')
		console_obj.append('<div>' + htmlChars(msg) + add + '</div>');
		else
		{
			if(add != '')console_obj.append('<div>' + add + '</div>');
			console_obj.append(trace_r(msg,0));
		}
		console_obj.get(0).scrollTop=console_obj.get(0).scrollHeight;
	}

	function htmlChars(str)
	{
		if(str && str.replace)
		{
		  str = str.replace(/</g, "&lt;");
		  str = str.replace(/>/g, "&gt;");
		}
	  return str;
	}

	function trace_r(obj,level)
	{
		level++;
		if(level >1) return '{...}';
		var rez = '<div style="padding-left:5px; margin:5px; border-left:1px solid #00FF00;">{';
  		for(var i in obj)
  		{
  			if(typeof obj[i] == 'object')
  			rez += '<div>' + i + ': ' + trace_r(obj[i],level) +',</div>';
  			else
  			{
  				if(typeof obj[i] == 'function')
  				rez += '<div>' + i + ': function(),</div>';
  				else
  				rez += '<div>' + i + ': ' + htmlChars(obj[i]) +',</div>';
  			}
  		}
  		rez+='}</div>';
  		return rez;
	}

	_rt(window).bind('load',function(){ _rt(document.body).append(console_obj); },'for console');

	_rt(document).bind('keydown',function(event){
		event=_rt().getEvent(event);
		if(event.ctrlKey && (event.keyCode ? event.keyCode : event.which ? event.which : null) == 192)
		{
			if(!isConsoleVisible)
				console_obj.set({style:{display:'block'}});
			else console_obj.set({style:{display:'none'}});
				isConsoleVisible=1-isConsoleVisible;
		}
	});


	if(_rtConsole == 'show')
	{
		console_obj.set({style:{display:'block'}});
		isConsoleVisible = 1;
	}

})();//end of concole namespace

