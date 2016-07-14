//version 2.0.1

var save_obj;
var save_obj_className;

function show_list_menu(add, obj) {

    save_obj           = obj;
    save_obj_className = obj.className;
    obj.className += ' red';

    show('popup_layer');
    document.getElementById('popup_layer').style.height = document.body.scrollHeight;

    cnt                  = document.getElementById('popup_frame');
    cnt.style.top        = yMousePos - 5;
    cnt.style.left       = xMousePos - 5;
    cnt.style.display    = 'block';
    cnt.style.visibility = 'visible';
    r                    = '';
    for (i in popup_menu_content)
        if (popup_menu_content[i]) {
            if (popup_menu_content[i][2] == 1)
                r += '<a href="javascript:if(confirm(\'Вы уверены?\'))document.location=\'' + popup_menu_content[i][0] + add + '&_ref=' + escape(escape(document.location)) + '\';hide_list_menu();">' + popup_menu_content[i][1] + '</a>';
            else
                r += '<a href="' + popup_menu_content[i][0] + add + '">' + popup_menu_content[i][1] + '</a>';
        }

    cnt.innerHTML = r;
    shadowing(cnt);
}

function hide_list_menu() {
    if (save_obj != null)
        save_obj.className = save_obj_className;
    hide('popup_layer');
    hide('popup_frame');
    endShadowing();
}

function shadowing(obj) {
    document.getElementById('shadowBox').style.zIndex        = 101;
    document.getElementById('shadowBox').style.display       = 'block';
    document.getElementById('shadowBox').style.left          = obj.offsetLeft - 9;
    document.getElementById('shadowBox').style.top           = obj.offsetTop - 21;
    document.getElementById('shadowBoxContent').style.width  = obj.offsetWidth - 24 - 9;
    document.getElementById('shadowBoxContent').style.height = obj.offsetHeight - 9;

    document.getElementById('whiteBox').style.zIndex            = 102;
    document.getElementById('whiteBox').style.display           = 'block';
    document.getElementById('whiteBox').style.left              = obj.offsetLeft;
    document.getElementById('whiteBox').style.top               = obj.offsetTop - 12;
    document.getElementById('whiteBoxContentWidth').style.width = obj.offsetWidth - 24;
    document.getElementById('whiteBoxContent').style.height     = obj.offsetHeight;
    obj.style.zIndex                                            = 103;
}

function endShadowing() {

    document.getElementById('shadowBox').style.display = 'none';
    document.getElementById('whiteBox').style.display  = 'none';
}

function tree_content_open(id) {
    cnt_b = document.getElementById(id + '_b');
    cnt_c = document.getElementById(id + '_c');
    if (cnt_b.className == "tree_level_closed_end")
        cnt_b.className = "tree_level_opened_end";
    else
        cnt_b.className = "tree_level_opened";
    cnt_c.style.display = 'block';

}

function tree_content_close(id) {
    cnt_b = document.getElementById(id + '_b');
    cnt_c = document.getElementById(id + '_c');
    if (cnt_b.className == "tree_level_opened_end")
        cnt_b.className = "tree_level_closed_end";
    else
        cnt_b.className = "tree_level_closed";
    cnt_c.style.display = 'none';
}

function tree_content_oc(id) {
    cnt_b = document.getElementById(id + '_b');
    if (cnt_b == null)return;
    if (cnt_b.className == "tree_level_opened" || cnt_b.className == "tree_level_opened_end")
        tree_content_close(id);
    else
        tree_content_open(id);

}

function tree_oc(obj) {
    if (obj.className == "tree_opened") {
        obj.className = "tree_closed";
        for (k in tree_lavels_array)
            tree_content_close(tree_lavels_array[k]);
    }
    else {
        obj.className = "tree_opened";
        for (k in tree_lavels_array)
            tree_content_open(tree_lavels_array[k]);
    }
}

//------------------------------------------------------------------------------

