import React, {Suspense} from 'react';
import {HashRouter as Router, Route, Switch} from 'react-router-dom'
import {hot} from 'react-hot-loader'

import CircularProgress from "@material-ui/core/CircularProgress/CircularProgress";
import InboxIcon from '@material-ui/icons/MoveToInbox';
import MailIcon from '@material-ui/icons/Mail';

import AppFrame from './AppFrame';
import Navigation from './Navigation';

const Home = React.lazy(() => import('./Home'));

const App = ({baseUrl, homeUrl}) => (
    <Router>
        <AppFrame nav={<Navigation items={[
            {
                label: 'Home',
                to: '/',
                icon: <InboxIcon/>

            },
            {
                label: 'Country NGBs',
                to: '/countries',
                icon: <MailIcon/>

            },
        ]}/>}>
            <Suspense fallback={<CircularProgress/>}>
                <Switch>
                    <Route exact path="/" render={() => <Home homeUrl={homeUrl} baseUrl={baseUrl}/>}/>
                </Switch>
            </Suspense>
        </AppFrame>
    </Router>
);

export default hot(module)(App);
