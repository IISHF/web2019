import React from 'react';
import ReactDOM from 'react-dom';
import Button from '@material-ui/core/Button';
import CssBaseline from '@material-ui/core/CssBaseline';
import AppBar from '@material-ui/core/AppBar';
import Toolbar from '@material-ui/core/Toolbar';
import Typography from '@material-ui/core/Typography';

import '../css/app.scss';

console.log('abc');

const App = () => (
    <>
        <CssBaseline/>
        <AppBar position="static" color="default">
            <Toolbar>
                <Typography variant="h6" color="inherit">
                    Photos
                </Typography>
            </Toolbar>
        </AppBar>
        <Button variant="contained" color="primary">
            Hello World
        </Button>
    </>
);

ReactDOM.render(
    <App/>,
    document.getElementById('app')
);
