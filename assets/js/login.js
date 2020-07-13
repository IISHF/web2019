import '@webcomponents/webcomponentsjs';
import '@ungap/custom-elements-builtin';
import '@github/time-elements';
import './web_components/safe_email';
import $ from 'jquery';
import moment from 'moment';

import '../css/login.scss';

global.$ = global.jQuery = $;
global.moment = moment;

require('bootstrap');

$(document).ready(function () {
    $('[data-toggle=tooltip]').tooltip();
});
