import '@webcomponents/webcomponentsjs';
import '@ungap/custom-elements-builtin';
import 'time-elements';
import './web_components/safe_email';
import $ from 'jquery';
import moment from 'moment';
import React from 'react';
import ReactDOM from 'react-dom';
import {Lazy} from './components/Loading';

import '../css/public.scss';
import '@fortawesome/fontawesome-free/css/all.css';
import 'trix/dist/trix.css';

global.$ = global.jQuery = $;
global.moment = moment;

require('bootstrap');

require('select2');
$.fn.select2.defaults.set('theme', 'bootstrap4');

require('tempusdominus-bootstrap-4');
$.fn.datetimepicker.Constructor.Default = Object.assign({}, $.fn.datetimepicker.Constructor.Default, {
    icons: {
        time: 'far fa-clock',
        date: 'far fa-calendar',
        up: 'fas fa-arrow-up',
        down: 'fas fa-arrow-down',
        previous: 'fas fa-chevron-left',
        next: 'fas fa-chevron-right',
        today: 'far fa-calendar-check',
        clear: 'fas fa-times',
        close: 'fas fa-minus'
    },
    buttons: {
        showToday: true,
        showClear: false,
        showClose: false
    }
});

const Upload = React.lazy(() => import('./components/Upload.js'));
document.querySelectorAll('[data-enable-dropzone="true"]')
    .forEach((el) => {
        ReactDOM.render(<Lazy><Upload/></Lazy>, el);
    });

const TrixEditor = React.lazy(() => import('./components/TrixEditor.js'));
document.querySelectorAll('[data-enable-trix="true"]')
    .forEach((el) => {
        const {name, value = '', trixOptions} = el.dataset;
        let properties = {};
        if (trixOptions) {
            properties = JSON.parse(trixOptions);
        }
        ReactDOM.render(<Lazy><TrixEditor {...properties} name={name} value={value}/></Lazy>, el);
    });

$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();

    $('select[data-enable-select2="true"]').each(function () {
        $(this).select2($(this).data('select2-options') || {});
    });

    $('[data-enable-datepicker="true"]').each(function () {
        $(this).datetimepicker($(this).data('datepicker-options') || {});
    });
});
