import '../css/public.scss';
import '@webcomponents/webcomponentsjs';
import '@ungap/custom-elements-builtin';
import 'time-elements';
import './public/app-email';

const $ = require('jquery');
require('bootstrap');

$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
    console.log('ready');
});
