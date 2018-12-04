import '../css/public.scss';

const $ = require('jquery');
require('bootstrap');

$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
    console.log('ready');
});
