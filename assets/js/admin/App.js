import React, {Suspense} from 'react';
import {HashRouter as Router, Route, Switch} from 'react-router-dom'
import {hot} from 'react-hot-loader'

import CircularProgress from "@material-ui/core/CircularProgress";
import InboxIcon from '@material-ui/icons/MoveToInbox';
import Contacts from '@material-ui/icons/Contacts';
import Flag from '@material-ui/icons/Flag';
import Notes from '@material-ui/icons/Notes';

import AppFrame from './AppFrame';
import Navigation from './Navigation';

const Home = React.lazy(() => import('./Home'));
const Articles = React.lazy(() => import('./articles/Articles'));
const NationalGoverningBodies = React.lazy(() => import('./national_governing_bodies/NationalGoverningBodies'));
const Users = React.lazy(() => import('./users/Users'));

const App = ({homeUrl}) => (
    <Router>
        <AppFrame nav={<Navigation items={[
            {
                label: 'Home',
                to: '/',
                icon: <InboxIcon/>

            },
            {
                label: 'Articles',
                to: '/articles',
                icon: <Notes/>

            },
            {
                label: 'NGBs',
                to: '/national-governing-bodies',
                icon: <Flag/>

            },
            {
                label: 'Users',
                to: '/users',
                icon: <Contacts/>

            },
        ]}/>}>
            <Suspense fallback={<CircularProgress/>}>
                <Switch>
                    <Route exact path="/" render={() => <Home homeUrl={homeUrl}/>}/>
                    <Route exact path="/articles" render={() => <Articles/>}/>}/>
                    <Route exact path="/national-governing-bodies"
                           render={() => <NationalGoverningBodies/>}/>}/>
                    <Route exact path="/users" render={() => <Users/>}/>
                </Switch>
            </Suspense>
        </AppFrame>
    </Router>
);

export default hot(module)(App);
