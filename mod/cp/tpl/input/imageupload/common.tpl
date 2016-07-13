<input name="{$item.name}[files]" type="hidden" value="" id="files_{$item.name}">
<button type="submit" class="uploadPhoto wild_text">Добавить файлы...</button>
<!-- if ! $images_common.isset() -->
<!--set $images_common = 1 -->
<link rel="stylesheet" href="cp/style/fileupload/jquery.fileupload.css">

<div class="popup-upload popup" >
    <div id="fileupload">
        <div class="fileupload-buttonbar">
            <div class="clearfix">
                <button type="button" class="btn right" id="popup-close">×</button>
                <span class="fileupload-process"></span>
            </div>
            <div class="fileupload-progress fade">
                <div class="progress" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                </div>
                <div class="progress-extended system">&nbsp;</div>
            </div>
        </div>
        <table role="presentation" width="100%" class="fileupload-tmpl"><tbody class="files"></tbody></table>

        <div class="fileupload-buttonbar clearfix">
            <div class="fileupload-buttons right">
                <span class="fileinput-button">
					<button class="wild_text">Добавить файлы...</button>
					<input type="file" id="galleryFiles" name="files[]"  style="height: 40px;" multiple>
                </span>
                <button type="reset" class="wild_text accept">Применить</button>
            </div>
        </div>
    </div>
</div>
<div class="popup-image-view popup">
    <div></div>
</div>

<script src="cp/js/fileupload/vendor/jquery.ui.widget.js"></script>
<script src="cp/js/fileupload/tmpl.min.js"></script>
<script src="cp/js/fileupload/load-image.all.min.js"></script>
<script src="cp/js/fileupload/canvas-to-blob.min.js"></script>


<script src="cp/js/fileupload/jquery.iframe-transport.js"></script>
<script src="cp/js/fileupload/jquery.fileupload.js"></script>
<script src="cp/js/fileupload/jquery.fileupload-process.js"></script>
<script src="cp/js/fileupload/jquery.fileupload-image.js"></script>
<script src="cp/js/fileupload/jquery.fileupload-validate.js"></script>
<script src="cp/js/fileupload/jquery.fileupload-ui.js"></script>
<script src="cp/js/fileupload/main.js"></script>
<script src="cp/js/sort/jquery.dragsort.js"></script>
<!--/if-->