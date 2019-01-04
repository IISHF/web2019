import '@webcomponents/webcomponentsjs';
import '@ungap/custom-elements-builtin';
import Trix from 'trix';
import moment from 'moment';
import * as FilePond from 'filepond';
import FilePondPluginImageExifOrientation from 'filepond-plugin-image-exif-orientation';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import FilePondPluginImageResize from 'filepond-plugin-image-resize';
import FilePondPluginImageTransform from 'filepond-plugin-image-transform';
import FilePondPluginFileEncode from 'filepond-plugin-file-encode';

import 'filepond/dist/filepond.css';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';

require('select2');
$.fn.select2.defaults.set('theme', 'bootstrap4');

Trix.config.attachments.preview.caption.name = false;
Trix.config.attachments.preview.caption.size = false;
Trix.config.attachments.file.caption.size = false;

global.moment = moment;
require('tempusdominus-bootstrap-4');
$.fn.datetimepicker.Constructor.Default = $.extend({}, $.fn.datetimepicker.Constructor.Default, {
    icons: {
        time: 'far fa-clock',
        date: 'far fa-calendar',
        up: 'fas fa-arrow-up',
        down: 'fas fa-arrow-down',
        previous: 'fas fa-chevron-left',
        next: 'fas fa-chevron-right',
        today: 'far fa-calendar-check',
        clear: 'fas fa-trash',
        close: 'fas fa-times'
    },
    buttons: {
        showToday: true,
        showClear: false,
        showClose: false
    }
});

FilePond.setOptions({});
FilePond.registerPlugin(
    FilePondPluginImageExifOrientation,
    FilePondPluginFileValidateType,
    FilePondPluginFileValidateSize,
    FilePondPluginImagePreview,
    FilePondPluginImageResize,
    FilePondPluginImageTransform,
    FilePondPluginFileEncode
);

$(document).ready(function () {
    $('select[data-enable-select2="true"]').each(function () {
        $(this).select2($(this).data('select2-options'));
    });

    $('[data-enable-datepicker="true"]').each(function () {
        $(this).datetimepicker($(this).data('datepicker-options'));
    });

    FilePond.parse(document.body);
});
