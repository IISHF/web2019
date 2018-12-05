import React from 'react';
import ReactDOM from 'react-dom';

import '@webcomponents/webcomponentsjs';
import '@ungap/custom-elements-builtin';
import 'time-elements';

import {setBaseUrl} from './admin/api';
import App from './admin/App';

import '../css/admin.scss';

const rootElement = document.getElementById('app');
const appConfig = Object.keys(rootElement.dataset).reduce((result, key) => {
    result[key] = JSON.parse(rootElement.dataset[key]);
    return result
}, {});

setBaseUrl(appConfig.config.baseUrl);

ReactDOM.render(
    <App {...appConfig.config}/>,
    rootElement
);
