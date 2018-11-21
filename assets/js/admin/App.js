import React from 'react';
import {BrowserRouter as Router, Route, Switch} from 'react-router-dom'
import {hot} from 'react-hot-loader'

import AppFrame from './AppFrame';
import Home from './Home';

const App = ({baseUrl, homeUrl}) => (
    <Router basename={baseUrl}>
        <AppFrame>
            <Switch>
                <Route exact path="/" render={() => <Home homeUrl={homeUrl}/>}/>
            </Switch>
        </AppFrame>
    </Router>
);

export default hot(module)(App);
