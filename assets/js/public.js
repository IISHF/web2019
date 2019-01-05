import '@webcomponents/webcomponentsjs';
import '@ungap/custom-elements-builtin';
import 'time-elements';
import './public/app-email';
import Trix from 'trix';
import moment from 'moment';
import Dropzone from 'dropzone';

import '../css/public.scss';
import '@fortawesome/fontawesome-free/css/all.css';
import 'trix/dist/trix.css';

const $ = require('jquery');
global.$ = global.jQuery = $;
require('bootstrap');

require('select2');
$.fn.select2.defaults.set('theme', 'bootstrap4');

Trix.config.attachments.preview.caption.name = false;
Trix.config.attachments.preview.caption.size = false;
Trix.config.attachments.file.caption.size = false;

Dropzone.autoDiscover = false;

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

$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();

    $('select[data-enable-select2="true"]').each(function () {
        $(this).select2($(this).data('select2-options') || {});
    });

    $('[data-enable-datepicker="true"]').each(function () {
        $(this).datetimepicker($(this).data('datepicker-options') || {});
    });

    $('[data-enable-dropzone="true"]').each(function () {
        const templateSelector = $(this).data('dropzone-template') || null;

        const options = $.extend({
            url: $(this).data('dropzone-url') || null,
            init: function () {
                this.on('error', (file, msg, xhr) => {
                    if (!file.accepted) {
                        this.removeFile(file);
                    }
                });
                this.on('maxfilesexceeded', (file) => {
                    this.removeFile(this.getQueuedFiles()[0]);
                    this.emit("addedfile", file);
                    this.emit("complete", file);
                });
            }
        }, $(this).data('dropzone-options') || {});

        if (templateSelector) {
            options.previewTemplate = $(templateSelector).html();
            $(templateSelector).remove();
        }

        $(this).dropzone(options);
    });
});
