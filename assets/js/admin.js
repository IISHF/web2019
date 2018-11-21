import React from 'react';
import ReactDOM from 'react-dom';

import App from './admin/App';

import '../css/admin.scss';

const rootElement = document.getElementById('app');
const appConfig = Object.keys(rootElement.dataset).reduce((result, key) => {
    result[key] = JSON.parse(rootElement.dataset[key]);
    return result
}, {});

ReactDOM.render(
    <App {...appConfig.config}/>,
    rootElement
);
