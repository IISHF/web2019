import '../css/public.scss';
import '@webcomponents/webcomponentsjs';
import '@ungap/custom-elements-builtin';
import 'time-elements';
import './public/app-email';

import '@fortawesome/fontawesome-free/css/all.css';
import 'tempusdominus-bootstrap-4/src/sass/tempusdominus-bootstrap-4-build.scss';

const $ = require('jquery');
require('bootstrap');
const moment = require('moment');
global.moment = moment;
require('tempusdominus-bootstrap-4');

$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();

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

    console.log($.fn.datetimepicker.Constructor.Default);

    $('[data-enable-datepicker="true"]').each(function () {
        $(this).datetimepicker($(this).data('datepicker-options'));
    });
});
