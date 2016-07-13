<!--box  input_block-->

<!--execute template=input/imageupload/common.tpl-->

<ul class="files-list clearfix" >
    <!--foreach from=$data.$item&name item=$it key=$key-->
    <li class="img-drag">
        <div>
            <button type="button" class="close"><span>×</span></button>
            <img src="!{@ FILE_URL , $it.file}_240.jpg" border="0" data-url="{$it.file}">
        </div>
    </li>
    <!--/foreach-->
</ul>

<script type="text/javascript">
    $(function () {
        'use strict';
        $('#form_id_{$_form_id}').on('submit', function (ev) {
            var files = [];
            $('.img-drag', this).each(function () {
                var $this = $(this);
                var srcpath = $this.find('img').data('url');
                var obj = { 'file': srcpath, 'real': srcpath, 'desc': '' };
                if (!$this.is(":visible")) {
                    obj.delete = 1;
                }
                files.push(obj);
            });
            $('#files_{$item.name}').val(JSON.stringify(files));
        });
        var $files = $('.files-list');
        $files.on('click', '.img-drag .close', function () {
            var p = $(this).closest('li');
            p.hide();
        });
        $('#popup-close').on('click', function () {
            var $popup = $('.popup-upload');
            $popup.hide();
            $('body').removeClass('onpopup');
        });
        $('.fileupload-buttonbar .accept').on('click', function (ev) {
            $('.template-download').each(function () {
                var img = $(this).find('img').clone();
                var url = $(this).find('.preview a').attr('href').split('{@ FILE_URL}')[1];
                img.attr('data-url', url);
                var thumb = $('<li class="img-drag"><div><button type="button" class="close"><span>×</span></button></div></li>');
                thumb.find('div').append(img);
                thumb.find('img').on('click', imageClick);
                thumb.appendTo($files);
                $(this).remove();
            });
            $files.dragsort("destroy");
            $files.dragsort({dragSelector: "div"});
            $('.popup-upload').hide();
            $('body').removeClass('onpopup');
        });
        $files.dragsort({dragSelector: "div"});
        var $popup_img = $('.popup-image-view');
        $('.img-drag img').on('click', imageClick);
        function imageClick(ev){
            ev.preventDefault();
            $('.popup-upload').hide();
            var path = '{@ FILE_URL}' + $(this).data('url');
            $popup_img.find('div').css('background-image', 'url("'+path+'")');
            $popup_img.show();
            $('body').addClass('onpopup');
            return false;
        }
    });
</script>

<!--execute template=input/imageupload/template_upload.tpl -->

<!--execute template=input/imageupload/template_download.tpl -->

<!--/box-->