function sel_int(id_input, id_text, data, param, callback) {
    this.input         = _rt(id_input).first();
    this.text          = _rt(id_text).first();
    this.callback      = callback;
    var _this          = this;
    _this.text.onclick = area_show;
    if (data != 0) {
        var req       = param.intSelect;
        req['filter'] = data;
        req['getOne'] = 1;
        _rt().request(req, handle_init);

    }
    else
        handle_init({}, '0');

    function handle_init(get, data) {
        eval('data=' + data);
        if (data != 0) {
            _this.text.innerHTML = data;
            _this.text.title     = data;
            _this.input.value    = get.filter;
            if (typeof(_this.callback) == 'function') _this.callback();
        }
        else
            _this.text.innerHTML = 'Не выбрано';
    }

    function area_show(id) {
        show('popup_layer');
        document.getElementById('popup_layer').style.height = document.body.scrollHeight;
        _this.obj                                           = document.getElementById('popup_frame');
        _rt('#popup_layer').bind('click', _this.cleenup_handllers);

        _rt(_this.obj).bind('click', handle_list_click);
        _this.obj.innerHTML        = '';
        _this.obj.style.top        = yMousePos - 5;
        _this.obj.style.left       = xMousePos - 5;
        _this.obj.style.display    = 'block';
        _this.obj.style.visibility = 'visible';

        _this.obj.appendChild(_this.obj_search = _rt().create('input', {
            name:    '_search',
            style:   {width: '200px', margin: '0 20px'},
            value:   'Начните поиск',
            onkeyup: handle_list_search,
            onclick: handle_list_search_start
        }));
        _this.obj.appendChild(_this.obj_loading = _rt().create('div', {
            style:     {color: "#555555", display: 'none'},
            className: 'popUpMenuLine',
            innerHTML: 'Загрузка...'
        }));
        _this.obj.appendChild(_this.obj_list = _rt().create('div', {innerHTML: '</br>'}));
        _this.obj_list.appendChild(_rt().create('a', {
            'href':      'javascript:void(0)',
            'innerHTML': 'Сбросить значение',
            'key':       '',
            'keyName':   'Не выбрано'
        }));
        shadowing(_this.obj);
    }

    this.cleenup_handllers = function () {
        _rt(_this.obj).unbind('click', handle_list_click);
        _rt('#popup_layer').unbind('click', _this.cleenup_handllers);
    }

    function handle_list_click(event) {
        if (event == 'none')return;
        targ = _rt().getTarget(event);
        if (targ.key == null)return;

        _this.input.value    = targ.key;
        _this.text.innerHTML = targ.keyName;
        _rt(_this.obj).unbind('click', handle_list_click);
        if (typeof(_this.callback) == 'function') _this.callback();
        hide_list_menu();

    }

    var time_f = new Date().getTime();
    var time_h;

    function handle_list_search() {

        var now = new Date().getTime();

        if (now - time_f > 2000)time_f = now;
        if (now - time_f < 1000) {
            if (time_h == null)
                time_h = setTimeout(handle_list_search, 1000);
            return;
        }
        time_f = now;
        if (time_h != null) {
            clearTimeout(time_h);
            time_h = null;
        }

        var req                         = param.intSelect;
        req['filter']                   = _this.obj_search.value;
        req['getOne']                   = 0;
        _this.obj_loading.style.display = 'block';
        _rt().request(req, handle_search_answer);

    }

    function handle_list_search_start(event) {
        if (_this.obj_search.value == "Начните поиск")
            _this.obj_search.value = '';

    }

    function handle_search_answer(get, data) {
        if (_this.obj.style.display == 'none')return;
        eval('data=' + data);
        _this.obj_loading.style.display = 'none';
        _this.obj_list.innerHTML        = '';
        var f                           = 0;
        for (var i in data) {
            f = 1;
            _this.obj_list.appendChild(_rt().create('a', {
                'href':      'javascript:void(0)',
                'innerHTML': data[i],
                'key':       i,
                'keyName':   data[i]
            }));
        }
        if (f == 0)_this.obj_list.innerHTML = "<span class='popUpMenuLine'>Ничего не найдено</span>";

        shadowing(_this.obj);
    }

}//end of class




