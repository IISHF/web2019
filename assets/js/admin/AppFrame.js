import React from 'react';
import CssBaseline from '@material-ui/core/CssBaseline';
import AppBar from '@material-ui/core/AppBar';
import Toolbar from '@material-ui/core/Toolbar';
import Typography from '@material-ui/core/Typography';
import withStyles from "@material-ui/core/styles/withStyles";
import IconButton from "@material-ui/core/IconButton/IconButton";
import MenuIcon from '@material-ui/icons/Menu';
import AccountCircle from '@material-ui/icons/AccountCircle';

const styles = (theme) => ({
    root: {
        display: 'flex',
    },

    menuButton: {
        marginLeft: -12,
        marginRight: 20,
    },

    title: {
        flexGrow: 1,
    },

    content: {
        flexGrow: 1,
        padding: theme.spacing.unit * 3,
        height: '100vh',
        overflow: 'auto',
    },

    appBarSpacer: theme.mixins.toolbar,
});

const App = ({classes, children}) => (
    <div className={classes.root}>
        <CssBaseline/>
        <AppBar position="absolute" color="default">
            <Toolbar>
                <IconButton className={classes.menuButton} color="inherit">
                    <MenuIcon/>
                </IconButton>
                <Typography component="h1" variant="h6" color="inherit" noWrap className={classes.title}>
                    IISHF - Administration
                </Typography>
                <>
                    <IconButton color="inherit">
                        <AccountCircle/>
                    </IconButton>
                </>
            </Toolbar>
        </AppBar>
        <main className={classes.content}>
            <div className={classes.appBarSpacer}/>
            {children}
        </main>
    </div>
);

export default withStyles(styles)(App);
