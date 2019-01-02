import '../css/public.scss';
import '@webcomponents/webcomponentsjs';
import '@ungap/custom-elements-builtin';
import 'time-elements';
import './public/app-email';

import '@fortawesome/fontawesome-free/css/all.css';
import 'trix/dist/trix.css';

const $ = require('jquery');
global.$ = global.jQuery = $;
require('bootstrap');

$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
});
