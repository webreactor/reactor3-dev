$(function () {
    'use strict';

    var $popup     = $('.popup-upload').detach().appendTo('body');
    var $popup_img = $('.popup-image-view').detach().appendTo('body');

    $('.uploadPhoto').on('click', function (ev) {
        ev.preventDefault();
        $popup.show();
        $popup_img.hide();
        $('body').addClass('onpopup');
        return false;
    });

    $popup_img.on('click', function (ev) {
        $popup_img.hide();
        $('body').removeClass('onpopup');
    });

    $('#fileupload').fileupload({
        url:        '/upload/',
        autoUpload: true
    }).bind('fileuploadadd', function (e, data) {
        $(this).find('.start').show();
    }).bind('fileuploaddestroyed', function (e, data) {
        var $this = $(this);
        if ($this.find('.template-download').length == 0) {
            $this.find('.delete').hide();
        }
        if ($this.find('.template-upload').length == 0) {
            $this.find('.start').hide();
        }
    }).bind('fileuploadstop', function (e, data) {
        var $this = $(this);
        $this.find('.delete').show();
        if ($this.find('.template-upload').length == 0) {
            $this.find('.start').hide();
        }
    });

});
