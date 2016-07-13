//version 1

function textareaHeighter(id) {
    var obj         = document.getElementById(id);
    var me          = document.createElement('div');
    me.style.width  = "100%";
    me.style.height = "5px";
    me.style.cursor = "s-resize";

    me.style.position = "relative";
//me.style.background="#BBBBBB";

    me.style.top  = "-6px";
    me.moveoffset = -1;
    me.id         = id + "_heighter";

    var save_handlers = new Array();
    me.onmousedown    = function () {
        save_handlers['onmouseup']   = document.onmouseup;
        save_handlers['onmousemove'] = document.onmousemove;
        document.body.onmouseup      = me.onmouseup;
        document.body.onmousemove    = me.onmousemove;
        me.textheight                = obj.offsetHeight;
        me.moveoffset                = yMousePos;
    };

    me.onmouseup = function () {
        me.moveoffset        = -1;
        document.onmouseup   = save_handlers['onmouseup'];
        document.onmousemove = save_handlers['onmousemove'];
    };

    me.onmousemove = function () {
        if (me.moveoffset != -1) {
            obj.style.height = me.textheight + yMousePos - me.moveoffset;
        }
    }

    obj.parentNode.appendChild(me);

}

function toggleEditor(id) {
    e = document.getElementById(id + '_e');
    d = document.getElementById(id + '_d');
    h = document.getElementById(id + '_heighter');

    if (tinyMCE.getInstanceById(id) == null) {
        tinyMCE.execCommand('mceAddControl', false, id);
        d.className     = 'red system';
        e.className     = 'system';
        h.style.display = "none";

    }
    else {
        tinyMCE.execCommand('mceRemoveControl', false, id);
        d.className     = 'system';
        e.className     = 'red system';
        h.style.display = "block";
    }
}

