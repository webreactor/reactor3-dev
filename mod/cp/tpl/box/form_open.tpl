<!--if !defined('XFORM')-->
<!--set define('XFORM',1)-->
<script language="javascript" type="text/javascript" src="cp/js/tiny_mce/tiny_mce.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="cp/js/jscalendar/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="cp/js/jscalendar/calendar.js"></script>
<script type="text/javascript" src="cp/js/jscalendar/calendar-setup.js"></script>
<script type="text/javascript" src="cp/js/jscalendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="cp/js/jscalendar/lang/calendar-ru.js"></script>

<script language="javascript" type="text/javascript" src="cp/js/form.js"></script>

<script language="javascript">

init_tiny_mce={
        mode : "exact",
        theme : "advanced",
        elements:"",
        plugins : "preview,paste,fullscreen",
        theme_advanced_buttons2_add_before: "preview,pastetext",
        theme_advanced_buttons2_add: "fullscreen",
        //content_css : "{@ MOD_URL}site/style.css",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        paste_auto_cleanup_on_paste : false,
        paste_strip_class_attributes : "none",
        paste_remove_spans : false,
        paste_remove_styles : false,
        plugin_preview_pageurl: "{$SITE_URL}cp/html_preview.html"
};
tinyMCE.init(init_tiny_mce);
</script>

<!--set $_form_id =0-->
<!--set $_input_id =0-->
<!--/if-->


<!--set $form = $exec_data.pool_get()-->

<!--set $exec_data = $form.object->toForm()-->
<!--set $data =& $exec_data-->
<!--set $_form_id ++-->
<form id="form_id_{$_form_id}" action="{$SITE_URL}{$_reactor.language_url}handleForm/?_so={$form.object->form_session}" method="post" enctype="multipart/form-data" onsubmit="_rt('#submit_form_id_{$_form_id}').set({disabled:true,value:'Сохранение...'});">
<table border="0" cellpadding="0" cellspasing="0" width="100%" class="table">
<!--if $act_description.empty() -->
<tr><td colspan="5" height="2" bgcolor="black"></td></tr>
<tr><td colspan="5" height="5"></td></tr>
<!--/if-->

<!--if $form.object->error._error_on_action.isset()-->
<tr><td width="7%"></td><td colspan="3" class="form_title system">
        <!--set $cm = 0 -->
        <!--foreach from=$form.object->error item=$err -->
        <!--if $err.is_array() && $err.msg.isset() --><!--set $cm ++ -->
        <p class="form_flag_bad">{$err.msg}</p>
        <!--/if-->
        <!--/foreach-->
        <!--if $cm == 0 --><p class="form_flag_bad">Такая запись уже существует</p><!--/if-->
</td><td width="7%"></td></tr>
<!--/if-->