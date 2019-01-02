import '@webcomponents/webcomponentsjs';
import '@ungap/custom-elements-builtin';

import 'tempusdominus-bootstrap-4/src/sass/tempusdominus-bootstrap-4-build.scss';

const Trix = require('trix');
Trix.config.attachments.preview.caption.name = false;
Trix.config.attachments.preview.caption.size = false;
Trix.config.attachments.file.caption.size = false;

const moment = require('moment');
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
    $('[data-enable-datepicker="true"]').each(function () {
        $(this).datetimepicker($(this).data('datepicker-options'));
    });
});
