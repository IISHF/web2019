import React from 'react';
import CssBaseline from '@material-ui/core/CssBaseline';
import AppBar from '@material-ui/core/AppBar';
import Toolbar from '@material-ui/core/Toolbar';
import Typography from '@material-ui/core/Typography';
import withStyles from "@material-ui/core/styles/withStyles";
import Drawer from "@material-ui/core/Drawer";

const drawerWidth = 240;
const styles = (theme) => ({
    root: {
        display: 'flex',
    },
    appBar: {
        zIndex: theme.zIndex.drawer + 1,
    },
    drawer: {
        width: drawerWidth,
        flexShrink: 0,
    },
    drawerPaper: {
        width: drawerWidth,
    },
    toolbar: theme.mixins.toolbar,
    content: {
        flexGrow: 1,
        backgroundColor: theme.palette.background.default,
        padding: theme.spacing.unit * 3,
    },
});

class AppFrame extends React.Component {
    render() {
        const {nav, classes, children} = this.props;

        return (
            <div className={classes.root}>
                <CssBaseline/>
                <AppBar position="fixed" className={classes.appBar}>
                    <Toolbar>
                        <Typography component="h1" variant="h6" color="inherit" noWrap className={classes.title}>
                            IISHF - Administration
                        </Typography>
                    </Toolbar>
                </AppBar>
                <Drawer className={classes.drawer} variant="permanent" classes={{
                    paper: classes.drawerPaper,
                }} anchor="left">
                    <div className={classes.toolbar}/>
                    {nav}
                </Drawer>
                <main className={classes.content}>
                    <div className={classes.toolbar}/>
                    {children}
                </main>
            </div>
        );
    }
}

export default withStyles(styles)(AppFrame);
